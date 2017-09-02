<?php


namespace Nokios\Cafe\Domain\Events;

use Prooph\Common\Messaging\DomainEvent;

class TabOpened extends DomainEvent
{
    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    /**
     * TabOpened constructor.
     *
     * @param int    $tableNumber
     * @param string $waiter
     */
    private function __construct(int $tableNumber, string $waiter)
    {
        $this->init();
        $this->tableNumber = $tableNumber;
        $this->waiter = $waiter;
    }

    protected function setPayload(array $payload): void
    {
        $this->tableNumber = array_get($payload, 'tableNumber');
        $this->waiter = array_get($payload, 'waiter');
    }

    public function payload(): array
    {
        return [
            'tableNumber' => $this->tableNumber,
            'waiter' => $this->waiter
        ];
    }
}