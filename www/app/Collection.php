<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 28/12/2018
 * Time: 19:33
 */

namespace YourOrange;


class Collection implements \IteratorAggregate
{
    protected $items = [];

    /**
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this as $item) {
            /**
             * @var Model $item
             */
            if (is_object($item)) {
                $item = $item->toArray();
            }

            $array[] = $item;
        }

        return $array;
    }

    /**
     * @param $item
     */
    public function push($item)
    {
        $this->items[] = $item;
    }

    /**
     * @return mixed
     */
    public function reset()
    {
        return reset($this->items);
    }

    /**
     * @param bool $pointer
     * @return mixed
     */
    public function first($pointer = false)
    {
        if ($pointer) {
            return $this->reset();
        }

        if (empty($this->items)) {
            return null;
        }

        return $this->items[0];
    }

    /**
     * @param bool $pointer
     * @return mixed
     */
    public function last($pointer = false)
    {
        if ($pointer) {
            return end($this->items);
        }
        return $this->items[count($this->items) - 1];
    }
}