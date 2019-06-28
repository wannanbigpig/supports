<?php
/**
 * HttpRequest.php
 *
 * Created by PhpStorm.
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-03-25  11:22
 */

namespace WannanBigPig\Supports\Curl;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

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
     * getOptions
     *
     * @return array
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-03  11:37
     */
    public function getOptions()
    {
        return array_merge([
            'base_uri'        => property_exists($this, 'baseUri') ? $this->baseUri : '',
            'timeout'         => property_exists($this, 'timeout') ? $this->timeout : 6.0,
            'connect_timeout' => property_exists($this, 'connectTimeout') ? $this->connectTimeout : 6.0,
        ], $this->httpOptions);
    }

    /**
     * setHttpClient
     *
     * @param  Client  $client
     *
     * @return $this
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-03  11:37
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * request
     *
     * @param         $method
     * @param         $uri
     * @param  array  $options
     *
     * @return mixed
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-03  11:37
     */
    public function request($method, $uri, $options = [])
    {
        return $this->unwrapResponse($this->getHttpClient()->{$method}($uri, $options));
    }

    /**
     * unwrapResponse
     *
     * @param  ResponseInterface  $response
     *
     * @return mixed|string
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-03  11:38
     */
    public function unwrapResponse(ResponseInterface $response)
    {
        return $response->getBody()->getContents();
    }
}