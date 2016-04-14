<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

class PsaAppliMobileRepository extends EntityRepository
{
    /**
     * @param PsaSite     $site
     * @param PsaLanguage $language
     * @param int         $id
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySiteAndLanguageAndId(PsaSite $site, PsaLanguage $language, $id)
    {
        return  $this->createQueryBuilder('am')
            ->where('am.id = :id')
            ->andWhere('am.site = :site')
            ->andWhere('am.langue = :langue')
            ->setParameter(':id',$id)
            ->setParameter(':site',$site)
            ->setParameter(':langue',$language)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
