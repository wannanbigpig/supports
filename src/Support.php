<?php
/**
 * Support.php
 *
 * Created by PhpStorm.
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-03-22  15:12
 */

namespace WannanBigPig\supports;

class Support
{
    /**
     * static make
     * @param       $name
     * @param array $config
     * @return mixed
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-03-22  15:30
     */
    public static function make($name, array $config)
    {
        $namespace   = Kernel\Support\Str::studly($name);
        $application = "\\EasyWeChat\\{$namespace}\\Application";
        return new $application($config);
    }

    /**
     * static __callStatic
     * @param $name
     * @param $arguments
     * @return mixed
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-03-22  15:32
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}