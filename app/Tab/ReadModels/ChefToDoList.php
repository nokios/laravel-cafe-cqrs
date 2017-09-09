<?php

namespace Nokios\Cafe\Tab\ReadModels;

use Illuminate\Support\Collection;
use Nokios\Cafe\Tab\Events\OrderedItem;
use Nokios\Cafe\Tab\Tab;
use Nokios\Cafe\Tab\TabRepository;

/**
 * Class ChefToDoList
 *
 * This will load all tabs from events that are open and load their unserved food orders
 *
 * @package Nokios\Cafe\Tab\ReadModels
 */
class ChefToDoList
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $todoList;

    /**
     * @return \Illuminate\Support\Collection|\Nokios\Cafe\Tab\ReadModels\ToDoListItem[]
     */
    public function getToDoList() : Collection
    {
        if (! $this->todoList) {
            $this->loadFromEvents();
        }
        return $this->todoList;
    }

    private function loadFromEvents()
    {
        $tabs = (new TabRepository)->getOpenTabs();

        $this->todoList = new Collection();

        $tabs->each(function (Tab $tab) {
            $this->todoList->push(new ToDoListItemGroup(
                $tab->getId(),
                $tab->outstandingFood()->map(function (OrderedItem $item) {
                    return new ToDoListItem($item->getMenuNumber(), $item->getDescription());
                })->all()
            ));
        });
    }
}