<?php

namespace Papalardo\BotConversaApiClient;

use Papalardo\BotConversaApiClient\Contracts\IHttpClient;
use Papalardo\BotConversaApiClient\Core\Response;
use Papalardo\BotConversaApiClient\Exceptions\BotConversaHttpException;
use Papalardo\BotConversaApiClient\Exceptions\InvalidConfigurationException;
use Papalardo\BotConversaApiClient\Exceptions\RecordNotFoundException;
use Papalardo\BotConversaApiClient\Utils\Debug;

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

    private function endpoint(string $path, array $params = []): string
    {
        $url = $this->getBaseUrl() . '/' . trim($path, '/') . '/';

        if (count($params) > 0) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }

    private function request(string $path, array $options = []): Response
    {
        $curlOptions = $options['curl'] ?? [];

        $pendingRequest = new Response();

        if (is_null($this->getAccessToken())) {
            return $pendingRequest->setException(new InvalidConfigurationException('Access Token cannot be empty'));
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
            CURLOPT_URL => $this->endpoint($path, $options['params'] ?? []),
        ]);

        Debug::varDump('CURL_OPTIONS', $curlOptions);

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        Debug::varDump('STATUS_CODE', $statusCode);

        curl_close($curl);

        $response = json_decode($response, true);

        Debug::varDump('RESPONSE', $response);

        return $pendingRequest
            ->setStatusCode($statusCode)
            ->setErrorMessage($response->error_message ?? null)
            ->setBody($response);
    }

    /**
     * @param $path
     * @param array $params
     * @return Response
     */
    public function get($path, array $params = []): Response
    {
        return $this->request($path, [
            'params' => $params
        ]);
    }

    /**
     * @param $path
     * @param array $payload
     * @return Response
     */
    public function post($path, array $payload): Response
    {
        return $this->request($path, [
            'curl' => [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
            ]
        ]);
    }
}
