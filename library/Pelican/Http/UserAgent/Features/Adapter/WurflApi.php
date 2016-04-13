<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Pelican_Http_UserAgent_Features
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Pelican_Http_UserAgent_Features_Adapter_Interface
 */
require_once 'Pelican/Http/UserAgent/Features/Adapter.php';

/**
 * Features adapter build with the official WURFL PHP API
 * See installation instruction here : http://wurfl.sourceforge.net/nphp/
 * Download : http://sourceforge.net/projects/wurfl/files/WURFL PHP/1.1/wurfl-php-1.1.tar.gz/download
 *
 * @category Zend
 * @package Itk
 * @subpackage Zend_Browser
 * @copyright Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
class Pelican_Http_UserAgent_Features_Adapter_WurflApi implements Pelican_Http_UserAgent_Features_Adapter
{

    const DEFAULT_API_VERSION = '1.5';

    /**
     *
     * @access public
     * @var string Download url of Wurfl Db
     */
    public static $WURFL_DATABASE_VERSION = 'http://phpfactory_mobile.interakting.com/get/version.php';

    public static $WURFL_DATABASE_URL = 'http://phpfactory_mobile.interakting.com/get/database.php?id=';

    /**
     * __DESC__
     *
     * @access public
     * @param array $request
     *            $_SERVER variable
     * @return array
     */
    public static function getFromRequest ($request)
    {
        Pelican_Http_UserAgent::$config['wurflapi'] = Pelican::$config['wurflapi'];
        
        if (empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_lib_dir'])) {
            require_once 'Pelican/Http/UserAgent/Features/Exception.php';
            throw new Pelican_Http_UserAgent_Features_Exception('The "wurfl_lib_dir" parameter is not defined');
            
            return;
        }
        if (empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_file']) && empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_array'])) {
            require_once 'Pelican/Http/UserAgent/Features/Exception.php';
            // throw new Pelican_Http_UserAgent_Features_Exception('The "wurfl_config_file" or "wurfl_config_array" parameter is not defined');
            throw new Pelican_Http_UserAgent_Features_Exception('The "wurfl_config_file" parameter is not defined');
            
            return;
        }
        if (empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_api_version'])) {
            Pelican_Http_UserAgent::$config['wurflapi']['wurfl_api_version'] = self::DEFAULT_API_VERSION;
        }
        
        switch (Pelican_Http_UserAgent::$config['wurflapi']['wurfl_api_version']) {
            case '1.0':
                {
                    /**
                     * Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_file'] must be an XML file
                     */
                    require_once (Pelican_Http_UserAgent::$config['wurflapi']['wurfl_lib_dir'] . 'WURFLManagerProvider.php');
                    $wurflManager = WURFL_WURFLManagerProvider::getWURFLManager(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_file']);
                    break;
                }
            case '1.1':
            case '1.5':
                {
                    
                    require_once (Pelican_Http_UserAgent::$config['wurflapi']['wurfl_lib_dir'] . 'Application.php');
                    if (! empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_file'])) {
                        $wurflConfig = WURFL_Configuration_ConfigFactory::create(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_file']);
                    }
                    /**
                     *
                     * @todo / NOT FINISHED
                     *       elseif (! empty(Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_array'])) {
                     *       $configuration = Pelican_Http_UserAgent::$config['wurflapi']['wurfl_config_array'];
                     *       $wurflConfig = new WURFL_Configuration_InMemoryConfig();
                     *       $wurflConfig->wurflFile($configuration['wurfl']['main-file'])->wurflPatch($configuration['wurfl']['patches'])->persistence($configuration['persistence']['provider'], $configuration['persistence']['params']['dir']);
                     *       }
                     */
                    $wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
                    $wurflManager = $wurflManagerFactory->create();
                    break;
                }
        }
        
        $device = $wurflManager->getDeviceForHttpRequest($request);
        $features = $device->getAllCapabilities();
        $features['device_id'] = $device->id;
        
        return $features;
    }

    public static function updateDatabase ()
    {
        
        // config : url d'origine
        if (! empty(Pelican::$config['wurflapi']['DATABASE_HASH'])) {
            
            set_time_limit(3000);
            ini_set("memory_limit", '2000M');
            $now = date('Y-m-d');
            $config['wurflapi'] = Pelican::$config['wurflapi'];
            
            include ($config['wurflapi']['wurfl_config_file']);
            // verification du fichier de stockage de wurfl.xml
            $dir = dirname($configuration['wurfl']['main-file']);
            if (! $dir) {
                self::msg('probleme de chemin pour wurfl.xml', true);
                die();
            }
            if (! is_dir($dir)) {
                mkdir($dir);
            }
            $cacheDir = realpath($configuration['persistence']['params']['dir']);
            $remoteVersion = self::$WURFL_DATABASE_VERSION;
            $remoteDatabase = self::$WURFL_DATABASE_URL . Pelican::$config['wurflapi']['DATABASE_HASH'];
            $target = dirname($configuration['wurfl']['main-file']) . '/wurfl.' . $now . '.zip';
            $target0 = dirname($configuration['wurfl']['main-file']) . '/wurfl.zip';
            $remote = self::getRemoteFile($remoteVersion);
            $local = '';
            
            if (file_exists($target0)) {
                $local = md5_file($target0);
            }
            if ($remote != $local) {
                
                self::msg('download : ');
                file_put_contents($target, self::getRemoteFile($remoteDatabase));
                self::msg("OK", true);
                flush();
                
                self::msg('unzip : ');
                chdir(dirname($target));
                @unlink($target0);
                copy($target, $target0);
                @unlink(dirname($configuration['wurfl']['main-file']) . '/wurfl.xml');
                system('unzip ' . $target0);
                self::msg("OK", true);
                flush();
                
                // si l'update est ok
                $local = md5_file($target0);
                if ($remote == $local) {
                    self::msg('cache provider update : ');
                    require_once ($config['wurflapi']['wurfl_lib_dir'] . 'Application.php');
                    
                    $configFile = $config['wurflapi']['wurfl_config_file'];
                    if (file_exists($configFile)) {
                        $configFile = str_replace('-config.php', '-update.php', $configFile);
                    }
                    include ($configFile);
                    
                    $tempCacheDir = $configuration['persistence']['params']['dir'];
                    if (is_dir($tempCacheDir)) {
                        $tempCacheDir = realpath($tempCacheDir);
                        if (basename($tempCacheDir) == 'mobile_temp') {
                            system('rm -rf ' . $tempCacheDir);
                        }
                    }
                    mkdir($tempCacheDir, 0777, true);
                    $tempCacheDir = realpath($tempCacheDir);
                    $wurflConfig = WURFL_Configuration_ConfigFactory::create($configFile);
                    $wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
                    $wurflManager = $wurflManagerFactory->create();
                    self::msg("OK", true);
                    flush();
                    
                    self::msg('update cache : ');
                    if (is_dir($cacheDir . '_old')) {
                        if (basename($cacheDir . '_old') == 'mobile_old') {
                            system('rm -rf ' . $cacheDir . '_old');
                        }
                    }
                    rename($cacheDir, $cacheDir . '_old');
                    rename($tempCacheDir, $cacheDir);
                    self::msg("OK", true);
                    flush();
                    
                    unset($wurflConfig);
                    
                    if (file_exists($target0)) {
                        $local = md5_file($target0);
                    }
                    if ($remote == $local) {
                        self::msg('update table Wurfl : ');
                        self::initWurflTable();
                        Pelican_Cache::clean('Mobile/List');
                        self::msg("OK", true);
                        flush();
                    }
                } else {
                    self::msg('bad update', true);
                }
            } else {
                self::msg('no update available', true);
            }
        } else {
            self::msg('no licence number', true);
        }
    }

    public static function getRemoteFile ($url)
    {
        /*
         * Pelican::$config['wurflapi']['DATABASE_CURLOPTIONS'] = array( CURLOPT_PROXY => 'http://http.internetpsa.inetpsa.com:80', CURLOPT_PROXYUSERPWD => 'mdecpw00:svncpw00', CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_PROXYTYPE => 'CURLPROXY_HTTP' );
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
        curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2); // if http server gives redirection responce
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt"); // cookies storage / here the changes have been made
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
        
        if (! empty(Pelican::$config['wurflapi']['DATABASE_CURLOPTIONS'])) {
            foreach (Pelican::$config['wurflapi']['DATABASE_CURLOPTIONS'] as $opt => $value) {
                curl_setopt($ch, $opt, $value);
            }
        }
        
        $return = curl_exec($ch); // execute the http request
        curl_close($ch); // close the connection
                         
        // $return = file_get_contents($url);
        
        return $return;
    }

    public static function initWurflTable ()
    {
        $oConnection = Pelican_Db::getInstance();
        
        /*
         * creation de la table CREATE TABLE #pref#_wurfl ( `device_id` VARCHAR( 100 ) NOT NULL , `brand_name` VARCHAR( 100 ) NOT NULL , `model_name` VARCHAR( 100 ) NOT NULL , `device_os` VARCHAR( 100 ) NOT NULL , `device_os_version` VARCHAR( 100 ) NOT NULL , `device_os_full` VARCHAR( 100 ) NOT NULL , `mobile_browser` VARCHAR( 100 ) NOT NULL , `mobile_browser_version` VARCHAR( 100 ) NOT NULL , `mobile_browser_full` VARCHAR( 100 ) NOT NULL , `release_date` VARCHAR( 100 ) NOT NULL , `release_year` VARCHAR( 4 ) NOT NULL , `user_agent` TEXT NOT NULL , `fall_back` VARCHAR( 100 ) NOT NULL , `preferred_markup` VARCHAR( 100 ) NOT NULL , `html_preferred_dtd` VARCHAR( 100 ) NOT NULL , `markup` VARCHAR( 100 ) NOT NULL , PRIMARY KEY ( `device_id` ) ) ENGINE = InnoDB;
         */
        set_time_limit(3000);
        ini_set("memory_limit", '2000M');
        
        $config['wurflapi'] = Pelican::$config['wurflapi'];
        
        require_once ($config['wurflapi']['wurfl_lib_dir'] . 'Application.php');
        
        $wurflConfig = WURFL_Configuration_ConfigFactory::create($config['wurflapi']['wurfl_config_file']);
        $wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
        $wurflManager = $wurflManagerFactory->create();
        
        $devices = $wurflManager->getAllDevicesID();
        $total = count($devices);
        $values = array();
        if ($devices) {
            $oConnection->query('truncate table #pref#_wurfl');
            foreach ($devices as $id) {
                $device = $wurflManager->getDevice($id);
                
                $features = $device->getAllCapabilities();
                if (ucwords($features['brand_name']) != 'Desktop') {
                    $values['DEVICE_ID'] = $device->id;
                    
                    if ($features['brand_name'] && ucwords($features['brand_name']) != 'Desktop') {
                        $values['BRAND_NAME'] = ucwords($features['brand_name']);
                        $values['MODEL_NAME'] = $features['model_name'];
                        $values['DEVICE_OS'] = $features['device_os'];
                        $values['DEVICE_OS_VERSION'] = $features['device_os_version'];
                        $values['DEVICE_OS_FULL'] = $features['device_os'] . ($features['device_os_version'] ? '/' . $features['device_os_version'] : '');
                        $values['MOBILE_BROWSER'] = ucwords($features['mobile_browser']);
                        $values['MOBILE_BROWSER_VERSION'] = $features['mobile_browser_version'];
                        $values['MOBILE_BROWSER_FULL'] = $values['MOBILE_BROWSER'] . ($features['mobile_browser_version'] ? '/' . $features['mobile_browser_version'] : '');
                        $values['RELEASE_DATE'] = $features['release_date'];
                        $date = explode('_', str_replace('june_2012', '2012_june', $features['release_date']));
                        $values['RELEASE_YEAR'] = $date[0];
                        $values['RELEASE_MONTH'] = str_replace(array(
                            'april',
                            'august',
                            'aug',
                            'december',
                            'dicember',
                            'febraury',
                            'february',
                            'januarypour',
                            'january',
                            'july',
                            'june',
                            'march',
                            'may',
                            'november',
                            'nov',
                            'october',
                            'september',
                            'sep'
                        ), array(
                            '04',
                            '08',
                            '08',
                            '12',
                            '12',
                            '02',
                            '02',
                            '01',
                            '01',
                            '07',
                            '06',
                            '03',
                            '05',
                            '11',
                            '11',
                            '10',
                            '09',
                            '09'
                        ), trim(strtolower($date[1]), '_'));
                        $values['USER_AGENT'] = $device->userAgent;
                        $values['FALL_BACK'] = $device->fallBack;
                        $values['POINTING_METHOD'] = $features['pointing_method'];
                        $values['PREFERRED_MARKUP'] = $features['preferred_markup'];
                        $values['HTML_PREFERRED_DTD'] = $features['html_preferred_dtd'];
                        $values['MARKETING_NAME'] = $features['marketing_name'];
                        $values['RESOLUTION_HEIGHT'] = $features['resolution_height'];
                        $values['RESOLUTION_WIDTH'] = $features['resolution_width'];
                        $values['MARKUP'] = self::getMarkup($features['preferred_markup'], $features['html_preferred_dtd'], $features['canvas_support'], $features['css_gradient'], $features['ajax_preferred_geoloc_api']);
                        // $values['DEVICE_TYPE'] = ($features['is_tablet'] !=
                        // 'false' ? 'TABLET' : ($features['fall_back'] ==
                        // 'generic_smarttv_browser' ? 'TV' : 'MOBILE'));
                        if ($features['is_wireless_device'] == 'true') {
                            $values['DEVICE_TYPE'] = 'MOBILE';
                        } else {
                            $values['DEVICE_TYPE'] = 'OTHERS';
                        }
                        if ($features['is_tablet'] == 'true') {
                            $values['DEVICE_TYPE'] = 'TABLET';
                        }
                        /*
                         * if ($features ['is_tablet'] == 'true') { $values ['DEVICE_TYPE'] = 'SMARTPHONE'; }
                         */
                        if ($features['is_stb'] == 'true' || substr_count($device->fallBack, '_stb_')) {
                            $values['DEVICE_TYPE'] = 'STB';
                        }
                        if ($features['is_bot'] == 'true') {
                            $values['DEVICE_TYPE'] = 'BOT';
                        }
                        if ($features['is_smarttv'] == 'true' || substr_count($device->fallBack, '_smarttv_') || substr_count($device->fallBack, '_tv_')) {
                            $values['DEVICE_TYPE'] = 'SMARTTV';
                        }
                        
                        Pelican_Db::$values = $values;
                        $oConnection->insertQuery('#pref#_wurfl');
                        $oConnection->commit();
                        // $values[$features['brand_name']] ++;
                        self::msg($device->fallBack . " -> reste : " . -- $total,true);
                        unset($features);
                        unset($device);
                        flush();
                    }
                }
            }
        }
    }

    public static function getMarkup ($markup, $dtd, $canvas, $css, $ajax)
    {
        if (strpos($markup, 'wml') !== false) {
            $return = 'WML';
        }
        if (strpos($markup, 'xhtmlbasic') !== false) {
            $return = 'XHTML basic';
        }
        if (strpos($markup, 'xhtmlmp') !== false) {
            $return = 'XHTML MP';
        }
        if (strpos($markup, 'imode') !== false) {
            $return = 'cHTML (iMode)';
        }
        if (strpos($markup, 'web_3') !== false) {
            $return = 'HTML 3';
        }
        if (strpos($markup, 'web_4') !== false) {
            $return = 'HTML 4';
            switch ($dtd) {
                case "xhtml_transitional":
                    {
                        $retrun = "XHTML";
                        break;
                    }
                case "html5":
                    {
                        $retrun = "HTML 5";
                        break;
                    }
            }
        }
        if (strpos($markup, 'voicexml') !== false) {
            $return = 'Voice XML';
        }
        if (! empty($canvas) && ! empty($css) && ! empty($ajax)) {
            if ($canvas != 'none' && $css != 'none' && $ajax != 'none') {
                $return = 'HTML 5/CSS 3';
            }
        }
        
        return $return;
    }

    public static function msg ($text, $return = false)
    {
        if (PHP_SAPI === 'cli') {
            echo $text . ($return ? "\r\n" : "");
        } else {
            echo $text . ($return ? "\r\n" : "");
        }
    }
}
