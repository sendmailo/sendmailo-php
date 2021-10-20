<?php

declare(strict_types=1);

/*
 * Copyright (C) 2021 Sendmailo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Sendmailo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientTrait as GuzzleClientTrait;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;


class Api
{


    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $body;

    /**
     * @var array
     */
    private $auth;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $requestOptions = [];

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * Build a new Http request.
     *
     * @param array $auth [apikey, apisecret]
     */
    public function __construct(
        array $auth
    ) {
        $this->auth = $auth;
        $this->guzzleClient = new GuzzleClient(
            [
                'auth' => [
                    $auth[0],
                    $auth[1]
                ],
                'defaults' => [
                    'headers' => [
                        'user-agent' => Config::USER_AGENT . PHP_VERSION . '/' . Config::VERSION,
                    ],
                ]
            ]
        );
    }

    /**
     * Trigger the actual call
     *
     * @param string $method http method
     * @param string $url call url
     * @param array $filters Mailjet resource filters
     * @param mixed $body Mailjet resource body
     * @param string $type Request Content-type
     * @param array $requestOptions
     * @param boolean $call
     *
     * @return Response the call response
     */
    public function call(
        string $method,
        string $url,
        $body,
        string $type = 'application/json',
        array $requestOptions = [],
        $call = false
    ) {
        $this->type = $type;
        $this->method = $method;
        $this->url = $url;
        $this->body = $body;
        $this->requestOptions = $requestOptions;
        $payload = [
            'json' => $this->body
        ];
        $headers = [
            'content-type' => $this->type,
        ];

        $payload['headers'] = $headers;

        $payload = array_merge_recursive($payload, $this->requestOptions);
        try {

            $response =  $this->guzzleClient->request($this->method, "https://" . Config::MAIN_URL . $this->url, $payload);
            
        } catch (ClientException $e) {

            $response = $e->getResponse();

        }catch (ServerException $e) {

            $response = $e->getResponse();

        }


        return  $response;
    }

   

    /**
     * Http method getter.
     *
     * @return string Request method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Call Url getter.
     *
     * @return string Request Url
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Request body getter.
     *
     * @return array request body
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Auth getter. to discuss.
     *
     * @return array Request auth
     */
    public function getAuth(): array
    {
        return $this->auth;
    }


    /**
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        return $this->guzzleClient->requestAsync($method, $uri, $options);
    }
}
