<?php
/**
 * Fichier de Pelican_Caches_Citroen : Zonne
 *
 * Cache remontant les noms des outils associé au vehicule
 * 
 * @package Cache
 * @subpackage Pelican
 * @author  Kristopher Perin <kristopher.perin@businessdecision.com>
 * @since   02/08/2013
 * @param 0 SITE_ID                    Identifiant du Site
 * @param 1 ZONE_TEXTE                 Concatenation des BARRE_OUTILS_ID
 * @param 2 SUPPORT                    Pour le support d'affichage MOBILE / WEB
 * 
 */ 
class Frontend_Citroen_VehiculeOutil extends Pelican_Cache
{
    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */ 
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":SITE_ID"] = $oConnection->strToBind($this->params[0]);
        $aBind[":LANGUE_ID"] = $oConnection->strToBind($this->params[1]);
        $iOutil = $this->params[2];
        $Support = $this->params[3];
        
        if(!empty($iOutil)){
            if($Support == 'WEB')
            {   
                // requette d'amorçage
                $sSQL = '
                    SELECT 
                        BARRE_OUTILS_TITRE,
						BARRE_OUTILS_TITRE_COURT,
                        BARRE_OUTILS_MODE_OUVERTURE,
                        BARRE_OUTILS_URL_WEB,
                        BARRE_OUTILS_FORMULAIRE,
                        BARRE_OUTILS_ID,
                        MEDIA_GENERIQUE_ON,
                        MEDIA_GENERIQUE_OFF,
                        MEDIA_DS_ON,
                        MEDIA_DS_OFF,
                        MEDIA_DS_VERTICAL,
                        MEDIA_C_VERTICAL,
                        MEDIA_TRANS_VERTICAL
                    FROM 
                        psa_barre_outils
                    WHERE 
                        SITE_ID=:SITE_ID
                    AND LANGUE_ID=:LANGUE_ID
                    AND BARRE_OUTILS_AFFICHAGE_WEB=1 AND ';

                $aTabWeb = explode('|',$iOutil);

                // creation de la requete pour les outils web
                if(!empty($aTabWeb) && is_array($aTabWeb)){
                    $sqlWeb = '';
                    foreach($aTabWeb as $i=>$item){
                        if(!empty($item)){
                            $sqlWeb .= ' BARRE_OUTILS_ID='.$item.' OR';
                        }
                    }
                   $sSQL = $sSQL . substr($sqlWeb, 0, strlen($sqlWeb)-3);         
                }

            }
            elseif($Support == 'MOBILE')
            {
                // requette d'amorçage
                $sSQL = '
                    SELECT 
                        BARRE_OUTILS_TITRE,
						BARRE_OUTILS_TITRE_COURT,
                        BARRE_OUTILS_MODE_OUVERTURE,
                        BARRE_OUTILS_URL_MOBILE,
                        BARRE_OUTILS_FORMULAIRE,
                        BARRE_OUTILS_ID,
                        MEDIA_GENERIQUE_ON
                    FROM 
                        psa_barre_outils
                    WHERE 
                        SITE_ID=:SITE_ID 
                    AND LANGUE_ID=:LANGUE_ID
                    AND BARRE_OUTILS_AFFICHAGE_MOBILE=1 AND ';

                $aTabMobile = explode('|',$iOutil);

                 // creation de la requete pour les outils mobiles
                if(!empty($aTabMobile) && is_array($aTabMobile)){
                    $sqlMobile = '';
                    foreach($aTabMobile as $i=>$item){
                        if(!empty($item)){
                            $sqlMobile .= ' BARRE_OUTILS_ID='.$item.' OR';
                        }
                    }
                   $sSQL = $sSQL . substr($sqlMobile, 0, strlen($sqlMobile)-3);
                }
            }
           
            
            $aTemp = $oConnection->queryTab($sSQL, $aBind);
             $aResults = array();
            if ($aTemp) {
                foreach ($aTemp as $temp) {
                    
                    $aResults[$temp['BARRE_OUTILS_ID']] = $temp;
                }
            }
            if(is_array($aResults) && is_array(explode('|',$this->params[2]))) $aResults = self::sortArrayByArray($aResults, explode('|',$this->params[2]));

            //définition de l'index de position de l'outil qui sera utilisé pour la fonctionnalité GTM
            foreach ($aResults as $key => $temp) {
                    $aResults[$key]['INDEX']=$key+1;
            }
            
            $this->value = $aResults;
        }
    }

     /**
     * Méthode statique sauvegardant remettant en ordre les outils
     */
    public static function sortArrayByArray(Array $array, Array $orderArray) {
        $ordered = array();
        foreach($orderArray as $key) {
            if(array_key_exists($key,$array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return array_values($ordered + $array);
    }
    
}
?>
