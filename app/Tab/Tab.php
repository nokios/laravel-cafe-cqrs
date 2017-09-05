<?php

namespace Nokios\Cafe\Tab;

use Nokios\Cafe\Domain\Aggregates\EventSourcedAggregateRoot;
use Nokios\Cafe\Tab\Events\TabOpened;
use Ramsey\Uuid\Uuid;

class Tab extends EventSourcedAggregateRoot
{
    /** @var \Ramsey\Uuid\Uuid */
    private $uuid;

    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    protected function __construct()
    {
        // This makes straight instantiation not possible
    }

    public static function openTab(Uuid $tabId, int $tableNumber, string $waiter)
    {
        $tab = new self;

        $tab->apply(
            new TabOpened($tabId, $tableNumber, $waiter)
        );

        return $tab;
    }

    public function getId() : Uuid
    {
        return $this->uuid;
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
     * @param \Nokios\Cafe\Tab\Events\TabOpened $event
     */
    protected function applyTabOpened(TabOpened $event)
    {
        $this->uuid = $event->getTabId();
        $this->tableNumber = $event->getTableNumber();
        $this->waiter = $event->getWaiter();
    }
}
