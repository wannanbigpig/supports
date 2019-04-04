<?php
/**
 * ApplicationException.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-04  16:21
 */

namespace WannanBigPig\Supports\Exceptions;


class ApplicationException extends Exception
{
    /**
     * ApplicationException constructor.
     *
     * @param       $message
     * @param array $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('APPLICATION_ERROR: '.$message, $raw, self::APPLICATION_ERROR);
    }
}