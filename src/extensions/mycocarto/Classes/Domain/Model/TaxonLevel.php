<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class TaxonLevel extends AbstractEntity
{
    #[Validate([
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
    ])]
    #[Validate([
        'validator' => 'NotEmpty'
    ])]
    protected string $name;

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
}