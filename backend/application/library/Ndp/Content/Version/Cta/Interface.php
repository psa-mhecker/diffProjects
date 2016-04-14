<?php

interface Ndp_Content_Version_Cta_Interface
{

    /**
     * set tous les membres de la classe parent Cta
     * @param integer $pageId
     * @param integer $langueId
     * @param integer $contentVersion
     * @param integer $contentId
     * @param string  $ctaType
     * @param integer $ctaId
     *
     */
    public function setValues($pageId, $langueId, $contentVersion, $contentId, $ctaType, $ctaId);

    public function getValues();

    public function save();

    public function delete();
}
