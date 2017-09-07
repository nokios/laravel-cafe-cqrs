<?php

namespace Nokios\Cafe\Tab\Commands;

use Nokios\Cafe\Domain\Commands\CommandInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class CloseTab
 * @package Nokios\Cafe\Tab\Commands
 */
class CloseTab implements CommandInterface
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var int */
    private $amountPaid;

    /**
     * CloseTab constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param int               $amountPaid
     */
    public function __construct(Uuid $tabId, $amountPaid)
    {
        $this->tabId = $tabId;
        $this->amountPaid = $amountPaid;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): Uuid
    {
        return $this->tabId;
    }

    /**
     * @return int
     */
    public function getAmountPaid(): int
    {
        return $this->amountPaid;
    }
}