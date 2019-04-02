<?php
/**
 * String.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 *
 * DateTime: 2019-04-02  10:37
 */

namespace WannanBigPig\Supports;

class Str
{

    /**
     * This is a cache of case strings
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * static studly
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
}