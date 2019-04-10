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

    /**
     * static getRandomString
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
        // 字符集，可任意添加你需要的字符
        $chars = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        );
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        shuffle($keys);
        // print_r($keys);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            // 将 $length 个数组元素连接成字符串
            $randomString .= $chars[$keys[$i]];
        }
        return $randomString;
    }

    /**
     * @static   getRandomInt
     * 生成唯一值随机数
     *
     * @param string $prefix
     * @param int    $more_entropy
     *
     * @return string 该函数默认使用年月日时分秒加6位微妙，为保证随机性最小长度20位，可以添加前缀，可以在尾部添加指定长度的随机数字
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  09:34
     */
    public static function getRandomInt($prefix = '', $more_entropy = 0)
    {
        list($usec, $sec) = explode(' ', microtime());
        $str = date('YmdHis', $sec) . mb_substr($usec, 2, 6);
        if ($more_entropy > 0) {
            $chars = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            // 在 $chars 中随机取 $length 个数组元素键名
            $keys = array_rand($chars, $more_entropy);
            shuffle($keys);
            for ($i = 0; $i < $more_entropy; $i++) {
                // 将 $length 个数组元素连接成字符串
                $str .= $chars[$keys[$i]];
            }
        }
        return $prefix . $str;
    }

    /**
     * static endsWith
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

        if (substr($haystack, -strlen($needles)) === (string) $needles) {
            return true;
        }
        return false;
    }
}