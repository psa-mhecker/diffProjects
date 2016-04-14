<?php

namespace PsaNdp\MappingBundle\Object\Meta;

class MetaName extends AbstractMeta
{
    /**
     * @var string
     */
    protected $name;

    /**
     * MetaName constructor.
     *
     * @param string  $name
     * @param string   $content
     * @param null|int $encodeFlag
     */
    public function __construct($name, $content, $encodeFlag = null)
    {
        parent::__construct($encodeFlag);
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return  sprintf('<meta name="%s" content="%s" />', $this->getName(), $this->getContent());
    }
}
