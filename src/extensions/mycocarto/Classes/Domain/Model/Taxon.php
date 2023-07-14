<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class Taxon extends AbstractEntity
{
    #[Validate([
        'validator' => 'StringLength',
        'options' => ['minimum' => 3, 'maximum' => 150],
    ])]
    #[Validate([
        'validator' => 'NotEmpty'
    ])]
    protected string $scientificName;

    /**
     * @var LazyLoadingProxy|Taxon|null
     */
    #[lazy]
    protected Taxon|LazyLoadingProxy|null $parentTaxon;

    #[Validate([
        'validator' => 'NotEmpty'
    ])]
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