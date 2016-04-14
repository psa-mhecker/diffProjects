<?php

namespace PsaNdp\MappingBundle\Object\configuration;

use PsaNdp\MappingBundle\Object\BlockTrait\ConfigurationTrait;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf53Configuration2
 */
class Pf53Configuration2 extends Content
{
    protected $mapping = array(
        'text1' => 'title',
        'text2' => 'subtitle',
    );

    use ConfigurationTrait;

    /**
     * @var string $second
     */
    protected $second;

    /**
     * @var string $seconds
     */
    protected $seconds;

    /**
     * @var string $counter
     */
    protected $counter;

    /**
     * @var array
     */
    protected $link;

    /**
     * @return string
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param string $counter
     *
     * @return $this
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param string $second
     *
     * @return $this
     */
    public function setSecond($second)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param string $seconds
     *
     * @return $this
     */
    public function setSeconds($seconds)
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * @return array
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param array $link
     *
     * @return $this
     */
    public function setLink(array $link)
    {
        $this->link = $link;

        return $this;
    }
}
