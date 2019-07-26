<?php
/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports\Http;

use Psr\Http\Message\ResponseInterface;
use WannanBigPig\Supports\Collection;

/**
 * Class Response
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-07-20  14:11
 */
class Response extends \GuzzleHttp\Psr7\Response
{
    /**
     * @var array
     */
    public $array = [];

    /**
     * @return string
     */
    final public function getBodyContents()
    {
        return (string)$this->getBody();

    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \WannanBigPig\Supports\Http\Response
     */
    public static function buildFromPsrResponse(ResponseInterface $response)
    {
        $responses = new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
        $responses->getBody()->rewind();

        return $responses;
    }

    /**
     * Build to json.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * toArray.
     *
     * @param string|null $content
     *
     * @return array
     */
    public function toArray(string $content = null)
    {
        if (!empty($this->array)) {
            return $this->array;
        }
        if (is_null($content)) {
            $content = $this->getBodyContents();
        }

        $array = \GuzzleHttp\json_decode($content, true, 512, JSON_BIGINT_AS_STRING);

        if (JSON_ERROR_NONE === json_last_error()) {
            return (array)$array;
        }

        return [];
    }

    /**
     * toCollection.
     *
     * @return \WannanBigPig\Supports\Collection
     */
    public function toCollection()
    {
        return new Collection($this->toArray());
    }

    /**
     * @return object
     */
    public function toObject()
    {
        return json_decode($this->toJson());
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->getBodyContents();
    }
}
