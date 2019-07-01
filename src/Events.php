<?php
/**
 * EventSupport.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 *
 * DateTime: 2019-04-01  15:55
 */

namespace WannanBigPig\Supports;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Events
 *
 * @method static dispatch($event)
 * @method static getListeners($eventName = null)
 * @method static getListenerPriority($eventName, $listener)
 * @method static hasListeners($eventName = null)
 * @method static addListener($eventName, $listener, $priority = 0)
 * @method static removeListener($eventName, $listener)
 * @method static addSubscriber(EventSubscriberInterface $subscriber)
 * @method static removeSubscriber(EventSubscriberInterface $subscriber)
 * @method static callListeners(iterable $listeners, string $eventName, $event)
 * @method static sortListeners(string $eventName)
 * @method static optimizeListeners(string $eventName)
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-06-27  11:47
 *
 * @package  WannanBigPig\Supports
 */
class Events
{
    /**
     * dispatcher.
     *
     * @var EventDispatcher
     */
    protected static $dispatcher;

    /**
     * @static  __callStatic.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * __call.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * @static  getDispatcher.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public static function getDispatcher(): EventDispatcher
    {
        if (self::$dispatcher) {
            return self::$dispatcher;
        }

        return self::$dispatcher = self::createDispatcher();
    }

    /**
     * @static  createDispatcher.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public static function createDispatcher(): EventDispatcher
    {
        return new EventDispatcher();
    }

    /**
     * @static  setDispatcher.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public static function setDispatcher(EventDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }
}