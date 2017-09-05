<?php


namespace Nokios\Cafe\Tab\Events;


use Nokios\Cafe\Domain\Events\SerializableEvent;

class OrderedItem implements SerializableEvent
{
    /** @var int */
    private $menuNumber;

    /** @var string */
    private $description;

    /** @var bool */
    private $isDrink;

    /** @var float */
    private $price;

    /**
     * OrderedItem constructor.
     *
     * @param int    $menuNumber
     * @param string $description
     * @param bool   $isDrink
     * @param float  $price
     */
    public function __construct(int $menuNumber, string $description, bool $isDrink, float $price)
    {
        $this->menuNumber = $menuNumber;
        $this->description = $description;
        $this->isDrink = $isDrink;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getMenuNumber(): int
    {
        return $this->menuNumber;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isDrink(): bool
    {
        return $this->isDrink;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'menuNumber' => $this->menuNumber,
            'description' => $this->description,
            'isDrink' => $this->isDrink,
            'price' => $this->price,
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
            array_get($data, 'menuNumber'),
            array_get($data, 'description'),
            array_get($data, 'isDrink'),
            array_get($data, 'price')
        );
    }
}