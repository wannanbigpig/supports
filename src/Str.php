<?php

/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports;

use WannanBigPig\Supports\Exceptions\RuntimeException;

/**
 * Class Str
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-06-25  10:17
 *
 * @package  WannanBigPig\Supports
 */
class Str
{

    /**
     * This is a cache of case strings
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * studly
     *
     * @param $value
     *
     * @return mixed
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-02  14:31
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * getRandomString
     *
     * @param int $length
     *
     * @return string
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  09:31
     */
    public static function getRandomString($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param int $length
     *
     * @return string
     *
     * @throws \WannanBigPig\Supports\Exceptions\RuntimeException
     */
    public static function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = static::randomBytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }


    /**
     * Get random integers.
     *
     * @param int  $length Random integer length
     * @param bool $date   Does it include subtle time? Boolean value
     *
     * @return bool|string
     */
    public static function getRandomInt($length = 16, $date = false)
    {
        list($usec, $sec) = explode(' ', microtime());
        $microsecond = date('YmdHis', $sec).mb_substr($usec, 2, 6);
        $integer = '0123456789';
        $str = substr(str_shuffle($microsecond.$integer), 0, $length);
        if ($date === true) {
            return $microsecond.$str;
        }

        return $str;
    }

    /**
     * Generate a more truly "random" bytes..
     *
     * @param int $length
     *
     * @return string
     *
     * @throws \WannanBigPig\Supports\Exceptions\RuntimeException
     * @throws \Exception
     */
    public static function randomBytes($length = 16)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if (false === $bytes || false === $strong) {
                throw new RuntimeException('Unable to generate random string.');
            }
        } else {
            throw new RuntimeException('OpenSSL extension is required for PHP 5 users.');
        }

        return $bytes;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param $haystack
     * @param $needles
     *
     * @return bool
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-08  14:30
     */
    public static function endsWith(string $haystack, string $needles)
    {
        if (substr($haystack, -(strlen($needles))) === (string)$needles) {
            return true;
        }

        return false;
    }
}