<?php

namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaVehicleCategorySiteRepository
 */
class PsaVehicleCategorySiteRepository extends EntityRepository
{
    /**
     * @param integer $site
     * @param string $languageCode
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getBySiteAndLanguageCodeQuery($site, $languageCode){
        return  $this->createQueryBuilder('vehiclecategory')
            ->innerJoin(
                'vehiclecategory.langue',
                'langue'
            )
            ->where('vehiclecategory.site =:site')
            ->andWhere('langue.langueCode =:language')
            ->setParameter(':site',$site)
            ->setParameter(':language',$languageCode)
            ;
    }

    /**
     * @param $site
     * @param $languageCode
     *
     * @return mixed
     */
    public function findBySiteIdAndLanguageCode($site, $languageCode)
    {
        $qb = $this->getBySiteAndLanguageCodeQuery($site,$languageCode);

        return $qb->getQuery()->execute();
    }

    /**
     * @param array $ids
     * @param string $site
     * @param string $languageCode
     *
     * @return mixed
     */
    public function findByIdsSiteAndLanguageCode($ids, $site, $languageCode)
    {
        $qb = $this->getBySiteAndLanguageCodeQuery($site,$languageCode)
            ->andWhere('vehiclecategory.category IN (:ids)')
            ->setParameter(':ids',$ids);

        return $qb->getQuery()->execute();
    }

    /**
     * @param $site
     * @param $languageCode
     * @param $code
     *
     * @return mixed
     */
    public function findOneBySiteIdAndLanguageAndCode($site, $languageCode, $category)
    {
        return $this->getBySiteAndLanguageCodeQuery($site, $languageCode)
            ->andWhere('vehiclecategory.marketingCriteria =:category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
