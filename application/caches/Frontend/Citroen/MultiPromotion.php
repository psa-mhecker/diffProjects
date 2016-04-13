<?php
/**
 * Fichier de Pelican_Cache : Liste des véhicules par page promotion
 * @package Cache
 * @subpackage Pelican
 */

class Frontend_Citroen_MultiPromotion extends Pelican_Cache {

    var $duration = WEEK;
    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {

        $oConnection = Pelican_Db::getInstance ();

        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $aBind[":PAGE_VERSION"] = $this->params[2];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
        $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($this->params[4]);
        $aBind[':SITE_ID'] = $this->params[5];
        if ($this->params [6]) {
                $type_version = $this->params [6];
        } else {
                $type_version = "CURRENT";
        }
        $aBind[":STATE_ID"] = 1;
        if ($type_version == "CURRENT") {
            $aBind[":STATE_ID"] = 4;
        }
        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[7];
        $aBind[":PAGE_ZONE_MULTI_TYPE2"] = $oConnection->strToBind($this->params[8]);
        if(!empty($this->params[9])){
            $iVehiculeId = $this->params[9];
        }else{
            $iVehiculeId = "";
        }
        $forcePublished = "";
        if ($this->params[5] && $type_version == "DRAFT") {
            $forcePublished = " OR pv.STATE_ID = 4";
        }

        //Requête pour la liste des promotions
        $query = "select
                    pzm.*, m.MEDIA_PATH, m.MEDIA_ALT, m2.MEDIA_PATH as MEDIA_PATH_FLASH, m2.MEDIA_ALT as MEDIA_ALT_FLASH, m3.MEDIA_PATH as YOUTUBE_PATH, m3.MEDIA_ALT as YOUTUBE_ALT
                  from
                    #pref#_page_zone_multi pzm
                  left join
                      #pref#_media m
                        ON (pzm.MEDIA_ID = m.MEDIA_ID)
                  left join
                      #pref#_media m2
                        ON (pzm.MEDIA_ID2 = m2.MEDIA_ID)
                  left join
                      #pref#_media m3
                        ON (pzm.YOUTUBE_ID = m3.MEDIA_ID)
                  where
                    PAGE_ID = :PAGE_ID
                    and LANGUE_ID = :LANGUE_ID
                    and PAGE_VERSION = :PAGE_VERSION
                    and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                    and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                  ORDER BY
                    PAGE_ZONE_MULTI_ID
	        ";

        //Requêtes pour la liste des promotions filles
        $sSQL = "select
                    v.VEHICULE_ID, v.VEHICULE_LABEL,pv.PAGE_URL_EXTERNE,pv.PAGE_URL_EXTERNE_MODE_OUVERTURE,PAGE_CLEAR_URL,CONCAT(pzm.PAGE_ID,'||',pzm.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) as IDENTIFIANT
                from
                    #pref#_page_zone_multi pzm
                INNER JOIN
                    #pref#_page p
                        ON (p.PAGE_ID = pzm.PAGE_ID AND p.PAGE_".$type_version."_VERSION = pzm.PAGE_VERSION AND p.LANGUE_ID = pzm.LANGUE_ID)
                INNER JOIN
                    #pref#_page_version pv
                        ON (pv.PAGE_ID = pzm.PAGE_ID AND pv.PAGE_VERSION = pzm.PAGE_VERSION AND pv.LANGUE_ID = pzm.LANGUE_ID)
                LEFT JOIN
                    #pref#_vehicule v
                        on (v.vehicule_id = pzm.PAGE_ZONE_MULTI_LABEL5 AND v.LANGUE_ID = pzm.LANGUE_ID)
                LEFT JOIN
                    #pref#_media m
                        ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
                where
                    pzm.LANGUE_ID= :LANGUE_ID
                AND
                    PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE2
                AND
                    CONCAT(pzm.PAGE_ID,'||',pzm.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) IN(:CLE_PROMOTION)
                AND
                    p.SITE_ID = :SITE_ID
                AND
                    TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                and 
                    p.PAGE_STATUS = 1
                and 
                    (pv.STATE_ID = :STATE_ID $forcePublished)
                    ";

        //Requête pour la liste des CTA des promotions
        $sSql2 = "select
                    pzmm.*, m.MEDIA_PATH
                  from
                    #pref#_page_zone_multi_multi pzmm
                  left join
                      #pref#_media m
                        ON (pzmm.MEDIA_ID = m.MEDIA_ID)
                  where
                    PAGE_ID = :PAGE_ID
                    and LANGUE_ID = :LANGUE_ID
                    and PAGE_VERSION = :PAGE_VERSION
                    and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE3
                    and PAGE_ZONE_MULTI_ID = :PAGE_ZONE_MULTI_ID
                    and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                  ORDER BY
                    PAGE_ZONE_MULTI_MULTI_ID
	        ";

        $aListePromotions = $oConnection->queryTab( $query, $aBind );
        $iCount = count($aListePromotions);
        //var_dump($aListePromotions) ;
        //var_dump($aBind) ;
        for($i=0;$i<$iCount;$i++){
            //On va chercher les CTA associées
            $aBind[":PAGE_ZONE_MULTI_ID"] = $aListePromotions[$i]["PAGE_ZONE_MULTI_ID"];
            $aBind[":PAGE_ZONE_MULTI_TYPE3"] = $oConnection->strToBind("CTAFORM");
            $aListePromotions[$i]["CTA"] = $oConnection->queryTab( $sSql2, $aBind );
            
          
            
            
            if(!empty($aListePromotions[$i]["PAGE_ZONE_MULTI_TEXT2"])){
                
                $aClePromotion = explode(",",$aListePromotions[$i]["PAGE_ZONE_MULTI_TEXT2"]);
                
                if(count($aClePromotion) > 0){
                   
                    for($k=0;$k<count($aClePromotion);$k++){
                        $aClePromotion[$k] = $oConnection->strToBind($aClePromotion[$k]);
                    }
                     $aBind[":CLE_PROMOTION"] = implode(',',$aClePromotion);
                }else{
                     $aBind[":CLE_PROMOTION"] = '';
                }
                                
				//On va chercher les promotions filles
				$aListePromotionsChild = $oConnection->queryTab( $sSQL, $aBind );
			}
                     // ; 
            if(!empty($aListePromotionsChild) && is_array($aListePromotionsChild)){
                $aVehiculeIds = array();
                for($j=0;$j<count($aListePromotionsChild);$j++){
                    //On va chercher les CTA associées
                    $aVehiculeIds[] =  $aListePromotionsChild[$j]["VEHICULE_ID"];
                }
            }
            //On ne garde que les promos concernant un véhicule si celui ci est spécifié
            if(!empty($iVehiculeId)){
                if(in_array($iVehiculeId,$aVehiculeIds)){
                    $aListePromotions[$i]["CHILD"] = $aListePromotionsChild;
                }else{
                    unset($aListePromotions[(int)$i]);
                }
            }else{
                $aListePromotions[$i]["CHILD"] = $aListePromotionsChild;
                if(is_array($aListePromotionsChild) && count($aListePromotionsChild)>0){
                    foreach($aListePromotionsChild as $promoChild){
                        $aListePromotions[$i]["CHILD_LIST"][] = $promoChild['IDENTIFIANT'];
                    }
                }
            }
        }

        $this->value = array_values($aListePromotions);
        return $this->value;
    }
}