<?php
namespace PsaNdp\MappingBundle\Object;

interface MediaInterface
{
    const BLANK_IMAGE ='/design/frontend/desktop/img/blank.png';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';

    /**
     * @return string
     */
    public function getType();
}


