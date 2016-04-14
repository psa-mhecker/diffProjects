<?php

namespace PsaNdp\MappingBundle\Object;

class BadgeApplication extends Content
{
    protected $mapping = array(
        'href' => 'url'
    );

    /**
     * @var string
     */
    protected $src;

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return BadgeApplication
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }
}
