<?php

namespace PsaNdp\MappingBundle\Repository;

use BadMethodCallException;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Repository\ApiClientRepositoryInterface;
use OpenOrchestra\Pagination\Configuration\FinderConfiguration;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;

/**
 * use for orchestra 's dependency
 *
 * Class ApiClientRepository
 */
class ApiClientRepository implements ApiClientRepositoryInterface
{
    /**
     * @param string $key
     * @param string $secret
     *
     * @return ApiClientInterface
     */
    public function findOneByKeyAndSecret($key, $secret)
    {
        throw new BadMethodCallException('TODO: Implement findOneByKeyAndSecret() method.');
    }

    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration)
    {
        throw new BadMethodCallException('TODO: Implement findForPaginate() method.');
    }

    /**
     * @return int
     */
    public function count()
    {
        throw new BadMethodCallException('TODO: Implement count() method.');
    }

    /**
     * @param FinderConfiguration $configuration
     *
     * @return mixed
     */
    public function countWithFilter(FinderConfiguration $configuration)
    {
        throw new BadMethodCallException('TODO: Implement countWithFilter() method.');
    }
}
