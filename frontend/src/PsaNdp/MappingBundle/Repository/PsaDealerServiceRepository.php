<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaDealerServiceRepository
 */
class PsaDealerServiceRepository extends EntityRepository
{
    public function findByIds(array $serviceIds)
    {
        $qb = $this->createQueryBuilder('dealerService');
        $qb->where($qb->expr()->in('dealerService.id', $serviceIds));

        return $qb->getQuery()->execute();
    }
}
