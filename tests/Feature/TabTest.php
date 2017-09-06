<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Nokios\Cafe\EventStream;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Commands\PlaceOrder;
use Nokios\Cafe\Tab\Events\DrinksOrdered;
use Nokios\Cafe\Tab\Events\FoodOrdered;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Events\TabOpened;
use Nokios\Cafe\Tab\Handlers\OpenTabHandler;
use Nokios\Cafe\Tab\Handlers\PlaceOrderHandler;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TabTest extends TestCase
{
    use DatabaseTransactions;

    protected $id;
    protected $tableNumber;
    protected $waiter;

    protected function setUp()
    {
        parent::setUp();

        $this->id = Uuid::uuid4();
        $this->tableNumber = 42;
        $this->waiter = 'Liz';
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanOpenANewTab()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $this->assertEquals(1, EventStream::forAggregateId($this->id)->count());

        $this->assertEventsSeen($this->id, [TabOpened::class]);
    }

    /**
     * @expectedException \Nokios\Cafe\Tab\Exceptions\TabNotOpened
     */
    public function testCanNotOrderWithUnopenedTab()
    {
        $command = new PlaceOrder($this->id, [new OrderedItem(1, 'Coke', true, 2.50)]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $this->assertEquals(0, EventStream::forAggregateId($this->id));
    }

    public function testCanPlaceDrinksOrder()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [new OrderedItem(1, 'Coke', true, 2.50)]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            DrinksOrdered::class
        ]);
    }

    public function testCanPlaceFoodOrder()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [new OrderedItem(2, 'Soup de Jour', false, 4.50)]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            FoodOrdered::class,
        ]);
    }

    public function testCanPlaceFoodAndDrinkOrder()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [
            new OrderedItem(1, 'Coke', true, 2.50),
            new OrderedItem(2, 'Soup de Jour', false, 4.50),
        ]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();


        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            DrinksOrdered::class,
            FoodOrdered::class,
        ]);
    }

    public function testOrderedDrinksCanBeServed()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [new OrderedItem(1, 'Coke', true, 2.50)]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();



        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            DrinksOrdered::class
        ]);
    }

    /**
     * Asserts that the given events are seen and in the correct order
     *
     * @param \Ramsey\Uuid\Uuid $id
     * @param array             $events
     */
    protected function assertEventsSeen(Uuid $id, array $events): void
    {
        /** @var EventStream $recordedEvents */
        $recordedEvents = EventStream::forAggregateId($id)->map(function (EventStream $event) {
            return $event->toEvent();
        });

        $this->assertEquals(count($events), $recordedEvents->count());

        collect($events)->each(function ($eventClass, $index) use ($recordedEvents) {
            $this->assertInstanceOf($eventClass, $recordedEvents[$index]);
        });
    }
}
