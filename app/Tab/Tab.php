<?php

namespace Nokios\Cafe\Tab;

use Nokios\Cafe\Domain\Aggregates\EventSourcedAggregateRoot;
use Nokios\Cafe\Tab\Events\DrinksOrdered;
use Nokios\Cafe\Tab\Events\DrinksServed;
use Nokios\Cafe\Tab\Events\FoodOrdered;
use Nokios\Cafe\Tab\Events\FoodServed;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Events\TabClosed;
use Nokios\Cafe\Tab\Events\TabOpened;
use Nokios\Cafe\Tab\Exceptions\DrinksNotOutstanding;
use Nokios\Cafe\Tab\Exceptions\FoodNotOutstanding;
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

    /** @var \Illuminate\Support\Collection */
    private $preparedFood;

    /** @var float */
    private $servedItemsValue = 0.0;

    protected function __construct()
    {
        // This makes straight instantiation not possible
        $this->outstandingDrinks = collect();
        $this->outstandingFood = collect();
        $this->preparedFood = collect();
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

    public function serveDrinks(array $orderedItems)
    {
        if (! $this->areDrinksOutstanding($orderedItems)) {
            throw new DrinksNotOutstanding;
        }

        $this->apply(new DrinksServed(
            $this->getId(),
            $orderedItems
        ));
    }

    public function serveFood(array $orderedItems)
    {
        if (! $this->areFoodOutstanding($orderedItems)) {
            throw new FoodNotOutstanding;
        }

        $this->apply(new FoodServed(
            $this->getId(),
            $orderedItems
        ));
    }

    public function closeTab(float $amountPaid)
    {
        $this->apply(new TabClosed(
            $this->uuid,
            $amountPaid,
            $this->servedItemsValue,
            ($amountPaid - $this->servedItemsValue)
        ));
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

    private function areDrinksOutstanding(array $items) : bool
    {
        // For each item in question check if it is in the outstanding list.
        // If it is in the outstanding list,
        return collect($items)->filter(function (OrderedItem $drinkInQuestion) {

            return $this->outstandingDrinks->filter(function (OrderedItem $outstandingDrink) use ($drinkInQuestion) {
                return $drinkInQuestion->getMenuNumber() == $outstandingDrink->getMenuNumber();
            })->isEmpty();

        })->isEmpty();
    }


    private function areFoodOutstanding(array $items) : bool
    {
        // For each item in question check if it is in the outstanding list.
        // If it is in the outstanding list,
        return collect($items)->filter(function (OrderedItem $foodInQuestion) {

            return $this->outstandingFood->filter(function (OrderedItem $outstandingFood) use ($foodInQuestion) {
                return $foodInQuestion->getMenuNumber() == $outstandingFood->getMenuNumber();
            })->isEmpty();

        })->isEmpty();
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
        $this->outstandingDrinks = $this->outstandingDrinks->merge($event->getItems());
    }

    protected function applyFoodOrdered(FoodOrdered $event)
    {
        $this->outstandingFood = $this->outstandingFood->merge($event->getItems());
    }

    protected function applyDrinksServed(DrinksServed $event)
    {
        $servedDrinks = collect($event->getMenuNumbers());

        $servedDrinks->each(function (OrderedItem $orderedDrink) {
            $index = $this->outstandingDrinks->search(function (OrderedItem $outstandingDrink) use ($orderedDrink) {
                return $orderedDrink->getMenuNumber() == $outstandingDrink->getMenuNumber();
            });
            /** @var OrderedItem $orderedItem */
            $orderedItem = $this->outstandingDrinks->get($index);
            $this->servedItemsValue += $orderedItem->getPrice();
            $this->outstandingDrinks->forget($index);
        });

        $this->outstandingDrinks = $this->outstandingDrinks->values();
    }

    /**
     * @param \Nokios\Cafe\Tab\Events\FoodServed $event
     */
    protected function applyFoodServed(FoodServed $event)
    {
        $servedFood = collect($event->getServedItems());

        $servedFood->each(function (OrderedItem $orderedFood) {
            $index = $this->outstandingFood->search(function (OrderedItem $outstandingFood) use ($orderedFood) {
                return $orderedFood->getMenuNumber() == $outstandingFood->getMenuNumber();
            });

            /** @var OrderedItem $orderedItem */
            $orderedItem = $this->outstandingFood->get($index);
            $this->servedItemsValue += $orderedItem->getPrice();

            $this->outstandingFood->forget($index);
        });

        $this->outstandingFood = $this->outstandingFood->values();
    }

    protected function applyTabClosed(TabClosed $event)
    {

    }
}
