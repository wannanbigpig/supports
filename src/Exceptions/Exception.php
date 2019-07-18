<?php
/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports\Exceptions;

/**
 * Class Exception
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-06-19  15:17
 *
 * @package  WannanBigPig\Supports\Exceptions
 */
class Exception extends \Exception
{
    /**
     * Raw error info.
     *
     * @var array
     */
    public $raw;

    /**
     * Exception constructor.
     *
     * @param string $message
     * @param        $code
     * @param        $raw
     */
    public function __construct($message = '', $code = 0, $raw = null)
    {
        $message = $message ?: 'Unknown Error';
        $this->raw = $raw;

        parent::__construct($message, intval($code));
    }
}
