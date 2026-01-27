<?php

namespace App\Helper;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;

class FirebaseHelper
{
    public static function sendNotification(string $token, string $title, string $body, array $data = []): array
    {
        $projectId = "offers-project-d190d" ?? env('FIREBASE_PROJECT_ID');
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // ✅ Firebase requires all data values be strings
        $data = array_map(fn($v) => (string)$v, $data);

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

        $client = new Client(['timeout' => 15]);

        $res = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAccessToken(),
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload,
        ]);

        return json_decode((string)$res->getBody(), true) ?? [];
    }

    private static function getAccessToken(): string
    {
        $credsPath = $path = storage_path('service-account.json') ?? base_path(env('FIREBASE_CREDENTIALS'));
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credsPath);

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? '';
    }
}
