<?php


namespace Nokios\Cafe\Domain\Commands;


class OpenTab
{
    /** @var \Ramsey\Uuid\Uuid */
    public $id;

    /** @var int */
    public $tableNumber;

    /** @var string */
    public $waiter;
}