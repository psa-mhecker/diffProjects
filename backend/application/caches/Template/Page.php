<?php
/**
 *
 */

/**
 * Fichier de Pelican_Cache : Liste des templates de page d'un site.
 *
 * @param string $this->params[0] ID du site
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 27/09/2004
 */
class Template_Page extends Pelican_Cache
{
    public static $storage = 'file';

    /**
     * * Valeur ou objet à mettre en cache.
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":PAGE_TYPE_ID"] = $this->params[1];
        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[2];
        $restrict = $this->params[3];
        $aBind[":LANGUE_ID"] = ($this->params[4] ? $this->params[4] : 1);
        $aBind[":SITE_ID_FIXE"] = $this->params[5];

        if ($this->params[3]) {
            /* pour ne pas rejeter le page_type_id en cours d'utilisation */
            $aBind[":PAGE_TYPE_ID"] = $this->params[3];
            $aBind[":CORBEILLE_STATE"] = Pelican::$config["CORBEILLE_STATE"];

            /* recherche des templates uniques déjà utilisé */
            $unique = "select DISTINCT pt.PAGE_TYPE_ID from
			#pref#_page p
			inner join #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND pv.PAGE_VERSION=p.PAGE_CURRENT_VERSION)
			inner join #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
			inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where p.SITE_ID=:SITE_ID
			AND PAGE_TYPE_ONE_USE=1
			AND p.LANGUE_ID=:LANGUE_ID
			AND pt.PAGE_TYPE_ID != :PAGE_TYPE_ID
			AND pv.STATE_ID <> :CORBEILLE_STATE";
            $oConnection->query($unique, $aBind);
            $exclude = $oConnection->data['PAGE_TYPE_ID'];
        }

        if ($this->params[5]) {
            $aBind[":SITE_ID"] = $aBind[":SITE_ID_FIXE"];
        }

        $strSqlPage = "select
				TEMPLATE_PAGE_ID as \"id\",
				TEMPLATE_PAGE_LABEL as \"lib\"
				from
				#pref#_template_page tp
				inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
				where SITE_ID = :SITE_ID
				AND (PAGE_TYPE_HIDE = 0 OR PAGE_TYPE_HIDE IS NULL) ";

        /* @todo: remise du test du type de page apres la mise au carré de template_age et template      */
        if ($this->params[1]) {
            $strSqlPage .= " AND (PAGE_TYPE_ID = :PAGE_TYPE_ID";

            if ($this->params[1] != 1) {
                $strSqlPage .= " OR PAGE_TYPE_ID = -1 ";
            }
            $strSqlPage .= ")";
        }

        if ($this->params[2]) {
            $strSqlPage .= " AND TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID ";
        }

        if ($exclude) {
            $strSqlPage .= " AND pt.PAGE_TYPE_ID not in (".implode(',', $exclude).") ";
        }

        $strSqlPage .= " order by TEMPLATE_PAGE_LABEL";
        $resultat = $oConnection->queryTab($strSqlPage, $aBind);
        $this->value = $resultat;
    }
}
