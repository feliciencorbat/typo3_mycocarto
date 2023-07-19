<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Observation extends AbstractEntity
{
    #[Validate(
        [
        'validator' => 'DateTime',
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    protected ?DateTime $date = null;

    #[Validate(
        [
        'validator' => 'NumberRange',
        'options' => ['minimum' => 450000, 'maximum' => 850000],
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    #[Validate(
        [
        'validator' => 'Float'
        ]
    )]
    protected ?float $latitude = null;

    #[Validate(
        [
        'validator' => 'NumberRange',
        'options' => ['minimum' => 50000, 'maximum' => 300000],
        ]
    )]
    #[Validate(
        [
        'validator' => 'NotEmpty'
        ]
    )]
    #[Validate(
        [
        'validator' => 'Float'
        ]
    )]
    protected ?float $longitude = null;

    protected ?Ecology $ecology = null;

    /**
     * @var ObjectStorage<Tree>
     */
    protected ObjectStorage $trees;

    protected ?Species $species = null;

    protected ?User $user = null;

    protected ?FileReference $image = null;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->trees = new ObjectStorage();
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     */
    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return Ecology|null
     */
    public function getEcology(): ?Ecology
    {
        return $this->ecology;
    }

    /**
     * @param Ecology|null $ecology
     */
    public function setEcology(?Ecology $ecology): void
    {
        $this->ecology = $ecology;
    }

    /**
     * @return ObjectStorage<Tree>
     */
    public function getTrees(): ObjectStorage
    {
        return $this->trees;
    }

    /**
     * @param  Tree $tree
     * @return void
     */
    public function addTree(Tree $tree): void
    {
        $this->trees->attach($tree);
    }

    /**
     * @param  Tree $tree
     * @return void
     */
    public function removeTree(Tree $tree): void
    {
        $this->trees->detach($tree);
    }

    /**
     * @param  ObjectStorage<Tree> $trees
     * @return void
     */
    public function setTrees(ObjectStorage $trees): void
    {
        $this->trees = $trees;
    }

    /**
     * @return Species|null
     */
    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    /**
     * @param Species|null $species
     */
    public function setSpecies(?Species $species): void
    {
        $this->species = $species;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return FileReference|null
     */
    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    /**
     * @param FileReference|null $image
     */
    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }
}
