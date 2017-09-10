<?php

namespace Nokios\Cafe\Tab\ReadModels;

interface OpenTabQueriesInterface
{
    public function getActiveTableNumbers();

    public function getInvoiceForTable();

    public function getTabForTable();

    public function getTodoListForWaiter();

    public function getOpenTabs();
}