<?php

namespace Papalardo\BotConversaApiClient;

use Papalardo\BotConversaApiClient\Contracts\IHttpClient;
use Papalardo\BotConversaApiClient\Exceptions\BotConversaHttpException;
use Papalardo\BotConversaApiClient\Exceptions\InvalidConfigurationException;

class HttpClient implements IHttpClient
{
    protected string $baseUrl;

    protected string $accessToken;

    public function __construct(string $baseUrl, string $accessToken)
    {
        $this->baseUrl = $baseUrl;
        $this->accessToken = $accessToken;
    }

    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function getAccessToken(): string
    {
        return $this->accessToken;
    }

    private function endpoint(string $path): string
    {
        return $this->getBaseUrl() . '/' . trim($path, '/') . '/';
    }

    private function request(string $path, array $curlOptions = []): array
    {
        if (is_null($this->getAccessToken())) {
            throw new InvalidConfigurationException('Access Token cannot be empty');
        }

        $curl = curl_init();

        $headers = array_merge($curlOptions[CURLOPT_HTTPHEADER] ?? [], [
            'api-key: '.$this->getAccessToken(),
            'accept: application/json',
        ]);

        $curlOptions = array_replace($curlOptions, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $this->endpoint($path),
        ]);

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);

        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $response = json_decode($response);

        if ($headerCode >= 400) {
            throw new BotConversaHttpException($response->error_message ?? '', $headerCode);
        }

        return (array) $response;
    }


    public function get($path)
    {
        return $this->request($path);
    }

    public function post($path, array $payload)
    {
        return $this->request($path, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
        ]);
    }
}
