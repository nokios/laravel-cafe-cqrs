<?php

namespace Tests\Feature;

use Nokios\Cafe\Domain\Commands\OpenTab;
use Nokios\Cafe\Domain\Events\TabOpened;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $this->expectsEvents(TabOpened::class);

        $command = OpenTab::fromTableNumberAndWaiter($this->tableNumber, $this->waiter);
        \CommandBus::dispatch($command);
    }
}
