<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Infos liées à un media.
 *
 * retour : *
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 20/05/2006
 */
class Media_Directory extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":ID"] = $this->params[0];
        $file = $oConnection->queryRow("select * from #pref#_media_directory where media_directory_id=:ID", $aBind);
        $this->value = $file;
    }
}
