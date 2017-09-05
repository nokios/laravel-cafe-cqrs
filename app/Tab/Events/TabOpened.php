<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class TabOpened implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    /**
     * TabOpened constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param int               $tableNumber
     * @param string            $waiter
     */
    public function __construct(Uuid $tabId, int $tableNumber, string $waiter)
    {
        $this->tabId = $tabId;
        $this->tableNumber = $tableNumber;
        $this->waiter = $waiter;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): \Ramsey\Uuid\Uuid
    {
        return $this->tabId;
    }

    /**
     * @return int
     */
    public function getTableNumber(): int
    {
        return $this->tableNumber;
    }

    /**
     * @return string
     */
    public function getWaiter(): string
    {
        return $this->waiter;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'tabId' => "$this->tabId",
            'tableNumber' => $this->tableNumber,
            'waiter' => $this->waiter
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
            array_get($data, 'tableNumber'),
            array_get($data, 'waiter')
        );
    }
}