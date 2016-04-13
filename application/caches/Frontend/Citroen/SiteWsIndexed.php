<?php

class Frontend_Citroen_SiteWsIndexed extends Pelican_Cache {

    var $duration = UNLIMITED;

    function getValue() {
        $oConnection = Pelican_Db::getInstance();
        $aResults = array();
        
        if ($this->params[0]) {
            $aBind = array(
                ':SITE_ID' => $this->params[0]
            );
            $sQuery = 'SELECT ws_id, status FROM #pref#_site_webservice WHERE site_id = :SITE_ID';
            $aSitesWs = $oConnection->queryTab($sQuery,$aBind);
            
            $sQuery = 'SELECT ws_id,ws_name FROM #pref#_liste_webservices ';
            $aWs = $oConnection->queryTab($sQuery, $aBind);
            if(
                    is_array($aSitesWs)&& count($aSitesWs)&&
                    is_array($aWs)&& count($aWs)                   
                    ){
                foreach($aSitesWs as $aOneSiteWs){
                    foreach($aWs as $aOneWs){
                        if($aOneSiteWs['ws_id']==$aOneWs['ws_id']){
                            $aResults[$aOneWs['ws_name']]=  intval($aOneWs['ws_id']);
                        }
                    }
                     
                }
               
            }
            
        }

        $this->value = $aResults;
    }

}
