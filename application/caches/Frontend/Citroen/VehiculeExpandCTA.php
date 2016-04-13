<?php

/**
 * Fichier de Pelican_Cache : CTA dex Expand d'un Vehicule 
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_VehiculeExpandCTA extends Pelican_Cache
{

    var $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */

    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':VEHICULE_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $sSQL = "
            SELECT 
		*
            FROM 
                #pref#_vehicule_cta_expand vce
            WHERE vce.SITE_ID = :SITE_ID
            AND vce.LANGUE_ID = :LANGUE_ID
            AND vce.VEHICULE_ID = :VEHICULE_ID";
			if(isset($this->params[3]) && $this->params[3] =='CTA_HOME'){
				 $sSQL .=" AND vce.VEHICULE_CTA_EXPAND_HOME = 1";
			}
			if(isset($this->params[3]) && $this->params[3] =='CTA_MASTER'){
				$sSQL .=" AND vce.VEHICULE_CTA_EXPAND_MASTER = 1";
			}
            $sSQL .=" ORDER BY PAGE_ZONE_MULTI_ORDER
        ";
        $ctas = $oConnection->queryTab($sSQL, $aBind);

        foreach ($ctas as $indexCta => $cta) {
            if (!empty($cta['VEHICULE_CTA_EXPAND_OUTIL'])) {
                $sql = 'SELECT *
                        FROM
                            #pref#_barre_outils
                        WHERE
                            BARRE_OUTILS_ID = '.intval($cta['VEHICULE_CTA_EXPAND_OUTIL']);

                $temp = $oConnection->queryTab($sql, $aBind);
					
                if ($temp[0]['BARRE_OUTILS_AFFICHAGE_WEB']) {
                    $ctas[$indexCta] = array(
                        'VEHICULE_CTA_EXPAND_LABEL' => $temp[0]['BARRE_OUTILS_TITRE'],
                        'VEHICULE_CTA_EXPAND_VALUE' => $temp[0]['BARRE_OUTILS_MODE_OUVERTURE'],
                        'VEHICULE_CTA_EXPAND_URL' => $temp[0]['BARRE_OUTILS_URL_WEB'],
                        'VEHICULE_CTA_EXPAND_OUTIL' => $temp[0]['BARRE_OUTILS_ID'],
                        'VEHICULE_CTA_EXPAND' =>  $cta['VEHICULE_CTA_EXPAND'],
                        'VEHICULE_CTA_EXPAND_HOME' =>  $cta['VEHICULE_CTA_EXPAND_HOME'],
                        'VEHICULE_CTA_EXPAND_MASTER' =>  $cta['VEHICULE_CTA_EXPAND_MASTER'],
                    );
                }
            }
            if ($cta['VEHICULE_CTA_EXPAND_VALUE'] == 'SELF') {
                $ctas[$indexCta]['VEHICULE_CTA_EXPAND_VALUE'] = 1;
            }
            if ($cta['VEHICULE_CTA_EXPAND_VALUE'] == 'BLANK') {
                $ctas[$indexCta]['VEHICULE_CTA_EXPAND_VALUE'] = 2;
            }
        }

        $this->value = $ctas;
    }
}
