<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class SpeciesRepository extends Repository
{
    use PaginationTrait;

    const TABLE_NAME = "tx_mycocarto_domain_model_species";

    protected $defaultOrderings = [
        'genus' => QueryInterface::ORDER_ASCENDING,
        'species' => QueryInterface::ORDER_ASCENDING,
    ];

    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
    }

    /**
     * @throws InvalidQueryException
     */
    public function getSpeciesByQuery(string $search): array
    {
        /*
        $query = $this->createQuery();
        $searchs = explode(" ", $search);

        if(isset($searchs[1])) {
            $query->logicalAnd(
                $query->like('genus', '%' . $searchs[0] . '%'),
                $query->like('species', '%' . $searchs[1] . '%')
            );
        } else {
            $query->like('genus', '%' . $searchs[0] . '%');
        }
        return $query->execute();
        */

        $searchs = explode(" ", $search);
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(ObservationRepository::TABLE_NAME);
        $queryBuilder
            ->select('uid', 'genus', 'species', 'author')
            ->from(SpeciesRepository::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->like('genus', $queryBuilder->createNamedParameter('%' . $searchs[0] . '%'))
            );

        if (isset($searchs[1])) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->like('species', $queryBuilder->createNamedParameter('%' . $searchs[1] . '%'))
                );
        }

        return $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
