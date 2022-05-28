<?php

use Illuminate\Support\Carbon;

if (!function_exists('generateRandomString')) {
    function generateRandomString(int $lengthOfString = 15): string
    {
        $strResult = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($strResult), 0, $lengthOfString);
    }
}

if (!function_exists('slackTheError')) {
    /**
     * @param string $title
     * @param Throwable|null $e
     * @return array|mixed|void
     */
    function slackTheError(string $title, Throwable $e = null)
    {
        $url = config('api.slack.error_url');
        $headers = ['content-type' => 'application/json'];

        $machine = gethostname();
        $environment = strtoupper(app()->environment()) . ' - ' . $machine;
        $time = Carbon::now()->toDayDateTimeString();
        $app = config('app.name');
        $appUrl = config('app.url');
        $error = 'app info';
        $file = 'Non specified';
        $line = 'Non specified';
        $code = 'Non specified';
        $errorClass = 'Non Specified';
        $userId = auth()->user()->id ?? null;
        $uri = request()->path();

        if ($e) {
            $error = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $code = $e->getCode();
            $errorClass = get_class($e);
        }
        $blocks = [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $title,
                    'emoji' => true,
                ],
            ],
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => "Error Message: {$error}",
                    'emoji' => true,
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*When:*\n{$time}",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Environment:*\n{$environment}",
                    ],
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Error Code:*\n{$code}",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*App:*\n<{$appUrl}|{$app}>",
                    ],
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*User ID:*\n{$userId}",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Endpoint:*\n{$uri}",
                    ],
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*File of Occurrence:*\n{$file}",
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Line of Occurrence:*\n{$line}",
                    ],
                ],
            ],
            [
                'type' => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*Error Class:*\n{$errorClass}",
                    ],
                ],
            ],
            [
                'type' => 'actions',
                'elements' => [
                    [
                        'type' => 'button',
                        'text' => [
                            'type' => 'plain_text',
                            'emoji' => true,
                            'text' => 'Done!',
                        ],
                        'style' => 'primary',
                        'value' => 'click_me_123',
                    ],
                    [
                        'type' => 'button',
                        'text' => [
                            'type' => 'plain_text',
                            'emoji' => true,
                            'text' => 'I need help!',
                        ],
                        'style' => 'danger',
                        'value' => 'click_me_123',
                    ],
                ],
            ],
        ];

        $body = [
            'text' => $title,
            'blocks' => $blocks,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($url, $body);
            return $response->json();
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
