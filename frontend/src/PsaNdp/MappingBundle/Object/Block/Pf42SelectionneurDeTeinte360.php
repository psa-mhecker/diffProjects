<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf42SelectionneurDeTeinte360
 * @codeCoverageIgnore
 */
class Pf42SelectionneurDeTeinte360 extends Content
{

    /**
     * @var string $mentions
     */
    protected $mentions;

    /**
     * @return string
     */
    public function getMentions()
    {
        return $this->mentions;
    }

    /**
     * @param string $mentions
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;
    }
}
