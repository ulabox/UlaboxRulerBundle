<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Doctrine\ORM\QueryBuilder;

/**
 * Repository interface definition.
 */
interface RepositoryInterface
{
    public function find($id);

    public function findAll();

    public function findOneBy(array $criteria);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function createPaginator(array $criteria = null, array $orderBy = null);

    public function getPaginator(QueryBuilder $queryBuilder);
}
