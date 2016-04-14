<?php
/**
 * Récupération des datas via la bdd
 *
 * @author David Moaté <david.moate@businessdecision.com>
 */

namespace ParamsApi\v1;
use Luracast\Restler\RestException;
use Api\Params\Db;

class Data
{ 
    private $countrie;
    private $language;
    private $connection;
    const APP_PREFIXE ='psa_';
    const CITROEN_SERVICE_GAMMEVU   = 'CITROEN_SERVICE_GAMMEVU';
    const CITROEN_SERVICE_WEBSTORE  = 'CITROEN_SERVICE_WEBSTORE';
    const CITROEN_SERVICE_SIMULFIN  = 'CITROEN_SERVICE_SIMULFIN';
    const CITROEN_SERVICE_MTGCFG    = 'CITROEN_SERVICE_MTGCFG';
    
    
    
    
    public function __construct() {
        $this->connection = Db::connect();
    }
    
    /**
     * Retourne le pays.
     *
     */
    public function getCountrie()
    {
        
        return $this->countrie;
    }  
    
    /**
     * set le pays.
     * @param string $countrie
     */
    public function setCountrie($countrie)
    {
        $this->countrie = $countrie;
    }  
    
    /**
     * Retourne la langue
     *
     */
    public function getLanguage()
    {
        
        return $this->language;
    }  
    
    /**
     * set le language.
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    
    /**
     * Retourne les parametres du ws Web store.
     *
     */
    public function getSiteId()
    {
        $siteCodePays = $this->getCountrie();
        if(empty($siteCodePays)){
            throw new RestException(500, "Vous devez setter le code pays");
        }
        $stmt = $this->connection->prepare("SELECT SITE_ID FROM " . self::APP_PREFIXE . "site_code sc WHERE sc.SITE_CODE_PAYS = :SITE_CODE_PAYS");
        $stmt->execute(array('SITE_CODE_PAYS' => $siteCodePays));
        $result = $stmt->fetch();
        if(empty($result)){
            throw new RestException(500, "Il n'y a pas de siteId pour ce code Pays {$siteCodePays}");
        }

        return $result['SITE_ID'];
    }    
    /**
     * Retourne les parametres du ws gamme.
     *
     */
    public function getWSgamme()
    {
        $gamme = array();
        $configurationsWs = $this->getConfigurationWSBySiteId();
        if(!is_array($configurationsWs)){
            return false;
        }
        $gamme['Activated'] = false;
        $gamme['Url'] = 'Not exist';        
        foreach($configurationsWs as $configurationWs){
            if($configurationWs['ws_name'] == self::CITROEN_SERVICE_GAMMEVU){
                $gamme['Activated'] = $configurationWs['status'];
                $gamme['Url'] = $configurationWs['ws_url'];
            }
        }
        
        return $gamme;
    }
    
    /**
     * Retourne les parametres du ws CFG.
     *
     */
    public function getMotCFG()
    {
        $cfg = array();
        $configurationsWs = $this->getConfigurationWSBySiteId();
        if(!is_array($configurationsWs)){
            return false;
        }
        $cfg['Activated'] = false;
        $cfg['Url'] = 'Not exist';
        $cfg['timeout'] = 0;
        foreach($configurationsWs as $configurationWs){
            if($configurationWs['ws_name'] == self::CITROEN_SERVICE_MTGCFG){
                $cfg['timeout']     =   \Pelican::$config["API"]['PARAMS']['TIMEOUT']['MOT_CFG'];
                $cfg['Activated']   =   $configurationWs['status'];
                $cfg['Url']         =   $configurationWs['ws_url'];
            }
        }
        
        return $cfg;
    }
    
    /**
     * Retourne les parametres du ws Web store.
     *
     */
    public function getWebStore()
    {
        $webStore = array();
        $configurationsWs = $this->getConfigurationWSBySiteId();
        if(!is_array($configurationsWs)){
            return false;
        }
        $webStore['Activated']  = false;
        $webStore['Url']        = 'Not exist';
        $webStore['timeout']    = 0;
        foreach($configurationsWs as $configurationWs){
            if($configurationWs['ws_name'] == self::CITROEN_SERVICE_WEBSTORE){
                $webStore['Activated']  = $configurationWs['status'];
                $webStore['Url']        = $configurationWs['ws_url'];
                $webStore['timeout']    = \Pelican::$config["API"]['PARAMS']['TIMEOUT']['WEBSTORE'];
            }
        }
        
        return $webStore;
    }
    
        /**
     * Retourne les parametres du ws SFG.
     *
     */
    public function getSFG() 
    {
        $sfg = array();
        $configurationsWs = $this->getConfigurationWSBySiteId();
        if(!is_array($configurationsWs)){
            return false;
        }
        $sfg['Activated'] = false;
        $sfg['Url'] = 'Not exist';        
        foreach($configurationsWs as $configurationWs){
            if($configurationWs['ws_name'] == self::CITROEN_SERVICE_SIMULFIN){
                $sfg['Activated'] = $configurationWs['status'];
                $sfg['Url'] = $configurationWs['ws_url'];
            }
        }
        
        return $sfg;
    }
    
        /**
     * Retourne les parametres du ws Configurateur.
     *
     */
    public function getConfigurateur()
    {
        $cfg['Activated'] = false;
        if(empty(\Pelican::$config["API"]['PARAMS']['CFG_COUNTRY_ACTIVATED'])){
         
            return $cfg;
        }
        $siteCodePays = $this->getCountrie();
        if(in_array($siteCodePays, \Pelican::$config["API"]['PARAMS']['CFG_COUNTRY_ACTIVATED'])){
            $cfg['Activated'] = true;
            
            return $cfg;
        }
        
        return $cfg;
    }
    
    /**
     * Retourne les parametres du ws Confishow.
     *
     */
    public function getConfishow()
    {
        
        return false;
    }
    
    /**
     * Retourne la configuration des WS pour un site
     *
     */
    public function getConfigurationWSBySiteId(){
        $siteId = $this->getSiteId();
        if(empty($siteId)){
            throw new RestException(500, "siteID manquant");
        }

        $stmt = $this->connection->prepare("SELECT status, ws_name, ws_url 
                                            FROM " . self::APP_PREFIXE . "site_webservice sw 
                                            INNER JOIN  " . self::APP_PREFIXE . "liste_webservices lw 
                                            ON sw.ws_id = lw.ws_id 
                                            WHERE sw.site_id = :site_id");
        $stmt->execute(array('site_id' => $siteId));
        $result = $stmt->fetchAll();
        if(empty($result)){
            throw new RestException(500, "Il n'y a pas de config pour ce code Pays {$siteCodePays}");
        }

        return $result;
    }
}
