<?php
/**
 * Fichier de Pelican_Cache : Barre Outils
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_BarreOutils extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
		if(is_array($this->params[2]) && count($this->params[2]) > 0){
			foreach($this->params[2] as $i => $id) {
				$aBind[':BARRE_OUTILS_ID'.$i] = $id;
			}
		}
        $sSQL = "
            select rs.*
            from #pref#_barre_outils rs
            where SITE_ID = :SITE_ID
            and LANGUE_ID = :LANGUE_ID
            and BARRE_OUTILS_ID in (";
			if(is_array($this->params[2]) && count($this->params[2]) > 0){
				foreach($this->params[2] as $i => $id) {
					if($i!=0) { $sSQL .= ","; }
					$sSQL .= ":BARRE_OUTILS_ID".$i;
				}
			}else{
				$sSQL .= "''";
			}
        $sSQL .= ")
            order by BARRE_OUTILS_ID asc
        ";
        $aTemp = $oConnection->queryTab($sSQL, $aBind);
        $aResults = array();
        if ($aTemp) {
            foreach ($aTemp as $temp) {
                $aResults[$temp['BARRE_OUTILS_ID']] = $temp;
            }
        }
        if(is_array($aResults) && is_array($this->params[2])) $aResults = self::sortArrayByArray($aResults, $this->params[2]);

        //définition de l'index de position de l'outil qui sera utilisé pour la fonctionnalité GTM
        foreach ($aResults as $key => $temp) {
                $aResults[$key]['INDEX']=$key+1;
        }            
        $this->value = $aResults;
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
        return $ordered + $array;
    }

}