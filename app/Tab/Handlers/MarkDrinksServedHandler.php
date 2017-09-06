<?php


namespace Nokios\Cafe\Tab\Handlers;


use Nokios\Cafe\Tab\Commands\MarkDrinksServed;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Nokios\Cafe\Tab\TabRepository;

class MarkDrinksServedHandler
{
    /**
     * @var \Nokios\Cafe\Tab\Commands\PlaceOrder
     */
    protected $command;

    /**
     * OpenTabHandler constructor.
     *
     * @param $command
     */
    public function __construct(MarkDrinksServed $command)
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
        // @todo: Add handling here
    }
}