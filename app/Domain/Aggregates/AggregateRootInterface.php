<?php


namespace Nokios\Cafe\Domain\Aggregates;


use Ramsey\Uuid\Uuid;

interface AggregateRootInterface
{
    public function getId() : Uuid;


}