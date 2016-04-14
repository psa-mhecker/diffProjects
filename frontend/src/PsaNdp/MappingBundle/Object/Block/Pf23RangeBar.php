<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf23RangeBar
 */
class Pf23RangeBar extends Content
{

    /**
     * @var array
     */
    protected $models;

    /**
     * @var string
     */
    protected $mobileLabel;

    /**
     * @return string
     */
    public function getMobileLabel()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return array
     */
    public function getModels()
    {
        $models = array();

        if ($this->block instanceof PsaPageZoneConfigurableInterface)
        {
            foreach ($this->block->getMultis() as $multi)
            {
                $models[] = array(
                    'title' => $multi->getPageZoneMultiTitre(),
                    'url'   => $multi->getPageZoneMultiUrl(),
                );
            }
        }
        return $models;
    }

}
