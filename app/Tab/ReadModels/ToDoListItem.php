<?php


namespace Nokios\Cafe\Tab\ReadModels;


class ToDoListItem
{
    /** @var int */
    private $menuNumber;

    /** @var string */
    private $description;

    /**
     * ToDoItem constructor.
     *
     * @param int    $menuNumber
     * @param string $description
     */
    public function __construct(int $menuNumber, string $description)
    {
        $this->menuNumber = $menuNumber;
        $this->description = $description;
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
}