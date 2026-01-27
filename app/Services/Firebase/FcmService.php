<?php

namespace App\Services\Firebase;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;

class FcmService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    private function accessToken(): string
    {
        $credsPath = $path = storage_path('service-account.json') ?? base_path(env('FIREBASE_CREDENTIALS'));

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credsPath);

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? '';
    }

    public function sendToTopic(string $topic, array $notification, array $data = []): array
    {
        $projectId = "offers-project-d190d" . env('FIREBASE_PROJECT_ID');
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $body = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $notification['title'] ?? '',
                    'body'  => $notification['body'] ?? '',
                ],
                'data' => $data,
            ]
        ];

        $res = $this->http->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken(),
                'Content-Type'  => 'application/json',
            ],
            'json' => $body,
        ]);
        return json_decode((string) $res->getBody(), true) ?? [];
    }
}
