<?php

/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports\Logs;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * @method static void emergency($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void log($message, array $context = [])
 */
class Log
{
    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var self
     */
    private static $instance;

    /**
     * @var array
     */
    public $config = [];

    /**
     * @static  __callStatic.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function __callStatic($method, $args)
    {
        self::$instance ?: self::$instance = new static();

        return forward_static_call_array([self::$instance->getLogger(), $method], $args);
    }

    /**
     * getLogger.
     *
     * @return \Monolog\Logger|\Psr\Log\LoggerInterface
     *
     * @throws \Exception
     */
    public function getLogger()
    {
        if ($this->hasLogger()) {
            $logger = $this->logger;
        } else {
            $logger = $this->createLogger();
            $this->setLogger($logger);
        }

        return $logger;
    }

    /**
     * setLogger.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * hasLogger.
     *
     * @return bool
     */
    public function hasLogger()
    {
        return $this->logger ? true : false;
    }

    /**
     * createLogger.
     *
     * @return \Monolog\Logger
     */
    public function createLogger()
    {
        // $file = null, $identify = 'wannanbigpig.supports', $level = Logger::DEBUG, $handler = 'daily', $max_files = 30
        $file = is_null($file) ? sys_get_temp_dir().'logs/'.$identify.'.log' : $file;

        $handler = call_user_func([static::class, $handler]);
        $handler->setFormatter(
            new LineFormatter(
                "%datetime% > %channel% [ %level_name% ] > %message% %context% %extra%\r\n\n",
                null,
                false,
                true
            )
        );

        $logger = new Logger($identify);
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * single.
     *
     * @return \Monolog\Handler\StreamHandler
     *
     * @throws \Exception
     */
    public function single()
    {
        return new StreamHandler($this->config['file'], $this->config['level']);
    }

    /**
     * daily.
     *
     * @return \Monolog\Handler\RotatingFileHandler
     */
    public function daily()
    {
        return new RotatingFileHandler($this->config['file'], $this->config['max_files'], $this->config['level']);
    }
}
