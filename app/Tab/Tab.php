<?php

namespace Nokios\Cafe\Tab;

use Nokios\Cafe\Domain\Aggregates\EventSourcedAggregateRoot;
use Nokios\Cafe\Tab\Events\DrinksOrdered;
use Nokios\Cafe\Tab\Events\DrinksServed;
use Nokios\Cafe\Tab\Events\FoodOrdered;
use Nokios\Cafe\Tab\Events\FoodPrepared;
use Nokios\Cafe\Tab\Events\FoodServed;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Events\TabClosed;
use Nokios\Cafe\Tab\Events\TabOpened;
use Nokios\Cafe\Tab\Exceptions\DrinksNotOutstanding;
use Nokios\Cafe\Tab\Exceptions\FoodNotOutstanding;
use Nokios\Cafe\Tab\Exceptions\MustPayEnough;
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

    public function isOpen()
    {
        return $this->open;
    }

    /**
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param int               $tableNumber
     * @param string            $waiter
     *
     * @return \Nokios\Cafe\Tab\Tab
     */
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

    public function markFoodPrepared(array $menuNumbers)
    {
        if (! $this->isFoodOutstanding($menuNumbers)) {
            throw new FoodNotOutstanding;
        }

        $this->apply(new FoodPrepared(
            $this->uuid,
            $menuNumbers
        ));
    }

    public function serveDrinks(array $menuNumbers)
    {
        if (! $this->areDrinksOutstanding($menuNumbers)) {
            throw new DrinksNotOutstanding;
        }

        $this->apply(new DrinksServed(
            $this->getId(),
            $menuNumbers
        ));
    }

    public function serveFood(array $menuNumbers)
    {
        if (! $this->isFoodPrepared($menuNumbers)) {
            throw new FoodNotOutstanding;
        }

        $this->apply(new FoodServed(
            $this->getId(),
            $menuNumbers
        ));
    }

    public function closeTab(float $amountPaid)
    {
        if ($amountPaid < $this->servedItemsValue) {
            throw new MustPayEnough("They shortchanged us!");
        }

        $this->apply(new TabClosed(
            $this->uuid,
            $amountPaid,
            $this->servedItemsValue,
            ($amountPaid - $this->servedItemsValue)
        ));
    }

    private function areDrinksOutstanding(array $menuNumbers) : bool
    {
        // For each item in question check if it is in the outstanding list.
        // If it is in the outstanding list,
        return collect($menuNumbers)->filter(function ($menuNumberInQuestion) {

            return $this->outstandingDrinks->filter(
                function (OrderedItem $outstandingDrink) use ($menuNumberInQuestion) {
                    return $menuNumberInQuestion == $outstandingDrink->getMenuNumber();
                })
                ->isEmpty();

        })->isEmpty();
    }

    private function isFoodOutstanding(array $menuNumbers) : bool
    {
        // For each item in question check if it is in the outstanding list.
        // If it is in the outstanding list,
        return collect($menuNumbers)->filter(function ($menuNumberInQuestion) {

            return $this->outstandingFood->filter(
                function (OrderedItem $outstandingFood) use ($menuNumberInQuestion) {
                    return $menuNumberInQuestion == $outstandingFood->getMenuNumber();
                })->isEmpty();

        })->isEmpty();
    }

    private function isFoodPrepared(array $menuNumbers) : bool
    {
        // For each item in question check if it is in the outstanding list.
        // If it is in the outstanding list,
        return collect($menuNumbers)->filter(function ($menuNumberInQuestion) {

            return $this->preparedFood->filter(
                function (OrderedItem $outstandingFood) use ($menuNumberInQuestion) {
                    return $menuNumberInQuestion == $outstandingFood->getMenuNumber();
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

    protected function applyFoodPrepared(FoodPrepared $event)
    {
        collect($event->getMenuNumbers())->each(function ($menuNumber) {
            $index = $this->outstandingFood->search(function (OrderedItem $outstandingFood) use ($menuNumber) {
                return $menuNumber == $outstandingFood->getMenuNumber();
            });

            /** @var OrderedItem $orderedItem */
            $orderedItem = $this->outstandingFood->get($index);

            $this->outstandingFood->forget($index);

            $this->preparedFood->push($orderedItem);
        });
    }

    protected function applyDrinksServed(DrinksServed $event)
    {
        $servedDrinks = collect($event->getMenuNumbers());

        $servedDrinks->each(function ($menuNumber) {
            $index = $this->outstandingDrinks->search(function (OrderedItem $outstandingDrink) use ($menuNumber) {
                return $menuNumber == $outstandingDrink->getMenuNumber();
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
        $servedFood = collect($event->getMenuNumbers());

        $servedFood->each(function ($menuNumber) {
            $index = $this->preparedFood->search(function (OrderedItem $preparedFood) use ($menuNumber) {
                return $menuNumber == $preparedFood->getMenuNumber();
            });

            /** @var OrderedItem $orderedItem */
            $orderedItem = $this->preparedFood->get($index);
            $this->servedItemsValue += $orderedItem->getPrice();

            $this->preparedFood->forget($index);
        });

        $this->preparedFood = $this->preparedFood->values();
    }

    protected function applyTabClosed(TabClosed $event)
    {
        $this->open = false;
    }
}
