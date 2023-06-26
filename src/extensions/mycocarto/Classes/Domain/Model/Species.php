<?php

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Species extends AbstractEntity
{
    protected string $genus;

    protected string $species;

    protected ?string $author = null;

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
}