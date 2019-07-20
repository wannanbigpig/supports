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
abstract class Response extends \GuzzleHttp\Psr7\Response
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
    final public static function buildFromPsrResponse(ResponseInterface $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
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
     * Build to array.
     *
     * @return array
     */
    abstract public function toArray();

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
