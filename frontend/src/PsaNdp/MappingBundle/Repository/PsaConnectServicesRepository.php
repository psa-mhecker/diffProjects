<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaConnectServicesRepository
 */
class PsaConnectServicesRepository extends EntityRepository
{
    /**
     * Find list of ConnectServices by ids
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        return $this->createQueryBuilder('pcs')->where('pcs.id in (:ids)')->setParameter('ids', $ids)->getQuery()->getResult();
    }
}
