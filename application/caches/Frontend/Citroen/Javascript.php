<?php

class Frontend_Citroen_Javascript extends Pelican_Cache {

    var $duration = DAY;

    function getValue() {
        $oConnection = Pelican_Db::getInstance();

        $aBind = array();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $aBind[":PAGE_VERSION"] = $this->params[2];

        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
        $aBind[":AREA_ID"] = $this->params[4];
        $aBind[":ZONE_ORDER"] = $this->params[5];
        
        $table = '#pref#_page_zone';
        $where = ' and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        
        if ($aBind[":ZONE_ORDER"] != '') {
            $table = '#pref#_page_multi_zone';
            $where = ' and AREA_ID = :AREA_ID and ZONE_ORDER = :ZONE_ORDER';
        }

        $query = " SELECT * FROM " . $table . " WHERE
                PAGE_ID = :PAGE_ID
                and LANGUE_ID = :LANGUE_ID
                and PAGE_VERSION = :PAGE_VERSION
                " . $where . "";
        $this->value = $oConnection->queryRow($query, $aBind);
    }

}
