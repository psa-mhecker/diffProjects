<?php

class SiteCodePays extends Pelican_Cache {

    public $duration = DAY;

    function getValue() {
        $oConnection = Pelican_Db::getInstance ();

        $aBind[':SITE_ID'] = (int)$this->params[0];

        $Sql = "select SITE_CODE_PAYS from #pref#_site_code where SITE_ID= :SITE_ID";

        $results = $oConnection->queryTab ($Sql,$aBind);

        $aValue = $results[0]['SITE_CODE_PAYS'];

        $this->value = $aValue;
    }
}