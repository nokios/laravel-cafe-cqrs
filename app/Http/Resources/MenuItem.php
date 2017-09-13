<?php

namespace Nokios\Cafe\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MenuItem extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'price' => $this->price,
            'is_drink' => $this->is_drink
        ];
    }
}
