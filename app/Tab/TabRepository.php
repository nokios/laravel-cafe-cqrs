<?php


namespace Nokios\Cafe\Tab;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Nokios\Cafe\Infrastructure\EloquentEventStoreRepository;

class TabRepository
{
    public function __construct() {
        $this->eventStoreRepository = new EloquentEventStoreRepository;
    }

    public function save(Tab $tab)
    {
        collect($tab->getUncommittedEvents())->each(function($event) use ($tab) {
            $this->eventStoreRepository->append($tab->getId(), $event, new Carbon());
        });
    }

    /**
     * @param \Ramsey\Uuid\Uuid $uuid
     *
     * @return Tab|null
     */
    public function load(Uuid $uuid)
    {
        $events = $this->eventStoreRepository->load($uuid);

        if (empty($events)) {
            return null;
        }

        return Tab::initializeState($events);
    }
}