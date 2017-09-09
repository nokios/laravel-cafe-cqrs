<?php


namespace Nokios\Cafe\Tab\ReadModels;


use Illuminate\Support\Collection;

interface ChefTodoListQueriesInterface
{
    public function getTodoList() : Collection;
}