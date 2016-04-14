<?php

namespace PsaNdp\MappingBundle\Repository\Accessories;

use Doctrine\ORM\EntityRepository;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

class PsaAccessoriesSiteRepository extends EntityRepository
{
    /**
     * @param $site
     * @param $languageCode
     *
     * @return mixed
     */
    public function findOneBySiteIdAndLanguageCode($site, $languageCode)
    {
        $qb = $this->createQueryBuilder('config')
            ->innerJoin(
                'config.langue',
                'langue'
            )
            ->where('config.site =:site')
            ->andWhere('langue.langueCode =:langue')
            ->setParameters(array(':site' => $site, ':langue' => $languageCode));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
