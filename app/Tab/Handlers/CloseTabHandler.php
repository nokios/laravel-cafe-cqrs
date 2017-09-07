<?php

namespace Nokios\Cafe\Tab\Handlers;

use Nokios\Cafe\Tab\Commands\CloseTab;
use Nokios\Cafe\Tab\Exceptions\TabNotOpened;
use Nokios\Cafe\Tab\TabRepository;

class CloseTabHandler
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
    public function __construct(CloseTab $command)
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

        $tab->closeTab($this->command->getAmountPaid());

        $tabRepository->save($tab);
    }
}