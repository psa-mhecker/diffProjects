<?php

namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaModelConfigRepository
 */
class PsaModelConfigRepository extends EntityRepository
{
    /**
     * @param $site
     * @param $languageCode
     *
     * @return mixed
     */
    public function findOneBySiteIdAndLanguageCode($site, $languageCode)
    {
        $qb = $this->createQueryBuilder('modelconfig')
            ->innerJoin(
                'modelconfig.langue',
                'langue'
            )
            ->where('modelconfig.site =:site')
            ->andWhere('langue.langueCode =:langue')
            ->setParameters(array(':site' => $site, ':langue' => $languageCode));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
