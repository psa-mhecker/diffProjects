<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;

/**
 * Class PsaSitesEtWebservicesPsaRepository
 */
class PsaSitesEtWebservicesPsaRepository extends EntityRepository
{
    /**
     * @param $siteId
     * @return PsaSitesEtWebservicesPsa
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySiteId($siteId)
    {
        return  $this->createQueryBuilder('swp')
            ->where('swp.site = :siteId')
            ->setParameter(':siteId',$siteId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
