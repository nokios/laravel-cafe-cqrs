<?php

namespace Nokios\Cafe\Tab\Commands;

use Nokios\Cafe\Domain\Commands\CommandInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class OpenTab
 * @package Nokios\Cafe\Domain\Commands
 */
class OpenTab implements CommandInterface
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    public function __construct(Uuid $tabId, int $tableNumber, string $waiter)
    {
        $this->tabId = $tabId;
        $this->tableNumber = $tableNumber;
        $this->waiter = $waiter;
    }

    public function getTabId() : Uuid
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
}