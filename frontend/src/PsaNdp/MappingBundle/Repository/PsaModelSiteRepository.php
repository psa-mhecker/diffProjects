<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;


/**
 * Class PsaModelSiteRepository
 */
class PsaModelSiteRepository extends EntityRepository
{
    /**
     * @param $site
     * @param $languageCode
     *
     * @return mixed
     */
    public function findBySiteIdAndLanguageCode($site, $languageCode)
    {
        $qb = $this->createQueryBuilder('model')
            ->innerJoin(
                'model.language',
                'language'
            )
            ->where('model.site =:site')
            ->andWhere('language.langueCode =:language')
            ->setParameters(array(':site' => $site, ':language' => $languageCode));

        return $qb->getQuery()->execute();
    }

    private function findByModelLanguageAndSiteQb(array $modelIds,PsaSite $site, PsaLanguage $language)
        {
        $queryBuilder = $this->createQueryBuilder('ms');
        $queryBuilder
            ->where($queryBuilder->expr()->in('m.lcdv4', $modelIds))
            ->innerJoin('ms.model','m')
            ->andWhere('ms.site =:site')
            ->andWhere('ms.language =:language')
            ->setParameter(':site', $site)
            ->setParameter(':language', $language)
        ;
        
        return $queryBuilder;
        }

    /**
     * Finds Car Models from a list of LCDV4
     *
     * @param array $modelIds
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return mixed
     */
    public function findByModelLanguageAndSite(array $modelIds, PsaSite $site,PsaLanguage $language)
    {

        return $this->findByModelLanguageAndSiteQb($modelIds, $site, $language)->getQuery()->execute();
    }
    
    /**
     * 
     * @param string $modelId
     * @param \PsaNdp\MappingBundle\Repository\PsaSite $site
     * @param \PsaNdp\MappingBundle\Repository\PsaLanguage $language
     * 
     * @return mixed
     */
    public function findOneByModelLanguageAndSite($modelId, PsaSite $site,PsaLanguage $language)
    {

       return $this->findByModelLanguageAndSiteQb([$modelId], $site, $language)->getQuery()
            ->getOneOrNullResult();

    }
}
