<?php

class Frontend_Citroen_UrlEncyclopedique extends Pelican_Cache {

    var $duration = UNLIMITED;

    function getValue() {
        $oConnection = Pelican_Db::getInstance();
        $sql = '';
        $sql = "SELECT URL_ENCYCLOPEDIQUE_ID, URL_ENCYCLOPEDIQUE_SOURCE, URL_ENCYCLOPEDIQUE_DESTINATION
                FROM #pref#_url_encyclopedique";

        $this->value = $oConnection->queryTab($sql);  
    }
}

