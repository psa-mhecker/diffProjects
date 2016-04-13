<?php

class Frontend_Citroen_PageByUrlClear extends Pelican_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {

        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        if ($this->params[2]) {
            $type_version = $this->params[2];
        } else {
            $type_version = "CURRENT";
        }
        if ($type_version == "CURRENT") {
            $status = " AND PAGE_STATUS=1";
        }
        $aBind[":URL_CLEAR"] = $this->params[3];

        $sSQL = "SELECT
            p.*,
            pv.*
            FROM #pref#_page p
            INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_" . $type_version . "_VERSION=pv.PAGE_VERSION)
            WHERE p.SITE_ID = :SITE_ID
            AND p.LANGUE_ID = :LANGUE_ID
            AND pv.PAGE_CLEAR_URL = :URL_CLEAR" . $status;

        $return = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $return;
    }

}
