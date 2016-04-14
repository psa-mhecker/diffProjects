<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pc23MurMedia.
 */
class Pc23MurMedia extends Content
{
    const TYPE_MUR_MEDIA = 'MUR_MEDIA';

    protected $structures;

    /**
     * @return mixed
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * @param mixed $structures
     */
    public function setStructures($structures)
    {
        $this->structures = $structures;
    }

    private function camelize($string)
    {
        return strtr(ucwords(strtr($string, array('_' => ' ', '.' => '_ ', '\\' => '_ ', '-' => ' '))), array(' ' => ''));
    }

    public function init()
    {
        $this->title = $this->getBlock()->getZoneTitre();
        $this->subtitle = $this->getBlock()->getZoneTitre2();

        $multis = $this->block->getMultis();

        /** @var \PSA\MigrationBundle\Entity\Page\PsaPageMultiZoneMulti $multi */
        foreach ($multis as $multi) {
            $className = '\PsaNdp\MappingBundle\Object\Block\Pc23Object\\'.$this->camelize($multi->getPageZoneMultiValue());
            $structure = new $className();
            $structure->setMediaFactory($this->mediaFactory);
            $structure->init($multi);
            $this->structures[] = $structure;
        }
    }
}
