<?php

class Frontend_Citroen_WsConfig extends Pelican_Cache {

    var $duration = UNLIMITED;

    function getValue() {
        $oConnection = Pelican_Db::getInstance();
        $aResults = array();
        $aBind = array(
            ':SITE_ID' => $this->params[0]
        );
        $query = 'SELECT * FROM #pref#_liste_webservices ';
        $aWs = $oConnection->queryTab($query, $aBind);

        if (is_array($aWs) && count($aWs)) {
            foreach ($aWs as $aOneWs) {
                $aResults[$aOneWs['ws_name']] = array(
                    'url'=>$aOneWs['ws_url'],
                    'id'=>$aOneWs['ws_id']
                        );
            }
        }


        $this->value = $aResults;
    }

}

