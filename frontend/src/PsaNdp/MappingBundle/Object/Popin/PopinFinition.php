<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class PopinFinition
 */
class PopinFinition extends Content
{
    protected $mapping = array(
        'text' => 'subtitle',
    );

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var Collection $gallerie
     */
    protected $gallerie;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->gallerie = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getGallerie()
    {
        return $this->gallerie;
    }

    /**
     * @param Collection $gallerie
     *
     * @return $this
     */
    public function setGallerie(Collection $gallerie)
    {
        $this->gallerie = $gallerie;

        return $this;
    }

    /**
     * @param Media $media
     */
    public function addGallerie(Media $media)
    {
        $this->gallerie->add($media);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
