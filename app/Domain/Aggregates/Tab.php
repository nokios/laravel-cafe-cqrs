<?php


namespace Nokios\Cafe\Domain\Aggregates;


use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Tab extends AggregateRoot
{
    /** @var \Ramsey\Uuid\Uuid */
    private $uuid;

    protected function aggregateId(): string
    {
        return $this->uuid->toString();
    }

    /**
     * Apply given event
     *
     * @param \Prooph\EventSourcing\AggregateChanged $event
     */
    protected function apply(AggregateChanged $event): void
    {
        // TODO: Implement apply() method.
    }
}