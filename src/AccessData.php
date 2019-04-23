<?php
/**
 * StorageMetadata.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 *
 * DateTime: 2019-03-29  14:32
 */

namespace WannanBigPig\Supports;

use IteratorAggregate;
use ArrayAccess;
use Serializable;
use Countable;
use ArrayIterator;
use Traversable;

class AccessData implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * Deposited data.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Initialization data.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->setItems($items);
    }

    /**
     * static __set_state
     *
     * @param array $array
     *
     * @return array
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public static function __set_state(array $array = [])
    {
        return (new static())->getItems();
    }

    /**
     * __get
     *
     * @param $name
     *
     * @return array|mixed|null
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  16:04
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * __set
     *
     * @param $key
     * @param $value
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  16:05
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * __isset
     *
     * @param $name
     *
     * @return bool
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  16:08
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * __toString
     *
     * @return false|string
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * toJson
     *
     * @param int $option
     *
     * @return false|string
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->getItems(), $option);
    }

    /**
     * set
     *
     * @param $key
     * @param $value
     *
     * @return array|mixed
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-08  15:54
     */
    public function set($key, $value)
    {
        // get items
        $array = &$this->items;

        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * merge
     *
     * @param $items
     *
     * @return array
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function merge($items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }

        return $this->getItems();
    }

    /**
     * get
     *
     * @param null $key
     * @param null $default
     *
     * @return array|mixed|null
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function get($key = NULL, $default = NULL)
    {
        $array = $this->getItems();

        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * forget
     *
     * @param $array
     * @param $keys
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function forget(&$array, $keys)
    {
        $original = &$array;

        foreach ((array) $keys as $key) {
            $parts = explode('.', $key);
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                }
            }
            unset($array[array_shift($parts)]);
            // clean up after each pass
            $array = &$original;
        }
    }

    /**
     * has
     *
     * @param $key
     *
     * @return bool
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  16:07
     */
    public function has($key)
    {
        return !is_null($this->get($key));
    }

    /**
     * @param  array  $items
     */
    protected function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        return $this->items;
    }

    /**
     * count
     *
     * @return int
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:19
     */
    public function count()
    {
        return count($this->getItems());
    }

    /**
     * serialize
     *
     * @return string
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * unserialize
     *
     * @param string $serialized
     *
     * @return mixed|void
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }

    /**
     * offsetExists
     *
     * @param mixed $offset
     *
     * @return bool
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-30  10:54
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * offsetSet
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * offsetGet
     *
     * @param string $offset
     *
     * @return array|mixed|null
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function offsetGet($offset = '')
    {
        return $this->get($offset);
    }

    /**
     * offsetUnset
     *
     * @param mixed $offset
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($this->items, $offset);
        }
    }

    /**
     * getIterator
     *
     * @return ArrayIterator|Traversable
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-04  14:18
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}