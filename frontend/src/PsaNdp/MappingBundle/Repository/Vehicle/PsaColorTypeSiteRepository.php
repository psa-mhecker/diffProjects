<?php

namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaColorTypeSiteRepository
 */
class PsaColorTypeSiteRepository extends EntityRepository
{
    /**
     * @param $site
     *
     * @return mixed
     */
    public function findBySite($site)
    {
        $qb = $this->createQueryBuilder('psaColorTypeSite')
            ->where('psaColorTypeSite.site =:site')
            ->andWhere("psaColorTypeSite.labelLocal !='' ")
            ->setParameter('site', $site);

        return $qb->getQuery()->execute();
    }
}
