<?php

namespace Nokios\Cafe\Tab\ReadModels;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ToDoListItemGroup
{
    /**
     * @var \Ramsey\Uuid\Uuid
     */
    private $tabId;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $items;

    /**
     * ToDoListItemGroup constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param array|iterable    $items
     */
    public function __construct(Uuid $tabId, iterable $items = [])
    {
        $this->tabId = $tabId;
        $this->items = collect($items);

        // ensure we have valid items
        $this->items->each(function ($item) {
            $this->validate($item);
        });
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): Uuid
    {
        return $this->tabId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param \Nokios\Cafe\Tab\ReadModels\ToDoListItem $item
     */
    public function add($item)
    {
        $this->validate($item);

        $this->items->push($item);
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    private function isInvalid($item) : bool
    {
        return ! $this->isValid($item);
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    private function isValid($item) : bool
    {
        return ($item instanceof ToDoListItem);
    }

    /**
     * @param $value
     * @throws \InvalidArgumentException When the object is not the proper item
     */
    private function throwException($value): void
    {
        throw new \InvalidArgumentException("Expected a ToDoItem, got " . get_class($value));
    }

    /**
     * @param $item
     */
    private function validate($item)
    {
        if ($this->isInvalid($item)) {
            $this->throwException($item);
        }
    }
}