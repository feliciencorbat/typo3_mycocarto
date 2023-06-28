<?php

namespace Feliciencorbat\Mycocarto\Event;

final class CompleteSpecies
{
    public function __construct(protected string $mutableProperty)
    {

    }

    /**
     * @return string
     */
    public function getMutableProperty(): string
    {
        return $this->mutableProperty;
    }

    /**
     * @param string $mutableProperty
     */
    public function setMutableProperty(string $mutableProperty): void
    {
        $this->mutableProperty = $mutableProperty;
    }

    /**
     * @return int
     */
    public function getImmutableProperty(): int
    {
        return $this->immutableProperty;
    }



}