<?php


namespace Nokios\Cafe\Tab\ReadModels;


use Nokios\Cafe\Tab\Tab;
use Nokios\Cafe\Tab\TabRepository;

class EventSourcedOpenTabQueries implements OpenTabQueriesInterface
{
    private $tabRepository;

    public function __construct()
    {
        $this->tabRepository = new TabRepository;
    }

    public function getActiveTableNumbers()
    {
        // TODO: Implement getActiveTableNumbers() method.
    }

    public function getInvoiceForTable()
    {
        // TODO: Implement getInvoiceForTable() method.
    }

    public function getTabForTable()
    {
        // TODO: Implement getTabForTable() method.
    }

    public function getTodoListForWaiter()
    {
        // TODO: Implement getTodoListForWaiter() method.
    }

    public function getOpenTabs()
    {
        return $this->tabRepository->getOpenTabs()->map(function (Tab $tab) {
            return [
                'id' => "{$tab->getId()}",
                'waiter' => $tab->getWaiter(),
                'table_number' => $tab->getTableNumber(),
            ];
        });
    }
}