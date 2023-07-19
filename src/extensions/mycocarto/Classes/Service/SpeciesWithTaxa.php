<?php

namespace Feliciencorbat\Mycocarto\Service;

use Exception;
use Feliciencorbat\Mycocarto\Domain\Model\Species;
use Feliciencorbat\Mycocarto\Domain\Model\Taxon;
use Feliciencorbat\Mycocarto\Domain\Model\TaxonLevel;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\TaxonLevelRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\TaxonRepository;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class SpeciesWithTaxa
{
    public function __construct(
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly TaxonRepository $taxonRepository,
        protected readonly TaxonLevelRepository $taxonLevelRepository,
        protected readonly PersistenceManager $persistenceManager
    ) {
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws BadRequestException
     * @throws UnknownObjectException
     * @throws Exception
     */
    public function persistCompleteSpecies(Species $species, string $action): void
    {

        // add kingdom if it doesn't exist
        $kingdom = $species->getFamily()->getParentTaxon()->getParentTaxon()->getParentTaxon()->getParentTaxon();
        $kingdom = $this->addTaxon($kingdom);

        // add phylum if it doesn't exist
        $phylum = $species->getFamily()->getParentTaxon()->getParentTaxon()->getParentTaxon();
        $phylum->setParentTaxon($kingdom);
        $phylum = $this->addTaxon($phylum);

        // add class if it doesn't exist
        $class = $species->getFamily()->getParentTaxon()->getParentTaxon();
        $class->setParentTaxon($phylum);
        $class = $this->addTaxon($class);

        // add order if it doesn't exist
        $order = $species->getFamily()->getParentTaxon();
        $order->setParentTaxon($class);
        $order = $this->addTaxon($order);

        // add family if it doesn't exist
        $family = $species->getFamily();
        $family->setParentTaxon($order);
        $family = $this->addTaxon($family);

        // add species if it doesn't exist and create action
        if ($action == "create") {
            $speciesFound = $this->speciesRepository->findOneBy(['genus' => $species->getGenus(), 'species' => $species->getSpecies()]);
            if (empty($speciesFound)) {
                $species->setFamily($family);
                $this->speciesRepository->add($species);
                $this->persistenceManager->persistAll();
            } else {
                throw new BadRequestException("L'espèce existe déjà.", 404);
            }
            // update species if update action
        } elseif ($action == "update") {
            $speciesFound = $this->speciesRepository->findOneBy(['genus' => $species->getGenus(), 'species' => $species->getSpecies()]);
            $speciesFound->setFamily($family);
            $speciesFound->setGenus($species->getGenus());
            $speciesFound->setSpecies($species->getSpecies());
            $speciesFound->setAuthor($species->getAuthor());
            $this->speciesRepository->update($speciesFound);
        } else {
            throw new Exception("Il ne s'agit ni d'un ajout d'espèce, ni d'une mise-à-jour...", 500);
        }
    }

    /**
     * @throws IllegalObjectTypeException
     */
    private function addTaxon(Taxon $taxon): Taxon
    {
        $taxonLevel = $this->addTaxonLevel($taxon->getTaxonLevel());

        $taxonFound = $this->taxonRepository->findOneBy(['scientificName' => $taxon->getScientificName()]);
        if (empty($taxonFound)) {
            $taxon->setTaxonLevel($taxonLevel);
            $this->taxonRepository->add($taxon);
            $this->persistenceManager->persistAll();
            $taxonFound = $this->taxonRepository->findOneBy(['scientificName' => $taxon->getScientificName()]);
        }

        return $taxonFound;
    }

    /**
     * @throws IllegalObjectTypeException
     */
    private function addTaxonLevel(TaxonLevel $taxonLevel): TaxonLevel
    {
        $taxonLevelFound = $this->taxonLevelRepository->findOneBy(['name' => $taxonLevel->getName()]);
        if (empty($taxonLevelFound)) {
            $this->taxonLevelRepository->add($taxonLevel);
            $this->persistenceManager->persistAll();
            $taxonLevelFound = $this->taxonLevelRepository->findOneBy(['name' => $taxonLevel->getName()]);
        }

        return $taxonLevelFound;
    }
}
