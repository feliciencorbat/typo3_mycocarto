<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Tree extends AbstractEntity
{
    #[Validate([
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
    ])]
    #[Validate([
        'validator' => 'NotEmpty'
    ])]
    protected string $name;

    #[Validate([
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
    ])]
    #[Validate([
        'validator' => 'NotEmpty'
    ])]
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

    public function getCompleteName(): string
    {
        return $this->scientificName . " - " . $this->name;
    }
}