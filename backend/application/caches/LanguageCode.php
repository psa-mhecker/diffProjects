<?php
/**
 */

/**
 * Fichier de Pelican_Cache : langues.
 *
 * @author Laurent Boulay <glenormand@businessdecision.com>
 *
 * @since 26/06/2013
 */
class LanguageCode extends Pelican_Cache
{
    public $duration = DAY;

    public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $sqlQuery = "select * from #pref#_language";

        $results = $oConnection->queryTab($sqlQuery);

        foreach ($results as $lang) {
            $value[$lang['LANGUE_CODE']] = $lang['LANGUE_ID'];
        }

        $this->value = $value;
    }
}
