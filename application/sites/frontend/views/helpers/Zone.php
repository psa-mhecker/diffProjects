<?php
/**
 * View Date
 * 
 * @version 1.0
 * @since 18/07/2013
 */

Class Frontoffice_Zone_Helper {
	
	/**
	 * Méthode permettant de définir la position de la zone affichée
	 * 
	 * @param $iZoneId int : Identifiant de la zone
	 * @param $iZoneOrder int : Order de la zone
	 * @param $iAreaId int : Identifiant de l'area
	 */
	
	public static function setPositionZone($iZoneId, $iZoneOrder, $iAreaId) 
	{
		//a supprimer
		$iZoneOrder = 1;
		if(isset($_SESSION[APP]['ZONE_POSITION'][$iZoneId][$iZoneOrder][$iAreaId]['POSITION'])){
			$_SESSION[APP]['ZONE_POSITION'][$iZoneId][$iZoneOrder][$iAreaId]['POSITION']++;
		}else{
			$_SESSION[APP]['ZONE_POSITION'][$iZoneId][$iZoneOrder][$iAreaId]['POSITION'] = 1;
		}
	}
	
	/**
	 * Méthode permettant de retourner la position de la zone affichée
	 * 
	 * @param $iZoneId int : Identifiant de la zone
	 * @param $iZoneOrder int : Order de la zone
	 * @param $iAreaId int : Identifiant de l'area
	 * @return $iReturn int : Position de la zone
	 */
	
	public static function getPositionZone($iZoneId, $iZoneOrder, $iAreaId) 
	{
		//a supprimer
		$iZoneOrder = 1;
		$iReturn = $_SESSION[APP]['ZONE_POSITION'][$iZoneId][$iZoneOrder][$iAreaId]['POSITION'];
		return $iReturn;
	}
	
	/**
	 * Méthode permettant de retourner la position de la zone affichée
	 * 
	 * @param $iZoneId int : Identifiant de la zone
	 * @param $iZoneOrder int : Order de la zone
	 * @param $iAreaId int : Identifiant de l'area
	 * @return $iReturn int : Position de la zone
	 */
	
	public static function getAffichePrixCredit() 
	{
		/*
		 *  Page globale
		 */
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID'],
			'CURRENT',
			Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		/*
		 *  Configuration
		 */
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
			$pageGlobal['PAGE_ID'],
			Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
			$pageGlobal['PAGE_VERSION'],
			$_SESSION[APP]['LANGUE_ID']
		));
		
		return $aConfiguration['ZONE_PARAMETERS'];
	}
    
    /**
     * Retourne le tableau de code couleur à utiliser pour la tranche outil
     * @param $mode string : Mode d'affichage de la tranche (C/DS/Neutre)
     * @param $couleurOn string : Code couleur ON (backoffice)
     * @param $couleurOff string : Code couleur OFF (backoffice)
     */
    public static function getCodeCouleurOutil($mode, $couleurOn, $couleurOff)
    {
        $couleurOn  = !empty($couleurOn)  ? '#'.$couleurOn  : '#DC002E';
        $couleurOff = !empty($couleurOff) ? '#'.$couleurOff : '#DC002E';
        
        switch ($mode) {
            case 'C':
                // Couleurs BO
                $codeCouleur = array(
                    'default' => array(
                        'background' => '#ffffff',
                        'border'     => $couleurOn,
                        'color'      => '#4b4a4d',
                    ),
                    'hover' => array(
                        'background' => $couleurOn,
                        'border'     => $couleurOff,
                        'color'      => '#ffffff',
                    ),
                );
                break;
            case 'DS':
                // Couleurs DS
                $codeCouleur = array(
                    'default' => array(
                        'background' => '#201a19',
                        'border'     => '#a70242',
                        'color'      => '#ffffff',
                    ),
                    'hover' => array(
                        'background' => '#a70242',
                        'border'     => '#201a19',
                        'color'      => '#ffffff',
                    ),
                );
                break;
            case 'NEUTRE':
            default:
                // Couleurs génériques
                $codeCouleur = array(
                    'default' => array(
                        'background' => '#ffffff',
                        'border'     => '#DC002E',
                        'color'      => '#4b4a4d',
                    ),
                    'hover' => array(
                        'background' => '#B40027',
                        'border'     => '#B40027',
                        'color'      => '#ffffff',
                    ),
                );
                break;
        }
        return $codeCouleur;
    }
    
    /**
    * Ajoute l'URL des picto on/off à une liste d'outils en fonction du mode d'affichage de la tranche.
    * @param $mode string : Mode d'affichage de la tranche (C/DS/Neutre)
    * @param $outils array : Liste d'outils (obtenue via appel au cache Frontend/Citroen/VehiculeOutil). Le tableau est passé par référence.
    */
    public static function addPictoOutil($ligne, &$outils, $mode)
    {
        if (empty($outils) || !is_array($outils)) {
            return;
        }
        
        foreach ($outils as $key => $val) {
            if ($ligne == 'DS') {
            	if($mode=='vertical'){
            		$picto = array(
	                    'on'  => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_OFF'])),
	                    'off' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_OFF'])),
	                    'new_show' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_TRANS_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_TRANS_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_OFF'])),
	                );
            	}
            	else{
	                $picto = array(
	                    'on'  => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_ON'])),
	                    'off' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_OFF'])),
	                    'new_show' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_DS_OFF'])),
	                );
            	}
            } else {
            	if($mode=='vertical'){
            		$picto = array(
	                    'on'  => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_C_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_C_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_OFF'])),
	                    'off' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_C_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_C_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_OFF'])),
	                    'new_show' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_TRANS_VERTICAL']))?Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_TRANS_VERTICAL'])):Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_OFF'])),
	                );
            	}
            	else{
	                $picto = array(
	                    'on'  => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_ON'])),
	                    'off' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_OFF'])),
	                    'new_show' => Pelican_Cache::fetch('Media/Detail', array($val['MEDIA_GENERIQUE_OFF'])),
	                );
            	}
            }
            $outils[$key]['picto']['on'] = !empty($picto['on']['MEDIA_PATH']) ? Pelican::$config['MEDIA_HTTP'].$picto['on']['MEDIA_PATH'] : null;
            $outils[$key]['picto']['off'] = !empty($picto['off']['MEDIA_PATH']) ? Pelican::$config['MEDIA_HTTP'].$picto['off']['MEDIA_PATH'] : null;
            $outils[$key]['picto']['new_show'] = !empty($picto['new_show']['MEDIA_PATH']) ? Pelican::$config['MEDIA_HTTP'].$picto['new_show']['MEDIA_PATH'] : null;
        }
    }
    
    /**
     * Indique si la zone utilise des données perso ou les données par défaut.
     * cf. library/Pelican/View/plugins/function.gtm.php
     * @param $zoneParams : Données de la zone ($this->getParams() dans le contrôleur)
     * @param $idMulti (faculatif) : Utilisé pour les tranches avec multi (comme slideshow).
     *                               Il s'agit du champ qui stocke le mode de synchronisation du multi.
     */
    public static function usingPersoData(&$zoneParams, $idMulti = null)
    {
        // Pour les multi, on se base uniquement sur la valeur du champ synchronisation
        if (isset($idMulti) && !empty($idMulti)) {
            return $idMulti == '-2' ? true : false;
        }
        
        // Si la zone n'a pas de données perso, alors elle utilise forcément les données génériques
        if (empty($zoneParams['ZONE_PERSO'])) {
            return false;
        }
        
        $aZonePerso = json_decode($zoneParams['ZONE_PERSO']);
        
        $index=1;
        while($aZonePerso->{'PROFIL_'.$index}->PROFILE_ID){
			if(is_array($_SESSION[APP]['PROFILES_USER'])){
				if (isset($aZonePerso->{'PROFIL_'.$index}->PROFILE_ID) && in_array($aZonePerso->{'PROFIL_'.$index}->PROFILE_ID, $_SESSION[APP]['PROFILES_USER'])) {
					return true;
				}
			}
            $index++;
        }
     
        return false;
    }
    
    /**
     * Ajoute ou modifie des paramètres dans la queryString d'une URL
     * @param $url : URL à modifier
     * @param $params : tableau de paramètres à injecter dans $url
     */
    public static function setUrlQueryString($url, $params)
    {
        $addparams=array();
        foreach( $params as $key => $value){
            $addparams[] = $key.'='.urlencode($value);
        }
      return   \Citroen\Html\Util::replaceTagsInUrl($url,array(),false, $addparams);
     
    }
}
?>