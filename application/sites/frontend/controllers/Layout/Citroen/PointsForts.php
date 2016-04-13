<?php
/**
 * Classe d'affichage Front de la tranche Points Forts du Showroom Accueil
 * 
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 01/08/2013
 */
class Layout_Citroen_PointsForts_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {        
        /* Récupération des informations de page et page_zone à afficher */
        $aParams = Frontoffice_Vehicule_Helper::getShowroomAccueilValues($this->getParams(),$_SESSION[APP]['LANGUE_ID']);
		$aDataParams	=	$this->getParams();	

		if(!isset($aDataParams['PAGE_PARENT_ID']) || empty($aDataParams['PAGE_PARENT_ID'])){
			$this->assign('display', '0');
			$this->fetch();
		}else{
			$aDatasPageParent = Pelican_Cache::fetch("Frontend/Page", array($aDataParams['PAGE_PARENT_ID'],$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],'CURRENT'));	
			if( $aDataParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'] && $aDatasPageParent['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']){
				$this->assign('display', '0');
				$this->fetch();
			}else{
				$this->assign('display', '1');
			}
		}

        /* Récupération des Points forts (multi) */
        $aPointsForts = Citroen_Cache::fetchProfiling($aParams['ZONE_PERSO'],'Frontend/Citroen/ZoneMulti', array(
            $aParams['PAGE_ID'],
            $aParams['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aParams['ZONE_TEMPLATE_ID'],
            'POINTS_FORTS',
            $aParams['AREA_ID'],
            $aParams['ZONE_ORDER']
        ));
        /* Si il y a moins de 3 points forts, ceux ci ne sont pas affichés */
        if ( is_array($aPointsForts) && count($aPointsForts) < 3 ){
            $aPointsForts = array();
        }
        
        /* Récupération du visuel */
        $aMediaVisuel = Pelican_Cache::fetch('Media/Detail', array(
                $aParams['MEDIA_ID']
        ));
        
        /* Assignation SMARTY */
        $this->assign('aPointsForts', $aPointsForts);
        $this->assign('aParams', $aParams);
        $this->assign('aMediaVisuel', $aMediaVisuel);
        
        if(!$this->isMobile() || $aDataParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'])
            $this->fetch();
    }
}