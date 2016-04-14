<?php

namespace PsaNdp\MappingBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Itkg\ConsumerBundle\Model\ServiceConfig;
use Itkg\ConsumerBundle\Repository\ServiceConfigRepositoryInterface;
use PsaNdp\MappingBundle\Entity\PsaWebservice;

class PsaWebserviceRepository extends EntityRepository implements ServiceConfigRepositoryInterface{

    /**
     * Finds a service configuration given it's key
     * @param string $serviceKey
     *
     * @return PsaWebservice|null
     */
    public function findOneByServiceKey($serviceKey)
    {

        return  $this->createQueryBuilder('ws')
            ->where('ws.name = :serviceKey')
            ->setParameter(':serviceKey',$serviceKey)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    /**
     * @param ServiceConfig $serviceConfig
     * @throws \BadMethodCallException
     */
    public function update(ServiceConfig $serviceConfig)
    {
        throw new \BadMethodCallException('TODO: Implement update() method.');
    }
}
