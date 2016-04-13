<?php
/**
 * Criteo catalog feed, utilisé par criteo pour générer des bannières de pub ciblées
 * 
 * @package Citroen
 * @subpackage Citroen_Controller_CriteoCatalogFeed_Controller
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 * @since 22/01/2015
 */
class Citroen_Controller_CriteoCatalogFeed_Controller extends Pelican_Controller
{
    public function indexAction()
    {
        // Récupération de la langue courante (première langue du site courant)
        $aLangue = Pelican_Cache::fetch("Frontend/Citroen/SiteLangues", $_SESSION[APP]['SITE_ID']);
        $currentLang = array_shift($aLangue);
        
        // Récupération des données (véhicule + showroom)
        $result = self::getCarsAndShowroom($_SESSION[APP]['SITE_ID'], $currentLang['LANGUE_ID']);
        
        // Assemblage données pour le flux XML
        $feedData = array();
        foreach ($result as $key => $val) {
            // Si le véhicule n'a pas de page showroom accueil, on ne l'affiche pas dans le flux
            if ($val['showroom_page_id'] == null) {
                continue;
            }
            
            // Récupération des 3 premiers points forts
            $multiPointsForts = Pelican_Cache::fetch('Frontend/Citroen/ZoneMulti', array(
                $val['showroom_page_id'],
                $val['showroom_langue_id'],
                'CURRENT',
                Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_POINTS_FORTS'],
                'POINTS_FORTS'
            ));
            $pointsForts = array();
            if (!empty($multiPointsForts) && is_array($multiPointsForts)) {
                $multiPointsForts = array_slice($multiPointsForts, 0, 3);
                $maxLen = floor(500/3);
                foreach ($multiPointsForts as $point) {
                    $pointsForts[] = self::tuncateWordBoundary(trim($point['PAGE_ZONE_MULTI_TITRE']), $maxLen);
                }
            }
            $pointsForts = implode('|', $pointsForts);
            
			
			// Page globale
			$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion(),
					Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
			));			
			
			// Zone Configuration de la page globale
			$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
					$pageGlobal['PAGE_ID'],
					Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
					$pageGlobal['PAGE_VERSION'],
					$_SESSION[APP]['LANGUE_ID']
			));
			
			
            $producturl2 = '';
            if($aConfiguration['ZONE_TITRE22'] == 1 && $val['PAGE_URL_FLUX_XML'] != ""){
               $producturl2 = $val['PAGE_URL_FLUX_XML'];
            }
            
			
            $feedData[] = array(
                'id'          => !empty($val['VEHICULE_LCDV6_MANUAL']) ? $val['VEHICULE_LCDV6_MANUAL'] : $val['VEHICULE_LCDV6_CONFIG'],
                'name'        => $val['VEHICULE_LABEL'],
                'producturl'  => Pelican::$config['DOCUMENT_HTTP'].$val['showroom_page_clear_url'],
                'producturl2'  => $producturl2,
                'bigimage'    => 'http://'.Pelican::$config['HTTP_MEDIA'].$val['media_path'],
                'Price'       => $val['VEHICULE_CASH_PRICE'],
                'categoryid1' => $val['VEHICULE_LABEL'],
                'description' => $pointsForts
            );
        }
        
        // Génération du flux XML
        $feed = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products/>');
        foreach ($feedData as $key => $val) {
            $productEl = $feed->addChild('product');
            $productEl->addAttribute('id', $val['id']);
            $productEl->name        = $val['name'];
            $productEl->producturl  = $val['producturl'];
            if($val['producturl2'] != ''){
				$productEl->producturl2  = $val['producturl2'];
			}
            $productEl->bigimage    = $val['bigimage'];
            $productEl->Price       = $val['Price'];
            $productEl->categoryid1 = $val['categoryid1'];
            $productEl->description = $val['description'];
        }
        
        $this->getRequest()->setHeaders('Content-type', 'text/xml');
        $this->setResponse($feed->asXML());
    }
    
    /**
     * Retourne la liste des voitures du référenciel véhicule ainsi qu'une page showroom accueil associée à chaque véhicule,
     * ainsi que la liste des points forts extrait de la page showroom accueil
     */
    private static function getCarsAndShowroom($siteId, $langueId)
    {
        $oConnection = Pelican_Db::getInstance();
        
        $bind = array(
            ':SITE_ID'   => $siteId,
            ':LANGUE_ID' => $langueId,
            ':TPID'      => Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'],
            ':ZTID'      => Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE']
        );		
		
        $stmt = "
        SELECT
            v.*,
            showroom.*,
            m.MEDIA_PATH AS media_path            
        FROM #pref#_vehicule v
        LEFT JOIN (
            SELECT
                pv.PAGE_URL_FLUX_XML,
                pv.PAGE_ID AS showroom_page_id,
                pv.LANGUE_ID AS showroom_langue_id,
                pv.PAGE_VERSION AS showroom_page_version,
                pv.PAGE_CLEAR_URL AS showroom_page_clear_url,
                pz.ZONE_ATTRIBUT AS showroom_vehicule_id,
                COUNT(pz.ZONE_ATTRIBUT) AS nb_page_found
            FROM #pref#_page_version pv
            INNER JOIN #pref#_page p
                ON  p.PAGE_ID = pv.PAGE_ID
                AND p.LANGUE_ID = pv.LANGUE_ID
                AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                AND p.PAGE_STATUS = 1 /* Page en ligne */
                AND pv.STATE_ID = 4 /* Page publiée */
                AND p.SITE_ID = :SITE_ID
                AND p.LANGUE_ID = :LANGUE_ID
                AND pv.TEMPLATE_PAGE_ID = :TPID
            INNER JOIN #pref#_page_zone pz
                ON  pz.PAGE_ID = pv.PAGE_ID
                AND pz.LANGUE_ID = pv.LANGUE_ID
                AND pz.PAGE_VERSION = pv.PAGE_VERSION
                AND pz.ZONE_TEMPLATE_ID = :ZTID
            GROUP BY showroom_vehicule_id
        ) showroom ON showroom.showroom_vehicule_id = v.VEHICULE_ID
        LEFT JOIN #pref#_media m ON m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL
        WHERE v.SITE_ID = :SITE_ID AND v.LANGUE_ID = :LANGUE_ID;";
        
        
        $result = $oConnection->queryTab($stmt, $bind);
        return $result;
    }
    
    /**
     * Coupe le texte $string si sa longueur dépasse $max et ajoute $suffix à la fin
     */
    public static function tuncateWordBoundary($string, $max = 3000, $suffix = '')
    {
        $innerMax = $max - strlen($suffix);
        return (strlen($string) < $max) ? $string : substr($string, 0, strpos(wordwrap($string, $innerMax), "\n")).$suffix;
    }
}
?>