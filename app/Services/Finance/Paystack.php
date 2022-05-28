<?php

namespace App\Services\Finance;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Http;
use Prophecy\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\Response;

class Paystack
{
    private const TIMEOUT = 120;
    public mixed $baseUrl;
    public mixed $secretKey;
    public mixed $publicKey;

    public function __construct()
    {
        $this->init();
        $this->baseUrl = config('api.paystack.base_url');
    }

    public function init(): void
    {
        match (config('app.env')) {
            'production' => $this->getLiveConfig(),
            default => $this->getTestConfig()
        };
    }

    public function getLiveConfig(): void
    {
        $this->secretKey = config('api.paystack.secret_key.live');
        $this->publicKey = config('api.paystack.public_key.live');
    }

    public function getTestConfig(): void
    {
        $this->secretKey = config('api.paystack.secret_key.test');
        $this->publicKey = config('api.paystack.public_key.test');
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->secretKey
        ];
    }

    /**
     * @param string $reference
     * @return mixed
     * @throws HttpException
     */
    public function verifyTransaction(string $reference): mixed
    {
        $url = $this->baseUrl . "/transaction/verify/{$reference}";
        $response = $this->httpClient($url, 'get', [], $this->getHeaders(), timeout: self::TIMEOUT);
        return $response->json();
    }

    /**
     * @param mixed $paymentCard
     * @param float|int $amount
     * @return mixed
     * @throws HttpException
     */
    public function chargeCard(mixed $paymentCard, float|int $amount): mixed
    {
        $params = [
            'amount' => $amount,
            'email' => $paymentCard['email'],
            'authorization_code' => $paymentCard['authorization_code'],
        ];
        $url = $this->baseUrl . "/transaction/charge_authorization";
        $response = $this->httpClient($url, 'POST', $params, $this->getHeaders(), timeout: self::TIMEOUT);
        return $response->json();
    }

    /**
     * @param mixed $accountNumber
     * @param string $bankCode
     * @return mixed
     * @throws HttpException
     */
    public function resolveAccountNumber(mixed $accountNumber, string $bankCode): mixed
    {
        $url = $this->baseUrl . "/bank/resolve?account_number={$accountNumber}&bank_code={$bankCode}";
        $response = $this->httpClient(url: $url, headers: $this->getHeaders(), timeout: self::TIMEOUT);
        return $response->json();
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $body
     * @param array $headers
     * @param int $timeout
     * @return PromiseInterface|\Illuminate\Http\Client\Response
     * @throws HttpException
     */
    public function httpClient(
        string $url,
        string $method = 'GET',
        array $body = [],
        array $headers = [],
        int $timeout = 30,
    ): PromiseInterface|\Illuminate\Http\Client\Response {
        if ($method === 'POST') {
            $httpResponse = Http::timeout($timeout)->withHeaders($headers)->post($url, $body);
        } else {
            $httpResponse = Http::timeout($timeout)->withHeaders($headers)->get($url);
        }

        $response = $httpResponse->json();
        if ($httpResponse->failed()) {
            throw new HttpException(
                $response['message'],
                Response::HTTP_FAILED_DEPENDENCY
            );
        }
        return $httpResponse;
    }
}
