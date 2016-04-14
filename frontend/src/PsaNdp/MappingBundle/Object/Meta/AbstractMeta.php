<?php

namespace PsaNdp\MappingBundle\Object\Meta;

class AbstractMeta
{
    /**
     * @var int
     */
    protected $encodeFlag;

    /**
     * @var bool
     */
    protected $doubleEncode = false;

    /**
     * @var string
     */
    protected $charset;

    /**
     * @var string
     */
    protected $content;

    /**
     * AbstractMeta constructor.
     * @param null|int $encodeFlag
     */
    public function __construct($encodeFlag = null)
    {

        $this->encodeFlag = ENT_QUOTES | ENT_HTML5;
        if(null !== $encodeFlag)
        {
            $this->encodeFlag = $encodeFlag;
        }
        $this->charset = 'UTF-8';
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return htmlentities($this->content, $this->encodeFlag, $this->charset, $this->doubleEncode);
    }

    /**
     * @param mixed $content
     *
     * @return MetaName
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param int $encodeFlag
     *
     * @return MetaName
     */
    public function setEncodeFlag($encodeFlag)
    {
        $this->encodeFlag = $encodeFlag;

        return $this;
    }

    /**
     * @param bool $doubleEncode
     *
     * @return MetaName
     */
    public function setDoubleEncode($doubleEncode)
    {
        $this->doubleEncode = $doubleEncode;

        return $this;
    }

    /**
     * @param string $charset
     *
     * @return AbstractMeta
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }
}
