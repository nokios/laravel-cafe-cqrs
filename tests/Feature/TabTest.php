<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Nokios\Cafe\EventStream;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Commands\PlaceOrder;
use Nokios\Cafe\Tab\Events\OrderedItem;
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
    }

    /**
     * @expectedException \Nokios\Cafe\Tab\Exceptions\TabNotOpened
     */
    public function testCanNotOrderWithUnopenedTab()
    {
        $command = new PlaceOrder($this->id, [new OrderedItem(1, 'Coke', true, 2.50)]);
        $commandHandler = new PlaceOrderHandler($command);
        $commandHandler->handle();
    }
}
