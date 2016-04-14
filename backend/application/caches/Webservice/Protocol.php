<?php
/**
 * Fichier de Pelican_Cache : Liste des protocoles de webservice.
 *
 * retour : *
 *
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 *
 * @since 09/01/2009
 */
class Webservice_Protocol extends Pelican_Cache
{
    public $duration = MONTH;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $p = $oConnection->queryTab("select * from #pref#_WEBSERVICE_PROTOCOL");
        $this->value = $p;
    }
}
