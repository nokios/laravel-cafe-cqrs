<?php

namespace Nokios\Cafe\Tab;

use Nokios\Cafe\Domain\Aggregates\EventSourcedAggregateRoot;
use Nokios\Cafe\Tab\Events\DrinksOrdered;
use Nokios\Cafe\Tab\Events\FoodOrdered;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Events\TabOpened;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Ramsey\Uuid\Uuid;

class Tab extends EventSourcedAggregateRoot
{
    /** @var \Ramsey\Uuid\Uuid */
    private $uuid;

    /** @var bool */
    private $open = false;

    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    /** @var \Illuminate\Support\Collection */
    private $outstandingDrinks;

    /** @var \Illuminate\Support\Collection */
    private $outstandingFood;

    protected function __construct()
    {
        // This makes straight instantiation not possible
        $this->outstandingDrinks = collect();
        $this->outstandingFood = collect();
    }

    public static function openTab(Uuid $tabId, int $tableNumber, string $waiter)
    {
        $tab = new self;

        $tab->apply(
            new TabOpened($tabId, $tableNumber, $waiter)
        );

        return $tab;
    }

    /**
     * @param array $items
     *
     * @throws \Nokios\Cafe\Tab\Exceptions\TabNotOpened
     */
    public function placeOrder(array $items)
    {
        if (! $this->open) {
            throw new TabNotOpened;
        }

        $drinks = collect($items)->filter(function (OrderedItem $item) {
            return $item->isDrink();
        });

        if ($drinks->isNotEmpty()) {
            $this->apply(new DrinksOrdered($this->uuid, $drinks->all()));
        }

        $food = collect($items)->filter(function (OrderedItem $item) {
            return ! $item->isDrink();
        });

        if ($food->isNotEmpty()) {
            $this->apply(new FoodOrdered($this->uuid, $food->all()));
        }
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
        $this->open = true;
    }

    protected function applyDrinksOrdered(DrinksOrdered $event)
    {
        $this->outstandingDrinks->merge($event->getItems());
    }

    protected function applyFoodOrdered(FoodOrdered $event)
    {
        $this->outstandingFood->merge($event->getItems());
    }
}
