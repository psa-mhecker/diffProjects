<?php
/**
 * Récupère le groupe réseaux sociaux principal pour un site et une langue.
 * Ce groupe est défini en backoffice : Administration > Groupes de réseaux sociaux, checkbox "Regroupement par défaut (Galerie média)".
 */
class Frontend_Citroen_FindGroupeReseauxSociaux extends Pelican_Cache
{
    public $duration = DAY;

    public $isPersistent = true;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            SELECT *
            FROM #pref#_groupe_reseaux_sociaux grs
            WHERE grs.LANGUE_ID = :LANGUE_ID
            AND grs.SITE_ID = :SITE_ID
            AND grs.GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA = 1
            LIMIT 0, 1
        ";
        $aResults = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aResults;
    }
}
