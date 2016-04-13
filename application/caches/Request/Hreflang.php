<?php

class Request_Hreflang extends Pelican_Cache
{

    var $duration = UNLIMITED;

    /**
     * Valeur ou objet à mettre en Cache
     */
    function getValue ()
    {
        if ($this->params[0] && $this->params[1] && $this->params[2]) {
            $oConnection = Pelican_Db::getInstance();
            
            $aBind[":SITE_ID"] = $this->params[0];
            $aBind[":LANGUE_ID"] = $this->params[1];
            $aBind[":HREFLANG_ID"] = $this->params[2];
            
            $query = "select HREFLANG_TEXT from #pref#_hreflang 
                    where SITE_ID=:SITE_ID
                    AND LANGUE_ID=:LANGUE_ID
                    AND HREFLANG_ID=:HREFLANG_ID";
            $this->value = $oConnection->queryRow($query, $aBind);
        }
    }
}
