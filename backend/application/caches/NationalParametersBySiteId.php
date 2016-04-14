<?php

class NationalParametersBySiteId extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet a mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        if (!$this->params['SITE_ID']){
            throw new Exception('SITE_ID parameters is mandatory');
        }

        $aBind[":SITE_ID"] = $this->params['SITE_ID'];

        $sSql = "
            SELECT NATIONAL_PARAMS
            FROM
                #pref#_site_national_param
                WHERE
				SITE_ID = :SITE_ID
		";

        $nationalParams = $oConnection->queryRow($sSql, $aBind);

        $this->value = json_decode($nationalParams['NATIONAL_PARAMS'], true);
    }
}
