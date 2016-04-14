<?php

namespace PsaNdp\MappingBundle\Object\Meta;

class MetaProperty extends AbstractMeta
{
    /**
     * @var string
     */
    protected $property;

    /**
     * MetaProperty constructor.
     *
     * @param string   $property
     * @param string   $content
     * @param null|int $encodeFlag
     */
    public function __construct($property, $content, $encodeFlag = null )
    {
        parent::__construct($encodeFlag);
        $this->property = $property;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return MetaName
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return  sprintf('<meta property="%s" content="%s" />', $this->getProperty(), $this->getContent());
    }
}
