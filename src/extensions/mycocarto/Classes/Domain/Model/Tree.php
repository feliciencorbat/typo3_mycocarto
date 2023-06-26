<?php

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Tree extends AbstractEntity
{
    protected string $name;

    protected string $scientificName;

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
    public function getScientificName(): string
    {
        return $this->scientificName;
    }

    /**
     * @param string $scientificName
     */
    public function setScientificName(string $scientificName): void
    {
        $this->scientificName = $scientificName;
    }
}