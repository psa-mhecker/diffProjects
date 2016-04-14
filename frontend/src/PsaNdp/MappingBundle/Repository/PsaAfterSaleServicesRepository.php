<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaAfterSaleServiceRepository
 */
class PsaAfterSaleServicesRepository extends EntityRepository
{
    /**
     * @param $siteId
     * @param $languageId
     *
     * @return array
     */
    public function findBySiteIdAndLanguageId($siteId, $languageId)
    {
        $qb = $this->createQueryBuilder('after_sale_services')
            ->where('after_sale_services.site = :siteId')
            ->andWhere('after_sale_services.language = :languageId')
            ->setParameter('siteId', $siteId)
            ->setParameter('languageId', $languageId);

        return $qb->getQuery()->getResult();
    }
}
