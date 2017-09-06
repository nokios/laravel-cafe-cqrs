<?php


namespace Nokios\Cafe\Tab\Commands;


class MarkDrinksServed
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array */
    private $menuNumbers = [];
}