<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class FoodServed implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array  */
    private $servedItems = [];

    /**
     * DrinksServed constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param array             $servedItems
     */
    public function __construct(Uuid $tabId, array $servedItems)
    {
        $this->tabId = $tabId;
        $this->servedItems = $servedItems;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): Uuid
    {
        return $this->tabId;
    }

    /**
     * @return array
     */
    public function getServedItems(): array
    {
        return $this->servedItems;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'tabId' => $this->getTabId(),
            'servedItems' => collect($this->getServedItems())->map(function (OrderedItem $item) {
                return $item->getPayload();
            })->all()
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
            Uuid::fromString(array_get($data, 'tabId')),
            collect(array_get($data, 'servedItems'))->map(function (array $data) {
                return OrderedItem::fromPayload($data);
            })->all()
        );
    }
}