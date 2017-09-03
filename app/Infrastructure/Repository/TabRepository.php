<?php

namespace Nokios\Cafe\Infrastructure\Repository;

use Nokios\Cafe\Domain\Aggregates\Tab;
use Nokios\Cafe\Domain\Repository\TabRepositoryInterface;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Ramsey\Uuid\Uuid;

class TabRepository extends AggregateRepository implements TabRepositoryInterface
{
    public function add(Tab $tab)
    {
        $this->saveAggregateRoot($tab);
    }

    public function get(Uuid $id): Tab
    {
        return $this->getAggregateRoot($id->toString());
    }
}