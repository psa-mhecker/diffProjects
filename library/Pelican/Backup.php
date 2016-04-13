<?php

/**
 * Backup
 *
 * @package Pelican
 * @subpackage Backup
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @category Pelican
 * @package Backup
 * @subpackage Backup
 */
class Pelican_Backup
{

    public static $primaryTables = array(
        'site' => 'SITE_ID',
        'page' => 'PAGE_ID',
        'content' => 'CONTENT_ID',
        'media_directory' =>  'MEDIA_DIRECTORY_ID'
    );

    public static $secondaryTables = array(
        'SITE_ID' => array(
            'content',
            'content_category',
            'content_type_site',
            'directory_site',
            'label_langue_site',
            'media_directory',
            'page',
            'profile',
            'rewrite',
            'site',
            'site_code',
            'site_dns',
            'site_language',
            'site_parameter',
            'site_parameter_dns',
            'template_page',
            'template_site',
            'user',
            'user_role'
        ),
        'PAGE_ID' => array(
            'navigation',
            'page_multi',
            'page_multi_zone',
            'page_multi_zone_content',
            'page_multi_zone_media',
            'page_multi_zone_multi',
            'page_order',
            'page_version',
            'page_version_content',
            'page_version_media',
            'page_zone',
            'page_zone_content',
            'page_zone_media',
            'page_zone_multi',
            'page_zone_multi_multi',
            'page_zone_vehicule'
        ),
        'CONTENT_ID' => array(
            'content_version',
            'content_version_content',
            'content_version_media',
            'content_zone',
            'content_zone_media',
            'content_zone_multi',
            'faq_rubrique_content',
            'paragraph',
            'paragraph_media'
        ),
        'MEDIA_DIRECTORY_ID' => array(
            'media',
            'media_format_intercept',
            'media_usage'
        )
    );

    public static $exceptions = array(
        'media_format_intercept' => 'MEDIA',
        'media_usage' => 'MEDIA'
    );

    /**
     *
     * @param string $rootPath
     *            Root Directory for backup files
     * @param array $primaryAdd
     *            Addons for primary tables
     * @param array $secondaryAdd
     *            Addons for secondary tables
     */
    public function backup($rootPath, $primaryAdd = array(), $secondaryAdd = array())
    {
        ini_set('memory_limit', '5000M');
        $oConnection = Pelican_Db::getInstance();
        $root = $rootPath . '/' . date("Y-m-d") . '/';
        if (! is_dir($root)) {
            mkdir($root, 0777, true);
        }
        $id = array();
        
        // extend default parameters
        $this->extendTables($primaryAdd, $secondaryAdd);
        
        // browse SITE_ID
        $sites = $this->getSites();
        if (! empty($sites)) {
            foreach ($sites as $site) {
                // on backup file / site                
                if( empty($site['SITE_LABEL']) ){
                    $siteName   =   $site['SITE_ID'];
                }else{
                    $siteName   =   str_replace(' ', '_', $site['SITE_LABEL']);
                }
                $file = $root . $siteName . '.sql';
                @unlink($file);
                
                $log = $root . $siteName . '.log';
                @unlink($log);
                
                $erreurLog = $root . $siteName . '_erreur.log';
                @unlink($erreurLog);
                
                $fp     = fopen($file, 'w');
                $lp     = fopen($log, 'w');
                $elp    = fopen($erreurLog, 'w');
                echo ('SITE : ' . $site['SITE_ID'] . ' ' . $site['SITE_LABEL'] . "\n\n");
                fwrite($fp,"SET foreign_key_checks = 0;\n");
                // backup of primary tables first
                foreach (self::$primaryTables as $table => $key) {
                    $sqlPrimaryData     = "select * from #pref#_" . $table . " where site_id=" . $site['SITE_ID'];
                    $sqlPrimaryKey      = "select " . $key . " from #pref#_" . $table . " where site_id=" . $site['SITE_ID'];
                    // On vérifie que la table existe dans la base                  
                    if($this->getTableExist('#pref#_' . $table)){                                 
                        try {
                            $primary = $oConnection->queryTab($sqlPrimaryData);                                                     
                        } catch (Exception $e) {                                                        
                            fwrite($elp, $sqlPrimaryData . "NOK \n");
                            fwrite($elp, 'Exception reçue : ' . $e->getMessage() . "\n");
                        }                        
                        if (is_array($primary)) {                            
                            fwrite($lp, $sqlPrimaryData . ' : ' . count($primary) . "\n");                            
                            foreach ($primary as $values) {
                                Pelican_Db::$values =   $values;                                                            
                                // Récupération de la requete de suppression
                                $sSqlDelete = $this->getSQL("#pref#_" . $table, Pelican_Db::DATABASE_DELETE);

                                // Récupération de la requete d'insertion
                                $sSqlInsert = $this->getSQL("#pref#_" . $table, Pelican_Db::DATABASE_INSERT);                                                                

                                if ($sSqlDelete) {
                                    fwrite($fp, $sSqlDelete . "\n");
                                }
                                if ($sSqlInsert) {
                                    fwrite($fp, $sSqlInsert . "\n");
                                }
                                
                            }
                            
                            // backup of secondary tables
                            if (is_array(self::$secondaryTables[$key])) {
                                foreach (self::$secondaryTables[$key] as $child) {
                                    if (empty(self::$exceptions[$child])) {
                                        $sqlSecondary = 'select * from #pref#_' . $child . ' where ' . $key . ' in (' . $sqlPrimaryKey . ')';
                                    } else {
                                        switch (self::$exceptions[$child]) {
                                            case 'MEDIA':
                                                {
                                                    $sqlSecondary = 'select * from #pref#_' . $child . ' where MEDIA_ID in (select MEDIA_ID from #pref#_media where ' . $key . ' in (' . $sqlPrimaryKey . '))';
                                                    break;
                                                }
                                        }
                                    }
                                    if($this->getTableExist('#pref#_' . $child)){                                        
                                        try {                                            
                                            $secondary = $oConnection->queryTab($sqlSecondary);
                                        } catch (Exception $e) {
                                            fwrite($elp, $sqlSecondary . "NOK \n");
                                            fwrite($elp, 'Exception reçue : ' . $e->getMessage() . "\n");
                                        }                                        
                                        if (is_array($secondary)) {
                                            fwrite($lp, $sqlSecondary . ' : ' . count($secondary) . "\n");
                                            foreach ($secondary as $values) {
                                                //On remplit le tableau Pelican_Db::$values avec toutes les données à insérer
                                                Pelican_Db::$values = $values;

                                                // Récupération de la requete de suppression                                                
                                                $sSqlDelete = $this->getSQL("#pref#_" . $child, Pelican_Db::DATABASE_DELETE);
                                                                                                
                                                // Récupération de la requete d'insertion
                                                $sSqlInsert = $this->getSQL("#pref#_" . $child, Pelican_Db::DATABASE_INSERT);                           
                                                
                                                if ($sSqlDelete) {
                                                    fwrite($fp, $sSqlDelete . "\n");
                                                }
                                                if ($sSqlInsert) {
                                                    fwrite($fp, $sSqlInsert . "\n");
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                fwrite($fp,"SET foreign_key_checks = 1;\n");
                fclose($fp);
                fclose($lp);
            }
        }
    }

    /**
     * extends default parameters
     *
     * @param array $primaryAdd
     *            Addons for primary tables
     * @param array $secondaryAdd
     *            Addons for secondary tables
     */
    public function extendTables($primaryAdd = array(), $secondaryAdd = array())
    {
        if (! empty($primaryAdd)) {
            self::$primaryTables = array_merge(self::$primaryTables, $primaryAdd);
        }
        foreach (self::$secondaryTables as $key => $array) {
            if (! empty($secondaryAdd[$key])) {
                self::$secondaryTables[$key] = array_merge($array, $secondaryAdd[$key]);
            }
        }
    }

    /**
     * list Sites
     *
     * @return array:
     */
    public function getSites()
    {
        $oConnection = Pelican_Db::getInstance();        
        $sql = "select * from #pref#_site";
        $sites = $oConnection->queryTab($sql);
        return $sites;
    }

    /**
     * generate SQL queries with data vfrom given table and for a specific action (INS, UPD, DEL, REP)
     *
     * @param string $table            
     * @param string $action            
     * @return string
     */
    public function getSQL($table, $action)
    {
        $oConnection = Pelican_Db::getInstance();       
        set_time_limit(3000);        
        $oConnection->allowBind = false;
        // if action is REP (REPLACE) , the INS clause is used
        $aSql   =   $oConnection->updateTable(($action == 'REP' ? 'INS' : $action), $table, "", "", "", true);
        $sSql   =   $this->cleanSQL($aSql, $action);
        return $sSql;
    }
    
    public function cleanSQL($aSql, $action = ''){
        if ($aSql) {
            $sSql = implode(";###", $aSql);
            $sSql = str_replace("'''", "''", str_replace("\n", "", str_replace(";###", ";<br />", str_replace("'NULL'", "NULL", $sSql)) . ";\n"));
            $sSql = str_replace('#pref#_', APP_PREFIXE, $sSql);
            // if action is REP (REPLACE) , the INS clause is replaced
            if ($action == 'REP') {
                return str_replace('INSERT ', 'REPLACE ' , $sSql);
            } else {
                return $sSql;
            }
        } else {
            return '';
        }
    }

    
    /**
     * Permet de savoir si une table existe
     *
     * @access public
     * @param string $table Nom de la table
     * @return bool
     */
    public function getTableExist($table){
        $sql='SHOW TABLES LIKE "' . $table . '"';       
        $oConnection = Pelican_Db::getInstance();
        $result =   $oConnection->queryItem ($sql);
        if(empty($result)){
            return false;
        }
        return true;
    }    
}