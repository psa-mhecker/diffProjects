<?php

namespace PsaNdp\MappingBundle\Repository;


use Doctrine\ORM\EntityRepository;


class PsaModelRepository extends EntityRepository
{

    /**
     * Finds Car Models from a list of LCDV4
     *
     * @param array $modelIds
     *
     * @return mixed
     */
    public function findByModelIds(array $modelIds)
    {
        $queryBuilder = $this->createQueryBuilder('model');
        $queryBuilder
            ->where($queryBuilder->expr()->in('model.lcdv4', $modelIds))
        ;

        return $queryBuilder->getQuery()->execute();
    }

}
