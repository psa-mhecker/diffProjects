<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaModelViewAngleRepository
 */
class PsaModelViewAngleRepository extends EntityRepository
{
    /**
     * @param  string $lcdv4
     * @return mixed
     */
    public function findByLcdv4($lcdv4)
    {
        $qb = $this->createQueryBuilder('modelViewAngle')
            ->select('modelViewAngle.code')
            ->where('modelViewAngle.startAngle = 1')
            ->andWhere('modelViewAngle.lcdv4 =:lcdv4')
            ->setParameters(array(':lcdv4' => $lcdv4));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $lcdv4
     *
     * @return mixed
     */
    public function findInitialAngleByLcdv4($lcdv4)
    {
        $qb = $this->createQueryBuilder('va')
            ->select('va')
            ->where('va.lcdv4 = :lcdv4')
            ->andWhere('va.startAngle = 1')
            ->setParameters(array(':lcdv4' => $lcdv4));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
