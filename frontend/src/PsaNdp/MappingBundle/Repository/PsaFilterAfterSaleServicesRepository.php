<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaFilterAfterSaleServicesRepository
 */
class PsaFilterAfterSaleServicesRepository extends EntityRepository
{

    public function findAllFiltersAssociatedToApv($siteId, $languageId) {

        $qb = $this->createQueryBuilder('filter_after_sale_services')
            ->innerJoin('psa_after_sale_services_filters_relation', 'relations', 'ON', 'filter_after_sale_services.id = relations.id')
            ->where('filter_after_sale_services.site = :siteId')
            ->andWhere('filter_after_sale_services.language = :languageId')
            ->setParameter('siteId', $siteId)
            ->setParameter('languageId', $languageId);

        return $qb->getQuery()->getResult();

    }

    public function getUsedFilters()
    {

    }
}
