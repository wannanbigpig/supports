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

use Psr\Http\Message\ResponseInterface;
use WannanBigPig\Supports\Exceptions\InvalidArgumentException;
use WannanBigPig\Supports\Http\Response;

/**
 * Trait ResponseCastable
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-07-19  17:10
 */
trait ResponseCastable
{
    /**
     * castResponseToType.
     *
     * @param \Psr\Http\Message\ResponseInterface  $return
     * @param \WannanBigPig\Supports\Http\Response $response
     * @param string                               $type
     *
     * @return array|object|\Psr\Http\Message\ResponseInterface|\WannanBigPig\Supports\Collection|\WannanBigPig\Supports\Http\Response
     *
     * @throws \WannanBigPig\Supports\Exceptions\InvalidArgumentException
     */
    protected function castResponseToType(ResponseInterface $return, Response $response, $type = 'array')
    {
        $return = $response::buildFromPsrResponse($return);
        switch ($type) {
            case 'collection':
                return $return->toCollection();
            case 'array':
                return $return->toArray();
            case 'object':
                return $return->toObject();
            case 'raw':
                return $return;
            default:
                if (!method_exists(Response::class, $type)) {
                    throw new InvalidArgumentException(sprintf(
                        'The conversion type failed, the converted type "%s" is invalid.',
                        $type
                    ));
                }

                return $return->{$type()};
        }
    }
}