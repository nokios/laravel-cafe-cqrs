<?php

namespace Nokios\Cafe;

use Illuminate\Database\Eloquent\Model;
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

}
