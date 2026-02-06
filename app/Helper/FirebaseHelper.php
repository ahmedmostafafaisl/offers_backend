<?php

namespace App\Helper;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FirebaseHelper
{
    public static function sendNotification(string $token, string $title, string $body, array $data = []): array
    {
        // ✅ اقرأ project id من env (أفضل)
        $projectId = env('FIREBASE_PROJECT_ID', 'offers-project-d190d');

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // ✅ Firebase requires all data values be strings
        $data = array_map(fn($v) => (string) $v, $data);

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data,
            ],
        ];

        $accessToken = self::getAccessToken();

        $client = new Client(['timeout' => 15]);

        try {
            $res = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode((string) $res->getBody(), true) ?? [];
        } catch (RequestException $e) {
            // ✅ رجّع تفاصيل مفيدة بدل ما تكسر النظام
            $body = $e->hasResponse() ? (string) $e->getResponse()->getBody() : null;

            return [
                'success' => false,
                'url' => $url,
                'project_id' => $projectId,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0,
                'error' => $body,
            ];
        }
    }

    private static function getAccessToken(): string
    {
        // ✅ استخدم env لمسار الملف (وخليه default)
        $credsPath = env('FIREBASE_CREDENTIALS_s', storage_path('service-account.json'));

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credsPath);

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? '';
    }
}
