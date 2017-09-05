<?php


namespace Nokios\Cafe\Infrastructure;


use Carbon\Carbon;
use Nokios\Cafe\Domain\Events\EventStoreRepository;
use Nokios\Cafe\Domain\Events\SerializableEvent;
use Nokios\Cafe\EventStream;
use Ramsey\Uuid\Uuid;

class EloquentEventStoreRepository implements EventStoreRepository
{

    public function append(Uuid $uuid, SerializableEvent $event, Carbon $recordedAt): void
    {
        $eventStoreMessage = new EventStream();
        $eventStoreMessage->uuid = $uuid->toString();
        $eventStoreMessage->payload = [
            'class' => get_class($event),
            'payload' => $event->getPayload()
        ];
        $eventStoreMessage->recorded_at = $recordedAt;
        $eventStoreMessage->save();
    }

    public function load(Uuid $uuid): array
    {
        $events = EventStream::forAggregateId($uuid);

        return $events->map(function (EventStream $event) {
            /** @var SerializableEvent $className */
            $className = $event->payload['class'];
            $payload = $event->payload['payload'];

            return $className::fromPayload($payload);
        })
            ->all();

    }
}