<?php


interface Ndp_Page_Cta_Interface
{

    /**
     * set tous les membres de la classe parent Cta
     * @param integer $pageId
     * @param integer $langueId
     * @param integer $pageVersion
     * @param integer $zoneTemplateId
     * @param string $ctaType
     * @param integer $ctaId
     * @param integer $zoneOrder
     *
     * @return $this
     */
    public function setValues($pageId, $langueId, $pageVersion, $zoneTemplateId, $ctaType, $ctaId, $zoneOrder);

    public function getValues();

    public function save();

    public function delete();
}
