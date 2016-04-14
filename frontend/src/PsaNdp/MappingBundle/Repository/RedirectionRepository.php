<?php

namespace PsaNdp\MappingBundle\Repository;

use BadMethodCallException;
use OpenOrchestra\ModelInterface\Model\RedirectionInterface;
use OpenOrchestra\ModelInterface\Repository\RedirectionRepositoryInterface;
use OpenOrchestra\Pagination\Configuration\FinderConfiguration;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaRewriteRepository;

/**
 * Class RouteRedirection
 */
class RedirectionRepository implements RedirectionRepositoryInterface
{
    /**
     * @var PsaPageRepository
     */
    protected $pageRepository;

    /**
     * @var PsaRewriteRepository
     */
    protected $rewriteRepository;

    /**
     * @param PsaPageRepository    $pageRepository
     * @param PsaRewriteRepository $rewriteRepository
     */
    public function __construct(PsaPageRepository $pageRepository, PsaRewriteRepository $rewriteRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->rewriteRepository = $rewriteRepository;
    }

    /**
     * @return array
     */
    public function findAll()
    {


        return [];
    }

    /**
     * @param string $id
     *
     * @return RedirectionInterface
     */
    public function find($id)
    {
        throw new BadMethodCallException('TODO: Implement find() method.');
    }

    /**
     * @param array|null $descriptionEntity
     * @param array|null $columns
     * @param string|null $search
     * @param array|null $order
     * @param int|null $skip
     * @param int|null $limit
     *
     * @return array
     */
    public function findForPaginateAndSearch($descriptionEntity = null, $columns = null, $search = null, $order = null, $skip = null, $limit = null)
    {
        throw new BadMethodCallException('TODO: Implement findForPaginateAndSearch() method.');
    }

    /**
     * @param array|null $columns
     * @param array|null $descriptionEntity
     * @param array|null $search
     *
     * @return int
     */
    public function countWithSearchFilter($descriptionEntity = null, $columns = null, $search = null)
    {
        throw new BadMethodCallException('TODO: Implement countWithSearchFilter() method.');
    }

    /**
     * @return int
     */
    public function count()
    {
        throw new BadMethodCallException('TODO: Implement count() method.');
    }

    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return array
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration)
    {
        throw new BadMethodCallException('TODO: Implement findForPaginate() method.');
    }

    /**
     * @param FinderConfiguration $configuration
     *
     * @return int
     */
    public function countWithFilter(FinderConfiguration $configuration)
    {
        throw new BadMethodCallException('TODO: Implement countWithFilter() method.');
    }
}
