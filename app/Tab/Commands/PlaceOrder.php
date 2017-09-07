<?php

namespace Nokios\Cafe\Tab\Commands;

use Ramsey\Uuid\Uuid;
use Nokios\Cafe\Domain\Commands\CommandInterface;

/**
 * Class PlaceOrder
 * @package Nokios\Cafe\Tab\Commands
 */
class PlaceOrder implements CommandInterface
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array */
    private $items = [];

    public function __construct(Uuid $tabId, array $items)
    {
        $this->tabId = $tabId;
        $this->items = $items;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId() : Uuid
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
}