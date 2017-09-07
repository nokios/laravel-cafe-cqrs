<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Nokios\Cafe\EventStream;
use Nokios\Cafe\Tab\Commands\CloseTab;
use Nokios\Cafe\Tab\Commands\MarkDrinksServed;
use Nokios\Cafe\Tab\Commands\MarkFoodServed;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Commands\PlaceOrder;
use Nokios\Cafe\Tab\Events\DrinksOrdered;
use Nokios\Cafe\Tab\Events\DrinksServed;
use Nokios\Cafe\Tab\Events\FoodOrdered;
use Nokios\Cafe\Tab\Events\FoodServed;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Events\TabClosed;
use Nokios\Cafe\Tab\Events\TabOpened;
use Nokios\Cafe\Tab\Handlers\CloseTabHandler;
use Nokios\Cafe\Tab\Handlers\MarkDrinksServedHandler;
use Nokios\Cafe\Tab\Handlers\MarkFoodServedHandler;
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

    /** @var OrderedItem */
    private $testDrink1;

    /** @var OrderedItem */
    private $testDrink2;

    /** @var OrderedItem */
    private $testFood1;

    /** @var OrderedItem */
    private $testFood2;

    protected function setUp()
    {
        parent::setUp();

        $this->id = Uuid::uuid4();
        $this->tableNumber = 42;
        $this->waiter = 'Liz';

        $this->testDrink1 = new OrderedItem(
            10,
            'Coke',
            true,
            1.50
        );
        $this->testDrink2 = new OrderedItem(
            11,
            'Beer',
            true,
            4.50
        );

        $this->testFood1 = new OrderedItem(
            20,
            'Soup de Jour',
            false,
            3.50
        );
        $this->testFood2 = new OrderedItem(
            21,
            'Salad',
            false,
            5.50
        );
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

        $command = new PlaceOrder($this->id, [$this->testDrink1]);
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

        $command = new PlaceOrder($this->id, [$this->testFood1]);
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
            $this->testDrink1,
            $this->testFood1,
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

        $command = new PlaceOrder($this->id, [$this->testDrink1]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $command = new MarkDrinksServed($this->id, [$this->testDrink1]);
        $commandHandler = new MarkDrinksServedHandler($command);
        $commandHandler->handle();

        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            DrinksOrdered::class,
            DrinksServed::class
        ]);
    }

    /**
     * @expectedException \Nokios\Cafe\Tab\Exceptions\DrinksNotOutstanding
     */
    public function testCanNotServeAnUnorderedDrink()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [$this->testDrink1]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $command = new MarkDrinksServed($this->id, [$this->testDrink2]);
        $commandHandler = new MarkDrinksServedHandler($command);
        $commandHandler->handle();
    }

    public function testOrderedFoodCanBeServed()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [$this->testFood1]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $command = new MarkFoodServed($this->id, [$this->testFood1]);
        $commandHandler = new MarkFoodServedHandler($command);
        $commandHandler->handle();

        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            FoodOrdered::class,
            FoodServed::class
        ]);
    }

    /**
     * @expectedException \Nokios\Cafe\Tab\Exceptions\FoodNotOutstanding
     */
    public function testCanNotServeAnUnorderedFood()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [$this->testFood1]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $command = new MarkFoodServed($this->id, [$this->testFood2]);
        $commandHandler = new MarkFoodServedHandler($command);
        $commandHandler->handle();
    }

    public function testCanCloseTabWithTip()
    {
        $command = new OpenTab($this->id, $this->tableNumber, $this->waiter);
        $commandHandler = new OpenTabHandler($command);
        $commandHandler->handle();

        $command = new PlaceOrder($this->id, [$this->testFood1]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();

        $command = new MarkFoodServed($this->id, [$this->testFood1]);
        $commandHandler = new MarkFoodServedHandler($command);
        $commandHandler->handle();

        $command = new CloseTab($this->id, $this->testFood1->getPrice() + 0.50);
        $commandHandler = new CloseTabHandler($command);
        $commandHandler->handle();

        $this->assertEventsSeen($this->id, [
            TabOpened::class,
            FoodOrdered::class,
            FoodServed::class,
            [
                'class' => TabClosed::class,
                'properties' => [
                    'amountPaid' => $this->testFood1->getPrice() + 0.50,
                    'orderValue' => $this->testFood1->getPrice(),
                    'tipValue' => 0.50
                ]
            ]
        ]);
    }

    /* ---------------------------------------------------------------------------------------------------------------+
     |
     +-------------------------------------------------------------------------------------------------------------- */

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

        collect($events)->each(function ($options, $index) use ($recordedEvents) {
            if (is_array($options)) {
                $eventClass = array_get($options, 'class');
                $properties = array_get($options, 'properties');
            } else {
                $eventClass = $options;
                $properties = [];
            }

            $event = $recordedEvents[$index];
            $this->assertInstanceOf($eventClass, $event);

            collect($properties)->each(function ($value, $property) use ($event) {
                $methodName = 'get' . studly_case($property);
                $this->assertEquals($value, $event->$methodName(), "Property Value Mismatch for $property");
            });
        });
    }
}
