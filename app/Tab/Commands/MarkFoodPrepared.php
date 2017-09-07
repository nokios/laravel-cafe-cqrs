<?php

namespace Nokios\Cafe\Tab\Commands;

use Ramsey\Uuid\Uuid;
use Nokios\Cafe\Domain\Commands\CommandInterface;

class MarkFoodPrepared implements CommandInterface
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var array */
    private $menuNumbers = [];

    /**
     * MarkDrinksServed constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param array             $menuNumbers
     */
    public function __construct(Uuid $tabId, array $menuNumbers)
    {
        $this->tabId = $tabId;
        $this->menuNumbers = $menuNumbers;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): Uuid
    {
        return $this->tabId;
    }

    /**
     * @return array
     */
    public function getMenuNumbers(): array
    {
        return $this->menuNumbers;
    }
}