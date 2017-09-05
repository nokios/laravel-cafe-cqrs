<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class DrinksOrdered implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array */
    private $items = [];

    /**
     * DrinksOrdered constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param array             $items
     */
    public function __construct(Uuid $tabId, array $items)
    {
        $this->tabId = $tabId;
        $this->items = $items;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): \Ramsey\Uuid\Uuid
    {
        return $this->tabId;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'tabId' => $this->getTabId(),
            'items' => $this->getItems()
        ];
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromPayload(array $data)
    {
        return new static(
            array_get($data, 'tabId'),
            array_get($data, 'items')
        );
    }
}