<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

use PSA\MigrationBundle\Entity\Media\PsaMedia;

class StructureManager
{
    /**
     * @var array
     */
    protected $structures;

    /**
     * @var array
     */
    protected $pool;

    public function getAvailableStructures()
    {
        if (!isset($this->structures)) {
            $this->structures = [];
            $namespace = 'PsaNdp\MappingBundle\Object\Block\Pc23Object\\';
            $classes = ['StructureA','StructureASquare','StructureB','StructureBSquare','StructureC','StructureCSquare','StructureD','StructureDSquare',
                'StructureE','StructureESquare','StructureF','StructureFSquare','StructureG','StructureGSquare', ];
            foreach ($classes as $className) {
                $fullClassName = $namespace.$className;
                $this->structures[$className] = new $fullClassName();
            }
        }

        return $this->structures;
    }

    /**
     * @return array
     */
    public function getMediaNames()
    {
        return ['MEDIA_ID', 'MEDIA_ID2', 'MEDIA_ID3', 'MEDIA_ID4', 'MEDIA_ID5', 'MEDIA_ID6'];
    }

    /**
     * @param array $medias
     * @param bool $migration
     *
     * @return array
     */
    public function autoFill($medias, $migration = false)
    {
        $return = array();

        $this->getAvailableStructures();

        $this->pool = $medias;
        // on melage les images;
        shuffle($this->pool);
        $nbImage = count($this->pool);
        // tant qu'on a assez d'image
        while ($nbImage >= 2) {
            // on selectionne une structure au hasard
            $structKey = array_rand($this->structures);
            /* @var StructureInterface $structure */
            $nbImageForStruc = $this->structures[$structKey]->countImages();
            if ($nbImageForStruc <= $nbImage) {
                $return[] = $this->fillStructure($nbImageForStruc, $structKey, $migration);
            }

            $nbImage = count($this->pool);
        }

        return $return;
    }

    /**
     * @param int $nbImageForStruc
     * @param string $structKey
     * @param bool $migration
     *
     * @return array
     */
    protected function fillStructure($nbImageForStruc, $structKey, $migration = false)
    {
        $mediaNames = $this->getMediaNames();

        $struct = [];
        // flag pour savoir il futur structure contient une video
        $containsVideo = false;
        //on ajout les images à la structure
        for ($i = 0; $i < $nbImageForStruc; ++$i) {
            $media = array_shift($this->pool);
            // check si le media est une video streamlike
            $containsVideo = $containsVideo || ($media['MEDIA_TYPE_ID'] == PsaMedia::STREAMLIKE);
            $struct[$mediaNames[$i]] = ($migration) ? $media : $media['MEDIA_ID'];
        }
        // si c'est une structure carré et qu'elle contient une video on la remplace par une structure 16/9
        if ($this->structures[$structKey]->getType() == StructureInterface::NDP_SQUARE && $containsVideo) {
            $structKey = str_replace('Square', '', $structKey);
        }
        $struct['PAGE_ZONE_MULTI_VALUE'] = $this->structures[$structKey]->getName();

        return $struct;
    }
}
