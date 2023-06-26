<?php

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Taxon extends AbstractEntity
{
    protected string $scientificName;

    protected ?Taxon $parentTaxon;

    protected TaxonLevel $taxonLevel;

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

    /**
     * @return Taxon|null
     */
    public function getParentTaxon(): ?Taxon
    {
        return $this->parentTaxon;
    }

    /**
     * @param Taxon|null $parentTaxon
     */
    public function setParentTaxon(?Taxon $parentTaxon): void
    {
        $this->parentTaxon = $parentTaxon;
    }

    /**
     * @return TaxonLevel
     */
    public function getTaxonLevel(): TaxonLevel
    {
        return $this->taxonLevel;
    }

    /**
     * @param TaxonLevel $taxonLevel
     */
    public function setTaxonLevel(TaxonLevel $taxonLevel): void
    {
        $this->taxonLevel = $taxonLevel;
    }
}