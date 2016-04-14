<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\QueryBuilder;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaBecomeAgentRepository
 */
class PsaBecomeAgentRepository extends EntityRepository
{
    const DIAMETER = 111.13384;
    /**
     * @param string $country
     *
     * @return mixed
     */
    public function findByCountry($country)
    {
        $qb = $this->createQueryBuilder('becomeAgent')
            ->where('becomeAgent.country =:country')
            ->setParameters('country', $country);

        return $qb->getQuery()->execute();
    }

    /**
     * @param PsaSite $site
     * @param int     $linkId
     *
     * @return mixed
     */
    public function findBySiteAndCriteria($site, $linkId) {
        $qb = $this->findByPsaSite();
        $qb = $this->findByCriteria($qb);
        $qb->setParameter('site', $site)
            ->setParameter('linkId', $linkId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param PsaSite $site
     * @param array   $criteria
     *
     * @return mixed
     */
    public function findBySiteAndTwoCriteria($site, array $criteria)
    {
        $qb = $this->findByPsaSite();
        $qb->andWhere('becomeAgent.linkId IN (:criteria)')
            ->setParameter('site', $site)
            ->setParameter('criteria', $criteria);

        return $qb->getQuery()->execute();
    }

    /**
     * @return QueryBuilder
     */
    public function findByPsaSite()
    {
        $qb = $this->createQueryBuilder('becomeAgent')
            ->where('becomeAgent.site =:site');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function findByCriteria(QueryBuilder $qb)
    {
        $qb->andWhere('becomeAgent.linkId =:linkId');

        return $qb;
    }

    /**
     * @param $lat
     * @param $long
     * @param $rayon
     * @param $siteId
     * @param array $linkIds
     *
     * @return array
     */
    public function getDistance($lat, $long, $rayon , $siteId , $linkIds = array())
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('PsaNdpMappingBundle:PsaBecomeAgent', 'becomeAgent');
        $qn = $this->_em->createNativeQuery($this->getSql($linkIds), $rsm);
        $qn->setParameter(1, $lat)
            ->setParameter(2, $long)
            ->setParameter(3, $siteId );
            if (!empty($linkIds)) {
                $qn->setParameter(4, $linkIds)
                    ->setParameter(5, $rayon);
            } else {
            $qn->setParameter(4, $rayon);
        }

        return $qn->getResult();
    }

    /**
     * @param $linkIds
     *
     * @return string
     */
    public function getSql($linkIds)
    {
        $result = 'SELECT psa_pdv_deveniragent.*, ( GLength( LineString(( PointFromWKB( POINT(?, ? ))), ( PointFromWKB( POINT( psa_pdv_deveniragent.PDV_DEVENIRAGENT_LAT, psa_pdv_deveniragent.PDV_DEVENIRAGENT_LNG ) ))))) * 111.13384 AS distance
        FROM psa_pdv_deveniragent
WHERE psa_pdv_deveniragent.SITE_ID = ?';

        if (!empty($linkIds)) {
            $result .= ' AND psa_pdv_deveniragent.PDV_DEVENIRAGENT_LIAISON_ID IN (?)';
        }

        $result .= ' HAVING distance < ? ORDER BY distance;';

        return $result;
    }
}
