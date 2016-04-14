<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaFinishingColorRepository
 */
class PsaFinishingColorRepository extends EntityRepository
{

    public function getDefaultColor()
    {
        return $this->find(1);
    }
}
