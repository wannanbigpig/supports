<?php
/**
 * Log.php
 *
 * Created by PhpStorm.
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-03-27  16:28
 */

namespace WannanBigPig\Supports\Logs;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * @method static void emergency($message, array $context = array())
 * @method static void alert($message, array $context = array())
 * @method static void critical($message, array $context = array())
 * @method static void error($message, array $context = array())
 * @method static void warning($message, array $context = array())
 * @method static void notice($message, array $context = array())
 * @method static void info($message, array $context = array())
 * @method static void debug($message, array $context = array())
 * @method static void log($message, array $context = array())
 */
class Log
{
    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    protected static $logger;

    /**
     * @static  __callStatic
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws Exception
     */
    public static function __callStatic($method, $args)
    {
        return forward_static_call_array([self::getLogger(), $method], $args);
    }

    /**
     * __call
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    /**
     * @static  getLogger
     *
     * @return LoggerInterface
     *
     * @throws Exception
     */
    public static function getLogger()
    {
        return self::$logger ? : self::$logger = self::createLogger();
    }

    /**
     * @static  setLogger
     *
     * @param  LoggerInterface  $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @static  hasLogger
     *
     * @return bool
     */
    public static function hasLogger()
    {
        return self::$logger ? true : false;
    }

    /**
     * @static  createLogger
     *
     * @param  null  $file
     * @param  string  $identify
     * @param  int  $level
     * @param  string  $type
     * @param  int  $max_files
     *
     * @return Logger
     *
     * @throws Exception
     */
    public static function createLogger(
        $file = null,
        $identify = 'wannanbigpig.supports',
        $level = Logger::DEBUG,
        $type = 'daily',
        $max_files = 30
    ) {
        $file = is_null($file) ? sys_get_temp_dir().'logs/'.$identify.'.log' : $file;

        $handler = $type === 'single' ? new StreamHandler($file, $level)
            : new RotatingFileHandler($file, $max_files, $level);
        $handler->setFormatter(
            new LineFormatter(
                "%datetime% > %channel%.%level_name% > %message% %context% %extra%\r\n",
                null,
                false,
                true
            )
        );

        $logger = new Logger($identify);
        $logger->pushHandler($handler);

        return $logger;
    }
}