<?php

namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaColorTypeRepository
 */
class PsaColorTypeRepository extends EntityRepository
{
    /**
     * @param $site
     * @param $languageCode
     *
     * @return mixed
     */
    public function findBySiteIdAndLanguageCode($site, $languageCode)
    {
        $qb = $this->createQueryBuilder('vehiclecategory')
            ->innerJoin(
                'vehiclecategory.langue',
                'langue'
            )
            ->where('vehiclecategory.site =:site')
            ->andWhere('langue.langueCode =:langue')
            ->setParameters(array(':site' => $site, ':langue' => $languageCode));

        return $qb->getQuery()->execute();
    }
}
