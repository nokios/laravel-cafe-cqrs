<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class FoodServed implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array  */
    private $menuNumbers = [];

    /**
     * DrinksServed constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param array             $menuNumbers
     */
    public function __construct(Uuid $tabId, array $menuNumbers)
    {
        $this->tabId = $tabId;
        $this->menuNumbers = $menuNumbers;
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
    public function getMenuNumbers(): array
    {
        return $this->menuNumbers;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'tabId' => $this->getTabId(),
            'menuNumbers' => $this->getMenuNumbers()
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
            array_get($data, 'menuNumbers')
        );
    }
}