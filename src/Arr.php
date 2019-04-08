<?php
/**
 * Arr.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-08  16:47
 */

namespace WannanBigPig\Supports;


class Arr
{
    /**
     * static encoding
     *
     * @param        $array
     * @param        $to_encoding
     * @param string $from_encoding
     *
     * @return array
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-08  16:48
     */
    public static function encoding($array, $to_encoding, $from_encoding = 'gb2312')
    {
        $encoded = [];

        foreach ($array as $key => $value) {
            $encoded[$key] = is_array($value) ? self::encoding($value, $to_encoding, $from_encoding) :
                mb_convert_encoding($value, $to_encoding, $from_encoding);
        }

        return $encoded;
    }

}