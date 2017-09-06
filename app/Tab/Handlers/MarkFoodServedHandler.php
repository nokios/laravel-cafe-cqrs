<?php

namespace Nokios\Cafe\Tab\Handlers;

use Nokios\Cafe\Tab\Commands\MarkFoodServed;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Nokios\Cafe\Tab\TabRepository;

class MarkFoodServedHandler
{
    /**
     * @var \Nokios\Cafe\Tab\Commands\MarkFoodServed
     */
    protected $command;

    /**
     * OpenTabHandler constructor.
     *
     * @param $command
     */
    public function __construct(MarkFoodServed $command)
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

        $tab->serveFood($this->command->getMenuNumbers());

        $tabRepository->save($tab);
    }
}