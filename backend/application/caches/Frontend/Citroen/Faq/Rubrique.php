<?php
/**
 * Fichier de Pelican_Caches_Citroen_Faq : PageRubrique.
 *
 * Cache remontant les rubriques de FAQ associées à la page
 *
 *
 * @author  Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since   23/08/2013
 *
 * @param 0 SITE_ID                 Identifiant du site
 * @param 1 LANGUE_ID               Identifiant de la langue
 * @param 2 FAQ_RUBRIQUE_ID         Identifiant de la rubrique
 */
class Frontend_Citroen_Faq_Rubrique extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation du Bind */
        $aBind[':SITE_ID']          = (int) $this->params[0];
        $aBind[':LANGUE_ID']        = (int) $this->params[1];
        $aBind[':FAQ_RUBRIQUE_ID']  = (int) $this->params[2];

        /* Initialisation des variables */
        $aReturn                    = array();

        /* Récupération des informations de la rubrique de FAQ passées en paramètre */
        $sSql = <<<SQL
                SELECT
                    fr.FAQ_RUBRIQUE_ID,
                    fr.FAQ_RUBRIQUE_LABEL,
                    fr.FAQ_RUBRIQUE_PICTO as PICTO_PATH,
                    m1.MEDIA_ALT as PICTO_ALT
                FROM
                    #pref#_faq_rubrique fr
                        INNER JOIN #pref#_media m1 ON (m1.MEDIA_ID = fr.FAQ_RUBRIQUE_PICTO)
                WHERE
                    fr.SITE_ID = :SITE_ID
                    AND fr.LANGUE_ID = :LANGUE_ID
                    AND fr.FAQ_RUBRIQUE_ID = :FAQ_RUBRIQUE_ID
                ORDER BY fr.FAQ_RUBRIQUE_ID
SQL;
        $aRubriques = $oConnection->queryTab($sSql, $aBind);

        if (is_array($aRubriques)) {
            $aReturn = $aRubriques;
        }
        $this->value = $aReturn;
    }
}
