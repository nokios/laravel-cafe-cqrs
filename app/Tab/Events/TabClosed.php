<?php

namespace Nokios\Cafe\Tab\Events;

use Nokios\Cafe\Domain\Events\SerializableEvent;
use Ramsey\Uuid\Uuid;

class TabClosed implements SerializableEvent
{
    /** @var \Ramsey\Uuid\Uuid */
    private $tabId;

    /** @var float */
    private $amountPaid;

    /** @var float */
    private $orderValue;

    /** @var float */
    private $tipValue;

    /**
     * TabClosed constructor.
     *
     * @param \Ramsey\Uuid\Uuid $tabId
     * @param float             $amountPaid
     * @param float             $orderValue
     * @param float             $tipValue
     */
    public function __construct(Uuid $tabId, $amountPaid, $orderValue, $tipValue)
    {
        $this->tabId = $tabId;
        $this->amountPaid = $amountPaid;
        $this->orderValue = $orderValue;
        $this->tipValue = $tipValue;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getTabId(): Uuid
    {
        return $this->tabId;
    }

    /**
     * @return float
     */
    public function getAmountPaid(): float
    {
        return $this->amountPaid;
    }

    /**
     * @return float
     */
    public function getOrderValue(): float
    {
        return $this->orderValue;
    }

    /**
     * @return float
     */
    public function getTipValue(): float
    {
        return $this->tipValue;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'tabId' => "$this->tabId",
            'amountPaid' => $this->amountPaid,
            'orderValue' => $this->orderValue,
            'tipValue' => $this->tipValue
        ];
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromPayload(array $data)
    {
        return new static(
            Uuid::fromString(array_get($data, 'tabId')),
            array_get($data, 'amountPaid'),
            array_get($data, 'orderValue'),
            array_get($data, 'tipValue')
        );
    }
}