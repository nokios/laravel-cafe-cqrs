<?php


namespace Nokios\Cafe\Tab\Handlers;


use Nokios\Cafe\Tab\Commands\PlaceOrder;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Nokios\Cafe\Tab\TabRepository;

class PlaceOrderHandler
{
    /**
     * @var \Nokios\Cafe\Tab\Commands\OpenTab
     */
    protected $command;

    /**
     * OpenTabHandler constructor.
     *
     * @param $command
     */
    public function __construct(PlaceOrder $command)
    {
        $this->command = $command;
    }

    public function handle()
    {
        $tabRepository = new TabRepository;

        $tab = $tabRepository->load($this->command->getTabId());

        if (! $tab) {
            throw new TabNotOpened("No Tab with that ID found");
        }
    }
}