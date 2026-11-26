<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    private ConvessaService $convessa;

    public function __construct(ConvessaService $convessa)
    {
        $this->convessa = $convessa;
    }

    /**
     * Générer le PDF et l'envoyer par WhatsApp — point d'entrée principal
     */
    public function sendReceiptAfterPayment(Payment $payment): bool
    {
        try {
            // 1. Générer le PDF
            $pdfContent = $this->generatePdf($payment);

            // 2. Encoder en base64
            $base64 = base64_encode($pdfContent);

            // 3. Construire le numéro WhatsApp
            $phone = $this->buildWhatsappNumber($payment->customer_phone);

            if (!$phone) {
                Log::warning('ReceiptService: numéro de téléphone invalide', [
                    'payment_id' => $payment->id,
                    'raw_phone'  => $payment->customer_phone,
                ]);
                return false;
            }

            $firstname = $payment->metadata['firstname'] ?? 'Contributeur';
            $lastname  = $payment->metadata['lastname'] ?? '';
            $filename  = 'recu_mission_sourire_' . $payment->reference . '.pdf';

            // 4. Message de remerciement
            $message = $this->buildThankYouMessage($firstname, $lastname);

            // 5. Envoyer d'abord le message texte
            $this->convessa->sendText($phone, $message);

            // 6. Envoyer le PDF
            $result = $this->convessa->sendPdfBase64($phone, $base64, $filename);

            $success = isset($result['success']) ? $result['success'] : !isset($result['error']);

            Log::info('ReceiptService: reçu envoyé via WhatsApp', [
                'payment_id' => $payment->id,
                'phone'      => $phone,
                'success'    => $success,
            ]);

            return $success;

        } catch (\Throwable $e) {
            Log::error('ReceiptService: erreur envoi reçu WhatsApp', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Générer le contenu binaire du PDF
     */
    public function generatePdf(Payment $payment): string
    {
        $data = [
            'payment'   => $payment,
            'firstname' => $payment->metadata['firstname'] ?? 'Contributeur',
            'lastname'  => $payment->metadata['lastname'] ?? '',
            'reference' => $payment->reference,
            'amount'    => number_format($payment->amount, 0, ',', ' '),
            'currency'  => $payment->currency ?? 'XOF',
            'date'      => $payment->paid_at
                            ? $payment->paid_at->format('d/m/Y à H:i')
                            : now()->format('d/m/Y à H:i'),
            'method'    => $this->formatPaymentMethod($payment->payment_method),
            'email'     => $payment->customer_email,
            'phone'     => $payment->customer_phone,
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', false)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('dpi', 150);

        return $pdf->output();
    }

    /**
     * Construire le message de remerciement WhatsApp
     */
    private function buildThankYouMessage(string $firstname, string $lastname): string
    {
        $nom = trim($firstname . ' ' . $lastname);

        return "❤️ *MISSION SOURIRE – 2ᵉ ÉDITION*\n\n"
            . "Bonjour {$firstname} {$lastname},\n\n"
            . "Nous vous remercions sincèrement pour votre généreux don. 🙏🏾\n\n"
            . "Votre contribution a bien été prise en compte. Grâce à votre geste, vous participez à apporter du sourire et de l'espoir aux enfants de l'orphelinat La Providence.\n\n"
            . "Chaque don compte, chaque geste fait la différence. ❤️\n\n"
            . "Merci de faire partie de cette belle chaîne de solidarité !\n\n"
            . "*MISSION SOURIRE : UN DON, UN GESTE, UN SOURIRE !* 🎄\n\n"
            . "Avec gratitude,\n"
            . "_L'équipe MISSION SOURIRE – CSCS & IWA_\n\n"
            . "_Ci-joint votre reçu officiel de contribution._";
    }

    /**
     * Normaliser le numéro pour WhatsApp (format international sans +)
     * Convessa attend : 229XXXXXXXXXX
     */
    private function buildWhatsappNumber(string $rawPhone): ?string
    {
        // Nettoyer
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);

        // Déjà au format 229XXXXXXXXX (12 chiffres)
        if (preg_match('/^229\d{9}$/', $phone)) {
            return $phone;
        }

        // Format local 0XXXXXXXXX (10 chiffres, Bénin)
        if (preg_match('/^0\d{9}$/', $phone)) {
            return '229' . $phone;
        }

        // Format 9 chiffres sans 0
        if (preg_match('/^\d{9}$/', $phone)) {
            return '229' . '0' . $phone;
        }

        return null;
    }

    /**
     * Libellé méthode de paiement
     */
    private function formatPaymentMethod(?string $method): string
    {
        $methods = [
            'mtn'          => 'MTN Mobile Money',
            'moov'         => 'Moov Money',
            'mobile_money' => 'Mobile Money',
            'card'         => 'Carte bancaire',
            'orange'       => 'Orange Money',
        ];

        return $methods[strtolower($method ?? '')] ?? ($method ?? 'Mobile Money');
    }
}
