<?php
/**
 * @package Cache
 * @subpackage Config
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur PAGE_ZONE_MULTI
 *
 * retour : id, lib
 *
 * @package Cache
 * @subpackage Config
 * @author Kristopher Perin <kristopher.perin@businessdecision.com>
 * @since 09/07/2013
 */
class Frontend_Citroen_ZoneMulti extends Citroen_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        $oConnection = Pelican_Db::getInstance();

        $aBind = array();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        if($this->params[2] == "CURRENT")
        {
            $type_version = "CURRENT";
            $aBind[":STATE_ID"] = 4;
        }
        else
        {
            $type_version = $this->params[2];                        
            $aBind[":STATE_ID"] = 1; 
            $isDraft = 1;
        } 
        
        // conditionner sur le PAGE_STATUS
        // sans contrôler si en mode prévisu
        if($type_version == 'CURRENT'){ // mode normal
            $cond_status = "p.PAGE_STATUS = 1";
        }else{ // mode prévisu
            $cond_status = "1 = 1";
        }
        /*if (is_numeric($this->params[2]))
            $this->params[2] = 'CURRENT';
        //$aBind[":PAGE_VERSION"] = $this->params[2];
        $type_version = ($this->params[2]) ? $this->params[2] : 'CURRENT';*/

        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
        $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($this->params[4]);
        // Pour Modification de la requette
        $aBind[":AREA_ID"] = $this->params[5];
        $aBind[":ZONE_ORDER"] = $this->params[6];

        $table = '#pref#_page_zone_multi pzm';
        $where = ' and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        if ($aBind[":ZONE_ORDER"] != '') {
            $table = '#pref#_page_multi_zone_multi pzm';
            $where = ' and AREA_ID = :AREA_ID
                    and ZONE_ORDER = :ZONE_ORDER';
        }
        
        $query = "
	        select *,
	        " . $oConnection->dateSqlToString("PAGE_ZONE_MULTI_DATE_BEGIN", true) . " AS PAGE_ZONE_MULTI_DATE_BEGIN,
	        " . $oConnection->dateSqlToString("PAGE_ZONE_MULTI_DATE_END", true) . " AS PAGE_ZONE_MULTI_DATE_END
	        from
	            " . $table . "
                INNER JOIN #pref#_page p
                    ON (p.PAGE_ID = pzm.PAGE_ID
                        AND p.PAGE_" . $type_version . "_VERSION = pzm.PAGE_VERSION
						AND p.LANGUE_ID = pzm.LANGUE_ID
                    )
	        where
	            pzm.PAGE_ID = :PAGE_ID
	            and pzm.LANGUE_ID = :LANGUE_ID
                    and $cond_status
                and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                " . $where . "
	            ORDER BY PAGE_ZONE_MULTI_ORDER
	        ";
        
        $result = $oConnection->queryTab($query, $aBind);
        
        if($this->params[4] == 'CTAFORM'){
          
            $i = 0;
            $temp = '';
            $temps = array();
            

                $aPageZone = Pelican_Cache::fetch('Frontend/Page/Zone',array(
                        $this->params[0],
                        $this->params[1],
                        $this->params[2]
                    ));

                    
                    $aPageVehicule = Pelican_Cache::fetch('Frontend/Citroen/VehiculeById',array(
                        $aPageZone["areas"][0]["PAGE_VEHICULE"],
                        $aPageZone["areas"][0]["SITE_ID"],
                        $aPageZone["areas"][0]["LANGUE_ID"]
                         ));
    
                    $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
                            $aPageZone["areas"][0]["SITE_ID"],
                            $aPageZone["areas"][0]["LANGUE_ID"],
                            Pelican::getPreviewVersion()
                    ));
                  

                    $lcdvGamme = Citroen\GammeFinition\VehiculeGamme::getLCDV6Gamme(  
                            $aPageZone["areas"][0]["PAGE_VEHICULE"],
                            $aPageZone["areas"][0]["SITE_ID"],
                            $aPageZone["areas"][0]["LANGUE_ID"]
                            );
                    if($aPageVehicule){
                        $aPageVehicule = array_merge($aPageVehicule,$lcdvGamme);
                    }

            foreach($result as $key=>$value){
                if(!empty($value['PAGE_ZONE_MULTI_ATTRIBUT'])){
                    $sql='SELECT *
                        FROM
                            #pref#_barre_outils
                        WHERE
                            BARRE_OUTILS_ID = '.$value['PAGE_ZONE_MULTI_ATTRIBUT'];

                    $temp = $oConnection->queryTab($sql, $aBind);
                    $temps[$i]['OUTIL'] = $temp[0];
            

                }
                else{
                    $temps[$i]['PAGE_ZONE_MULTI_URL'] = $value['PAGE_ZONE_MULTI_URL'];
                    $temps[$i]['PAGE_ZONE_MULTI_URL2'] = $value['PAGE_ZONE_MULTI_URL2'];
                    $temps[$i]['PAGE_ZONE_MULTI_VALUE'] = $value['PAGE_ZONE_MULTI_VALUE'];
                    $temps[$i]['PAGE_ZONE_MULTI_LABEL'] = $value['PAGE_ZONE_MULTI_LABEL'];
                    $temps[$i]['PAGE_ZONE_MULTI_URL'] = $this->replaceTags($temps[$i]['PAGE_ZONE_MULTI_URL'],$aPageVehicule,$aConfiguration);
                    $temps[$i]['PAGE_ZONE_MULTI_URL2'] = $this->replaceTags($temps[$i]['PAGE_ZONE_MULTI_URL2'],$aPageVehicule,$aConfiguration);
              
                }

                


                $i++;
            }   
            $result = $temps;
        }        
        
        $this->value = $result;
    }

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValueProfiling() {
        $params = $this->params;
        $perso = self::$perso;
        $return = !empty($perso[$params[4]]) ? $perso[$params[4]] : array();
        $this->value = $return;
    }


    function replaceTags($url , $aVehicule, $aConfiguration){
        //Presence d'un tags à remplacer dans l'url
        if(preg_match('/##([0-9]|[a-zA-Z_-])*##/', $url)){
            
             if(preg_match('~(##URL_CONFIGURATEUR##|##URL_CONFIGURATEUR_PRO##)~i', $url)){
                //url pour le configurateur
               $url =  \Citroen\Configurateur::getConfigurateurUrl($aVehicule,$aConfiguration,true);
            }else{
                //url standard
                $tags = array(
                        '#LCVD#'=> $aVehicule['LCDV6'],
                        '##LCDV_CURRENT##' => $aVehicule['LCDV6']
                    );
                $url= \Citroen\Html\Util::replaceTagsInUrl(  $url ,$tags);
            }
                
        }
        return $url;
    }
}
?>