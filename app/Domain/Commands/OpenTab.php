<?php

namespace Nokios\Cafe\Domain\Commands;

use Prooph\Common\Messaging\Command;

class OpenTab extends Command
{
    /** @var int */
    private $tableNumber;

    /** @var string */
    private $waiter;

    /**
     * OpenTab constructor.
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

    /**
     * @param int    $tableNumber
     * @param string $waiter
     *
     * @return \Nokios\Cafe\Domain\Commands\OpenTab
     */
    public static function fromTableNumberAndWaiter(int $tableNumber, string $waiter)
    {
        return new self($tableNumber, $waiter);
    }

    /**
     * @return int
     */
    public function tableNumber(): int
    {
        return $this->tableNumber;
    }

    /**
     * @return string
     */
    public function waiter(): string
    {
        return $this->waiter;
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
            'waiter' => $this->waiter,
        ];
    }
}