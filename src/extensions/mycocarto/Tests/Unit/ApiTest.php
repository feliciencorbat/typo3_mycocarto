<?php

namespace Feliciencorbat\Mycocarto\Tests\Unit;

use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Core\Http\Client\GuzzleClientFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ApiTest extends UnitTestCase
{
    private $gbifSpecies;

    protected function setUp(): void
    {
        $guzzleFactory = new GuzzleClientFactory();
        $requestFactory = new RequestFactory($guzzleFactory);
        $this->gbifSpecies = new GbifSpecies($requestFactory);
    }

    /**
     * @test
     */
    public function getSpeciesTest()
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
     */
    public function getSpeciesNotSpeciesExceptionTest()
    {
        //test if only genus and not species
        $request = "Amanita";
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Le taxon avec le nom $request n'est pas une espèce.");
        $this->gbifSpecies->getSpeciesByScientificName($request);
    }

    /**
     * @test
     */
    public function getSpeciesUnknownExceptionTest()
    {
        //test if unknown species
        $request = "kjsbdjkbsfd";
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Aucune espèce trouvée dans GBIF avec le nom $request");
        $this->gbifSpecies->getSpeciesByScientificName($request);
    }

    /**
     * @test
     */
    public function getSpeciesEmptyExceptionTest()
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Le nom scientifique ne peut pas être vide.");
        $this->gbifSpecies->getSpeciesByScientificName("");
    }

    protected function tearDown(): void
    {
        $this->gbifSpecies = null;
    }
}