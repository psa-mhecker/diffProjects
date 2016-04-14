<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Récupération config google api.
 *
 *
 * @author Moaté david <david.moate@businessdecision.com>
 *
 * @since 10/09/2014
 */
class Service_ConfigGoogleApi extends Pelican_Cache
{
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection        =   Pelican_Db::getInstance();
        $aBind[':SITE_ID']  =   $this->params[0];
        $sqlApiGoogle       =   "Select  GOOGLE_KEY, CLIENT_ID, USER_ID, CLIENT_SECRET from #pref#_site where SITE_ID= :SITE_ID";
        $this->value        =   $oConnection->getRow($sqlApiGoogle, $aBind);
    }
}
