<?php

namespace Citroen\Perso\Flag;

use Citroen\SelectionVehicule;
use Citroen\Perso\Score\ScoreManager;
use Citroen\Perso\Score\IndicateurManager;

require_once 'External/Cpw/GRCOnline/Abstract.php';
require_once 'External/Cpw/GRCOnline/Customer.php';
require_once 'External/Cpw/GRCOnline/Customermanager.php';
require_once 'External/Cpw/GRCOnline/Customerxmlloader.php';
require_once 'External/Cpw/GRCOnline/Customerfields.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/User.php';

define('PUBLIC_PATH', \Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/User");

/**
 * Classe Detail
 *
 * Cette classe liste les méthodes calculant les indicateurs
 *
 * @package Flag/Detail
 * @author Khadidja MESSAOUDI <khadidja.messaoudi@businessdecision.com>
 */
class Detail {
    /*     * ** INDICATEUR TRANCHE SCORE *** */

    public static $trancheScore = null;

    /*     * ** Mémorise le score réel pour chaque produit, pour alimentation dataLayer GTM *** */
    public static $trancheTrueScore = null;

    /*     * ** INDICATEUR PRODUIT LE MIEUX SCORE *** */
    public static $productBestScore = null;

    /*     * ** INDICATEUR PRODUIT LE PLUS RECENT *** */
    public static $recentProduct = null;

    /*     * ** INDICATEUR CLIENT *** */
    public static $client = null;

    /*     * ** INDICATEUR PRODUIT POSSEDE *** */
    public static $productOwned = null;

    /*     * ** INDICATEUR PRO *** */
    public static $pro = null;

    /*     * ** INDICATEUR CLIENT RECENT *** */
    public static $recentClient = null;

    /*     * ** INDICATEUR EMAIL *** */
    public static $email = null;

    /*     * ** INDICATEUR DATA D'ACHAT *** */
    public static $datePurchase = null;

    /*     * ** INDICATEUR EXTENSION GARANTIE *** */
    public static $extendedWarranty = null;

    /*     * ** INDICATEUR CONTRAT SERVICE *** */
    public static $serviceContract = null;

    /*     * ** INDICATEUR PRODUIT COURANT *** */
    public static $currentProduct = null;

    /*     * ** INDICATEUR PRODUIT PREFERE *** */
    public static $preferredProduct = null;

    /*     * ** INDICATEUR MON PROJET OUVERT *** */
    public static $projectOpen = 'Non';

    /*     * ** Indicateur interne indiquant l'origine de la valeur de l'indicateur pro (BDI, clic languette pro/particulier...) *** */
    public static $__proSource = null;

    /** Cache de configuration de site */
    public static $__siteConfigCache = null;

    /** Pid de la page courante (change à chaque requête, non stocké dans MongoDB) */
    public static $__currentPid = null;

    /** Table de consultation des pages */
    public static $__consultations = null;

    /** Indicateur reconsultation */
    public static $reconsultation = null;

    /*     * ** INFOS DE LA BDI *** */
    public static $isCustomerBdi = null;
    public static $lastBoughtBdi = null;
    public static $productOwnedBdi = null;
    public static $datePurchaseBdi = null;
    public static $isSetFlag = false;
    public static $idBatch = null;

    /*     * ******************* Tests Tranche Score  ******************** */

    public function init() {
        if (self::$isSetFlag == false || self::$idBatch != null) {
            self::$isSetFlag = true;
            try {
                $indicateur = new IndicateurManager();
            } catch (\MongoConnectionException $ex) {
                return;
            }
            $user = \Citroen\UserProvider::getUser();
            $userId = ($user) ? $user->getId() : self::$idBatch;
            $indicateurs = $indicateur->getAllByUser($_SESSION[APP]['perso_sess'], $userId);

            if ($indicateurs) {
                foreach ($indicateurs as $indic) {
                    self::$trancheScore = $indic['tranche_score'];
                    self::$productBestScore = $indic['product_best_score'];
                    self::$recentProduct = $indic['recent_product'];
                    self::$client = $indic['client'];
                    self::$productOwned = $indic['product_owned'];
                    self::$pro = $indic['pro'];
                    self::$recentClient = $indic['recent_client'];
                    self::$email = $indic['email'];
                    self::$datePurchase = $indic['date_purchase'];
                    self::$extendedWarranty = $indic['extended_warranty'];
                    self::$serviceContract = $indic['service_contract'];
                    self::$currentProduct = $indic['current_product'];
                    self::$preferredProduct = $indic['preferred_product'];
                    self::$projectOpen = $indic['project_open'];
                    self::$isCustomerBdi = $indic['IS_CUSTOMER'];
                    self::$lastBoughtBdi = $indic['LAST_BOUGHT'];
                    self::$productOwnedBdi = $indic['PRODUCT_OWNED'];
                    self::$datePurchaseBdi = $indic['DATE_PURCHASE'];
                    self::$__proSource = $indic['__pro_source'];
                    self::$__consultations = is_array($indic['__consultations']) ? $indic['__consultations'] : array();
                }
            }
            
            // Mise à jour du compteur de consultation des pages
            self::updateConsultation();
        }
    }

    public function trancheScoreCalcul() {
        try {
            $score = new ScoreManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $user = \Citroen\UserProvider::getUser();
        $userId = ($user) ? $user->getId() : self::$idBatch;

        $products = $score->getAllProductsByUser($_SESSION[APP]['perso_sess'], $userId);



        if ($products != null) {


            foreach ($products as $mc => $product) {
                if ($product['product'] != null && !empty($product['product'])) {

                    if ($product['score'] == 0) {
                        self::$trancheScore[$product['product']] = 0;
                    } else if ($product['score'] < 0.3) {
                        self::$trancheScore[$product['product']] = 1;
                    } else if ($product['score'] < 0.6) {
                        self::$trancheScore[$product['product']] = 2;
                    } else if ($product['score'] < 0.9) {
                        self::$trancheScore[$product['product']] = 3;
                    } else {
                        self::$trancheScore[$product['product']] = 4;
                    }
                    self::$trancheTrueScore[$product['product']] = $product['score'];
                }
                //self::$trancheTrueScore[$product['product']] = $product['score'];
            }
        }
    }

    /*     * ******************* Tests Produit le mieux Scoré  ******************** */

    public function bestScore() {
        try {
            $score = new ScoreManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $user = \Citroen\UserProvider::getUser();
        $userId = ($user) ? $user->getId() : self::$idBatch;
        $product = $score->getProductWithMaxScoreByUser($_SESSION[APP]['perso_sess'], $userId);
        if ($product != null) {
            self::$productBestScore = $product['product'];
        }
    }

    /*     * ******************* Tests Produit le plus récent  ******************** */

    public function getRecentProduct() {
        try {
            $score = new ScoreManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $user = \Citroen\UserProvider::getUser();
        $userId = ($user) ? $user->getId() : self::$idBatch;
        $product = $score->getMostRecentProductByUser($_SESSION[APP]['perso_sess'], $userId);
        if ($product != null) {
            self::$recentProduct = $product['product'];
        }
    }

    /*     * ******************* Tests Client ******************** */
    /*
     * Teste si l'utilisateur a répondu 'oui' à la question etes vous client dans les formulaires
     */

    public function isClient() {
        if ($_POST['isClient'] == '1') {
            self::$client = 'Oui';
        }
    }

    /*
     * Teste si l'utilisateur a répondu 'non' à la question etes vous client dans les formulaires
     */

    public function isNotClient() {
        if ($_POST['isClient'] == '0') {
            self::$client = 'Non';
        }
    }

    /*     * ******************* Tests Client BDI ******************** */
    /*
     * Teste si l'utilisateur est reconnu comme étant client dans la BDI
     */

    public function isClientBdi() {
        $user = \Citroen\UserProvider::getUser();
        if (($user && $user->getEmail()) || self::$idBatch != "") {
            if (self::$isCustomerBdi == true) {
                self::$client = 'Oui';
            }
        }
    }

    /*
     * Teste si l'utilisateur n'est pas reconnu comme étant client dans la BDI
     */

    public function isNotClientBdi() {
        $user = \Citroen\UserProvider::getUser();
        if ($user && $user->getEmail() || self::$idBatch != "") {
            if (self::$isCustomerBdi == false) {
                self::$client = 'Non';
            }
        }
    }

    /*     * ******************* Tests Produit possédé Bdi ******************** */
    /*
     * Récupère le dernier produit possédé par le client
     */

    public function productOwned() {
        $user = \Citroen\UserProvider::getUser();
        if ($user && $user->getEmail() || self::$idBatch != "") {
            if (self::$isCustomerBdi == true) {
                self::$productOwned = self::$productOwnedBdi;
            }
        }
    }

    /*     * ******************* Tests Pro ******************** */
    /*
     * Teste si l'utilisateur a répondu 'pro' dans les formulaires languette
     */

    public function isPro() {
        if ($_POST['isPro'] == '1') {
            self::setIndicPro('Oui', 'formulaire languette');
        }
    }

    /*
     * Teste si l'utilisateur a répondu 'pro' dans les formulaires
     */

    public function isProForm() {
        if ($_GET['typeClient'] == 'PRO' || $_GET['values']['typeClient'] == 'PRO') {
            self::setIndicPro('Oui', 'formulaire');
        }
    }

    /*
     * Teste si l'utilisateur est client et possède un véhicule pro
     */

    public function isClientAndHasVU() {
        $aOwnedVehciule = \Pelican_Cache::fetch(
                        "Frontend/Citroen/Perso/VehiculeByLcdv", array(
                    $_SESSION[APP]['SITE_ID'],
                    self::$productOwned
                        )
        );
        if (self::$client == 'Oui' && !empty($aOwnedVehciule)) {

            if (!empty($aOwnedVehciule['VEHICULE_GAMME_MANUAL']) && $aOwnedVehciule['VEHICULE_GAMME_MANUAL'] == 'VU') {
                self::setIndicPro('Oui', 'bdi');
            } elseif (
                    empty($aOwnedVehciule['VEHICULE_GAMME_MANUAL']) &&
                    !empty($aOwnedVehciule['VEHICULE_GAMME_CONFIG']) &&
                    $aOwnedVehciule['VEHICULE_GAMME_CONFIG'] == 'VU'
            ) {
                self::setIndicPro('Oui', 'bdi');
            }
        }
    }

    /*
     * Teste si l'utilisateur est arrivé sur le site via une search de type PRO
     */

    public function isProFromSearch() {
        if (isset($_GET['search']) && !empty($_GET['search'])) {          
            $aQueryWords = array_filter(
                    explode(
                            ' ',
                            $_GET['search']
                            )
                    ); 
            $aTerms = \Pelican_Cache::fetch(
                    "Frontend/Citroen/Perso/SearchTermPro",
                    array(
                        $_SESSION[APP]['SITE_ID']
                    )
                );
            $iQueryWordCursor=0;
            
            while($iQueryWordCursor<count($aQueryWords)&&!$found){
                $iTermsProCursor=0;
                while($iTermsProCursor<count($aTerms)&&!$found){
                    if($aQueryWords[$iQueryWordCursor]===$aTerms[$iTermsProCursor]['PRODUCT_TERM_LABEL']){
                        $found=true;
                    }else{
                       $iTermsProCursor++; 
                    }
                }
                $iQueryWordCursor++;
            }
        }
        if($found){
            self::setIndicPro('Oui', 'search');
        }
    }

    /*
     * Teste si l'utilisateur n'a pas répondu 'pro' dans les formulaires
     */

    public function isNotPro() {
        if ($_POST['isPro'] == '0') {
            self::setIndicPro('Non', 'formulaire languette');
        }
    }

    /*
     * Teste si l'utilisateur a répondu 'pro' dans les formulaires
     */

    public function isNotProForm() {
        if ($_GET['typeClient'] == 'IND' || $_GET['values']['typeClient'] == 'IND') {
            self::setIndicPro('Non', 'formulaire');
        }
    }

    /*     * ******************* Tests Client récent ******************** */
    /*
     * Indique que l'utilisateur n'est pas reconnu comme un client récent (test de navigation)
     */

    public function isRecentClient() {
        if (self::$client == 'Non') {
            self::$recentClient = 'Non';
        }
    }

    /*     * ******************* Tests Client récent Bdi ******************** */
    /*
     * Teste si l'utilisateur est reconnu comme un client récent dans la BDI
     */

    public function isRecentClientBdi() {
        $user = \Citroen\UserProvider::getUser();

        if (($user && $user->getEmail()) || self::$idBatch != null) {
            try {
                $score = new ScoreManager();
            } catch (\MongoConnectionException $ex) {
                return;
            }
            $userId = ($user) ? $user->getId() : self::$idBatch;
            $productsSite = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
            $products = $score->getAllProductsByUser($_SESSION[APP]['perso_sess'], $userId);
            $recent = true;
            if ($products != null) {
                foreach ($products as $product) {
                    if (self::$productOwned != $productsSite[$product['product']] && $product['score'] >= 0.4) {
                        $recent = false;
                    }
                }
            }
            if (self::$isCustomerBdi == true && strtotime(self::$lastBoughtBdi) > strtotime('-4 year', time()) && $recent == true) {
                self::$recentClient = 'Oui';
            }
        }
    }

    /*
     * Teste si l'utilisateur n'est pas reconnu comme un client récent dans la BDI
     */

    public function isNotRecentClientBdi() {
        $user = \Citroen\UserProvider::getUser();
        if (($user && $user->getEmail()) || self::$idBatch != null) {
            try {
                $score = new ScoreManager();
            } catch (\MongoConnectionException $ex) {
                return;
            }
            $userId = ($user) ? $user->getId() : self::$idBatch;
            $productsSite = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
            $products = $score->getAllProductsByUser($_SESSION[APP]['perso_sess'], $userId);
            $recent = true;
            if ($products != null) {
                foreach ($products as $product) {
                    if (self::$productOwned != $productsSite[$product['product']] && $product['score'] >= 0.4) {
                        $recent = false;
                    }
                }
            }
            if (self::$isCustomerBdi != true || strtotime(self::$lastBoughtBdi) < strtotime('-4 year', time()) || $recent == false) {
                self::$recentClient = 'Non';
            }
        }
    }

    /*     * ******************* Tests Email ******************** */
    /*
     * Teste si l'utilisateur a renseigné son email pour s'inscrire à la newsletter
     */

    public function signInNewsletter() {
        if (!empty($_POST['email'])) {
            self::$email = $_POST['email'];
        }
    }

    /*
     * Teste si l'utilisateur s'inscrit à Mon Projet
     */

    public function signInMyProject() {
        $user = \Citroen\UserProvider::getUser();
        if ($user && $user->getEmail()) {
            self::$email = $user->getEmail();
        }
    }

    /*
     * Teste si l'utilisateur s'inscrit à Mon Projet
     */

    public function emailForm() {
        if (!empty($_POST['params']['email'])) {
            self::$email = $_POST['params']['email'];
        }
    }

    /*     * ******************* Tests Date d'Achat Bdi ******************** */
    /*
     * Récupère la date d'achat du dernier produit possédé par le client
     */

    public function datePurchase() {
        $user = \Citroen\UserProvider::getUser();
        if ($user && $user->getEmail()) {
            if (self::$isCustomerBdi = true) {
                self::$datePurchase = self::$datePurchaseBdi;
            }
        }
    }

    /*     * ******************* Tests Extension de garantie Bdi TODO ******************** */

    /*     * ******************* Tests Contrat de service Bdi TODO ******************** */

    /*     * ******************* Tests Produit courant ******************** */

    public function existCurrentProduct() {
        $aPages = \Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductPage", array($_SESSION[APP]['SITE_ID']));
        $aProductsLcvd = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
        $pageCourante = explode('?', str_replace('//', '/', $_SERVER['REQUEST_URI']));
        parse_str($pageCourante[1], $aQueryParams);
        $aQueryParamsNames = array_keys($aQueryParams);
        if (in_array('Car', $aQueryParamsNames) || in_array('lcdv', $aQueryParamsNames)) {
            if (count($aPages) > 1) {
                foreach ($aPages as $aOnePage) {
                    if(
                            $aOnePage['PRODUCT_PAGE_URL']==$pageCourante[0]&&(
                                $aProductsLcvd[$aOnePage['PRODUCT_ID']] == $aQueryParams['lcdv']||
                                $aProductsLcvd[$aOnePage['PRODUCT_ID']] == $aQueryParams['Car']
                                )
                            ){
                        self::$currentProduct = $aOnePage['PRODUCT_ID'];
                        return;
                    }

                }
            }
        }
        if (empty($aPages)) {
            $aPages['URL'] = array();
        }
        
        $aPagesCount = array_count_values($aPages['URL']);
        if (in_array($pageCourante[0], $aPages['URL']) && $aPagesCount[$pageCourante[0]] == 1) {
            self::$currentProduct = $aPages['URL_PRODUCT_ID'][$pageCourante[0]];
        } elseif(count($aPages) > 1){
            self::$currentProduct = null;
        }
    }

    public function myProjectCurrentProduct() {
        /*
          $currentPage = \Pelican_Cache::fetch("Frontend/Page",array($_GET['pid'],$_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
          if($currentPage['TEMPLATE_PAGE_ID'] == \Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']){
          $user = \Citroen\UserProvider::getUser();
          $userId = ($user) ? $user->getId() : self::$idBatch;
          $userSelection = SelectionVehicule::getUserSelection($userId);
          if(is_array($userSelection) && count($userSelection)>0){
          $products = \Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct",array($_SESSION[APP]['SITE_ID']));
          if(is_array($products) && count($products)>0){
          foreach($products as $key=>$product){
          if($product == $userSelection[0]['lcdv6_code']){
          self::$currentProduct = $key;
          }
          }
          }
          }
          } */
    }

    public function notExistCurrentProduct() {
        $pages = \Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductPage", array($_SESSION[APP]['SITE_ID']));
        $pageCouranteElements = explode('?', $_SERVER['REQUEST_URI']);
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax && strpos($_SERVER['REQUEST_URI'], 'route=')) {
            $aUriRaw = explode('&', substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'route=') + strlen('route=')));
            $sUri = $aUriRaw[0];
        } else {
            $sUri = $pageCouranteElements[0];
        }
        if (empty($pages)) {
            $pages['URL'] = array();
        }
        if (!in_array($sUri, $pages['URL']) && !$isAjax) {
            self::$currentProduct = null;
        }
    }

    /*     * ******************* Tests Produit préféré ******************** */

    public function preferedProduct() {
        //CPW-2487 : delai du calcul de l'indicateur
        $aSite = \Pelican_Cache::fetch("Frontend/Site", array(
                  $_SESSION[APP]['SITE_ID']
            ));
        //si la durée n'est pas set en BO, on reprend la valeur d'origine
        if(!isset($aSite['SITE_PERSO_DURATION_PRODUIT_PREFERE']))
            $aSite['SITE_PERSO_DURATION_PRODUIT_PREFERE'] = 7;

        try {
            $score = new ScoreManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $user = \Citroen\UserProvider::getUser();
        $userId = ($user) ? $user->getId() : self::$idBatch;
        $productBestScore = $score->getProductWithMaxScoreByUser($_SESSION[APP]['perso_sess'], $userId);
        $recentProduct = $score->getMostRecentProductByUser($_SESSION[APP]['perso_sess'], $userId);
        if (self::$currentProduct != null) {
            self::$preferredProduct = self::$currentProduct;
        } else if (self::$recentClient == 'Oui') {
            if (self::$productOwned == 'autre') {
                self::$preferredProduct = null;
            } else {
                self::$preferredProduct = self::$productOwned;
            }
        } else if (self::$productBestScore == null) {
            self::$preferredProduct = null;
        } elseif ($productBestScore['time'] > $recentProduct['time'] -  86400 * $aSite['SITE_PERSO_DURATION_PRODUIT_PREFERE']) {
            self::$preferredProduct = self::$productBestScore;
        } else {
            self::$preferredProduct = self::$recentProduct;
        }
    }

    /*     * ******************* Tests Mon projet ouvert ******************** */
    /*
     * Teste si l'utilisateur a ouvert mon projet
     */

    public function isProjectOpen() {
        $user = \Citroen\UserProvider::getUser();
        $userId = ($user) ? $user->getId() : self::$idBatch;
        $userSelection = SelectionVehicule::getUserSelection($userId);
        if (is_array($userSelection) && count($userSelection) > 0) {
            self::$projectOpen = 'Oui';
        }
    }
    
    /**
     * Calcule l'indicateur reconsultation, en fonction de la page courante et de la table de consultations
     */
    public function reconsultation()
    {
        $pid = isset(self::$__currentPid) ? self::$__currentPid : null;
        if (empty($pid)) {
            self::$reconsultation = false;
            return;
        }
        if (!isset(self::$__consultations[$pid])) {
            self::$reconsultation = false;
            return;
        }
        
        // Lecture configuration reconsultation
        $siteConfig = self::getSiteConfig($_SESSION[APP]['SITE_ID']);
        $reconPalier = isset($siteConfig['SITE_PERSO_RECONSULTATION_PALIER']) ? intval($siteConfig['SITE_PERSO_RECONSULTATION_PALIER']) : 3;
        
        // Vérification du palier
        self::$reconsultation = (self::$__consultations[$pid]['cpt'] >= $reconPalier) ? true : false;
    }

    public function saveIndicateur() {
        $aIndicateur = array(
            'tranche_score' => self::$trancheScore,
            'tranche_true_score' => self::$trancheTrueScore,
            'product_best_score' => self::$productBestScore, //score
            'recent_product' => self::$recentProduct,
            'client' => self::$client,
            'product_owned' => self::$productOwned,
            'pro' => self::$pro,
            'recent_client' => self::$recentClient,
            'email' => self::$email,
            'date_purchase' => self::$datePurchase,
            'extended_warranty' => self::$extendedWarranty,
            'service_contract' => self::$serviceContract,
            'current_product' => self::$currentProduct,
            'preferred_product' => self::$preferredProduct,
            'project_open' => self::$projectOpen,
            '__pro_source' => self::$__proSource
        );

        try {
            $indicateur = new IndicateurManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $indicateur->saveIndicateur(self::$idBatch, null, $aIndicateur);
    }

    /**
     * Fonction permettant de changer la valeur de l'indicateur pro seulement dans certaines conditions,
     * c'est à dire uniquement si la valeur courante de l'indicateur pro ne provient pas d'un formulaire,
     * car la réponse d'un internaute à un formulaire prime sur les données de la BDI
     * cf. https://jira-projets.mpsa.com/SCDV/browse/CPW-3069
     * Retourne true si l'indicateur pro a été modifié, false sinon.
     *
     * @param $value (String) Valeur de l'indicateur pro
     * @param $source (String) Origine de la $value (bdi, search, formulaire...)
     */
    private static function setIndicPro($value, $source) {
        $autorisation = true;
        if (preg_match('/formulaire/i', $source)) {
            $autorisation = true;  // Les formulaires sont des sources prioritaires
        } elseif (isset(self::$__proSource) && preg_match('/formulaire/i', self::$__proSource)) {
            $autorisation = false; // Si la valeur courante de l'indicateur pro a été définie par un formulaire, on ne met pas à jour, la valeur apportée par formulaire est prioritaire
        }

        if (!$autorisation) {
            return false;
        }
        self::$pro = $value;
        self::$__proSource = $source;
        return true;
    }
    
    /**
     * Mise à jour de la table de consultation des pages dans les indicateurs :
     *  - supprime les consultations des pages ayant dépassé le délai
     *  - incrémente le compteur de la page courante
     */
    private static function updateConsultation()
    {
        $pid = isset(self::$__currentPid) ? self::$__currentPid : null;
        if (empty($pid)) {
            return;
        }
        
        // Initialisation table de consultation
        if (!is_array(self::$__consultations)) {
            self::$__consultations = array();
        }
        
        // Lecture configuration reconsultation
        $siteConfig = self::getSiteConfig($_SESSION[APP]['SITE_ID']);
        $reconDelai = isset($siteConfig['SITE_PERSO_RECONSULTATION_DELAI']) ? $siteConfig['SITE_PERSO_RECONSULTATION_DELAI'] : 3;
		
        // Nettoyage de la table de consultation du visiteur
		$now = date("Y-m-d H:i:s");
        self::$__consultations = array_filter(self::$__consultations, function($val) use ($now, $reconDelai) {
			if(is_int($val['lastView'])){
				return false;
			}
			$date = new \DateTime($val['lastView']);
			$date->modify("+{$reconDelai} day");
			$lastView = $date->format( 'Y-m-d H:i:s' );
			if ($lastView < $now) {
                return false;
            }
            return true;
        });
        // Mise à jour de la table de consultation
        $cpt = isset(self::$__consultations[$pid]['cpt']) ? self::$__consultations[$pid]['cpt'] : 0;
        self::$__consultations[$pid] = array(
            'cpt' => $cpt + 1,
            'lastView' => date("Y-m-d H:i:s")
        );
    }
    
    /**
     * Retourne la configuration d'un site avec mise en cache dans l'objet
     *
     * @param int $siteId Identifiant du site
     */
    private static function getSiteConfig($siteId)
    {
        if (isset(self::$__siteConfigCache[$siteId])) {
            return self::$__siteConfigCache[$siteId];
        }
        self::$__siteConfigCache[$siteId] = \Pelican_Cache::fetch("Frontend/Site", array($siteId));
        return self::$__siteConfigCache[$siteId];
    }

}
