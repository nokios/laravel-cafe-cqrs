<?php

namespace Tests\Feature;

use Nokios\Cafe\EventStream;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Handlers\OpenTabHandler;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TabTest extends TestCase
{
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
}
