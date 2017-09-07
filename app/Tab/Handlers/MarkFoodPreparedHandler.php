<?php

namespace Nokios\Cafe\Tab\Handlers;

use Nokios\Cafe\Domain\Commands\CommandInterface;
use Nokios\Cafe\Tab\Commands\MarkFoodPrepared;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Nokios\Cafe\Tab\TabRepository;

class MarkFoodPreparedHandler implements CommandInterface
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
    public function __construct(MarkFoodPrepared $command)
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

        $tab->markFoodPrepared($this->command->getMenuNumbers());

        $tabRepository->save($tab);
    }
}