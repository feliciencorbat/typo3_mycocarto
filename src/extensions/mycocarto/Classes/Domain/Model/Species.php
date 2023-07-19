<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Species extends AbstractEntity
{
    #[Validate(
        [
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    protected string $genus;

    #[Validate(
        [
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    protected string $species;

    #[Validate(
        [
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    protected ?string $author = null;

    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    protected Taxon $family;

    /**
     * @return string
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * @param string $genus
     */
    public function setGenus(string $genus): void
    {
        $this->genus = $genus;
    }

    /**
     * @return string
     */
    public function getSpecies(): string
    {
        return $this->species;
    }

    /**
     * @param string $species
     */
    public function setSpecies(string $species): void
    {
        $this->species = $species;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Taxon
     */
    public function getFamily(): Taxon
    {
        return $this->family;
    }

    /**
     * @param Taxon $family
     */
    public function setFamily(Taxon $family): void
    {
        $this->family = $family;
    }

    public function getCanonicalName(): string
    {
        return $this->genus . " " . $this->species;
    }
}
