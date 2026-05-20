<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId       = config('services.firebase.project_id');
        $this->credentialsPath = config('services.firebase.credentials');
    }

    /**
     * Send a notification to a single user (all their devices).
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('token')->toArray();
        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Send a notification to all users who have a device token.
     */
    public function sendToAll(string $title, string $body, array $data = []): void
    {
        DeviceToken::select('token')->chunk(500, function ($chunk) use ($title, $body, $data) {
            $this->sendToTokens($chunk->pluck('token')->toArray(), $title, $body, $data);
        });
    }

    /**
     * Send to an array of FCM tokens via the FCM v1 HTTP API.
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        if (empty($tokens)) return;

        $accessToken = $this->getAccessToken();
        if (!$accessToken) return;

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        foreach ($tokens as $token) {
            try {
                $payload = [
                    'message' => [
                        'token'        => $token,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data'         => array_map('strval', $data),
                        'android'      => [
                            'notification' => ['sound' => 'default'],
                        ],
                    ],
                ];

                $response = Http::withToken($accessToken)
                    ->post($url, $payload);

                // Remove invalid/expired tokens automatically
                if ($response->status() === 404) {
                    DeviceToken::where('token', $token)->delete();
                }
            } catch (\Throwable $e) {
                Log::error('FCM send error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Exchange the service account JSON for a short-lived OAuth2 access token.
     */
    protected function getAccessToken(): ?string
    {
        try {
            // Prefer env var (for cloud deployments), fall back to file
            $raw = env('FIREBASE_CREDENTIALS_JSON');
            if ($raw) {
                $credentials = json_decode($raw, true);
            } else {
                $credentials = json_decode(file_get_contents($this->credentialsPath), true);
            }

            $now    = time();
            $expiry = $now + 3600;

            $header  = base64url_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64url_encode(json_encode([
                'iss'   => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $expiry,
            ]));

            $signingInput = "{$header}.{$payload}";

            openssl_sign($signingInput, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');

            $jwt = "{$signingInput}." . base64url_encode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $response->json('access_token');
        } catch (\Throwable $e) {
            Log::error('FCM token error: ' . $e->getMessage());
            return null;
        }
    }
}

function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
