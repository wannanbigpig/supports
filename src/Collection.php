<?php

/*
 * This file is part of the wannanbigpig/supports.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Supports;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;

/**
 * Class Collection
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-06-25  10:17
 *
 * @package  WannanBigPig\Supports
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Serializable
{
    /**
     * The collection data.
     *
     * @var array
     */
    protected $items = [];

    /**
     * AccessData constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->merge($items);
    }

    /**
     * Get a data by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Assigns a value to the specified data.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Whether or not an data exists by key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Unset an data by key.
     *
     * @param string $key
     */
    public function __unset($key)
    {
        $this->forget($key);
    }

    /**
     * var_export.
     *
     * @return array
     */
    public function __set_state()
    {
        return $this->all();
    }

    /**
     * Getting an item in a collection by key.
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Set the item value.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * Determine whether the specified key exists.
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return !is_null($this->get($key));
    }

    /**
     * Remove item form Collection.
     *
     * @param string $key
     */
    public function forget($key)
    {
        Arr::forget($this->items, $key);
    }

    /**
     * Retrun items.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Merge data.
     *
     * @param $items
     *
     * @return array
     */
    public function merge($items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }

        return $this->all();
    }

    /**
     * Retrieve the first item.
     *
     * @return mixed
     */
    public function first()
    {
        return Arr::first($this->items);
    }

    /**
     * Retrieve the last item.
     *
     * @return bool
     */
    public function last()
    {
        return Arr::last($this->items);
    }

    /**
     * Build to array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * Build to json.
     *
     * @param int $option
     *
     * @return string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->all(), $option);
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }


    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->get($offset) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }
}