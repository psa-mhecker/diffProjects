<?php
/**
 * Classe Factory de Pelican
 *
 * @package Pelican
 * @subpackage Factory
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 * @since 25/09/2009
 */

/**
 * Classe Factory de Pelican
 *
 * @package Pelican
 * @subpackage Factory
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Factory {
    
    /**
     * Chargement d'une instance de classe
     *
     * Exemple : /library/XXX/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX
     *
     * exemple : /library/XXX/V2/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX/V2
     *
     * exemple : /library/XXX/V2/V2.1/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX/V2/V2.1
     *
     * @static
     * @access public
     * @staticvar array $aInstance
     * @param __TYPE__ $class __DESC__
     * @return __TYPE__
     */
    static function &getInstance($class) {
        static $aInstance;
        $args = func_get_args();
        array_shift($args);
        if (!isset($aInstance[$class])) {
            //if (!is_object($aInstance[$class])) {
            pelican_import($class);
            $class = Pelican_Loader::files($class);
            $reflectionObj = new ReflectionClass($class);
            
            /**
             * si la classe possède une méthode getInstance => c'est un singleton : on l'utilise
             *
             * nomenclature : 'getInstance' pour un singleton
             */
            if (($reflectionObj->hasMethod('getInstance'))) {
                $instance = call_user_func_array(array($class, 'getInstance'), $args);
                $aInstance[$class] = $instance;
            } else {
                // un constructeur existe
                $instance = $reflectionObj->getConstructor() ? $reflectionObj->newInstanceArgs($args) : $reflectionObj->newInstance();
            }
            return $instance;
        } else {
            return $aInstance[$class];
        }
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $class __DESC__
     * @return __TYPE__
     */
    static function &newInstance($class) {
        $args = func_get_args();
        array_shift($args);
        
        /** les arguments doivent être dans un tableau */
        if (count($args) > 1 && !is_array($args[0])) {
            $args = array($args);
        }
        pelican_import($class);
        $class = Pelican_Loader::files($class);
        if ($args) {
            $instance = new $class($args);
        } else {
            $instance = new $class();
        }
        return $instance;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $class __DESC__
     * @param __TYPE__ $method __DESC__
     * @return __TYPE__
     */
    static function staticCall($class, $method) {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        $class = Pelican_Loader::files($class);
        if ($class) {
            if (method_exists($class, $method)) {
                return call_user_func_array(array($class, $method), $args);
            } else {
                throw new ErrorException('Méthode inexistante dans la classe ' . $class);
            }
        } else {
            throw new ErrorException('Classe inexistante');
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @staticvar xx $instance
     * @param string $file (option) __DESC__
     * @param __TYPE__ $type (option) __DESC__
     * @return __TYPE__
     */
    public static function getConfig($file = null, $type = 'PHP') {
        static $instance;
        if (!is_object($instance)) {
            if ($file === null) {
                $file = dirname(__FILE__) . DS . 'config.php';
            }
            $instance = self::_createConfig($file, $type);
        }
        return $instance;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @staticvar xx $instance
     * @param __TYPE__ $options (option) __DESC__
     * @return __TYPE__
     */
    public static function getSession($options = array()) {
        static $instance;
        if (!is_object($instance)) {
            $instance = Pelican_Factory::_createSession($options);
        }
        return $instance;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @staticvar xx $instance
     * @return __TYPE__
     */
    public static function getLanguage() {
        static $instance;
        if (!is_object($instance)) {
        }
        return $instance;
    }
    
    /**
     * Factory de la classe User
     *
     * @access public
     * @param string $type (option) __DESC__
     * @return object
     */
    public static function getUser($type = "") {
        $class = 'User';
        if ($type) {
            $class.= '.' . ucfirst($type);
        }
        return call_user_func(array(pelican_classname($class), 'getInstance'));
    }
    
    /**
     * Factory de la classe Pelican_Cache
     *
     * @access public
     * @return __TYPE__
     */
    public static function getCache() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @staticvar xx $instance
     * @return __TYPE__
     */
    public static function getACL() {
        static $instance;
        if (!is_object($instance)) {
        }
        return $instance;
    }
    
    /**
     * Factory de la classe Smarty
     *
     * @access public
     * @return Instance
     */
    public static function getView() {
        $return = Pelican_Factory::getInstance('View');
        return $return;
    }
    
    /**
     * Factory de la classe de BDD
     *
     * @access public
     * @staticvar xx $instance
     * @param string $app (option) __DESC__
     * @return __TYPE__
     */
    public static function getConnection($app = "") {
        $return = Pelican_Factory::getInstance('Db', $app);
        return $return;
    }
}

/**
 * Factory de la classe de BDD (obsolete)
 *
 * @deprecated __DESC__
 * @param string $app (option) __DESC__
 * @return __TYPE__
 */
function getConnection($app = "") {
    return Pelican_Factory::getConnection($app);
}

/**
 * Factory de la classe Smarty (obsolete)
 *
 * @deprecated __DESC__
 * @return __TYPE__
 */
function getSmarty() {
    return Pelican_Factory::getView();
}

/**
 * __DESC__
 *
 * @deprecated __DESC__
 * @param string $strValue __DESC__
 * @param string $strSep (option) __DESC__
 * @return __TYPE__
 */
function splitTextarea($strValue, $strSep = "\r\n") {
    include_once (pelican_path('Form'));
    return Pelican_Factory::staticCall('Form', 'splitTextarea', $strValue, $strSep);
}

/**
 * __DESC__
 *
 * @deprecated __DESC__
 * @param __TYPE__ $oConnection __DESC__
 * @param string $strName __DESC__
 * @param __TYPE__ $iID __DESC__
 * @param string $strQueryColumn __DESC__
 * @param string $strQueryRow __DESC__
 * @param string $strAbsFieldName __DESC__
 * @param string $strOrdFieldName __DESC__
 * @param string $strTableName __DESC__
 * @param string $strIDFieldName __DESC__
 * @return __TYPE__
 */
function recordTabCroiseGenerique($oConnection, $strName, $iID = "", $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName) {
    include_once (pelican_path('Form'));
    return Pelican_Factory::staticCall('Form', 'recordTabCroiseGenerique', $oConnection, $strName, $iID, $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName);
}

/**
 * __DESC__
 *
 * @deprecated __DESC__
 * @param string $strName __DESC__
 * @param string $strPrefixe (option) __DESC__
 * @return __TYPE__
 */
function readMulti($strName, $strPrefixe = "multi") {
    include_once (pelican_path('Form'));
    return Pelican_Factory::staticCall('Form', 'readMulti', $strName, $strPrefixe);
}

/**
 * Conversion des caractères UTF8 en unicode
 *
 * @param string $source Texte à convertir
 * @return string
 */
function utf8ToUnicodeEntities($source) {
    pelican_import('Text.Utf8');
    return Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $source);
}

/**
 * Gestion des valeurs "NULL"
 *
 * @param string $value (option) __DESC__
 * @return string
 */
function nvl($value = "") {
    pelican_import('Db');
    return Pelican_Db::nvl($value);
}

/**
 * @deprecated
 */
function addSlashesGPC($chaine) {
    pelican_import('Db');
    return Pelican_Db::addSlashesGPC($chaine);
}

/**
 * @deprecated
 */
function stripSlashesGPC($chaine) {
    pelican_import('Db');
    return Pelican_Db::stripSlashesGPC($chaine);
}

/**
 * @deprecated
 */
function stripSlashesSQL($chaine) {
    pelican_import('Db');
    return Pelican_Db::stripSlashesSQL($chaine);
}

/**
 * @deprecated
 */
function getAllowedExtensions() {
    pelican_import('Media');
    return Pelican_Media::getAllowedExtensions();
}

/**
 * @deprecated
 */
function _deprecated_arrayMin($tab) {
    pelican_import('Db');
    return Pelican_Db::arrayMin($tab);
}

/**
 * @deprecated
 */
function _deprecated_arrayToBind($aDataValues) {
    pelican_import('Db');
    return Pelican_Db::arrayToBind($aDataValues);
}

/**
 * @deprecated
 */
function _deprecated_log500($host, $content) {
    pelican_import('Db');
    return Pelican_Db::log500($host, $content);
}

/**
 * @deprecated
 */
function _deprecated_cleanOrder($value) {
    pelican_import('List');
    return Pelican_List::cleanOrder($value);
}

/**
 * @deprecated
 */
function _deprecated_array_sort($array, $field = "") {
    pelican_import('List');
    Pelican_List::array_sort($array, $field);
}
////////////////////////////////////


/**
 * @deprecated
 */
function _deprecated_makeList($dir) {
    pelican_import('Media');
    return Pelican_Media::makeList($dir);
}

/**
 * @deprecated
 */
function _deprecated_makeForm($readO = false) {
    pelican_import('Media');
    return Pelican_Media::makeForm($readO);
}

/**
 * @deprecated
 */
function _deprecated_makePreview($values = array(), $prop = true) {
    pelican_import('Media');
    return Pelican_Media::makePreview($values, $prop);
}

/**
 * @deprecated
 */
function _deprecated_makeMediaFormat($file = "", $title = "", $previewSize = array()) {
    pelican_import('Media');
    return Pelican_Media::makeMediaFormat($file, $title, $previewSize);
}

/**
 * @deprecated
 */
function _deprecated_getMediaPath($value = "") {
    pelican_import('Media');
    return Pelican_Media::getMediaPath($value);
}

/**
 * @deprecated
 */
function _deprecated_getMediaInfo($value = "") {
    pelican_import('Media');
    return Pelican_Media::getMediaInfo($value);
}

/**
 * @deprecated
 */
function _deprecated_folderAction() {
    pelican_import('Media');
    return Pelican_Media::folderAction();
}

/**
 * @deprecated
 */
function _deprecated_mediaAction() {
    pelican_import('Media');
    return Pelican_Media::mediaAction();
}

/**
 * @deprecated
 */
function _deprecated_getFlashSize($filename) {
    pelican_import('Media');
    return Pelican_Media::getFlashSize($filename);
}

/**
 * @deprecated
 */
function _deprecated_swfDecompress($buffer) {
    pelican_import('Media');
    return Pelican_Media::swfDecompress($buffer);
}

/**
 * @deprecated
 */
function _deprecated_getSearchFile($searchFile, $returnId = false) {
    pelican_import('Media');
    return Pelican_Media::getSearchFile($searchFile, $returnId);
}

/**
 * @deprecated
 */
function _deprecated_fileName($fileName, $ID, $subfolder = "") {
    pelican_import('Media');
    return Pelican_Media::fileName($fileName, $ID, $subfolder);
}

/**
 * @deprecated
 */
function _deprecated_getFileNameMediaFormat($path, $format, $extension = "") {
    pelican_import('Media');
    return Pelican_Media::getFileNameMediaFormat($path, $format, $extension);
}

/**
 * @deprecated
 */
function _deprecated_cleanDirectory($dir) {
    pelican_import('Media');
    return Pelican_Media::cleanDirectory($dir);
}

/**
 * @deprecated
 */
function _deprecated_getImageSizeMedia($file, $values = array()) {
    pelican_import('Media');
    return Pelican_Media::getImageSizeMedia($file, $values);
}

/**
 * @deprecated
 */
function _deprecated_fileMtimeMedia($file, $values = array()) {
    pelican_import('Media');
    return Pelican_Media::fileMtimeMedia($file, $values);
}

/**
 * @deprecated
 */
function _deprecated_fileSizeMedia($file, $values = array()) {
    pelican_import('Media');
    return Pelican_Media::fileSizeMedia($file, $values);
}

/**
 * @deprecated
 */
function _deprecated_createThumbnail($file, $values = array(), $altText = "") {
    pelican_import('Media');
    return Pelican_Media::createThumbnail($file, $values, $altText);
}

/**
 * @deprecated
 */
function _deprecated_fileDbExists($md5 = "", $size = "") {
    pelican_import('Media');
    return Pelican_Media::fileDbExists($md5, $size);
}

/**
 * @deprecated
 */
function _deprecated_checkMediaUsage($id) {
    pelican_import('Media');
    return Pelican_Media::checkMediaUsage($id);
}

/**
 * @deprecated
 */
function _deprecated_reduceText($texte, $size = "") {
    pelican_import('Media');
    return Pelican_Media::reduceText($texte, $size);
}

/**
 * @deprecated
 */
function _deprecated_getMediaId($path) {
    pelican_import('Media');
    return Pelican_Media::getMediaId($path);
}

/**
 * @deprecated
 */
function _deprecated_trigger($action, $values) {
    pelican_import('Media');
    return Pelican_Media::trigger($action, $values);
}

/**
 * @deprecated
 */
function _deprecated_triggerResearch($media, $action) {
    pelican_import('Media');
    return Pelican_Media::triggerResearch($media, $action);
}

/**
 * @deprecated
 */
function _deprecated_triggerSync($media, $host) {
    pelican_import('Media');
    return Pelican_Media::triggerSync($media, $host);
}

/**
 * @deprecated
 */
function _deprecated_triggerImageFormat($media, $format, $action) {
    pelican_import('Media');
    return Pelican_Media::triggerImageFormat($media, $format, $action);
}

/**
 * @deprecated
 */
function _deprecated_deleteFullFile($file) {
    pelican_import('Media');
    return Pelican_Media::deleteFullFile($file);
}

/**
 * @deprecated
 */
function _deprecated_getFlashPlayer($id, $media_id, $size = "1") {
    pelican_import('Media');
    return Pelican_Media::getFlashPlayer($id, $media_id, $size);
}

/**
 * @deprecated
 */
function _deprecated_cleanName($name) {
    pelican_import('Media');
    return Pelican_Media::cleanName($name);
}

/**
 * @deprecated
 */
function _deprecated_reduceSize($size, $max = "0") {
    pelican_import('Media');
    return Pelican_Media::reduceSize($size, $max);
}

/**
 * @deprecated
 */
function _deprecated_showTree($rubrique, $id, $type = "extjs", $complement = "", $options = array()) {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::showTree($rubrique, $id, $type, $complement, $options);
}

/**
 * @deprecated
 */
function _deprecated_initTree($dir = "", $rubrique, $rootlabel = " ", $type = "") {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::initTree($dir, $rubrique, $rootlabel, $type);
}

/**
 * @deprecated
 */
function _deprecated_initDbTree($dir = "", $rubrique, $rootlabel = " ") {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::initDbTree($dir, $rubrique, $rootlabel);
}

/**
 * @deprecated
 */
function _deprecated_initPhysicalTree($dir, $rubrique, $rootlabel = " ") {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::initPhysicalTree($dir, $rubrique, $rootlabel);
}

/**
 * @deprecated
 */
function _deprecated_getDir($rubrique, $idParent, $parentPath) {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::getDir($rubrique, $idParent, $parentPath);
}

/**
 * @deprecated
 */
function _deprecated_folderAllowed($folder) {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::folderAllowed($folder);
}

/**
 * @deprecated
 */
function _deprecated_execDefault($id, $type = "extjs") {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::execDefault($id, $type);
}

/**
 * @deprecated
 */
function _deprecated_addSearch($tree) {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::addSearch($tree);
}

/**
 * @deprecated
 */
function _deprecated_rightClick() {
    pelican_import('Media.Tree');
    return Pelican_Media_Tree::rightClick();
}
