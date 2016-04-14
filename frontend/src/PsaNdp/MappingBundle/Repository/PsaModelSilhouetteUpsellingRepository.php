<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaModelSilhouetteUpsellingRepository
 * @package PsaNdp\MappingBundle\Repository
 */

class PsaModelSilhouetteUpsellingRepository extends EntityRepository
{


    /**
     * @param $site
     * @param $languageId
     * @param $lcdv6
     *
     * @return array
     */
    public function findOneBySiteIdAndLanguageAndLcdv6($site, $languageId, $lcdv6 )
    {

        $qb = $this->createQueryBuilder('up');
        $qb->innerJoin(
            'up.modelSilhouette',
            's'
        )
            ->where('up.site =:site')
            ->andWhere('up.langue =:language')
            ->andWhere('s.lcdv6 = :lcdv6')
            ->setParameters(array(':site' => $site, ':language' => $languageId, ':lcdv6' => $lcdv6));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $site
     * @param $languageId
     * @param $lcdv6
     * @param $finition
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySiteIdAndLanguageAndLcdv6AndFinition($site, $languageId, $lcdv6, $finition )
    {

        $qb = $this->createQueryBuilder('up');
        $qb->innerJoin(
                'up.modelSilhouette',
                's'
            )
            ->where('up.site =:site')
            ->andWhere('up.langue =:language')
            ->andWhere('s.lcdv6 = :lcdv6')
            ->andWhere('up.finishingCode = :finition')
            ->setParameters(array(':site' => $site, ':language' => $languageId, ':lcdv6' => $lcdv6, ':finition'=>$finition));

        return $qb->getQuery()->getOneOrNullResult();
    }

}
