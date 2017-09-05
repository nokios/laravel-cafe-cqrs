<?php


namespace Nokios\Cafe\Tab\Handlers;


use Nokios\Cafe\Tab\Tab;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\TabRepository;

class OpenTabHandler
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
    public function __construct(OpenTab $command)
    {
        $this->command = $command;
    }

    public function handle()
    {
        $tab = Tab::openTab(
            $this->command->getTabId(),
            $this->command->getTableNumber(),
            $this->command->getWaiter()
        );

        $tabRepository = new TabRepository;
        $tabRepository->save($tab);
    }
}