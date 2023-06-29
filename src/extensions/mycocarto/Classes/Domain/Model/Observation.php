<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Observation extends AbstractEntity
{
    protected DateTime $date;

    protected float $latitude;

    protected float $longitude;

    protected Ecology $ecology;

    protected ObjectStorage $trees;

    protected Species $species;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->trees = new ObjectStorage();
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return Ecology
     */
    public function getEcology(): Ecology
    {
        return $this->ecology;
    }

    /**
     * @param Ecology $ecology
     */
    public function setEcology(Ecology $ecology): void
    {
        $this->ecology = $ecology;
    }

    /**
     * @return ObjectStorage
     */
    public function getTrees(): ObjectStorage
    {
        return $this->trees;
    }

    /**
     * @param ObjectStorage $trees
     * @return void
     */
    public function setTrees(ObjectStorage $trees): void
    {
        $this->trees = $trees;
    }

    /**
     * @return Species
     */
    public function getSpecies(): Species
    {
        return $this->species;
    }

    /**
     * @param Species $species
     */
    public function setSpecies(Species $species): void
    {
        $this->species = $species;
    }
}