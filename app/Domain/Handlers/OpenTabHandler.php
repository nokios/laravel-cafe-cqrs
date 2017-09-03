<?php


namespace Nokios\Cafe\Domain\Handlers;


use Nokios\Cafe\Domain\Aggregates\Tab;
use Nokios\Cafe\Domain\Commands\OpenTab;
use Nokios\Cafe\Infrastructure\Repository\TabRepository;

class OpenTabHandler
{
    /**
     * @var \Nokios\Cafe\Infrastructure\Repository\TabRepository
     */
    private $repository;

    public function __construct(TabRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(OpenTab $command)
    {
        $this->repository->add(Tab::new($command->tableNumber(), $command->waiter()));
    }
}