<?php

namespace Nokios\Cafe\Domain\Events;

use Prooph\EventSourcing\AggregateChanged;

class TabOpened extends AggregateChanged
{
    /**
     * @return int
     */
    public function tableNumber() : int
    {
        return array_get($this->payload, 'tableNumber');
    }

    /**
     * @return string
     */
    public function waiter() : string
    {
        return array_get($this->payload, 'waiter');
    }
}