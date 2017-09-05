<?php


namespace Nokios\Cafe\Domain\Events;


use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

interface EventStoreRepository
{
    public function append(Uuid $uuid, SerializableEvent $event, Carbon $recordedAt) : void;
    public function load(Uuid $uuid) : array;
}