<?php

/**
 * @package Cache
 * @subpackage Config
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur WS Annuaire PDV
 *
 * retour : tableau annuaire PDV
 *
 * @package Cache
 * @subpackage Config
 * @author Joseph Franclin <Joseph.Franclin@businessdecision.com>
 * @since 20/08/2014
 */
class Frontend_Citroen_Annuaire_Services extends Citroen_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
      
        $oConnection = Pelican_Db::getInstance(); 

        $aBind = array();
        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];

        $aLangue = Pelican_Cache::fetch("Language", array(
                    $aBind[":LANGUE_ID"]
        ));

        $aSite = Pelican_Cache::fetch("Backend/Generic", array(
            "site_code",
            'SITE_ID',
            "SITE_ID = ".$aBind[":SITE_ID"].""
        ));
                    
        $sPays = strtoupper($aSite[0]['SITE_CODE_PAYS']);
        $sLocale = $aLangue['LANGUE_CODE']."-".$sPays;
  
         $serviceParams = array(
            'country' => $sPays,
            'culture' => $sLocale,
            'consumer' => \Pelican::$config['SERVICE_ANNUPDV']['CONSUMER'],
            'brand' => \Pelican::$config['SERVICE_ANNUPDV']['BRAND'],
            'ViewActivities'=>true, 
            'ViewLicences'=>true, 
            'ViewIndicators'=>true, 
            'ViewServices'=>false

        );

        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_ANNUPDV', array());
            $response = $service->call('getBusinessList', $serviceParams);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $aBusinessList = $response->BusinessList;
        $aNewBusinessList = array();
        if(is_array($aBusinessList) && !empty($aBusinessList))
        {
            foreach ($aBusinessList as $keyBu => $valueBu) {
                $aNewBusinessList[] = (array)$valueBu;
            }
        }
        $sCode = array();

        foreach ($aNewBusinessList as $keyNewBu => $NewBu) {
         $sCode[] = $NewBu['Code'];
    
         $aBind[":CODE_SERVICE"] = $NewBu['Code'];
         $aBind[":LABEL_SERVICE"] = addslashes($NewBu['Label']);
         $aBind[":TYPE_SERVICE"] = $NewBu['Type'];

         $sqlReplace = "INSERT INTO #pref#_ws_services_pdv (
                        SITE_ID, 
                        LANGUE_ID, 
                        CODE_SERVICE,
                        LABEL_SERVICE, 
                        TYPE_SERVICE
                        )
                        VALUES(
                        :SITE_ID, 
                        :LANGUE_ID, 
                        ':CODE_SERVICE', 
                        ':LABEL_SERVICE', 
                        ':TYPE_SERVICE'
                        ) 
ON DUPLICATE KEY UPDATE LABEL_SERVICE = ':LABEL_SERVICE' , TYPE_SERVICE = ':TYPE_SERVICE'";
            $oConnection->query( $sqlReplace, $aBind );
        }

          $sqlDelete = "DELETE FROM #pref#_ws_services_pdv 
          WHERE  
          SITE_ID = :SITE_ID 
          AND LANGUE_ID = :LANGUE_ID
          AND CODE_SERVICE NOT IN ( '" . implode($sCode, "', '") . "' ) ";


         $oConnection->query( $sqlDelete, $aBind );


        $this->value =  $aNewBusinessList;

        

        }
}
?>