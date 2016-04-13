<?php
namespace Citroen\GammeFinition;
use Citroen\GammeFinition\Gamme;
/**
 * Fichier de Citroen_Gamme : langues
 *
 * Classe de gestion des import des diverses informations des
 * gammes dans la base de données à partir d'informations remontées
 * par les WebServices PSA
 *
 * @package Citroen
 * @subpackage Gamme
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 16/07/2013
 */


/**
 * Classe permettant l'introduction des données des modèles
 * issues d'un fichier CSV provenant des WebServices PSA
 */
class VehiculeGamme extends Gamme
{
    /* Nom de la table PHPFactory à utiliser pour y intégrer les données */
    private $sPHPFactoryTableName = '#pref#_ws_vehicule_gamme';

    /* Nom de la dataclass correspondante dans le CSV fourni */
    private $sWSDataclassName = 'VAG';

    /* Tableau des colonnes de la table modèle */
    private $aColumnsImportMatching = array();

    /* Tableau des données provenant du WebService et liées à l'objet */
    private $aWSChildData = array();

    /* Tableau des propriétés provenant du WebService et liées à l'objet */
    private $aWSChildProperties = array();


    /**
     * Constructeur de l'objet
     */
    public function __construct()
    {
        /* Initialisation du tableau de matching entre les noms
         * des tables fournies par PSA et les tables du SGBD
         * PHP factory
         */
        $this->setColumnsImportMatching();

        /* Initialisation des informations générales du véhicule */
    }

     /**
     * Méthode publique remontant ne nom de la Dataclass
     * de la classe
     */
    public function getWSDataclassName()
    {
        return $this->sWSDataclassName;
    }

    /**
     * Méthode publique remontant ne nom de la table
     * PHPFactory utilisée pour la classe
     */
    public function getPHPFactoryTableName()
    {
        return $this->sPHPFactoryTableName;
    }

    /**
     * Méthode privée permettant d'associer un nom de colonne
     * de la bdd PHPFactory à un nom de colonne du CSV
     * généré par les WebServices PSA
     */
    public function setColumnsImportMatching()
    {
        $this->aColumnsImportMatching[gamme_csv_culture_field_WS]           = gamme_csv_culture_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_gamme_field_WS]             = gamme_csv_gamme_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_lcdv4_field_WS]             = gamme_csv_lcdv4_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_lcdv6_field_WS]             = gamme_csv_lcdv6_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_modelLabel_field_WS]        = gamme_csv_modelLabel_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyLabel_field_WS]         = gamme_csv_bodyLabel_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyCode_field_WS]          = gamme_csv_bodyCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_modelBodyLabel_field_WS]    = gamme_csv_modelBodyLabel_field_PHPfact;

    }

    /**
     * Méthode publique permettant de remonter le tableau associatif
     * des colonnes de la base de données et de celles du WebService
     */
    public function getColumnsImportMatching()
    {
        return $this->aColumnsImportMatching;
    }

    /**
     * Méthode privée d'instanciation des données et propriétés provenant du WebService de l'objet
     */
    public function setWSChild($aWSAllProperties,$aWSAllData)
    {
        /* Instanciation des propriétés propres à l'objet et provenant de l'export
         * de toutes les données du WebService
         */
        $this->setWSChildProperties($aWSAllProperties);
        /* Instanciation des données propres à l'objet et provenant de l'export
         * de toutes les données du WebService
         */
        $this->setWSChildData($aWSAllData);
    }

    /**
     * Méthode privée d'instanciation des données provenant du WebService de l'objet
     */
    private function setWSChildData($aWSAllData)
    {
        if (is_array($aWSAllData) && array_key_exists($this->sWSDataclassName, $aWSAllData)){
            $this->aWSChildData = $aWSAllData[$this->sWSDataclassName];
        }
    }

    /**
     * Méthode privée d'instanciation des propriétés provenant du WebService de l'objet
     */
    private function setWSChildProperties($aWSAllProperties)
    {
        if (is_array($aWSAllProperties) && array_key_exists($this->sWSDataclassName, $aWSAllProperties)){
            $this->aWSChildProperties = $aWSAllProperties[$this->sWSDataclassName];
        }
    }

    /**
     * Méthode public pour la récupération des données du WebService
     */
    public function getWSChildData()
    {
        return $this->aWSChildData;
    }

    /**
     * Méthode public pour la récupération des Propriétés des données du WebService
     */
    public function getWSChildProperties()
    {
        return $this->aWSChildProperties;
    }


    /**
     * Méthode statique ramenant l'ensemble des informations des Véhicule à la gamme
     * présent dans notre table de véhicule Web (ou pas si le type de remontée
     * est 'combo' on ramène que les véhicules du WebService) pour un site et
     * une langue donné
     * Si un paramètre est manquant la méthode filtrera moins le résultat
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param string    $sLCDV6         Code LCDV6 identifiant du véhicule
     * @param string    $sDisplayMode   Mode d'affichage du résultat
     */
    public static function getVehiculesGamme($iSiteId = null, $iLangueId = null, $sLCDV6 = null, $sDisplayMode = '',$gammeFilter=array())
    {

        /* Initialisation des variables */
        if (!is_null($iSiteId)){
            $iSiteId = (int)$iSiteId;
        }
        if (!is_null($iLangueId)){
            $iLangueId = (int)$iLangueId;
        }
        if (!is_null($sLCDV6)){
            $sLCDV6 = (string)$sLCDV6;
        }
        $sDisplayMode = (string)$sDisplayMode;
        $aResult = array();

        /* Appel au fichier de cache ramenant l'ensemble des Véhicule à la gamme
         * pour un site et une langue donné
         */
        $aVehiculesGamme = \Pelican_Cache::fetch('Citroen/GammeVehiculeGamme',
                array($iSiteId, $iLangueId, $sLCDV6, $sDisplayMode,$gammeFilter));
        if (is_array($aVehiculesGamme)){
            $aResult = $aVehiculesGamme;
        }

        return $aResult;
    }

	 /**
     * Méthode statique ramenant l'ensemble des informations des Véhicule à la gamme
     * présent dans notre table de véhicule Web (ou pas si le type de remontée
     * est 'combo' on ramène que les véhicules du WebService) pour un site et
     * une langue donné
     * Si un paramètre est manquant la méthode filtrera moins le résultat
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param string    $sLCDV6         Code LCDV6 identifiant du véhicule
     * @param string    $sDisplayMode   Mode d'affichage du résultat
     */
     public static function getVehiculesGammeMtcfg($sCodePays)
    {
        $aVehiculesMtcfg = \Pelican_Cache::fetch('Backend/MoteurdeConfig',array($sCodePays));
        return $aVehiculesMtcfg;
    }

    /**
     * Méthode statique ramenant l'ensemble des informations d'un Véhicule
     * à mettre en avant dans un showroom
     * Si un paramètre est manquant la méthode filtrera moins le résultat
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param int       $iVehiculeId    Identifiant de langue
     * @param int       $iMinPrice      Prix minimum
     * @return array                    Tableau contenant l'ensemble des
     *                                  informations nécessaires à l'affichage
     *                                  du véhicule en ShowRoom (libellé, teintes,
     *                                  Prix comptant, Prix à crédit, mentions légales,...)
     *
     */
    public static function getShowRoomVehicule($iSiteId, $iLangueId, $iVehiculeId, $iMinPrice=null, $PageId='')
    {
        /* Initialisation des variables */
        $iSiteId = (int)$iSiteId;
        $iLangueId = (int)$iLangueId;
        $iVehiculeId = (int)$iVehiculeId;
        $sPrixHT = '';
        $sPrixTTC = '';
        $bTTCPrice = true;
        $aResult = array();

        /* Appel au fichier de cache ramenant l'ensemble des informations sur le
         * véhicule mais aussi ses teintes
         */
	
        $aShowRoomVehicule = \Pelican_Cache::fetch('Frontend/Citroen/VehiculeShowroomById',
                array($iSiteId, $iLangueId, $iVehiculeId, 'CURRENT', null, $iMinPrice, $PageId));

        if ( is_array($aShowRoomVehicule)
                && !empty($aShowRoomVehicule)
                && isset($aShowRoomVehicule['VEHICULE'])
                && isset($aShowRoomVehicule['COLORS'])
                ){

            $aResult = $aShowRoomVehicule;

            $aConfiguration = self::getSiteConfiguration($iSiteId, $iLangueId);
            $aResult['VEHICULE']['VEHICULE_CONF_WEB'] = '';
            $aResult['VEHICULE']['VEHICULE_CONF_MOBILE'] = '';


            /* Ajout de l'URL du configurateur pour la version Web */
            if ( is_array($aConfiguration)
                    && isset($aConfiguration['URL_CONFIGURATEUR'])
                    && !empty($aConfiguration['URL_CONFIGURATEUR'])
                    ){
                $iPosLastSlashUrl = strrpos($aConfiguration['URL_CONFIGURATEUR'], '/');
                $iLenUrl = strlen($aConfiguration['URL_CONFIGURATEUR']);
                $sUrlConf = $aConfiguration['URL_CONFIGURATEUR'];
                if ( $iPosLastSlashUrl !== false && $iPosLastSlashUrl !== $iLenUrl-1 ){
                    $sUrlConf = $aConfiguration['URL_CONFIGURATEUR'] . '/';
                }
                /* Ajout de l'URL du configurateur avec le code LCDV6 du véhicule */
                $aResult['VEHICULE']['VEHICULE_CONF_WEB'] = $sUrlConf . $aResult['VEHICULE']['LCDV6'] . '/';
            }
            /* Ajout de l'URL du configurateur pour la version mobile */
            if ( is_array($aConfiguration)
                    && isset($aConfiguration['URL_CONFIGURATEUR_MOBILE'])
                    && !empty($aConfiguration['URL_CONFIGURATEUR_MOBILE'])
                    ){
                $iPosLastSlashUrl = strrpos($aConfiguration['URL_CONFIGURATEUR_MOBILE'], '/');
                $iLenUrl = strlen($aConfiguration['URL_CONFIGURATEUR_MOBILE']);
                $sUrlConf = $aConfiguration['URL_CONFIGURATEUR_MOBILE'];
                if ( $iPosLastSlashUrl !== false && $iPosLastSlashUrl !== $iLenUrl-1 ){
                    $sUrlConf = $aConfiguration['URL_CONFIGURATEUR_MOBILE'] . '/';
                }
                /* Ajout de l'URL du configurateur avec le code LCDV6 du véhicule */
                $aResult['VEHICULE']['VEHICULE_CONF_MOBILE'] = $sUrlConf.$aResult['VEHICULE']['LCDV6'] . '/';
            }

            /* Ajout de la surcharge du prix à Crédit */
            /* Vérification si le prix est HT ou TTC */
            if ( isset($aShowRoomVehicule['VEHICULE']['VEHICULE_CASH_PRICE_TYPE'])
                    && $aShowRoomVehicule['VEHICULE']['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC'
                    ){
                $bTTCPrice = false;
            }

            if ( $bTTCPrice === true ){
                $sPrixTTC = $aShowRoomVehicule['VEHICULE']['CASH_PRICE'];
            }else{
                $sPrixHT = $aShowRoomVehicule['VEHICULE']['CASH_PRICE'];
            }
            /* Recherche du prix à crédit du véhicule
             * Si le véhicule provient du configurateur,
             * Si on doit afficher le prix à crédit
             * Et si l'option d'utilisation du WebService a été cochée
             */
			
            $aVehiculeCreditPrice  = array();
            if ( !empty($aShowRoomVehicule['VEHICULE']['VEHICULE_LCDV6_CONFIG'])
                    && $aShowRoomVehicule['VEHICULE']['VEHICULE_DISPLAY_CREDIT_PRICE'] == 1
                    && $aShowRoomVehicule['VEHICULE']['VEHICULE_USE_FINANCIAL_SIMULATOR'] == 1
                    && isset(\Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN'])
                    && \Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN']
                    ){
						
						
					if(($_SESSION[APP]['CODE_PAYS'] == 'DE' || $_SESSION[APP]['CODE_PAYS'] == 'AT') && !empty($aShowRoomVehicule['VEHICULE']['VEHICULE_LCDV6_MTCFG']) ){
						$sLcdvVehicule = $aShowRoomVehicule['VEHICULE']['VEHICULE_LCDV6_MTCFG'];
						
					}else{
						$sLcdvVehicule = $aShowRoomVehicule['VEHICULE']['VEHICULE_LCDV6_CONFIG'] ;
					}
					
					
					
                    $aVehiculeCreditPrice = self::getWSVehiculeCreditPrice(
                            $iSiteId,
                            $iLangueId,
                            $sLcdvVehicule,
                            $aShowRoomVehicule['VEHICULE']['VEHICULE_LABEL'],
                            $aShowRoomVehicule['VEHICULE']['VEHICULE_GAMME_CONFIG'],
                            $sPrixHT,
                            $sPrixTTC,
                            $aShowRoomVehicule['VEHICULE']['WS_MODEL_BODY_LABEL']
                            );
							
                    if( is_array($aVehiculeCreditPrice) && !empty($aVehiculeCreditPrice) ){
                        /* Si des informations sont remontées du WebService SimulFin
                         * on surcharge les valeurs de crédit
                         */
                        $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT'] = $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_NEXT_RENT'];
                        $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'] = $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'];
                        $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_FIRST_RENT'] = $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_FIRST_RENT'];
                        $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'] = $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'];

                        // Récupération des informations sur l'iframe de la calculatrice de financement
                        $calcKeys = array('PersoURL', 'PersoHeight', 'PersoWidth');
                        $calcData = null;
                        if (is_array($aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_ML_ALL'])) {
                            $calcData = array_intersect_key($aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_ML_ALL'], array_flip($calcKeys));
                        }
                        $aResult['VEHICULE']['VEHICULE_CREDIT_CALC'] = empty($calcData) ? null : $calcData;
                    }
            }



        }
        return array($aResult, $aVehiculeCreditPrice);
    }

    /**
     * Récupération des informations d'un véhicule associé à une page ShowRoom
     * @param int       $iPageId    Identifiant de la page ShowRoom
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId  Identifiant de la langue
     * @return array                    Tableau contenant l'ensemble des
     *                                  informations nécessaires à l'affichage
     *                                  du véhicule en ShowRoom (libellé, teintes,
     *                                  Prix comptant, Prix à crédit, mentions légales,...)
     */
    public static function  getShowRoomVehiculeByShowRoomPage($iPageId, $iSiteId, $iLangueId)
    {
        /* Initialisation des variables */
        $iSiteId = (int)$iSiteId;
        $iLangueId = (int)$iLangueId;
        $iPageId = (int)$iPageId;
        $aResult = array();

        /* Recherche de l'identifiant du véhicule pour la page ShowRoom passée
         * en paramètre
         */
        $iVehiculeId = VehiculeGamme::getVehiculeIdByShowRoomPage(
                $iPageId,
                $iSiteId,
                $iLangueId
                );
        if ( !is_null($iVehiculeId) ) {
            /* Recherche des informations sur l'ensemble des modèles disponibles
             * pour un site et une langue donné
             */
            $aResult = VehiculeGamme::getShowRoomVehicule(
                    $iSiteId,
                    $iLangueId,
                    $iVehiculeId
                    );
        }

        return $aResult;
    }

    /**
     * Méthode statique permettant de retrouver le véhicule associé à une page
     * showroom
     * @param int       $iPageId    Identifiant de la page ShowRoom
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId  Identifiant de la langue
     * @return mixed    int         Identifiant du véhicule si celui-ci est trouvé
     *                  null        Si aucun véhicule n'est trouvé
     */
    public static function getVehiculeIdByShowRoomPage($iPageId, $iSiteId, $iLangueId)
    {
        /* Initialisation des variables */
        $iSiteId = (int)$iSiteId;
        $iLangueId = (int)$iLangueId;
        $iPageId = (int)$iPageId;
        $iZoneTemplateId = \Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'];
        $mVehiculeId = null;

        /* Informations à remonter de la Page globale */
        $aShowRoomPage = \Pelican_Cache::fetch('Frontend/Page', array(
                $iPageId,
                $iSiteId,
                $iLangueId,
        ));
        /* Récupération du paramétrage de la zone Sélecteur de teinte qui contient
         * l'association avec le Véhicule
         */
        if ( is_array($aShowRoomPage) && !empty($aShowRoomPage) ){
            $aZone = \Pelican_Cache::fetch('Frontend/Page/ZoneTemplate', array(
                    $iPageId,
                    $iZoneTemplateId,
                    $aShowRoomPage['PAGE_VERSION'],
                    $iLangueId
            ));
        }
        if ( is_array($aZone) && isset($aZone['ZONE_ATTRIBUT']) && !empty($aZone['ZONE_ATTRIBUT']) ){
            $mVehiculeId = (int)$aZone['ZONE_ATTRIBUT'];
        }

        return $mVehiculeId;
    }



    /**
     * Méthode statique ramenant l'ensemble des PrixFinition Version
     * pour un site et une langue et un code LCDV6 donné. Un véhicule possède
     * plusieurs finitions et plusieurs versions
     *
     * Si un paramètre est manquant la méthode filtrera moins le résultat
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param string    $sLCDV6         Code LCDV6 identifiant du véhicule
     * @param string    $sDisplayMode   Mode d'affichage du résultat
     */
    public static function getWSPrixFinitionVersionByLCDV6($iSiteId = null, $iLangueId = null, $sLCDV6 = null, $sDisplayMode = '')
    {
        /* Initialisation des variables */
        if (!is_null($iSiteId)){
            $iSiteId = (int)$iSiteId;
        }
        if (!is_null($iLangueId)){
            $iLangueId = (int)$iLangueId;
        }
        if (!is_null($sLCDV6)){
            $sLCDV6 = (string)$sLCDV6;
        }
        $sDisplayMode = (string)$sDisplayMode;
        $aResult = array();

        /* Appel au fichier de cache ramenant l'ensemble des finitions
         * des véhicules pour un site et une langue donné
         */
		
        $aCacheTemp = \Pelican_Cache::fetch('Citroen/GammePrixFinitionVersion',
                array($iSiteId, $iLangueId, $sLCDV6, $sDisplayMode));

        if (is_array($aCacheTemp)){
            $aResult = $aCacheTemp;
        }

        return $aResult;
    }


       /**
     * Méthode statique ramenant l'ensemble les informations sur la version la moins
     * chère d'un véhicule
     * Si un paramètre est manquant la méthode filtrera moins le résultat
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param string    $sLCDV6         Code LCDV6 identifiant du véhicule
     */
    public static function getWSVehiculeFirstFinitionVersion($iSiteId, $iLangueId, $sLCDV6 )
    {
        /* Initialisation des variables */
        if (!is_null($iSiteId)){
            $iSiteId = (int)$iSiteId;
        }
        if (!is_null($iLangueId)){
            $iLangueId = (int)$iLangueId;
        }
        if (!is_null($sLCDV6)){
            $sLCDV6 = (string)$sLCDV6;
        }
        $aResult = array();

        /* Appel au fichier de cache ramenant l'ensemble des finitions
         * des véhicules pour un site et une langue donné
         */
        $aCacheTemp = self::getWSPrixFinitionVersionByLCDV6 ($iSiteId, $iLangueId, $sLCDV6, 'vehicule');

        if (is_array($aCacheTemp)){
            $aResult = $aCacheTemp;
        }

        return $aResult;
    }

    /**
     * Méthode statique ramenant le prix à afficher pour la version la moins chère
     * d'un véhicule
     *
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @param string    $sLCDV6         Code LCDV6 identifiant du véhicule
     */
    public static function getWSVehiculeFirstCashPrice($iSiteId, $iLangueId, $sLCDV6 )
    {
        /* Initialisation des variables */
        if (!is_null($iSiteId)){
            $iSiteId = (int)$iSiteId;
        }
        if (!is_null($iLangueId)){
            $iLangueId = (int)$iLangueId;
        }
        if (!is_null($sLCDV6)){
            $sLCDV6 = (string)$sLCDV6;
        }
        $mVehiculeFirstCashPrice = null;

        /* Appel au fichier de cache ramenant l'ensemble des finitions
         * des véhicules pour un site et une langue donné
         */
		 
		
        $aCacheTemp = self::getWSVehiculeFirstFinitionVersion ($iSiteId, $iLangueId, $sLCDV6, 'vehicule');

        if ( is_array($aCacheTemp) && isset($aCacheTemp['PRICE_DISPLAY']) ){
            $mVehiculeFirstCashPrice = $aCacheTemp['PRICE_DISPLAY'];
        }

        return $mVehiculeFirstCashPrice;
    }

    /**
     * Méthode statique permettant de remonter les informations de configuration
     * du site
     * @param int       $iSiteId        Identifiant de site
     * @param int       $iLangueId      Identifiant de langue
     * @return array                    Tableau contenant les informations
     *                                  de configuration du site
     */
    public static function getSiteConfiguration($iSiteId, $iLangueId)
    {
        /* Initialisation des variables */
        $iSiteId    = (int)$iSiteId;
        $iLangueId  = (int)$iLangueId;
        $aResult    = null;
         /* Informations à remonter de la Page globale */
        $aPageGlobal = \Pelican_Cache::fetch('Frontend/Page/Template', array(
                $iSiteId,
                $iLangueId,
                'CURRENT',
                \Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        if (is_array($aPageGlobal) & !empty($aPageGlobal) ){
            /* Configuration récupération de la devise */
            $aConfiguration = \Pelican_Cache::fetch('Frontend/Page/ZoneTemplate', array(
                    $aPageGlobal['PAGE_ID'],
                    \Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
                    $aPageGlobal['PAGE_VERSION'],
                    $iLangueId
            ));
        }

        $aLanguage = \Pelican_Cache::fetch('Language', array($iLangueId));
        $SITE_CODE_PAYS = \Pelican_Cache::fetch('SiteCodePays', array($iSiteId));
        
        if (is_array($aConfiguration) && !empty($aConfiguration)
                && is_array($aLanguage) && !empty($aLanguage)
                && !empty($SITE_CODE_PAYS)
                ){

            /* Construction du code Pays nécessaire à la localisation des données
             * pour les WebServices
             */
        /*    $sCodePays = '';
            $aCodePays = explode('-',$aLanguage['LANGUE_CODE']);
            if ( is_array($aCodePays) && !empty($aCodePays) ){
                $sCodePays = strtoupper($aCodePays[0]);
            }
            $site = \Pelican_Cache::fetch('Frontend/Site', array(
                    $iSiteId
            ));*/
        
            $sCodePays = $_SESSION[APP]['CODE_PAYS'];

            /* Construction du tableau de résultat */
            $aResult['DEVISE']          = \Pelican::$config['DEVISE'][trim($aConfiguration['ZONE_TITRE2'])];
            $aResult['COUNTRY_WS_CODE'] = $SITE_CODE_PAYS;
            $aResult['LANGUE_CODE']     = $aLanguage['LANGUE_CODE'];
            $aResult['LANGUE_CODE']     = $aLanguage['LANGUE_CODE'];
            $aResult['URL_CONFIGURATEUR'] = $aConfiguration['ZONE_TITRE5'];
            $aResult['URL_CONFIGURATEUR_MOBILE'] = $aConfiguration['ZONE_TITRE6'];
        }
        return $aResult;
    }

    /**
     * Méthode statique faisant appel au cache remontant le prix à crédit d'un
     * véhicule. Le fichier de cache fais lui même appel au WebService SimuFin
     * et à la WebMéthode saveCalculationDisplay. La gestion de ce WebService est
     * faite dans le fichie Citroen\Financement
     *
     * @param int       $iSiteId            Identifiant du site
     * @param int       $iLangueId          Identifiant de la langue
     * @param string    $sLCDV6             Code LCDV6 du véhicule
     * @param string    $sLabelVehicule     Nom du véhicule
     * @param string    $sGammeVehicule     Gamme du véhicule (VP/VU)
     * @param string    $sPrixHTVehicule    Prix HT du véhicule (si c'est un VU)
     * @param string    $sPrixTTCVehicule   Prix TTC du véhicule (si c'est un VP)
     * @param string    $sDescVehicule      Description du véhicule
     * @return array    Tableau des données renvoyées par la fichier de cache
     *                  et donc la WebMéthode saveCalculationDisplay
     */
    public static function getWSVehiculeCreditPrice($iSiteId, $iLangueId, $sLCDV6, $sLabelVehicule, $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule, $sDescVehicule = '')
    {
        /* Initialisation des variables */
        $iSiteId            = (int)$iSiteId;
        $iLangueId          = (int)$iLangueId;
        $sLCDV6             = (string)$sLCDV6;
        $sLabelVehicule     = (string)$sLabelVehicule;
        $sDescVehicule      = (string)$sDescVehicule;
        $sGammeVehicule     = (string)$sGammeVehicule;
        $sPrixHTVehicule    = (string)$sPrixHTVehicule;
        $sPrixTTCVehicule   = (string)$sPrixTTCVehicule;
        $aVehiculeCreditPrice = null;

        /* Récupération des informations de configuration */
        $aConf = self::getSiteConfiguration($iSiteId, $iLangueId);
		$sCodePays = empty($aConf['COUNTRY_WS_CODE'])?'fr':(strtoupper($aConf['COUNTRY_WS_CODE']) =='CT' ? 'fr' : strtolower($aConf['COUNTRY_WS_CODE']));

        /* Appel au fichier de cache de financement*/
        if ( is_array($aConf) && !empty($aConf) ){
            /* Appel à la méthode retravaillant les données de cache pour plus de
             * simplicité
             */
            $aCacheTemp['getCreditPriceML'] = \Citroen\Financement::getCreditPriceML(
                        strtoupper($sCodePays),
						$aConf['LANGUE_CODE']."-".strtolower($sCodePays),
                        $aConf['DEVISE'],
                        $sLCDV6,
                        $sLabelVehicule,
                        $sDescVehicule,
                        $sGammeVehicule,
                        $sPrixHTVehicule,
                        $sPrixTTCVehicule
                    );
            $aCacheTemp['getCreditPrice'] = \Citroen\Financement::getCreditPrice(
                        strtoupper($sCodePays),
						$aConf['LANGUE_CODE']."-".strtolower($sCodePays),
                        $aConf['DEVISE'],
                        $sLCDV6,
                        $sLabelVehicule,
                        $sDescVehicule,
                        $sGammeVehicule,
                        $sPrixHTVehicule,
                        $sPrixTTCVehicule
                    );

        }
		
		
		
        /* Formattage du tableau de sortie pour ne récupérer que les champs nécessaires
         * à l'affichage Front-Office
         */
        if ( is_array($aCacheTemp)
                && isset($aCacheTemp['getCreditPrice'])
                && isset($aCacheTemp['getCreditPriceML'])
                && !empty($aCacheTemp['getCreditPrice'])
                && !empty($aCacheTemp['getCreditPriceML'])
                ){
            $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_NEXT_RENT'] = $aCacheTemp['getCreditPrice']['PRIX'];
            $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'] = $aCacheTemp['getCreditPriceML']['HTML'];
            $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_FIRST_RENT'] = $aCacheTemp['getCreditPrice']['LEGAL_TEXT'];
            $aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'] = $aCacheTemp['getCreditPrice']['LEGAL_TEXT_LAGARDE'];
			$aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_ALL'] = $aCacheTemp['getCreditPrice'];
			$aVehiculeCreditPrice['VEHICULE_CREDIT_PRICE_ML_ALL'] = $aCacheTemp['getCreditPriceML'];
        }

        return $aVehiculeCreditPrice;
    }


    /**
     * Méthode statique retournant le LCDV6 pour une vehicule ID
     * @param int       $iVehiculeId    Identifiant du vehicule en BDD
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId    Identifiant de la langue
     * @return string   $sLCDV6   		Identifiant du vehicule dans le configurateur
     */
    public static function getLCDV6($iVehiculeId, $iSiteId, $iLangueId )
    {
        $aVehicule = \Pelican_Cache::fetch('Frontend/Citroen/VehiculeById',
                array($iVehiculeId, $iSiteId, $iLangueId));
        $sLCDV6 = '';
        if (!empty($aVehicule['VEHICULE_LCDV6_CONFIG'])){
			$sLCDV6 = $aVehicule['VEHICULE_LCDV6_CONFIG'];
        }elseif($aVehicule['VEHICULE_LCDV6_MANUAL']){
			$sLCDV6 = $aVehicule['VEHICULE_LCDV6_MANUAL'];
		}

        return $sLCDV6;
    }


	/**
     * Méthode statique retournant le LCDV6  et la gamme pour une vehicule ID
     * @param int       $iVehiculeId    Identifiant du vehicule en BDD
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId    Identifiant de la langue
     * @return array   $aCar   		tableau avec le lcvd6 et la gamme
     */
    public static function getLCDV6Gamme($iVehiculeId, $iSiteId, $iLangueId )
    {
        $aCar = array();
        $aVehicule = \Pelican_Cache::fetch(
                'Frontend/Citroen/VehiculeById',
                array($iVehiculeId, $iSiteId, $iLangueId)
                );
        
        if (!empty($aVehicule['VEHICULE_LCDV6_CONFIG'])){
			$aCar['LCDV6'] = $aVehicule['VEHICULE_LCDV6_CONFIG'];
			$aCar['GAMME'] = $aVehicule['VEHICULE_GAMME_CONFIG'];
        }elseif($aVehicule['VEHICULE_LCDV6_MANUAL']){
			$aCar['LCDV6'] = $aVehicule['VEHICULE_LCDV6_MANUAL'];
			$aCar['GAMME'] = $aVehicule['VEHICULE_GAMME_MANUAL'];
		}

        return $aCar;
    }

	 /**
     * Méthode statique retournant la liste des silhouettes disponibles par site et langue
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId    Identifiant de la langue
     * @return array   $aBodiesVehicule  Tableau de silhouettes
     */
    public static function getBodiesVehicule($iSiteId, $iLangueId,$cTypeGamme="")
    {
        return $aBodiesVehicule = \Pelican_Cache::fetch('Frontend/Citroen/BodiesVehicule',
                array($iSiteId, $iLangueId,$cTypeGamme));
    }

	 /**
     * Méthode statique retournant la liste des énergies disponibles par site et langue
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId    Identifiant de la langue
     * @return array   $aEnergiesVehicule  Tableau d'énergies
     */
    public static function getEnergiesVehicule($iSiteId, $iLangueId)
    {
         $aEnergiesVehicule = \Pelican_Cache::fetch('Frontend/Citroen/EnergiesVehicule',
                array($iSiteId, $iLangueId));
		if(is_array($aEnergiesVehicule) && count($aEnergiesVehicule)>0){
			foreach($aEnergiesVehicule as $key=>$result){
				$aEnergiesVehicule[$key]['ENERGY_CODE'] = $result['ENERGY_LABEL'];
			}
		}
		return $aEnergiesVehicule;
    }

	/**
     * Méthode statique retournant la liste des boites de vitesse disponibles par site et langue
     * @param int       $iSiteId    Identifiant du site
     * @param int       $iLangueId    Identifiant de la langue
     * @return array   $aTransmissionsVehicule  Tableau d'énergies
     */
    public static function getTransmissionsVehicule($iSiteId, $iLangueId,$cTypeGamme="")
    {
        return $aTransmissionsVehicule = \Pelican_Cache::fetch('Frontend/Citroen/TransmissionsVehicule',
                array($iSiteId, $iLangueId,$cTypeGamme));
    }

	 /**
     * Méthode retournant les équipements disponibles pour les finitions et traduit les libelles "Standard et Option"
	 * @param array $aLcvdGamme : array contenant le lcdv6 et la gamme
	 * @param int $iSiteId : Identifiant du site
	 * @param int $iLangueId : Identifiant de la langue
	 * @param string $sPageGammeVehicule : Page Gamme Vehicule
	 * @return array $aEquipements : tableau d'équipements
	 *
     */
    public static function getEquipementDispo($aLcvdGamme,$iSiteId, $iLangueId, $sPageGammeVehicule = "")
    {

        $aEquipements = \Pelican_Cache::fetch('Frontend/Citroen/Finitions/Equipements', array(
            $aLcvdGamme,
            $iSiteId,
            $iLangueId
        ));
        
        // traduction des libelles "Standard" et "Option"
        if(is_array($aEquipements) && !empty($aEquipements)){
            $aTemp = $aEquipements;
            
            foreach($aTemp as $n1=>$codeVehicule){
                foreach($codeVehicule as $n2=>$categ){
                    foreach($categ['EQUIPEMENTS'] as $n3=>$equipement){
                        if($equipement['DISPONIBILITY'] != 'None'){
                            $EQUIPEMENT_PICTO_DISPO = $sPageGammeVehicule==\Pelican::$config['VEHICULE_GAMME']['GAMME_LIGNE_DS']?\Pelican::$config['EQUIPEMENT_PICTO_DISPO_DS'][$equipement['DISPONIBILITY']]:\Pelican::$config['EQUIPEMENT_PICTO_DISPO'][$equipement['DISPONIBILITY']];
                            $aTemp[$n1][$n2]['EQUIPEMENTS'][$n3]['DISPONIBILITY'] = \Pelican::$config["IMAGE_FRONT_HTTP"]
                                                                                . "/" . 
                                                                                $EQUIPEMENT_PICTO_DISPO;
                        }                                          
                    }
                }
            }
            $aEquipements = $aTemp;
        }        
        return $aEquipements;
    }

	/**
     * Méthode retournant les caractéristiques moteurs pour les finitions
	 * @param string $sFinitionCode : Code de la finition
	 * @param string $sLcdv6 : Code du modèle
	 * @param int $iSiteId : Identifiant du site
	 * @param int $iLangueId : Identifiant de la langue
	 * @return array $aEngineList : Liste de moteurs
	 *
     */
    public static function getEngineList($sFinitionCode,$sLcdv6,$iSiteId, $iLangueId)
    {
	   $aEngineList = \Pelican_Cache::fetch('Frontend/Citroen/Finitions/EngineList', array(
						   $sFinitionCode,
						   $sLcdv6,
						   $iSiteId,
						   $iLangueId
                   ));

        return $aEngineList;
    }

	/**
     * Méthode retournant les caractéristiques techniques pour les finitions
	 * @param string $sEngineCode : Code moteur
	 * @param string $sLcdv6 : Lcdv6
	 * @param string $sGamme : Gamme
	 * @param int $iSiteId : Identifiant du site
	 * @param int $iLangueId : Identifiant de la langue
	 * @return array $aCaracteristiques : tableau de caracteristiques
	 *
     */
    public static function getCaracteristiques($sEngineCode,$sLcdv6,$sGamme,$iSiteId, $iLangueId,$sFinitionCode='')
    {
	   $aCaracteristiques = \Pelican_Cache::fetch('Frontend/Citroen/Finitions/Caracteristiques', array(
						   $sEngineCode,
						   $sLcdv6,
						   $sGamme,
						   $iSiteId,
						   $iLangueId,
						   $sFinitionCode
                   ));

        return $aCaracteristiques;
    }

	/**
     * Méthode statique retournant un vehicule en fonction de son lcdv6 et de sa gamme
     * @param string       $sLcdv6    LCDV6
     * @param string       $sGamme    GAMME
     * @param int       $iSiteId    SITEID
     * @param int       $iLangueId    LANGUEID
     * @return array   $aVehicule 	Tableau du véhicule
     */
    public static function getVehiculeByLCDVGamme($sLcdv6, $sGamme=null, $iSiteId, $iLangueId,$iVehiculeId=null )
    {
        $aVehicule = \Pelican_Cache::fetch('Frontend/Citroen/VehiculeByLCDVGamme',
            array($sLcdv6, $sGamme, $iSiteId, $iLangueId,$iVehiculeId));

        return $aVehicule;
    }


   


	/**
     * Méthode retournant les categories des caractéristiques moteurs
	 * @param string $sLcdv6 : Code de la finition
	 * @param string $sGamme : Code de la gamme
	 * @param int $iSiteId : Identifiant du site
	 * @param int $iLangueId : Identifiant de la langue
	 * @return array $aCategory : Liste de moteurs
	 *
     */
    public static function getCategoryCaracteristiques($sGamme, $iSiteId, $iLangueId)
    {
	   $aCategory = \Pelican_Cache::fetch('Frontend/Citroen/Finitions/CategoryCaracteristiques', array(
						   $sGamme,
						   $iSiteId,
						   $iLangueId
                   ));

        return $aCategory;
    }

}
