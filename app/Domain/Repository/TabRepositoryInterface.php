<?php


namespace Nokios\Cafe\Domain\Repository;


use Nokios\Cafe\Domain\Aggregates\Tab;
use Ramsey\Uuid\Uuid;

interface TabRepositoryInterface
{
    public function add(Tab $tab);
    public function get(Uuid $id) : Tab;
}