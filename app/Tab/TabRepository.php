<?php


namespace Nokios\Cafe\Tab;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nokios\Cafe\Aggregate;
use Nokios\Cafe\Domain\Aggregates\AggregateRootInterface;
use Ramsey\Uuid\Uuid;
use Nokios\Cafe\Infrastructure\EloquentEventStoreRepository;

class TabRepository
{
    public function __construct() {
        $this->eventStoreRepository = new EloquentEventStoreRepository;
    }

    public function save(Tab $tab)
    {
        Aggregate::createAggregateRecord($tab);
        collect($tab->getUncommittedEvents())->each(function($event) use ($tab) {
            $this->eventStoreRepository->append($tab->getId(), $event, new Carbon());
        });
    }

    /**
     * @param \Ramsey\Uuid\Uuid $uuid
     *
     * @return Tab|null
     */
    public function load(Uuid $uuid) : ?Tab
    {
        $events = $this->eventStoreRepository->load($uuid);

        if (empty($events)) {
            return null;
        }

        return Tab::initializeState($events);
    }

    public function getOpenTabs() : Collection
    {
        $tabIds = Aggregate::getAggregateIdsOfType(Tab::class);

        $tabs = $tabIds->map(
            function (Uuid $id) {
                return $this->load($id);
            })
            ->filter();

        return $tabs->filter(function (Tab $tab) {
            return $tab->isOpen();
        })->values();
    }
}