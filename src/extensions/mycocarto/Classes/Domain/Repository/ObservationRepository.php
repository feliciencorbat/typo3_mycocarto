<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use Feliciencorbat\Mycocarto\Domain\Model\Ecology;
use Feliciencorbat\Mycocarto\Domain\Model\Species;
use JetBrains\PhpStorm\NoReturn;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Exception;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ObservationRepository extends Repository
{
    use PaginationTrait;

    const TABLE_NAME = "tx_mycocarto_domain_model_observation";

    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
    ];

    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
    }


    /**
     * @param Species $species
     * @return void
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function testIfSpeciesExistsInObservation(Species $species): void
    {
        $this->testIfObjectExistsInObservation("species", $species->getUid(), "L'espèce ne peut pas être supprimée car des observations l'utilisent...");
    }

    /**
     * @param Ecology $ecology
     * @return void
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function testIfEcologyExistsInObservation(Ecology $ecology): void
    {
        $this->testIfObjectExistsInObservation("ecology", $ecology->getUid(), "L'écologie ne peut pas être supprimée car des observations l'utilisent...");
    }

    /**
     * @param string $column
     * @param int $uid
     * @param string $message
     * @return void
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    private function testIfObjectExistsInObservation(string $column, int $uid, string $message): void
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(ObservationRepository::TABLE_NAME);
        $count = $queryBuilder
            ->count('uid')
            ->from(ObservationRepository::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq($column, $queryBuilder->createNamedParameter($uid))
            )
            ->andWhere(
                $queryBuilder->expr()->eq("deleted", $queryBuilder->createNamedParameter(0))
            )
            ->executeQuery()
            ->fetchOne();

        if ($count > 0) {
            throw new Exception($message, 403);
        }
    }
}