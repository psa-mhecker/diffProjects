<?php

namespace PsaNdp\MappingBundle\Object;

class Breadcrumb extends  Content
{
    /** @var array $breadcrumb */
    protected $breadcrumb;

    /**
     * @return array
     */
    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    /**
     * @param array $breadcrumb
     *
     * @return Breadcrumb
     */
    public function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * @return array
     */
    protected function initBreadcrumb()
    {
        $return = [];
        foreach ($this->breadcrumb['parents'] as $item) {
            $menuItem = new MenuItem();
            $menuItem->setDataFromArray($item);
            $return[] = $menuItem;
        }

        $menuItem = new MenuItem();
        $menuItem->setDataFromArray(array(
            'text' => $this->breadcrumb['current']['name'],
            'url' => '#',
        ));
        $return[] = $menuItem;

        return $return;
    }
}
