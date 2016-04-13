<?php

/**
 *
 */
require_once 'Zend/Rest/Client.php';

/**
 *
 */
require_once 'Zend/Json.php';

/**
 *
 */
class Pelican_Webservice_Proxy extends Zend_Rest_Client
{

    /**
     *
     * Enter description here ...
     * @var unknown_type
     */
    protected $_response;

    /**
     *
     * Enter description here ...
     * @var unknown_type
     */
    protected $_url;

    /**
     *
     * Enter description here ...
     * @var unknown_type
     */
    protected $_params;

    protected $_infos;

    protected $logger;

    protected $_identifiedUrl;
    protected $_identifiedDomain;

    /**
     * Effectue l'appel au webservice demandé
     * Selon l'identification de l'appel, les données peuvent être récupérées à distance, via le cache ou un fichier bouchon
     * @param string $url       Url du webservice à appeler
     * @param mixed  $params    Paramètres additionnels pour l'appel du service
     * @param bool   $avoidMock Eviter les fichiers bouchons, quelle que soit la configuration
     */
    public function getService($url, $params=array(), $avoidMock=false)
    {
        // Démarrage du profiler
        Pelican_Profiler::start($url, 'webservices');

        $this->_init($url, $params);

        // Lecture des paramétrages liés à $url
        $this->_getInfosFromConfig();

        // Si nécessaire, évite les fichiers bouchons quelle que soit la règle fixée en configuration
        if ($avoidMock) {
            $this->_infos['access_policy'] = 'direct';
        }

        // Maintenant que des infos sont disponibles pour cet appel, on renomme la variable du profiler
        Pelican_Profiler::rename($url, $this->_getProfilerObjectName(), 'webservices');

        // Application de la stratégie de récupération
        switch ($this->_infos['access_policy']) {
            case 'mock':
                {
                    // appel au bouchon s'il est défini pour cette url
                    $sResponse = $this->_readMockFile($this->_infos['mock_path'].$this->_getMockFilename());
                    $this->_response = $sResponse;

                    // traitement et désérialiation des données
                    $this->_prepareData();
                    break;
                }
            /*case 'php':
                {
                    // appel au cache et filtrage ou tri en PHP
                    $this->_response = Pelican_Cache::fetch('Infinity_Webservice_Cache', array(
                        $url ,
                        $params));
                    $this->_filterResponse();
                    break;
                }*/
            default:
            case 'direct':
            {

                   // Appel direct non mis en cache
                   if ($this->_infos['storage_policy']=='never') {

                       $this->_response = $this->_call($url, $params);

                    // traitement et désérialiation des données
                    $this->_prepareData();

                   // Mise en cache
                   } else {

                       $aParams = array($url, $params);

                       // Calcul de la plage de temps si il y a un temps d'expiration
                       if ($this->_infos['expiry_policy']!='never' && is_numeric($this->_infos['expiry_policy']) && $this->_infos['expiry_policy']>0) {
                           $nbSeconds = date('G')*3600 + date('i')*60 + date('s');
                           $aParams[] = (int) (($nbSeconds-($nbSeconds%$this->_infos['expiry_policy']))/$this->_infos['expiry_policy']);
                       }

                    // Définition de l'adaptateur de stockage si il y en a un de défini
                       if (isset($this->_infos['storage_policy']) && $this->_infos['storage_policy']!='store') {
                           $aParams[] = $this->_infos['storage_policy'];
                       }

                       $this->_response = Pelican_Cache::fetch("Webservice/Result", $aParams);

                   }

                   // Dernière tentative si pas de données : récupération du bouchon
                   /*if (!$this->_response) {
                       $sResponse = $this->_readMockFile($this->_infos['mock_path'].$this->_getMockFilename());
                    $this->_response = $sResponse;
                   }*/

                break;
            }
            /*default:
                {
                    // appel à un fichier de cache générique qui met en cache le résultat de la méthode statique call
                    // stockage en FileSystem/Memcache/Session (à faire)
                    $this->_response = Pelican_Cache::fetch('Infinity_Webservice_Cache', array(
                        $url ,
                        $params));
                }*/
        }

        Pelican_Profiler::stop($this->_getProfilerObjectName(), 'webservices');

        return $this->_response;
    }


    /**
     * Constructeur de la classe. Permet d'y adjoindre directement un logger de type Zend_Log
     * @param Zend_Log $logger
     */
    public function __construct($logger=false)
    {
        if ($logger) {
            $this->setLogger($logger);
        }
        $this->log('New instance of Webservice proxy ', Zend_Log::INFO);
    }

    /**
     * Effectue un appel direct au webservice, sans passer par le cache ou les fichiers bouchons
     * @param string $url    Url du webservice à appeler
     * @param mixed  $params Paramètres additionnels pour l'appel du service
     */
    public function makeDirectCall($url, $params)
    {
        $this->_init($url, $params);
        $this->_getInfosFromConfig();
        debug($this->_url);
        $sResponse = $this->_call($this->_url, $params);
        $aData = $this->_prepareData($sResponse);
        //debug($aData);
        return $aData;
    }


    /**
     * Méthode privée, effectue réellement l'appel au webservice via CURL
     * @param string $url    Url du webservice à appeler
     * @param mixed  $params Paramètres additionnels pour l'appel du service
     */
    protected function _call($url, $params)
    {
        $sResponse = '';
        //var_dump('Calling webservice');
        $this->log(' - Calling webservice...', Zend_Log::INFO);

        // Initialisation de la récupération
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, Pelican::$config['SERVICES']['fetch_timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, Pelican::$config['SERVICES']['connection_timeout']);

        // Si besoin, on passe par le proxy spécifié dans la configuration
        if ( isset(Pelican::$config['SERVICES']['proxy_host']) && isset(Pelican::$config['SERVICES']['proxy_port']) ) {
            $this->log('   - Using proxy : '.Pelican::$config['SERVICES']['proxy_host'], Zend_Log::INFO);
            curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['SERVICES']['proxy_host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, Pelican::$config['SERVICES']['proxy_port']);

            if ( isset(Pelican::$config['SERVICES']['proxy_user']) && isset(Pelican::$config['SERVICES']['proxy_pass']) ) {
                curl_setopt ($ch, CURLOPT_PROXYUSERPWD, Pelican::$config['SERVICES']['proxy_user'].':'.Pelican::$config['SERVICES']['proxy_pass']);
            }

        }

        $sResponse = curl_exec ($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Vérification du résultat : cas OK
        if ($sResponse && $status<400 && $sResponse!='' && !preg_match('/Session Limits Exceeded/', $sResponse)) {
            curl_close ($ch);
            //var_dump('valid');
            $this->log('   - Some VALID data have been retrieved', Zend_Log::INFO);

            return $sResponse;

        // Cas Données vides
        } elseif ($sResponse=='') {
            curl_close($ch);
            //var_dump('Empty');
            $this->log('   - FAILED to get data : EMPTY DATA ', Zend_Log::ERR);

            return false;

        // Cas Session Limits Exceeded
        } elseif ( preg_match('/Session Limits Exceeded/', $sResponse)) {
            curl_close($ch);
            debug("Warning : Session limits exceeded for webservices !");
            //var_dump('Session Limits Exceeded');
            $this->log('   - FAILED to get data : EMPTY DATA ', Zend_Log::ERR);

            return false;

        // Cas Erreur
        } else {
            curl_close($ch);
            //var_dump('error code');
            $this->log('   - FAILED to get data : CODE '.$status, Zend_Log::ERR);

            return false;
        }
    }

    /**
     * potentiellement : modification centralisée des données issues d'ATG (format de date, libellés en anglais etc...)
     * => pour optimiser : travail par regexp sur les données sérialisées json avant json_decode pour éciter un parcours de tableaux
     */
    protected function _prepareData($aData = false)
    {

        if ($aData!==false) {
            return json_decode(utf8_encode($aData), true);
            //Zend_Json::decode($aData);
        } elseif ($this->_response!=false) {
            $this->_response = json_decode(utf8_encode($this->_response), true);
            //Zend_Json::decode($this->_response);
            return $this->_response;
        } else {
            return false;
        }

        //Zend_Json::decode($this->_response);

    }

    /**
     *
     * Filtrage ou tri du tableau PHP revenant du web service
     */
    protected function _filterResponse()
    {
        $return = $this->_response;

        return $return;
    }

    /***********************************************************************
     *
     *
     * Fonctions relatives au préchargement de webservices grace à la configuration
     *
     *
     **********************************************************************/

    /**
     * Effectue un chargement de différents services à partir d'une règle de préchargement
     * @param string $sRule              Intitulé d'une règle de préchargement définie dans la configuration des services
     * @param bool   $followDependencies Indique s'il faut effectuer les appels chainés indiqués dans la configuration
     * @param mixed  $aData              Données sur lesquelles s'appuyer pour effectuer l'appel
     */
    public function preload($sRule, $followDependencies=false, $aData=array())
    {
        $this->log('Preloading : '.$sRule, Zend_Log::INFO);

        // Cas d'erreur, règle inconnue
        if (!isset(Pelican::$config['SERVICES']['PRELOAD'][$sRule])) {
            $this->log(' - Unknown preload rule : '.$sRule, Zend_Log::ERR);

        // Cas ok, on poursuit le préchargement
        } else {

            // Pour chaque url webservice à précharger pour la règle demandée...
            foreach (Pelican::$config['SERVICES']['PRELOAD'][$sRule] as $aServiceToLoad) {

                // Remplacement de toutes les variables
                if (is_array($aData)) {
                    foreach ($aData as $sDataKey=>$sDataValue) {
                        if (!is_array($sDataValue)) {
                            $aServiceToLoad['call'] = preg_replace( '/#'.$sDataKey.'#/', $sDataValue,$aServiceToLoad['call'] );
                        }
                    }
                }

                // Appel du webservice
                $this->log(' - Calling : '.$aServiceToLoad['call'], Zend_Log::INFO);

                //Pelican_Cache::clean("Webservice/Result", array($aServiceToLoad['call']));
                $aData = Pelican_Cache::fetch("Webservice/Result", array($aServiceToLoad['call']));
                 //debug($aData);

                if (!$aData) {
                    $this->log(' - ERROR : Unable to retrieve data', Zend_Log::ERR);

                    return false;
                }

                // Poursuit le preload si il y a des règles disponibles pour être chainées
                if ($followDependencies && $aServiceToLoad['chain'] && is_array($aServiceToLoad['chain'])) {
                    foreach ($aServiceToLoad['chain'] as $oneChainedRule) {

                        // Recopie des données avant traitement
                        //$aCurrentData = $aData;

                        //debug($aCurrentData);
                        // Parcourt du tableau pour préparer les données au nouvel appel
                        $aCurrentData = $this->_followDataTree($aData, $oneChainedRule[1]);
                        //debug($aCurrentData);

                        // Effectue le nouvel appel avec les données préparées
                        if (!is_array($aCurrentData)) {
                            $aNewCallData = $this->preload($oneChainedRule[0], true, $aCurrentData);
                        } else {
                            foreach ($aCurrentData as $aDataRow) {
                                //debug($aDataRow, 'new call');
                                $aNewCallData = $this->preload($oneChainedRule[0], true, $aDataRow);
                            }
                        }

                    }
                }
            }

            return true;

        }

        return false;
    }

    /**
     * Permet de ramener une portion des données fournies en descendant en profondeur dans le tableau avec un chemin fourni
     * @param mixed $aCurrentData  Tablea de données
     * @param mixed $aPathElements Chemin à suivre dans le tableau de données
     */
    protected function _followDataTree($aCurrentData, $aPathElements)
    {
        //var_dump('Begin _followDataTree');
        //debug($aCurrentData, '$aCurrentData');
        //debug($aPathElements, '$$aPathElements');

        if (is_array($aPathElements)) {
            // On parcourt les données que l'on a
            for ($iCurrentPathElement=0 ; $iCurrentPathElement<count($aPathElements) ; $iCurrentPathElement++) {

                // Si il faut descendre dans les n sous éléments, on fait appel un appel récursif
                if ($aPathElements[$iCurrentPathElement]=='*' && is_array($aCurrentData)) {
                    $aReturnData = array();
                    //echo 'PATH ELEMENT *';
                    //var_dump($aPathElements[$iCurrentPathElement]);
                    //var_dump($aCurrentData);

                    // On divise la tache récursivement entre n appels de fonctions
                    foreach ($aCurrentData as $aOneCurrentData) {
                        if (($iCurrentPathElement+1)<count($aPathElements)) {
                            //var_dump('Recursive call');
                            //var_dump(array_slice($aPathElements, $iCurrentPathElement+1));
                            $aRetrievedData = $this->_followDataTree($aOneCurrentData, array_slice($aPathElements, $iCurrentPathElement+1));
                            //echo "ADD \n";
                            //var_dump($aRetrievedData);
                            if ($aRetrievedData) {
                                foreach ($aRetrievedData as $aOneRowRetrievedData) {
                                    $aReturnData[] = $aOneRowRetrievedData;
                                }

                            }
                        }

                    }
                    //echo '------------------------------------------';

                    // On ne poursuit pas la descente, vu que la fonction récursive l'a déjà fait
                    return $aReturnData;

                // Sinon on descend simplement d'un niveau dans l'arborescence des données
                } else {
                    //echo 'PATH ELEMENT';
                    //var_dump($aPathElements[$iCurrentPathElement]);
                    //var_dump($aCurrentData);
                    //echo '------------------------------------------';
                    $aCurrentData = $aCurrentData[$aPathElements[$iCurrentPathElement]];
                }
            }
        }

        //debug($aCurrentData);
        return $aCurrentData;

    }

    /**
     * Remonte récursivement le graphe des dépendances pour le préchargement des services
     * @param unknown_type $aPreloadChain
     *
    public function loadPreloadingRules($aCurrentRules)
    {
        //$this->log('   - loadPreloadingRules for : '.$currentService['name'], Zend_Log::INFO);
        $aRules =  array();

        if (is_array($aCurrentRules)) {
            foreach ($aCurrentRules as $aCurrentRule) {

                // Ajoute récursivement chaque dépendance au tableau
                if ( $aCurrentRule['dependencies'] && is_array($aCurrentRule['dependencies']) && count($aCurrentRule['dependencies'])>0 ) {
                    foreach ($aCurrentRule['dependencies'] as $currentDependency) {
                        $this->log('   - Dependency found : '.$currentDependency, Zend_Log::INFO);
                        $aRules []= $this->loadPreloadingRules(Pelican::$config['SERVICES']['PRELOAD'][$currentDependency]);
                    }

                }

                // Ajoute enfin les règles du service courant
                var_dump($aCurrentRule);
                $aRules[]= $aCurrentRule;

            }
        }

        return $aRules;
    }*/

    /***********************************************************************
     *
     *
     * Fonctions d'initialisation des appels et d'analyse de la configuration
     *
     *
     **********************************************************************/

    /**
     * Initialise l'appel à un webservice
     * @param string $url    Url du webservice à appeler
     * @param mixed  $params Paramètres additionnels pour l'appel du service
     */
    protected function _init($url,$params)
    {
        $this->log(' - Initialization of webservice call : '.$url, Zend_Log::INFO);
        $this->_url = $url;
        $this->_params = $params;
    }

    /**
     * fichier ini avec les éléments suivants :
     * - url du web service
     * - type d'appel : direct, simple, periodique, prégénération
     * - frequence de mise à jour
     * - paramètres/valeurs d'appel si nécessaire ou fonction (par exemple liste des saisons ramenées par un autre webservice)
     */
    protected function _getInfosFromConfig()
    {
        $this->log(' - Getting infos...', Zend_Log::INFO);

        $sSimplifiedHost = explode('/', preg_replace('/(http(s){0,1}:\/\/)/', '', $this->_url), 2);

        // Suite de l'url
        $sUrl = '/'.$sSimplifiedHost[1];
        // Host seulement
        $sSimplifiedHost = $sSimplifiedHost[0];

        $this->log('   - Host : '.$sSimplifiedHost, Zend_Log::INFO);
        $this->log('   - Url : '.$sUrl, Zend_Log::INFO);

        $bIsKnownHost = isset(Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]);

        // Recherche de politiques d'appel appropriées si le host est connu
        if ($bIsKnownHost) {

            // Formatage de l'url
            if (Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]['format_url']) {
                $this->_url = call_user_func( Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]['format_url'], $this->_url, $this->_params );
            }


            $this->log('   - Known host, trying to identify access policy', Zend_Log::INFO);
            $sHostKey = Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]['name'];
            $this->_identifiedDomain = $sHostKey;

            // Si il y a une fonction de nettoyage d'url, on l'utilise avant de déterminer si une politique d'appel existe
            if (Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]['simplify_url']) {
                $sIdentifiedServiceCall = call_user_func( Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost]['simplify_url'], $sUrl);
            } else {
                $sIdentifiedServiceCall = $sUrl;
            }

            $this->_identifiedUrl = $sIdentifiedServiceCall;

            $this->log('   - Service called : '.$sIdentifiedServiceCall, Zend_Log::WARN);

            $bIsKnownUrl = isset(Pelican::$config['SERVICES'][$sHostKey][$sIdentifiedServiceCall]);

            // Mise en application des politiques d'appel par défaut du host
            $this->_infos = Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost];

            // Mise en application des politiques d'appel spécifique à l'url si des paramètres existent pour elle
            if ($bIsKnownUrl) {
                $this->log('   - Known method : applying access policy', Zend_Log::INFO);
                $this->log('     - ACCESS : '.Pelican::$config['SERVICES'][$sHostKey][$sIdentifiedServiceCall]['access_policy'].' - STORE : '
                    .Pelican::$config['SERVICES'][$sHostKey][$sIdentifiedServiceCall]['storage_policy'] . ' - EXPIRY : '
                    .Pelican::$config['SERVICES'][$sHostKey][$sIdentifiedServiceCall]['expiry_policy'], Zend_Log::INFO);
                $this->_infos = array_merge( $this->_infos, Pelican::$config['SERVICES'][$sHostKey][$sIdentifiedServiceCall] );

            // Mise en application des politiques d'appel par défaut du host
            } else {
                $this->log('   - Unknown method : applying default policy', Zend_Log::INFO);
                $this->_infos = Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost];
            }

        // Appel direct si host inconnu
        } else {
            $this->log('   - Unknown method : applying default policy', Zend_Log::INFO);
            $this->_infos = Pelican::$config['SERVICES']['HOSTS'][$sSimplifiedHost];
        }

        // Fixe la règle de récupération selon la configuration qui correspond à cet appel
        if (!$this->_infos['access_policy']) {
            $this->_infos['access_policy'] = $this->_infos['default_access_policy'];
        }

        // Fixe la règle de stockage des données retrouvées selon la configuration qui correspond à cet appel
        if (!$this->_infos['storage_policy']) {
            $this->_infos['storage_policy'] = $this->_infos['default_storage_policy'];
        }

    }

    /**
     * Récupère les informations trouvées dans la configuration concernant cet appel (à des fins de débuggage)
     * @param string $url    Url du webservice à appeler
     * @param mixed  $params Paramètres additionnels pour l'appel du service
     */
    public function getServiceInfo($url, $params)
    {
        $this->_init($url, $params);
        $this->_getInfosFromConfig();

        if ($this->_infos['name']) {
            return $this->_infos;
        } else {
            return array('No info', 'No information could be found for this service call');
        }
    }



    /***********************************************************************
     *
     *
     * Fonctions relatives aux fichiers bouchons et à leur chargement
     *
     *
     **********************************************************************/

    /**
     * Lit les données d'un fichier en local contenant des données exemple
     * @param  string $sFilename Nom du fichier à charger
     * @return string Contenu du fichier
     */
    protected function _readMockFile($sFilename)
    {
        if (file_exists($sFilename)) {
            $this->log('     - Mock found : '.$sFilename, Zend_Log::INFO);

            return utf8_decode(file_get_contents($sFilename));
        } else {
            $this->log('     - Mock not found : '.$sFilename, Zend_Log::ERR);
        }
    }

    /**
     * Retourne le nom du fichier bouchon correspondant à un appel de service
     * @return string Nom du fichier bouchon
     */
    protected function _getMockFilename()
    {
        return preg_replace('/([^a-zA-Z0-9]+)/','_',$this->_identifiedUrl).'.tmp';
    }

    /***********************************************************************
     *
     *
     * Fonction permettant de logguer l'activité de la classe Proxy
     *
     *
     **********************************************************************/

    /**
     * Retourne le nom de l'objet pour le profiler
     * @return string Nom de l'objet pour le profiler
     */
    public function _getProfilerObjectName()
    {
        $sProfilerObjectName = $this->_identifiedDomain.'::'.$this->_identifiedUrl;
        $aCallTypes = array('direct'=>'appel direct', 'mock'=>'bouchon actif : '.$this->_getMockFilename());
        if ($sProfilerObjectName=='') {
            $sProfilerObjectName = $url;
        } elseif ($sProfilerObjectName==$this->_identifiedDomain.'::') {
            $sProfilerObjectName = $sProfilerObjectName==$this->_identifiedDomain.'::'.$url;
        }
        $sProfilerObjectName = '['.$aCallTypes[$this->_infos['access_policy']].'] '.$sProfilerObjectName;

        return $sProfilerObjectName;
    }


    /**
     * Logge un message grace au logger qui aura été fourni à la classe
     * @param string Texte du message
     * @param string $msgType Type Zend_Log du message
     */
    protected function log($msg, $msgType)
    {
        if (isset($this->logger)) {
            $this->logger->log($msg, $msgType);
        }
    }


    /**
     * Définit un logger pour la classe de service
     * @param  Zend_Log        $logger
     * @return Pelican_Service
     */
    public function setLogger($logger)
    {
        if ($logger instanceof Zend_Log) {
            $this->logger = $logger;
            //debug($this->logger);
        } else {
            throw new Exception('Invalid type for logger');
        }

        return $this;
    }
}
