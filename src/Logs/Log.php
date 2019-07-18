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
 * @method mixed emergency($message, array $context = [])
 * @method mixed alert($message, array $context = [])
 * @method mixed critical($message, array $context = [])
 * @method mixed error($message, array $context = [])
 * @method mixed warning($message, array $context = [])
 * @method mixed notice($message, array $context = [])
 * @method mixed info($message, array $context = [])
 * @method mixed debug($message, array $context = [])
 * @method mixed log($message, array $context = [])
 */
class Log
{
    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    public $config = [];

    /**
     * Log constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * __call.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->getLogger(), $method], $args);
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
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
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
     * Register a custom driver creator Closure.
     *
     * @param string   $driver
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend(\Closure $callback)
    {
        $this->setLogger($callback->bindTo($this, $this));

        return $this;
    }

    public function getDriver()
    {
    }

    /**
     * createLogger.
     *
     * @return \Monolog\Logger
     */
    public function createLogger()
    {
        $handler = $this->config['driver'];
        $handler = call_user_func([$this, $handler.'Driver']);
        $handler->setFormatter($this->formatter());

        $logger = new Logger($this->config['identify'] ?? 'wannanbigpig.support');
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * Get a Monolog formatter instance..
     *
     * @return \Monolog\Formatter\LineFormatter
     */
    public function formatter()
    {
        return new LineFormatter(
            $this->config['format'] ?? null,
            null,
            true,
            true
        );
    }

    /**
     * single.
     *
     * @return \Monolog\Handler\StreamHandler
     *
     * @throws \Exception
     */
    public function singleDriver()
    {
        return new StreamHandler($this->config['path'], $this->level());
    }

    /**
     * daily.
     *
     * @return \Monolog\Handler\RotatingFileHandler
     */
    public function dailyDriver()
    {
        return new RotatingFileHandler($this->config['path'], $this->config['day'], $this->level());
    }

    /**
     *  Parse the string level into a Monolog constant.
     *
     * @return mixed
     */
    protected function level()
    {
        $level = $this->config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new \InvalidArgumentException('Invalid log level.');
    }
}
