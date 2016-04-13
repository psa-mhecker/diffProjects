<?php
/**
 * * Gestion de l'optimisation des fichiers js et css
 *
 * @package Pelican
 * @subpackage Index
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 * @since 27/03/2009
 */
define('PACK_ACTIVE', false); // true, false
define('PACK_MAKEGROUP', true); //true, false
define('PACK_MINIFY', true); //true, false
define('PACK_ENCODE_TYPE', ''); // '', crc32, md5 etc...
define('PACK_DEBUG', false); // '', crc32, md5 etc...

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Index
 * @author Raphaël Carles <rcarles@businessdecision.com>
 */
class Pelican_Index_Pack {
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $ini __DESC__
     * @return __TYPE__
     */
    public function __construct($ini) {
        $ini = str_replace(array('//', '\/'), array('/', '/'), preg_replace('/public\/(.*)\//', '/application\/sites\/$1\/configs\//', $ini));
        if (PACK_ACTIVE) {
            $this->setParams($ini);
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $datas __DESC__
     * @param __TYPE__ $type (option) __DESC__
     * @return __TYPE__
     */
    public function process($datas, $type = "css") {
        $return = $datas;
        $this->type = $type;
        $this->group = array();
        if (!empty($this->params)) {
            if ($datas) {
                foreach($datas as $i => $data) {
                    $return[$i] = Pelican_Index_Pack::getName($data, $i);
                }
            }
            if (PACK_MAKEGROUP) {
                if ($this->group) {
                    if (array_key_exists('group', $this->group) && is_array($this->group['group'])) {
                        foreach($this->group['group'] as $group => $params) {
                            if ($params[$this->type]) {
                                $return[$this->group['group'][$group]['index']] = $this->setGroup($group, $params[$this->type]);
                            }
                        }
                    }
                    if (array_key_exists('index', $this->group) && is_array($this->group['index'])) {
                        foreach($this->group['index'] as $index) {
                            $return[$index] = array();
                        }
                    }
                }
            }
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $file __DESC__
     * @param string $index (option) __DESC__
     * @return __TYPE__
     */
    public function getName($file, $index = 0) {
        $return = $file;
        
        /** cas des css */
        if ($this->type == 'css') {
            $parse = parse_url($return['href']);
            if ($parse['path']) {
                $timestamp = $this->params['timestamp'][$parse['path']];
                $group = $this->params['group'][$parse['path']];
                $newFile = Pelican_Index_Pack::setTimestamp($return['href'], $timestamp, $group, $index);
                if ($newFile) {
                    $return['href'] = $newFile;
                } else {
                    $return = false;
                }
            }
        } elseif ($this->type == 'js') {
            $parse = parse_url($return['js']);
            if ($parse['path']) {
                $timestamp = $this->params['timestamp'][$parse['path']];
                $group = $this->params['group'][$parse['path']];
                $newFile = Pelican_Index_Pack::setTimestamp($return['js'], $timestamp, $group, $index);
                if ($newFile) {
                    $return['js'] = $newFile;
                } else {
                    $return = false;
                }
            }
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $file __DESC__
     * @param string $timestamp (option) __DESC__
     * @param string $group (option) __DESC__
     * @param string $index (option) __DESC__
     * @return __TYPE__
     */
    public function setTimestamp($file, $timestamp = "", $group = "", $index = 0) {
        $return = $file;
        if (!$timestamp) {
            $group = "";
        }
        if ($timestamp) {
            $pathinfo = pathinfo($return);
            $parse = parse_url($pathinfo['dirname']);
            $name = str_replace("/", "_", substr($parse['path'], 1, strlen($parse['path'])) . "/") . str_replace(array("?", "=", "&", "."), "_", $pathinfo['filename']) . '.' . Pelican_Index_Pack::encode($timestamp) . '.' . $this->type;
            $realPath = $this->root[$this->type] . '/' . $name;
            if (!is_file($realPath)) {
                $this->runPacker($file, $realPath);
            }
            $return = $this->http[$this->type] . '/' . $name;
        }
        if (PACK_MAKEGROUP && $group) {
            if (!isset($this->group['group'][$group]['index'])) {
                $this->group['group'][$group]['index'] = $index;
            } else {
                
                /** pour vider le tableau d'origine */
                $this->group['index'][] = $index;
            }
            $this->group['group'][$group][$this->type]['file'][] = $realPath;
            $this->group['group'][$group][$this->type]['timestamp'][] = $timestamp;
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $group __DESC__
     * @param __TYPE__ $params __DESC__
     * @return __TYPE__
     */
    public function setGroup($group, $params) {
        $newFile = $group . '.' . Pelican_Index_Pack::encode(implode('_', $params['timestamp'])) . '.' . $this->type;
        if (!is_file($this->root[$this->type] . '/' . $newFile)) {
            $cmd = 'cat ' . implode(' ', $params['file']) . ' > ' . $this->root[$this->type] . '/' . $newFile;
            Pelican::runCommand($cmd);
        }
        if ($this->type == 'css') {
            $return['href'] = $this->http[$this->type] . '/' . $newFile;
        } elseif ($this->type == 'js') {
            $return['js'] = $this->http[$this->type] . '/' . $newFile;
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $name __DESC__
     * @return __TYPE__
     */
    static function encode($name) {
        $return = $name;
        if (PACK_ENCODE_TYPE) {
            $return = call_user_func(PACK_ENCODE_TYPE, $name);
        }
        if (PACK_MINIFY) {
            $return.= '.min';
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $origin __DESC__
     * @param __TYPE__ $destination __DESC__
     * @return __TYPE__
     */
    public function runPacker($origin, $destination) {
        
        /** cas du dynamique */
        $dynamic = false;
        if (substr_count($origin, ".php")) {
            $origin = "http://" . $_SERVER['HTTP_HOST'] . $origin;
            $dynamic = true;
        }
        
        /** cas du lib */
        if (substr($origin, 0, strlen(Pelican::$config['LIB_PATH'])) == Pelican::$config['LIB_PATH']) {
            $origin = Pelican::$config["DOCUMENT_HTTP"] . $origin;
        }
        
        /** cas du Pelican_Plugin */
        if (substr($origin, 0, strlen(Pelican::$config['PLUGIN_PATH'])) == Pelican::$config['PLUGIN_PATH']) {
            $origin = Pelican::$config["DOCUMENT_HTTP"] . $origin;
        }
        $parse = parse_url($origin);
        if (!$parse['scheme']) {
            
            /** cas du document_root */
            $origin = $_SERVER['DOCUMENT_ROOT'] . $origin;
        }
        $pathinfo = pathinfo($destination);
        if ($dynamic) {
            if ($pathinfo['extension'] == 'css') {
                $content = str_replace("../../", "../", file_get_contents($origin) . "\n");
            } elseif ($pathinfo['extension'] == 'js') {
                $content = file_get_contents($origin) . ($pathinfo['extension'] == 'js' ? ";" : "") . "\n";
            }
            if (PACK_MINIFY) {
                $content = Pelican_Index_Pack::minify($content, $pathinfo['extension']);
            }
            file_put_contents($destination, $content);
        } else {
            $from = array(Pelican::$config['MEDIA_HTTP'] . Pelican::$config['LIB_PATH'], Pelican::$config['MEDIA_HTTP'], Pelican::$config["DOCUMENT_HTTP"] . Pelican::$config["PLUGIN_PATH"], Pelican::$config["DOCUMENT_HTTP"] . Pelican::$config['LIB_PATH']);
            $to = array(Pelican::$config['LIB_ROOT'], Pelican::$config['MEDIA_ROOT'], Pelican::$config["PLUGIN_ROOT"], Pelican::$config['LIB_ROOT']);
            $origin = str_replace($from, $to, $origin);
            if (PACK_MINIFY) {
                $content = file_get_contents($origin) . ($pathinfo['extension'] == 'js' ? ";" : "") . "\n";
                if (strpos($origin, '.min.') === false) {
                    $content = Pelican_Index_Pack::minify($content, $pathinfo['extension']);
                }
                file_put_contents($destination, $content);
            } else {
                copy($origin, $destination);
            }
        }
        return true;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $content __DESC__
     * @param __TYPE__ $type __DESC__
     * @return __TYPE__
     */
    static function minify($content, $type) {
        require_once dirname(__FILE__) . '/public/pack/minify/config.php';
        set_include_path(get_include_path() . ':' . dirname(__FILE__) . '/public/pack/minify/library/');
        $src = $content;
        switch ($type) {
            case 'css': {
                        require_once 'Minify/CSS.php';
                        $return = Minify_CSS::minify($src, $options);
                    break;
                }
            case 'js': {
                    require_once 'Minify/Javascript.php';
                    $return = Minify_Javascript::minify($src);
                    break;
                }
            default: {
                    $return = $content;
                    break;
                }
            }
            return $return;
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $ini __DESC__
         * @return __TYPE__
         */
        public function setParams($ini) {
            if (file_exists($ini)) {
                $this->params = parse_ini_file($ini, true);
                
                /**
                 * important : pour éviter des versions identiques, on retravaille le fichier pour préfixer les versions par les numéros de ligne
                 */
                if ($this->params['timestamp']) {
                    $i = 0;
                    foreach($this->params['timestamp'] as $key => $timestamp) {
                        $this->params['timestamp'][$key] = $i . "_" . $timestamp;
                        $i++;
                    }
                }
                $this->root['css'] = str_replace('design', 'design_pack', Pelican::$config["DESIGN_ROOT"] . '/css');
                $this->http['css'] = str_replace('design', 'design_pack', Pelican::$config["DESIGN_HTTP"] . '/css');
                if (!is_dir($this->root['css'])) {
                    Pelican::runCommand('mkdir -p ' . $this->root['css']);
                }
                $this->root['js'] = str_replace('design', 'design_pack', Pelican::$config["DESIGN_ROOT"] . '/js');
                $this->http['js'] = str_replace('design', 'design_pack', Pelican::$config["DESIGN_HTTP"] . '/js');
                if (!is_dir($this->root['js'])) {
                    Pelican::runCommand('mkdir -p ' . $this->root['js']);
                }
                if (isset(Pelican::$config["IMAGE_FRONT_HTTP"])) {
                    $images = str_replace(Pelican::$config["DESIGN_HTTP"], Pelican::$config["DESIGN_ROOT"], Pelican::$config["IMAGE_FRONT_HTTP"]);
                    $this->root['images'] = str_replace('design', 'design_pack', $images);
                    if (!is_link($this->root['js'])) {
                        Pelican::runCommand('ln -s ' . $images . ' ' . $this->root['images']);
                    }
                }
            }
        }
        
        /**
         * __DESC__
         *
         * @static
         * @access public
         * @return __TYPE__
         */
        static function findFiles() {
            $return = array();
            $aPath[] = Pelican::$config['DOCUMENT_INIT'] . '/back*';
            $aPath[] = Pelican::$config['DOCUMENT_INIT'] . '/front*';
            $aPath[] = Pelican::$config['DOCUMENT_INIT'] . '/media/design/*';
            if ($aPath) {
                foreach($aPath as $path) {
                    Pelican_Index_Pack::getFiles($path);
                }
            }
            return $return;
        }
        
        /**
         * __DESC__
         *
         * @static
         * @access public
         * @param __TYPE__ $path __DESC__
         * @return __TYPE__
         */
        static function getFiles($path) {
            $cmd = "find " . $path . " -type f -name '*.css*' "; //-ls |awk '{print $11\"#\";}'";
            $handle = popen($cmd, 'r');
            while (!feof($handle)) {
                $read.= fread($handle, 2096);
            }
            pclose($handle);
            return explode("\n", $read);
        }
        //Find background images in the CSS and convert their paths to absolute
        
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $content __DESC__
         * @param __TYPE__ $path __DESC__
         * @return __TYPE__
         */
        function convertPathsToAbsolute($content, $path) {
            $paths['full']['document_root'] = $this->ensureTrailingSlash($_SERVER['DOCUMENT_ROOT']);
            preg_match_all("/url\((.*?)\)/is", $content, $matches);
            if (count($matches[1]) > 0) {
                $counter = 0;
                foreach($matches[1] as $key => $file) {
                    if (strstr($file, "data:")) { //Don't touch data URIs
                        continue;
                    }
                    $counter++;
                    $original_file = trim($file);
                    $file = preg_replace("@'|\"@", "", $original_file);
                    if (substr($file, 0, 1) != "/" && substr($file, 0, 5) != "http:") { //Not absolute
                        $full_path_to_image = str_replace($this->getBasename($path['src']), "", $path['src']);
                        $absolute_path = "/" . $this->preventLeadingSlash(str_replace($this->unifyDirSeparator($paths['full']['document_root']), "", $this->unifyDirSeparator($full_path_to_image . $file)));
                        $marker = md5($counter);
                        $markers[$marker] = $absolute_path;
                        $content = str_replace($original_file, $marker, $content);
                    }
                }
            }
            if (!empty($markers) && is_array($markers)) {
                //Replace the markers for the real path
                foreach($markers as $md5 => $real_path) {
                    $content = str_replace($md5, $real_path, $content);
                }
            }
            return $content;
        }
        //Make the sep the same
        
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $path __DESC__
         * @return __TYPE__
         */
        function unifyDirSeparator($path) {
            if (DIRECTORY_SEPARATOR != '/') {
                return str_replace(DIRECTORY_SEPARATOR, '/', $path);
            } else {
                return $path;
            }
        }
        
        /**
         * Version of basename works on nix and windows
         *
         * @access public
         * @param __TYPE__ $filename __DESC__
         * @return __TYPE__
         */
        function getBasename($filename) {
            $basename = preg_replace('/^.+[\\\\\\/]/', '', $filename);
            return $basename;
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $path __DESC__
         * @return __TYPE__
         */
        function preventLeadingSlash($path) {
            if (substr($path, 0, 1) == "/" || substr($path, 0, 1) == "\\") {
                $path = substr($path, 1);
            }
            return $path;
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $path __DESC__
         * @return __TYPE__
         */
        function ensureTrailingSlash($path) {
            if (substr($path, -1, 1) != "/") {
                $path.= "/";
            }
            return $path;
        }
    }
    