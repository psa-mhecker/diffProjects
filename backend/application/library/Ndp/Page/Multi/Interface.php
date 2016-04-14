<?php

interface Ndp_Page_Multi_Interface
{

    /**
     *
     * @param integer $pageId
     * @param integer $langueId
     * @param integer $pageVersion
     * @param integer $areaId
     * @param string  $multiType
     * @param integer $multiId
     * @param integer $zoneOrder
     */
    public function setValues($pageId, $langueId, $pageVersion, $areaId, $multiType, $multiId, $zoneOrder);

    public function getValues();

    public function save();

    public function delete();
}
