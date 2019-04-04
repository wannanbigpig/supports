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

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Events
 *
 * @method static Event dispatch($eventName, Event $event = NULL) Dispatches an event to all registered listeners
 * @method static array getListeners($eventName = NULL) Gets the listeners of a specific event or all listeners sorted by descending priority.
 * @method static int|null getListenerPriority($eventName, $listener) Gets the listener priority for a specific event.
 * @method static bool hasListeners($eventName = NULL) Checks whether an event has any registered listeners.
 * @method static addListener($eventName, $listener, $priority = 0) Adds an event listener that listens on the specified events.
 * @method static removeListener($eventName, $listener) Removes an event listener from the specified events.
 * @method static addSubscriber(EventSubscriberInterface $subscriber) Adds an event subscriber.
 * @method static removeSubscriber(EventSubscriberInterface $subscriber)
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
     * static __callStatic
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-04-01  16:34
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * __call
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-03  16:58
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * static getDispatcher
     *
     * @return EventDispatcher
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-03  16:58
     */
    public static function getDispatcher(): EventDispatcher
    {
        if (self::$dispatcher) {
            return self::$dispatcher;
        }

        return self::$dispatcher = self::createDispatcher();
    }

    /**
     * static createDispatcher
     *
     * @return EventDispatcher
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-03  16:58
     */
    public static function createDispatcher(): EventDispatcher
    {
        return new EventDispatcher();
    }

    /**
     * static setDispatcher
     *
     * @param EventDispatcher $dispatcher
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-03  16:58
     */
    public static function setDispatcher(EventDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }
}