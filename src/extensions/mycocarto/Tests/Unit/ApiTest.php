<?php

namespace Feliciencorbat\Mycocarto\Tests\Unit;

use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use JsonException;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Core\Http\Client\GuzzleClientFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ApiTest extends UnitTestCase
{
    private ?GbifSpecies $gbifSpecies;

    protected function setUp(): void
    {
        parent::setUp();
        $guzzleFactory = new GuzzleClientFactory();
        $requestFactory = new RequestFactory($guzzleFactory);
        $this->gbifSpecies = new GbifSpecies($requestFactory);
    }

    /**
     * @test
     */
    public function getSpeciesTest(): void
    {
        $species = $this->gbifSpecies->getSpeciesByScientificName("Amanita muscaria");
        $this->assertEquals("Amanita", $species->getGenus());
        $this->assertEquals("muscaria", $species->getSpecies());
        $this->assertEquals("Amanitaceae", $species->getFamily()->getScientificName());
        $this->assertEquals("Fungi", $species->getFamily()->getParentTaxon()->getParentTaxon()->getParentTaxon()->getParentTaxon()->getScientificName());
        $this->assertEquals("Kingdom", $species->getFamily()->getParentTaxon()->getParentTaxon()->getParentTaxon()->getParentTaxon()->getTaxonLevel()->getName());
    }

    /**
     * @test
     * @throws JsonException
     */
    public function getSpeciesNotSpeciesExceptionTest(): void
    {
        //test if only genus and not species
        $request = "Amanita";
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Le taxon avec le nom $request n'est pas une espèce.");
        $this->gbifSpecies->getSpeciesByScientificName($request);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function getSpeciesUnknownExceptionTest(): void
    {
        //test if unknown species
        $request = "kjsbdjkbsfd";
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Aucune espèce trouvée dans GBIF avec le nom $request");
        $this->gbifSpecies->getSpeciesByScientificName($request);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function getSpeciesEmptyExceptionTest(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Le nom scientifique ne peut pas être vide.");
        $this->gbifSpecies->getSpeciesByScientificName("");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->gbifSpecies = null;
    }
}