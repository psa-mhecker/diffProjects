<?php
    /**
     * Fichier de Pelican_Cache : Récupération des Pelican_Media associé à une page dans une Pelican_Index_Frontoffice_Zone.
     *
     *
     * @author Fairouz Bihler <fbihler@businessdecision.com>
     *
     * @since 06/05/2005
     */
    class Frontend_Page_Media extends Pelican_Cache
    {
        public $duration = WEEK;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();
            $aBind = array();

            $aBind[":PAGE_ID"] = $this->params[0];
            $aBind[":ZONE_TEMPLATE_ID"] = $this->params[1];
            $aBind[":PAGE_VERSION"] = $this->params[2];
            $aBind[":LANGUE_ID"] = $this->params[3];

            $sSql = "select m.*, pzm.PAGE_ZONE_MEDIA_TYPE, pzm.PAGE_ZONE_MEDIA_LABEL
				FROM
				#pref#_MEDIA m,
				#pref#_page_zone_media pzm
				where pzm.PAGE_ID = :PAGE_ID
				and pzm.PAGE_VERSION = :PAGE_VERSION
				and pzm.LANGUE_ID = :LANGUE_ID
				and pzm.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID
				and m.MEDIA_ID=pzm.MEDIA_ID
				order by PAGE_ZONE_MEDIA_TYPE";
            $aResult = $oConnection->queryTab($sSql, $aBind);
            $this->value = $aResult;
        }
    }
