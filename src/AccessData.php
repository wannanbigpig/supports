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
use Countable;
use ArrayIterator;

class AccessData implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Deposited data.
     *
     * @var array
     */
    public $items = [];

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
     * __toString
     *
     *
     * @return string
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-29  14:57
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->toJson();
    }


    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->getItems(), $option);
    }

    public function set($key, $value)
    {
        if (is_null($key)) {
            $this->setItems($value);
            return $array = $value;
        }

        $array = $this->getItems();

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;

        $this->setItems($array);

        return $array;
    }

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
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }


    public function count()
    {
        return count($this->getItems());
    }

    public function offsetExists($offset)
    {
        return !is_null($this->get($offset));
    }

    /**
     * offsetSet
     *
     * @param mixed $offset
     * @param mixed $value
     *
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-29  15:16
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * offsetGet
     *
     * @param mixed $offset
     *
     * @return array|mixed|null
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-29  15:18
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * offsetUnset
     *
     * @param mixed $offset
     *
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-29  15:18
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($this->getItems(), $offset);
        }
    }

    /**
     * getIterator
     *
     *
     * @return ArrayIterator|\Traversable
     *
     *
     * @author   liuml  <liumenglei0211@163.com>
     *
     * @DateTime 2019-03-29  15:23
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getItems());
    }
}