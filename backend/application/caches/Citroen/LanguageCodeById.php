<?php
/**
 */

/**
 * Fichier de Pelican_Cache : langues.
 *
 * @author Moate david <david.moate@businessdecision.com>
 *
 * @since 11/06/2013
 */
class Citroen_LanguageCodeById extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet � mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":LANGUE_ID"] = $this->params[0];

        $sqlQuery = "select * from #pref#_language where LANGUE_ID= :LANGUE_ID";

        $result = $oConnection->queryRow($sqlQuery);

        $this->value = $result['LANGUE_CODE'];
    }
}
