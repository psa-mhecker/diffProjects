<?php

namespace PsaNdp\MappingBundle\Object;

class MenuItem extends Content
{
    protected $overrideMapping = ['name' => 'subtitle'];

    /**
     * @var array
     */
    protected $childs;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @var bool
     */
    protected $isAncestor;

    /**
     * @return mixed
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param array $items
     *
     * @return MenuItem
     */
    public function setChilds(array $items = null)
    {
        $this->childs = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $child = new self();
                $child->setDataFromArray($item);
                $this->childs[] = $child;
            }
        }

        return $this;
    }

    /**
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     *
     * @return MenuItem
     * @codeCoverageIgnore
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getIsAncestor()
    {
        return $this->isAncestor;
    }

    /**
     * @param mixed $isAncestor
     *
     * @return MenuItem
     * @codeCoverageIgnore
     */
    public function setIsAncestor($isAncestor)
    {
        $this->isAncestor = $isAncestor;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->url;
        if (count($this->childs)) {
            reset($this->childs);
            $firstChild = current($this->childs);
            $url = $firstChild['url'];
        }

        return $url;
    }
}
