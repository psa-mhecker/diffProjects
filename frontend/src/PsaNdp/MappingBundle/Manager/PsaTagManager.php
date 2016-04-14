<?php


namespace PsaNdp\MappingBundle\Manager;

use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class PsaTagManager
 * @package PsaNdp\MappingBundle\Manager
 */
class PsaTagManager extends TagManager
{
    /**
     * @param string $key
     * @param string $id
     *
     * @return string
     */
    public function formatKeyIdTag($key, $id)
    {
        return $key . '-' . $id;
    }
}
