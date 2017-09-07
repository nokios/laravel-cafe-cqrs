<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class FoodPrepared implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array */
    private $menuNumbers = [];

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
            'tabId' => $this->getTabId()->toString(),
            'menuNumbers' => $this->getMenuNumbers()
        ];
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromPayload(array $data) : FoodPrepared
    {
        return new static(
            Uuid::fromString(array_get($data, 'tabId')),
            array_get($data, 'menuNumbers')
        );
    }
}