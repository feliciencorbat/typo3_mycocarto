<?php

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Tree extends AbstractEntity
{
    protected string $name;

    protected string $scientifiName;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getScientifiName(): string
    {
        return $this->scientifiName;
    }

    /**
     * @param string $scientifiName
     */
    public function setScientifiName(string $scientifiName): void
    {
        $this->scientifiName = $scientifiName;
    }
}