<?php


namespace Nokios\Cafe\Domain\Events;

interface SerializableEvent
{
    /**
     * @return array
     */
    public function getPayload() : array;

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromPayload(array $data);
}