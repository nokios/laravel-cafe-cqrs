<?php

namespace Nokios\Cafe\Domain\Handlers;

/**
 * Interface CommandHandlerInterface
 *
 * @package Nokios\Cafe\Domain\Handlers
 */
interface CommandHandlerInterface
{
    /**
     * Handle the event
     */
    public function handle() : void;
}
