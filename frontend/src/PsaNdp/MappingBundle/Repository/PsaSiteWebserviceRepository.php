<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PsaNdp\MappingBundle\Entity\PsaSiteWebservice;

/**
 * Class PsaSiteWebserviceRepository
 */
class PsaSiteWebserviceRepository extends EntityRepository
{
    /**
     * @param $siteId
     * @param $webserviceId
     *
     * @return PsaSiteWebservice
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySiteIdAndWebserviceId($siteId, $webserviceId)
    {
        $qb = $this->createQueryBuilder('sitews')
            ->where('sitews.site = :siteId')
            ->andWhere('sitews.clientConfig = :clientConfigId')
            ->setParameter('siteId', $siteId)
            ->setParameter('clientConfigId', $webserviceId);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
