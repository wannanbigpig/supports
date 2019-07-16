<?php

/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

trait HttpRequest
{

    /**
     * Http client
     *
     * @var null | Client
     */
    protected $httpClient = null;

    /**
     * Http client options
     *
     * @var array
     */
    protected $httpOptions = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var callable
     */
    protected $handler = null;

    /**
     * @var array
     */
    protected $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public function setDefaultOptions($defaults = [])
    {
        $this->defaults = $defaults;
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public function getDefaultOptions(): array
    {
        return $this->defaults;
    }

    /**
     * getHttpClient
     *
     * @return Client|null
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-03  11:37
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->getOptions());
        }

        return $this->httpClient;
    }

    /**
     * setHttpClient.
     *
     * @param \GuzzleHttp\ClientInterface $client
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * getOptions.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge([
            'base_uri' => property_exists($this, 'baseUri') ? $this->baseUri : '',
            'timeout' => property_exists($this, 'timeout') ? $this->timeout : 6.0,
            'connect_timeout' => property_exists($this, 'connectTimeout') ? $this->connectTimeout : 6.0,
        ], $this->httpOptions);
    }

    /**
     * setOptions.
     *
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->httpOptions = $options;
    }


    /**
     * request.
     *
     * @param       $method
     * @param       $url
     * @param array $options
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $url, $options = [])
    {
        $method = strtoupper($method);
        $options = array_merge($this->getDefaultOptions(), $options, ['handler' => $this->getHandlerStack()]);

        $this->fixJsonIssue($options);

        $response = $this->getHttpClient()->request($method, $url, $options);
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * Add a middleware.
     *
     * @param callable    $middleware
     * @param string|null $name
     *
     * @return $this
     */
    public function pushMiddleware(callable $middleware, string $name = null)
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }

        return $this;
    }

    /**
     * @param \GuzzleHttp\HandlerStack $handlerStack
     *
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * Build a handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create($this->getGuzzleHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    /**
     * Return all middlewares.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function fixJsonIssue(array $options): array
    {
        if (isset($options['json']) && is_array($options['json'])) {
            $options['headers'] = array_merge($options['headers'] ?? [], ['Content-Type' => 'application/json']);

            if (empty($options['json'])) {
                $options['body'] = \GuzzleHttp\json_encode($options['json'], JSON_FORCE_OBJECT);
            } else {
                $options['body'] = \GuzzleHttp\json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            }

            unset($options['json']);
        }

        return $options;
    }

    /**
     * Get guzzle handler.
     *
     * @return mixed
     */
    public function getGuzzleHandler()
    {
        return $this->handler ?: \GuzzleHttp\choose_handler();
    }

    /**
     * Set guzzle handler.
     *
     * @param $handler
     */
    public function setGuzzleHandler(callable $handler)
    {
        $this->handler = $handler;
    }
}



