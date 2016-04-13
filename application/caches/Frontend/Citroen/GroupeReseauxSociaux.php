<?php
/**
 * Fichier de Pelican_Cache : Reseaux Sociaux
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_GroupeReseauxSociaux extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
		$aBind[':GROUPE_RESEAUX_SOCIAUX_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $sSQL = "
            select
                rs.*,
                m.MEDIA_PATH
			from #pref#_groupe_reseaux_sociaux grs
			inner join #pref#_groupe_reseaux_sociaux_rs grsrs
				on (grsrs.GROUPE_RESEAUX_SOCIAUX_ID = grs.GROUPE_RESEAUX_SOCIAUX_ID
					and grsrs.SITE_ID = grs.SITE_ID
					and grsrs.LANGUE_ID = grs.LANGUE_ID)
            inner join  #pref#_reseau_social rs
				on (rs.RESEAU_SOCIAL_ID = grsrs.RESEAU_SOCIAL_ID
					and rs.SITE_ID = grsrs.SITE_ID
					and rs.LANGUE_ID = grsrs.LANGUE_ID)
            inner join #pref#_media m
                on (m.MEDIA_ID = rs.MEDIA_ID)
            where grs.SITE_ID = :SITE_ID
            and grs.LANGUE_ID = :LANGUE_ID
			and grs.GROUPE_RESEAUX_SOCIAUX_ID = :GROUPE_RESEAUX_SOCIAUX_ID
            order by rs.RESEAU_SOCIAL_ORDER asc
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}