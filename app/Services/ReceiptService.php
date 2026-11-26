<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptService
{
    private ConvessaService $convessa;

    public function __construct(ConvessaService $convessa)
    {
        $this->convessa = $convessa;
    }

    /**
     * Générer le PDF et l'envoyer par WhatsApp - point d'entrée principal
     */
    public function sendReceiptAfterPayment(Payment $payment): bool
    {
        try {
            $pdfContent = $this->generatePdf($payment);
            $base64     = base64_encode($pdfContent);
            $phone      = $this->buildWhatsappNumber($payment->customer_phone);

            if (!$phone) {
                Log::warning('ReceiptService: numéro invalide', [
                    'payment_id' => $payment->id,
                    'raw_phone'  => $payment->customer_phone,
                ]);
                return false;
            }

            $firstname = $payment->metadata['firstname'] ?? 'Contributeur';
            $lastname  = $payment->metadata['lastname']  ?? '';
            $filename  = 'recu_mission_sourire_' . $payment->reference . '.pdf';
            $message   = $this->buildThankYouMessage($firstname, $lastname);

            $this->convessa->sendText($phone, $message);
            $result  = $this->convessa->sendPdfBase64($phone, $base64, $filename);
            $success = !isset($result['error']);

            Log::info('ReceiptService: reçu envoyé', [
                'payment_id' => $payment->id,
                'phone'      => $phone,
                'success'    => $success,
            ]);

            return $success;

        } catch (\Throwable $e) {
            Log::error('ReceiptService: erreur', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Générer le contenu binaire du PDF
     */
    public function generatePdf(Payment $payment): string
    {
        $tz   = new \DateTimeZone('Africa/Porto-Novo');
        $date = $payment->paid_at
            ? $payment->paid_at->setTimezone($tz)->format('d/m/Y - H:i')
            : (new \DateTime('now', $tz))->format('d/m/Y - H:i');

        // URL publique du PDF (pour QR code)
        $pdfUrl = url('/recu/' . $payment->reference);

        // Générer le QR code en SVG puis convertir en PNG base64
        $qrCode = $this->generateQrBase64($pdfUrl);

        // Logos en base64 — les 3 vrais partenaires
        $logoIwa     = $this->imageToBase64(public_path('images/2-1.png'));    // INSTI's Women in Action
        $logoDglink  = $this->imageToBase64(public_path('images/DGlink.png')); // DGlink
        $logoChorale = $this->imageToBase64(public_path('images/chorale.jpg'));// Chorale Ste Cathérine de Sienne

        $data = [
            'payment'      => $payment,
            'firstname'    => $payment->metadata['firstname'] ?? 'Contributeur',
            'lastname'     => $payment->metadata['lastname']  ?? '',
            'reference'    => $payment->reference,
            'amount'       => number_format($payment->amount, 0, ',', ' '),
            'currency'     => $payment->currency ?? 'XOF',
            'date'         => $date,
            'method'       => $this->formatPaymentMethod($payment->payment_method),
            'email'        => $payment->customer_email,
            'phone'        => $payment->customer_phone,
            'qr_base64'    => $qrCode,
            'logo_iwa'     => $logoIwa,
            'logo_dglink'  => $logoDglink,
            'logo_chorale' => $logoChorale,
            'pdf_url'      => $pdfUrl,
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
     * Générer QR code PNG en base64
     */
    public function generateQrBase64(string $url): string
    {
        try {
            $png = QrCode::format('png')
                ->size(200)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);

            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable $e) {
            Log::warning('QR code génération échouée', ['error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Convertir une image locale en base64 pour DomPDF
     */
    public function imageToBase64(string $path): string
    {
        if (!file_exists($path)) {
            return '';
        }
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    /**
     * Message WhatsApp de remerciement
     */
    private function buildThankYouMessage(string $firstname, string $lastname): string
    {
        return "❤️ *MISSION SOURIRE – 2ᵉ ÉDITION*\n\n"
            . "Bonjour {$firstname} {$lastname},\n\n"
            . "Nous vous remercions sincèrement pour votre généreux don. 🙏🏾\n\n"
            . "Votre contribution a bien été prise en compte. Grâce à votre geste, vous participez "
            . "à apporter du sourire et de l'espoir aux enfants de l'orphelinat La Providence.\n\n"
            . "Chaque don compte, chaque geste fait la différence. ❤️\n\n"
            . "Merci de faire partie de cette belle chaîne de solidarité !\n\n"
            . "*MISSION SOURIRE : UN DON, UN GESTE, UN SOURIRE !* 🎄\n\n"
            . "Avec gratitude,\n"
            . "_L'équipe MISSION SOURIRE – CSCS & IWA_\n\n"
            . "_Ci-joint votre reçu officiel de contribution._";
    }

    /**
     * Normaliser le numéro pour WhatsApp : format 229XXXXXXXXX
     */
    private function buildWhatsappNumber(string $rawPhone): ?string
    {
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);

        if (preg_match('/^229\d{9}$/', $phone))  return $phone;
        if (preg_match('/^0\d{9}$/', $phone))     return '229' . $phone;
        if (preg_match('/^\d{9}$/', $phone))      return '2290' . $phone;

        return null;
    }

    /**
     * Libellé méthode de paiement
     */
    private function formatPaymentMethod(?string $method): string
    {
        $map = [
            'mtn'          => 'MTN MOBILE MONEY',
            'moov'         => 'MOOV MONEY',
            'mobile_money' => 'MOBILE MONEY',
            'card'         => 'CARTE BANCAIRE',
            'orange'       => 'ORANGE MONEY',
        ];
        return $map[strtolower($method ?? '')] ?? strtoupper($method ?? 'MOBILE MONEY');
    }
}
