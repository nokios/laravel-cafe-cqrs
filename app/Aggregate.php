<?php

namespace Nokios\Cafe;

use Illuminate\Database\Eloquent\Model;
use Nokios\Cafe\Domain\Aggregates\EventSourcedAggregateRoot;
use Ramsey\Uuid\Uuid;

/**
 * Class Aggregate
 * @package Nokios\Cafe
 */
class Aggregate extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'type'
    ];

    public function getIdAttribute()
    {
        return Uuid::fromString($this->attributes['id']);
    }

    public function id()
    {
        return $this->id;
    }

    public function setIdAttribute(Uuid $uuid)
    {
        $this->attributes['id'] = $uuid;
    }

    public static function createAggregateRecord(EventSourcedAggregateRoot $aggregateRoot) : void
    {
        static::firstOrCreate([
            'id' => $aggregateRoot->getId(),
            'type' => get_class($aggregateRoot)
        ]);
    }

    public static function getAggregateIdsOfType(string $type)
    {
        return static::whereType($type)->get()->map->id;
    }
}
