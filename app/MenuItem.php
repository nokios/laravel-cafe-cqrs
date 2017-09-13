<?php

namespace Nokios\Cafe;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'description',
        'price',
        'is_drink'
    ];

    protected $casts = [
        'price' => 'float',
        'is_drink' => 'bool'
    ];

    public function scopeIsDrink($query)
    {
        return $query->where('is_drink', true);
    }

    public function scopeIsNotDrink($query)
    {
        return $query->where('is_drink', false);
    }
}
