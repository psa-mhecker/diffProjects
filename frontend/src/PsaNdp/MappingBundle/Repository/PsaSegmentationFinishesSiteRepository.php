<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaSegmentationFinishesSite;

/**
 * Class PsaSegmentationFinishesSiteRepository
 * @package PsaNdp\MappingBundle\Repository
 */
class PsaSegmentationFinishesSiteRepository extends EntityRepository
{

    /**
     * @param $siteId
     * @param $languageCode
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function findBySiteAndLanguageQuery($siteId, $languageCode)
    {
        return $this->createQueryBuilder('sfs')
            ->innerJoin(
                'sfs.langue',
                'language'
            )
            ->where('sfs.site =:site')
            ->andWhere('language.langueCode =:language')
            ->setParameter(':site', $siteId)
            ->setParameter(':language', $languageCode);
    }

    /**
     * @param $siteId
     * @param $languageCode
     *
     * @return array
     */
    public function findAllBySiteAndLanguage($siteId, $languageCode)
    {
        return $this->findBySiteAndLanguageQuery($siteId, $languageCode)->getQuery()->getResult();
    }

    /**
     * @param  integer $siteId
     * @param string $languageCode
     * @return null|PsaSegmentationFinishesSite
     */
    public function findDefaultSegmentation($siteId, $languageCode)
    {
        return $this->findBySiteAndLanguageQuery($siteId, $languageCode)
            ->andWhere('sfs.id =:defaultId')
            ->setParameter(':defaultId', 1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
