<?php
namespace PsaNdp\MappingBundle\Repository\Vehicle;

use Doctrine\ORM\EntityRepository;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteAngle;

/**
 * Class PsaMediaRepository
 * @package PsaNdp\MappingBundle\Repository
 */
class PsaModelSilhouetteAngleRepository extends EntityRepository
{
    /**
     * @param $lcdv6
     *
     * @return mixed
     */
    public function findByLcdv6($lcdv6)
    {
        $qb = $this->createQueryBuilder('sa')
            ->innerJoin(
                'sa.modeleSilhouette',
                's'
            )
            ->where('s.lcdv6 = :lcdv6')
            ->setParameter('lcdv6', $lcdv6);

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $lcdv6
     *
     * @return PsaModelSilhouetteAngle|null
     */
    public function findInitialAngleByLcdv6($lcdv6)
    {
        $qb = $this->createQueryBuilder('sa')
            ->innerJoin(
                'sa.modeleSilhouette',
                's'
            )
            ->where('s.lcdv6 = :lcdv6')
            ->andWhere('sa.angleInitial = 1')
            ->setParameters(array(':lcdv6' => $lcdv6));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
