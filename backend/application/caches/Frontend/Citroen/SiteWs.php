<?php

class Frontend_Citroen_SiteWs extends Pelican_Cache
{
    public $duration = UNLIMITED;

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aResults = array();
        if ($this->params[0]) {
            $aBind = array(
                ':SITE_ID' => $this->params[0],
            );
            $query = 'SELECT ws_id, status FROM #pref#_site_webservice WHERE site_id = :SITE_ID';
            $aSitesWs = $oConnection->queryTab($query, $aBind);

            if (is_array($aSitesWs) && count($aSitesWs)) {
                foreach ($aSitesWs as $aOneSiteWs) {
                    $aResults[$aOneSiteWs['ws_id']] = (bool) intval($aOneSiteWs['status']);
                }
            }
        }

        $this->value = $aResults;
    }
}
