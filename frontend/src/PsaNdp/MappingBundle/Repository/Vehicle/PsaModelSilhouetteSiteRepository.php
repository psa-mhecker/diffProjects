<?php

namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;

/**
 * Class PsaModelSilhouetteSiteRepository
 */
class PsaModelSilhouetteSiteRepository extends EntityRepository
{
    /**
     * @param string $site
     * @param string $languageCode
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function findBySiteAndLanguageCodeQuery($site, $languageCode)
    {
        $qb = $this->createQueryBuilder('ms')
            ->innerJoin(
                'ms.langue',
                'langue'
            )
            ->where('ms.site =:site')
            ->andWhere('langue.langueCode =:language')
            ->setParameter(':site', $site)
            ->setParameter(':language', $languageCode);

        return $qb;
    }

    /**
     * @param string $site
     * @param string $languageCode
     *
     * @return mixed
     */
    public function findBySiteIdAndLanguageCode($site, $languageCode)
    {

        return $this->findBySiteAndLanguageCodeQuery($site, $languageCode)
            ->getQuery()->getResult();
    }

    /**
     * @param integer $site
     * @param string  $languageCode
     * @param string  $lcdv6
     * @param string  $groupingCode
     *
     * @return array
     */
    public function findOneBySiteIdLanguageCodeLcdvAndGroupingCode($site, $languageCode, $lcdv6, $groupingCode)
    {

        return $this->findBySiteAndLanguageCodeQuery($site, $languageCode)
            ->andWhere('ms.lcdv6 =:lcdv6')
            ->andWhere('ms.groupingCode =:groupingCode')
            ->setParameter(':lcdv6', $lcdv6)
            ->setParameter(':groupingCode', $groupingCode)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /**
     * @param integer $site
     * @param string  $languageCode
     * @param string  $lcdv6
     *
     * @return array
     */
    public function findOneBySiteIdLanguageCodeLcdv6($site, $languageCode, $lcdv6)
    {

        return $this->findBySiteAndLanguageCodeQuery($site, $languageCode)
            ->andWhere('ms.lcdv6 =:lcdv6')
            ->setParameter(':lcdv6', $lcdv6)
            ->getQuery()
            ->getOneOrNullResult();

    }


    /**
     * @param PsaSite $site
     * @param PsaLanguage $language
     * @param string  $lcdv4
     *
     * @return PsaModelSilhouetteSite[]
     */
    public function findBySiteIdLanguageCodeLcdv4(PsaSite $site, PsaLanguage $language, $lcdv4)
    {
        $qb = $this
            ->createQueryBuilder('ms')
            ->where('ms.site =:site')
            ->andWhere('ms.langue =:language')
            ->andWhere('ms.lcdv6 LIKE :lcdv4')
            ->setParameter(':site', $site)
            ->setParameter(':language', $language)
            ->setParameter(':lcdv4', $lcdv4 . '%')
            ;

        return $qb->getQuery()->getResult();
    }


  /**
     * @param integer $site
     * @param string  $languageCode
     * @param string  $lcdv4
     *
     * @return array
     */
    public function findOneBySiteIdLanguageCodeLcdv4($site, $languageCode, $lcdv4)
    {

        return $this->findBySiteAndLanguageCodeQuery($site, $languageCode)
            ->andWhere('ms.lcdv6  LIKE :lcdv4')
            ->setParameter(':lcdv4', $lcdv4.'%')
            ->getQuery()
            ->getResult();

    }

}
