<?php


namespace Nokios\Cafe\Domain\Aggregates;


use Nokios\Cafe\Domain\Events\SerializableEvent;
use ReflectionClass;

abstract class EventSourcedAggregateRoot implements AggregateRootInterface
{
    /**
     * @var array
     */
    private $uncommittedEvents = [];

    /**
     * @return array
     */
    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }

    public function apply(SerializableEvent $event) {
        $this->handle($event);

        $this->uncommittedEvents[] = $event;
    }

    /**
     * @param array $events
     *
     * @return static
     */
    public static function initializeState(array $events)
    {
        $aggregateRoot = new static;
         collect($events)->each(function ($event) use ($aggregateRoot) {
             $aggregateRoot->handle($event);
         });

         return $aggregateRoot;
    }

    /**
     * @param SerializableEvent $event
     */
    public function handle(SerializableEvent $event) : void
    {
        $methodName = 'apply' . (new ReflectionClass($event))->getShortName();

        if (! method_exists($this, $methodName)) {
            return;
        }

        $this->$methodName($event);
    }
}