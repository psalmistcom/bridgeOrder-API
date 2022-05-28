<?php

return [
    'firebase' => [
        'api_key' => [
            'live' => env('FIREBASE_API_KEY_LIVE', 'firebase_api_key_live'),
            'test' => env('FIREBASE_API_KEY_TEST', 'firebase_api_key_test'),
        ],
        'project_id' => [
            'live' => env('FIREBASE_PROJECT_ID_LIVE', 'firebase_project_id_live'),
            'test' => env('FIREBASE_PROJECT_ID_TEST', 'firebase_project_id_test'),
        ],
        'server_key' => [
            'live' => env('FIREBASE_SERVER_KEY_LIVE', 'firebase_server_key_live'),
            'test' => env('FIREBASE_SERVER_KEY_TEST', 'firebase_server_key_test'),
        ],
    ],
    'paystack' => [
        'base_url' =>  env('PAYSTACK_BASE_URL'),
        'public_key' => [
            'live' => env('PAYSTACK_PUBLIC_KEY_LIVE'),
            'test' => env('PAYSTACK_PUBLIC_KEY_TEST'),
        ],
        'secret_key' => [
            'live' => env('PAYSTACK_SECRET_KEY_LIVE'),
            'test' => env('PAYSTACK_SECRET_KEY_TEST'),
        ],
    ],
    'slack' => [
        'error_url' => env(
            'LOG_SLACK_WEBHOOK_URL',
            'https://hooks.slack.com/services/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'
        ),
    ],
];
