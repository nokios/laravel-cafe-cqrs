<?php

namespace Nokios\Cafe;

use Illuminate\Database\Eloquent\Model;
use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

/**
 * Class EventStream
 * @package Nokios\Cafe
 */
class EventStream extends Model
{
    public $timestamps = false;

    protected $dates = [
        'recorded_on',
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public static function forAggregateId(Uuid $uuid)
    {
        return static::where('uuid', "$uuid")
            ->orderBy('recorded_at', 'ASC')
            ->get();
    }

    /**
     * @return SerializableEvent
     */
    public function toEvent()
    {
        /** @var SerializableEvent $className */
        $className = $this->payload['class'];
        $payload = $this->payload['payload'];

        return $className::fromPayload($payload);
    }
}
