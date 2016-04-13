<?php
/**
 * Fichier de Pelican_Cache : Véhicules visuel interieur
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_VehiculesVisuelInterieur extends Pelican_Cache {
 
    var $duration = DAY;
    
    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aBind[':VEHICULE_ID'] = $this->params[2];
		$aBind[':TYPE_VISUEL'] = $oConnection->strtobind($this->params[3]); 	
	
        $sqlAffichage = '
                SELECT
                   AFFICHAGE_VISUEL_360_WEB,
				   AFFICHAGE_VISUEL_360_MOBILE
                FROM
                    #pref#_vehicule
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID';
					
        $sqlVisuelInterieur360 = '
                SELECT
                   *
                FROM
                    #pref#_vehicule_media
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID
					AND TYPE_VISUEL = :TYPE_VISUEL';
					
		$affichage 			=	$oConnection->queryRow($sqlAffichage, $aBind);
		$visuelInterieur360 =	$oConnection->queryRow($sqlVisuelInterieur360, $aBind);
		$visuelInterieur360['AFFICHAGE'] = $affichage;
        $this->value = $visuelInterieur360;
    }
}

