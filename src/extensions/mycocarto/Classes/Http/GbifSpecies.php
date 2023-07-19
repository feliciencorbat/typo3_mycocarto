<?php

namespace Feliciencorbat\Mycocarto\Http;

use Feliciencorbat\Mycocarto\Domain\Model\Species;
use Feliciencorbat\Mycocarto\Domain\Model\Taxon;
use Feliciencorbat\Mycocarto\Domain\Model\TaxonLevel;
use http\Exception\RuntimeException;
use JsonException;
use stdClass;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Core\Http\RequestFactory;

final class GbifSpecies
{
    private const API_URL = 'https://api.gbif.org/v1/species?limit=1&name=';

    public function __construct(
        private readonly RequestFactory $requestFactory,
    ) {
    }

    /**
     * Get species from GBIF by scientific name
     *
     * @param  string $scientificName
     * @return Species
     * @throws BadRequestException
     * @throws JsonException
     */
    public function getSpeciesByScientificName(string $scientificName): Species
    {
        if ($scientificName == "") {
            throw new BadRequestException("Le nom scientifique ne peut pas être vide.", 404);
        }

        // Additional headers for this specific request
        // See: https://docs.guzzlephp.org/en/stable/request-options.html
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => false,
        ];

        // Get a PSR-7-compliant response object
        $response = $this->requestFactory->request(
            self::API_URL . $scientificName,
            'GET',
            $additionalOptions
        );

        // test if response is not 200
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new RuntimeException(
                'Erreur lors de la requête de code ' . $response->getStatusCode()
            );
        }

        // test if response has valid JSON
        if ($response->getHeaderLine('Content-Type') !== 'application/json') {
            throw new RuntimeException(
                "La requête n'a pas retourné du JSON valide",
                500
            );
        }

        // transform body as stdclass
        $content = $response->getBody()->getContents();
        $stdObjectSpeciesList = json_decode($content, false, flags: JSON_THROW_ON_ERROR);
        $stdObjectSpeciesList = $stdObjectSpeciesList->results;

        // test if taxon found
        if (sizeof($stdObjectSpeciesList) == 0) {
            throw new BadRequestException("Aucune espèce trouvée dans GBIF avec le nom $scientificName", 404);
        }
        $stdObjectSpecies = $stdObjectSpeciesList[0];

        // test if kingdom is Fungi
        if ($stdObjectSpecies->kingdom != "Fungi") {
            throw new BadRequestException("Le taxon avec le nom $scientificName n'est pas un champignon.", 404);
        }

        // test if taxon rank is species
        if ($stdObjectSpecies->rank != "SPECIES") {
            throw new BadRequestException("Le taxon avec le nom $scientificName n'est pas une espèce.", 404);
        }

        // test if taxon has doubtful status
        if ($stdObjectSpecies->taxonomicStatus == "DOUBTFUL") {
            throw new BadRequestException("L'espèce avec le nom $scientificName est douteuse.", 404);
        }

        return $this->deserializeSpecies($stdObjectSpecies, $scientificName);
    }

    /**
     * Deserialize stdclass species in species class
     *
     * @param  stdClass $stdObjectSpecies
     * @param  $scientificName
     * @return Species
     * @throws BadRequestException
     */
    private function deserializeSpecies(stdClass $stdObjectSpecies, $scientificName): Species
    {
        // get species from canonical name
        $speciesTaxon = explode(" ", $stdObjectSpecies->canonicalName);
        $speciesTaxon = $speciesTaxon[1];

        if (empty($stdObjectSpecies->kingdom) || empty($stdObjectSpecies->phylum) || empty($stdObjectSpecies->class) || empty($stdObjectSpecies->order) || empty($stdObjectSpecies->family) || empty($stdObjectSpecies->genus) || empty($speciesTaxon)) {
            throw new BadRequestException("L'espèce avec le nom $scientificName n'a pas toute la systématique établie.", 404);
        }

        $taxonsList = ["kingdom" => "Kingdom", 'phylum' => "Phylum", 'class' => "Classe", 'order' => "Ordre", 'family' => "Famille"];

        // create all taxa and taxa levels
        $parentTaxon = null;
        foreach ($taxonsList as $key => $taxonName) {
            $taxon = new Taxon();
            $taxon->setScientificName($stdObjectSpecies->{$key});
            $taxonLevel = new TaxonLevel();
            $taxonLevel->setName($taxonName);
            $taxon->setTaxonLevel($taxonLevel);
            $taxon->setParentTaxon($parentTaxon);
            $parentTaxon = $taxon;
        }

        // create species
        $species = new Species();
        $species->setGenus($stdObjectSpecies->genus);
        $species->setSpecies($speciesTaxon);
        $species->setAuthor($stdObjectSpecies->authorship);
        $species->setFamily($parentTaxon);

        return $species;
    }
}
