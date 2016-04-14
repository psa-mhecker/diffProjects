<?php

namespace PsaNdp\MappingBundle\Services;

/**
 * Class MediaServerInitializer
 */
class MediaServerInitializer
{
    /**
     * @return string
     */
    public function getMediaServer()
    {
        return  getenv('SYMFONY__HTTP__MEDIA');
    }
}
