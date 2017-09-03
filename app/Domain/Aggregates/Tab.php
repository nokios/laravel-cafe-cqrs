<?php


namespace Nokios\Cafe\Domain\Aggregates;


use Nokios\Cafe\Domain\Events\TabOpened;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;

class Tab extends AggregateRoot
{
    /** @var \Ramsey\Uuid\Uuid */
    private $uuid;

    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    protected function aggregateId(): string
    {
        return $this->uuid->toString();
    }

    public function id() : string
    {
        return $this->aggregateId();
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

    public static function new(int $tableNumber, string $waiter)
    {
        $self = new self();

        $self->recordThat(TabOpened::occur(
            Uuid::uuid4(),
            [
                'tableNumber' => $tableNumber,
                'waiter' => $waiter
            ]
        ));

        return $self;
    }

    public function whenTabOpened(TabOpened $event)
    {
        $this->uuid = $event->uuid();
        $this->tableNumber = $event->tableNumber();
        $this->waiter = $event->waiter();
    }
}