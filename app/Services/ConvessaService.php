<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ConvessaService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.convessa.key', '');
        $this->baseUrl = config('services.convessa.url', '');
    }

    /**
     * Envoyer un message texte WhatsApp
     */
    public function sendText(string|array $to, string $message): array
    {
        return $this->call('POST', '/api/v1/send', [
            'to'      => $to,
            'message' => $message,
        ]);
    }

    /**
     * Envoyer un document PDF par base64
     */
    public function sendPdfBase64(string $to, string $base64Content, string $filename, string $message = ''): array
    {
        $payload = [
            'to'    => $to,
            'media' => [
                'type'   => 'document',
                'mime'   => 'application/pdf',
                'base64' => $base64Content,
                'name'   => $filename,
            ],
        ];

        if (!empty($message)) {
            $payload['message'] = $message;
        }

        return $this->call('POST', '/api/v1/send', $payload);
    }

    /**
     * Envoyer un document PDF par URL publique
     */
    public function sendPdfUrl(string $to, string $url, string $filename, string $message = ''): array
    {
        $payload = [
            'to'    => $to,
            'media' => [
                'type' => 'document',
                'mime' => 'application/pdf',
                'url'  => $url,
                'name' => $filename,
            ],
        ];

        if (!empty($message)) {
            $payload['message'] = $message;
        }

        return $this->call('POST', '/api/v1/send', $payload);
    }

    /**
     * Vérifier la session WhatsApp
     */
    public function checkSession(): array
    {
        return $this->call('GET', '/api/v1/send/info');
    }

    /**
     * Appel HTTP vers l'API Convessa
     */
    private function call(string $method, string $path, ?array $body = null): array
    {
        if (empty($this->apiKey) || empty($this->baseUrl)) {
            Log::warning('Convessa: clé API ou URL non configurée');
            return ['success' => false, 'error' => 'Convessa non configuré'];
        }

        $url = rtrim($this->baseUrl, '/') . $path;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => [
                'X-Api-Key: ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => $body ? json_encode($body) : null,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            Log::error('Convessa cURL error', ['error' => $curlError, 'path' => $path]);
            return ['success' => false, 'error' => $curlError];
        }

        $data = json_decode($response, true) ?? [];

        if ($httpStatus >= 400) {
            $errorMsg = $data['error']['message'] ?? $data['message'] ?? "Erreur HTTP $httpStatus";
            Log::error('Convessa API error', [
                'status'  => $httpStatus,
                'path'    => $path,
                'message' => $errorMsg,
            ]);
            return ['success' => false, 'error' => $errorMsg, 'status' => $httpStatus];
        }

        Log::info('Convessa message envoyé', [
            'path'   => $path,
            'status' => $httpStatus,
            'to'     => $body['to'] ?? null,
        ]);

        return $data;
    }
}
