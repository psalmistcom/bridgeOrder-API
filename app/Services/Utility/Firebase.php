<?php

namespace App\Services\Utility;

use App\Models\Admin\Admin;
use App\Models\Customer\User;
use App\Models\Utility\FireBaseDeviceToken;
use App\Models\Vendor\Vendor;

class Firebase
{
    public static function notify(
        User|Admin|Vendor $user = null,
        $id = null,
        $title = 'Bridge Order',
        $message = 'Cards',
        $topic = ''
    ) {
//        $livemode = config('api.live');

//        if (config(key: "app.env") === 'production') {
//            $apiKey = config('api.firebase.api_key.live');
//
//            $projectId = config('api.firebase.project_id.live');
//        } else {
//            $apiKey = config('api.firebase.api_key.test');
//
//            $projectId = config('api.firebase.project_id.test');
//        }
//
//        $accessToken = self::getAccessToken();
//
//        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
//
//        $fields = [
//            'to' => $topic,
//            "priority" => "high",
//            'data' => [
//                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                'title' => $title,
//                'transactionId' => json_encode($id),
//                "messageSource" => "DENGAGE",
//                "notificationType" => "RICH",
//                "customParams" => [
//                    [
//                        "key" => "message",
//                        'value' => $message
//                    ],
//                ]
//            ],
//            'notification' => [
//                'title' => 'Bridge Order',
//                'body' => $message,
//                'id' => $id,
//            ],
//            'android' => [
//                'direct_boot_ok' => true,
//            ],
//        ];
//
//        if (!$topic) {
//            $device = self::getDeviceId($user);
//
//            if (!$device) {
//                return;
//            }
//
//            $fields = [
//                'to' => $device['device_token'],
//                "priority" => "high",
//                'data' => [
//                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                    'title' => $title,
//                    'transactionId' => json_encode($id),
//                    "messageSource" => "DENGAGE",
//                    "notificationType" => "TEXT",
//                    "customParams" => [
//                        [
//                            "key" => "message",
//                            'value' => $message
//                        ],
//                    ]
//                ],
//                'notification' => [
//                    'title' => 'Bridge Order',
//                    'body' => $title,
//                ],
//                'android' => [
//                    'direct_boot_ok' => true,
//                ],
//            ];
//        }
//
//        $headers = [
//            "Authorization: Bearer {$accessToken}",
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//
//        $result = curl_exec($ch);
//
//        curl_close($ch);
//
//        return $result;
    }

    public static function getDeviceId($user)
    {
        return FireBaseDeviceToken::whereUserId($user->id)->first();
    }

    public static function getAccessToken()
    {
//        $scope = 'https://www.googleapis.com/auth/firebase.messaging';
//
//        $client = new \Google_Client();
//
//        $basePath = base_path();
//
//        $client->setAuthConfig("{$basePath}/client_credentials.json");
//
//        $client->setScopes($scope);
//
//        $client->useApplicationDefaultCredentials();
//
//        $token = $client->fetchAccessTokenWithAssertion();
//
//        return $token['access_token'];
    }
}
