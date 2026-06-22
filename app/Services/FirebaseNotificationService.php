<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\AndroidConfig;
use App\Models\DeviceToken;
use App\Models\PushNotification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

        // Check file exists
        if (!file_exists($credentialsPath)) {
            \Log::error('FCM: credentials file NOT FOUND at ' . $credentialsPath);
            $this->messaging = null;
            return;
        }

        // Check file is valid JSON
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('FCM: credentials file is invalid JSON — ' . json_last_error_msg());
            $this->messaging = null;
            return;
        }

        // Check it's a service account key (not a web API key)
        if (!isset($credentials['type']) || $credentials['type'] !== 'service_account') {
            \Log::error('FCM: Wrong credentials type! Got: ' . ($credentials['type'] ?? 'missing') . ' — Expected: service_account');
            $this->messaging = null;
            return;
        }

        // Check required fields exist
        $requiredFields = ['project_id', 'private_key', 'client_email'];
        foreach ($requiredFields as $field) {
            if (empty($credentials[$field])) {
                \Log::error('FCM: credentials missing required field: ' . $field);
                $this->messaging = null;
                return;
            }
        }

        \Log::info('FCM: Credentials OK — project: ' . $credentials['project_id'] . ' — client: ' . $credentials['client_email']);

        try {
            $this->messaging = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->createMessaging();
        } catch (\Throwable $e) {
            \Log::error('FCM: Failed to initialize messaging — ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    public function sendToAll(string $title, string $body, ?int $collegeId = null): void
    {
        // Always save to DB first regardless of FCM status
        PushNotification::create([
            'college_id' => $collegeId,
            'title'      => $title,
            'message'    => $body,
        ]);

        if (!$this->messaging) {
            \Log::warning('FCM: Messaging not initialized, skipping push.');
            return;
        }

        $tokens = DeviceToken::whereNotNull('fcm_token')
            ->whereNull('failed_at')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            \Log::info('FCM: No tokens found, skipping push.');
            return;
        }

        \Log::info('FCM: Sending to ' . count($tokens) . ' device(s).');

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData([
                'type'       => 'new_college',
                'college_id' => (string) ($collegeId ?? ''),
            ])
            ->withAndroidConfig(AndroidConfig::fromArray([
                'priority' => 'high',
                'notification' => [
                    'sound'        => 'default',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
            ]))
            ->withApnsConfig(ApnsConfig::fromArray([
                'headers' => [
                    'apns-priority' => '10',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'sound'             => 'default',
                        'badge'             => 1,
                        'content-available' => 1,
                        'mutable-content'   => 1,
                    ],
                ],
            ]));

        foreach (array_chunk($tokens, 500) as $chunkIndex => $chunk) {
            try {
                $report = $this->messaging->sendMulticast($message, $chunk);

                \Log::info(sprintf(
                    'FCM: Chunk %d — %d succeeded, %d failed.',
                    $chunkIndex + 1,
                    $report->successes()->count(),
                    $report->failures()->count()
                ));

                foreach ($report->failures()->getItems() as $failure) {
                    $failedToken  = $failure->target()->value();
                    $error        = $failure->error();
                    $errorMessage = $error ? $error->getMessage() : 'Unknown error';

                    DeviceToken::where('fcm_token', $failedToken)->update([
                        'failed_at'   => now(),
                        'fail_reason' => $errorMessage,
                    ]);

                    \Log::warning('FCM: Token marked failed — ' . $errorMessage . ' — ' . $failedToken);
                }

            } catch (\Throwable $e) {
                \Log::error('FCM: sendMulticast failed on chunk ' . ($chunkIndex + 1) . ' — ' . $e->getMessage());
            }
        }
    }
}