<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
define('PLUGIN_CONFIG_FILE', 'config');
define('PLUGIN_ROOT', Pelican::$config["PLUGIN_ROOT"]);
define('PLUGIN_CONF_FILE', Pelican::$config["PLUGIN_ROOT"].'/plugins.ini.php');
define('PLUGIN_TABLE_PREFIX', Pelican::$config['FW_PREFIXE_TABLE']);
/*
 * if (isset($_GET["plugin"])) { define('PLUGIN_MEDIA_ROOT', PLUGIN_ROOT . "/" . $_GET["plugin"] . "/media"); }
 */

define('PLUGIN_ROUTE_BLOC', 'Cms_Page');
define('PLUGIN_ROUTE_CONTENT', 'Cms_Content');
define('PLUGIN_ROUTE_MODULE', 'Administration');
define('PLUGIN_ROUTE_NAVIGATION', 'Navigation');

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Plugin
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $js = array();

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $css = array();

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $id
     *                   (option) __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($id = "")
    {
        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function load()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $id
     *                     __DESC__
     *
     * @return __TYPE__
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * __DESC__.
     *
     *
     * @access public
     *
     * @param __TYPE__ $id
     *                        __DESC__
     * @param __TYPE__ $media
     *                        __DESC__
     * @param string   $host
     *                        (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getMediaPath($id)
    {
        return Pelican::$config["MEDIA_HTTP"]."/modules/".$id."/";
    }

    public static function getMediaRoot($id)
    {
        return Pelican::$config['MEDIA_ROOT']."/modules/".$id;
    }

    /**
     * __DESC__.
     *
     *
     * @access public
     *
     * @param __TYPE__ $id
     *                     __DESC__
     *
     * @return __TYPE__
     */
    public static function newInstance($id)
    {
        $files = glob(PLUGIN_ROOT."/".$id."/*.php");
        $class = str_replace(".php", "", basename($files[0]));
        if ($id == strtolower($class) && $files) {
            include_once $files[0];
            $class = "Module_".$class;
            if (phpversion() >= 5.3) {
                $obj = new $class($id);
            } else {
                eval("\$obj = new ".$class."(".$id.");");
            }
            if ($obj) {
                return $obj;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * __DESC__.
     *
     *
     * @access public
     *
     * @return __TYPE__
     */
    public static function getList()
    {
        self::controlPluginTable();

        $oConnection = Pelican_Db::getInstance();
        $oConnection->query("SELECT * FROM #pref#_plugin");
        $used = $oConnection->data["PLUGIN_ID"];
        $list = glob(PLUGIN_ROOT."/*/".PLUGIN_CONFIG_FILE);
        if ($list) {
            foreach ($list as $plugin) {
                $id = str_replace(PLUGIN_ROOT."/", "", dirname($plugin));
                $files = glob(PLUGIN_ROOT."/".$id."/*.php");
                $class = strtolower(str_replace(".php", "", basename($files[0])));
                if ($id == $class) {
                    $info = Pelican_Plugin::getInfo($id, $used);
                    $return[] = $info;
                }
            }
        }

        return $return;
    }

    public static function controlPluginTable()
    {
        $oConnection = Pelican_Db::getInstance();

        $oConnection->query("CREATE TABLE IF NOT EXISTS `#pref#_plugin` (
  `PLUGIN_ID` varchar(255) collate utf8_swedish_ci NOT NULL default '',
  `PLUGIN_LABEL` varchar(255) collate utf8_swedish_ci default NULL,
  `PLUGIN_VERSION` varchar(20) collate utf8_swedish_ci default NULL,
  `PLUGIN_MODULE` int(11) NOT NULL default '0',
  `PLUGIN_BLOC` int(11) NOT NULL default '0',
  `PLUGIN_CONTENT` int(11) NOT NULL default '0',
  `PLUGIN_NAVIGATION` int(11) NOT NULL default '0',
  `PLUGIN_INSTALL` int(11) default NULL,
  PRIMARY KEY  (`PLUGIN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci");
    }

    /**
     * __DESC__.
     *
     *
     * @access public
     *
     * @param __TYPE__ $id
     *                       __DESC__
     * @param bool     $used
     *                       (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getInfo($id, $used = false)
    {
        // $id = 'modele';
        $plugin = PLUGIN_ROOT."/".$id."/".PLUGIN_CONFIG_FILE;
        $tmp = (array) simplexml_load_file($plugin);
        $tmp["activated"] = 0;
        $tmp["id"] = $id;

        $cmd = "find ".PLUGIN_ROOT."/".$tmp["id"]." -type f -name \"*.php\" -ls | awk '{print $11\"#\";}' ";
        $handle = popen($cmd, 'r');
        $read = '';
        while (! feof($handle)) {
            $read .= str_replace("\n", "", fread($handle, 2096));
        }
        pclose($handle);
        $list = explode("#", $read);
        if (is_array($list)) {
            foreach ($list as $module) {
                $path = str_replace(PLUGIN_ROOT.'/'.$id.'/', '', $module);
                if (preg_match_all('/([^.]*)\/controllers\/([^.]*).php/', $path, $parts)) {
                    $route = str_replace('/', '_', $parts[2][0]);
                    $plugin_type = '';
                    if (substr_count(strtolower($route), strtolower($id.'_'.PLUGIN_ROUTE_NAVIGATION))) {
                        $plugin_type = "plugin_navigation";
                    }
                    if (substr_count(strtolower($route), strtolower($id.'_'.PLUGIN_ROUTE_BLOC))) {
                        $plugin_type = "plugin_bloc";
                    }
                    if (substr_count(strtolower($route), strtolower($id.'_'.PLUGIN_ROUTE_CONTENT))) {
                        $plugin_type = "plugin_content";
                    }
                    if (substr_count(strtolower($route), strtolower($id.'_'.PLUGIN_ROUTE_MODULE))) {
                        $plugin_type = "plugin_module";
                    }
                    if ($plugin_type) {
                        $tmp[$plugin_type] = 1;
                        $tmp['routes'][$plugin_type][$route][$parts[1][0]] = 1;
                    }
                }
            }
        }
        if ($used) {
            if (in_array($tmp["id"], $used)) {
                $tmp["activated"] = 1;
            }
        }
        if ($tmp["id"]) {
            $return = $tmp;
        }

        return $return;
    }

    /**
     * Génération du fichier de configuration des plugins (gestion des inclusions des
     * fichier de conf des plugins).
     *
     *
     * @access public
     *
     * @return __TYPE__
     */
    public static function setConf()
    {
        $oConnection = Pelican_Db::getInstance();
        $oConnection->query("SELECT PLUGIN_ID FROM #pref#_plugin");
        $array_plugins = array_flip($oConnection->data['PLUGIN_ID']);
        $file_content = "<?php\n/*\n** Fichier de configuration des plugins installés\n*/\n\n";
        $array_dir = glob(PLUGIN_ROOT.'/*', GLOB_ONLYDIR);
        if (is_array($array_dir)) {
            $file_content .= "\n".'/*'."\n".'** Inclusion des classes et des fichiers de configuration'."\n".'*/'."\n";
            foreach ($array_dir as $k => $dir_path) {
                $file_name = basename($dir_path);
                $file_path = PLUGIN_ROOT.'/'.$file_name.'/'.$file_name.'.ini.php';
                // $file_class_path = PLUGIN_ROOT.'/'.$file_name.'/'.$file_name.'.php';
                if (file_exists($file_path) && isset($array_plugins[$file_name])) {
                    $file_content .= 'include_once(Pelican::$config["PLUGIN_ROOT"].\''.str_replace(PLUGIN_ROOT, '', $file_path).'\');'."\n";
                    // $file_content .= 'include_once(Pelican::$config["PLUGIN_ROOT"].\''.str_replace(PLUGIN_ROOT,'',$file_class_path).'\');'."\n";
                }
            }
        }
        $file_content .= "\n?>";
        $fd = fopen(PLUGIN_CONF_FILE, 'w+');
        fwrite($fd, $file_content);
        fclose($fd);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param bool $activate
     *                       (option) __DESC__
     *
     * @return __TYPE__
     */
    public function activation($activate = true)
    {
        if ($activate) {
            $param = $this->getInfo($this->id);
            if ($param) {
                $files = $param['routes'];
                Pelican_Db::$values = array();
                Pelican_Db::$values["PLUGIN_ID"] = $this->id;
                Pelican_Db::$values["PLUGIN_LABEL"] = $param["title"];
                Pelican_Db::$values["ZONE_DESCRIPTION"] = $param["description"];
                Pelican_Db::$values["ZONE_COMPORTEMENT_FO"] = $param["comportement_fo"];
                Pelican_Db::$values["ZONE_COMPORTEMENT_BO"] = $param["comportement_bo"];

                /*
                 * copie du répertoire media
                 */
                if (!is_dir(Pelican::$config['MEDIA_ROOT']."/modules/")) {
                    mkdir(Pelican::$config['MEDIA_ROOT']."/modules/", 0777, true);
                }
                if (is_dir(PLUGIN_ROOT."/".$this->id."/public/")) {
                    system("ln -s ".PLUGIN_ROOT."/".$this->id."/public/ ".$this->getMediaRoot($this->id));
                }
                if ($files) {
                    $oConnection = Pelican_Db::getInstance();
                    $oConnection->replaceQuery("#pref#_plugin", "PLUGIN_ID='".$this->id."'");
                    foreach ($files as $type => $file) {
                        switch ($type) {
                            case "plugin_bloc":
                                {
                                    foreach ($file as $path => $values) {
                                        $tmp = explode('_', $path);
                                        $name = array_pop($tmp);
                                        $title = $param["title"].(count($file) > 0 ? " ".$name : "");
                                        Pelican_Db::$values["ZONE_ID"] = "-2";
                                        Pelican_Db::$values["ZONE_LABEL"] = "[plugin] ".$title;
                                        Pelican_Db::$values["ZONE_TYPE_ID"] = "1";
                                        Pelican_Db::$values["ZONE_CATEGORY_ID"] = "1";
                                        Pelican_Db::$values["ZONE_BO_PATH"] = '';
                                        Pelican_Db::$values["ZONE_FO_PATH"] = '';
                                        if ($values['backend']) {
                                            Pelican_Db::$values["ZONE_BO_PATH"] = $path;
                                        }
                                        if ($values['frontend']) {
                                            Pelican_Db::$values["ZONE_FO_PATH"] = $path;
                                        }
                                        if (empty(Pelican_Db::$values["ZONE_BO_PATH"])) {
                                            Pelican_Db::$values["ZONE_TYPE_ID"] = "2";
                                        }
                                        $oConnection->insertQuery("#pref#_zone");
                                        $oConnection->insertQuery("#pref#_zone_description");
                                        $code = strtoupper(trim(str_replace(strtolower(Pelican_Db::$values["PLUGIN_ID"].'_'.PLUGIN_ROUTE_BLOC), '', strtolower($path)), '_'));
                                        Pelican_Db::$values["PLUGIN_BLOC"][$code] = array(
                                            'id' => Pelican_Db::$values["ZONE_ID"],
                                            'title' => $title,
                                        );
                                    }
                                    break;
                                }

                            case "plugin_content":
                                {
                                    foreach ($file as $path => $values) {
                                        $tmp = explode('_', $path);
                                        $name = array_pop($tmp);
                                        $title = $param["title"].(count($file) > 0 ? " ".$name : "")." (contenu)";
                                        Pelican_Db::$values["TEMPLATE_ID"] = "-2";
                                        Pelican_Db::$values["TEMPLATE_LABEL"] = "[plugin] ".$title;
                                        Pelican_Db::$values["TEMPLATE_TYPE_ID"] = "3";
                                        // contenu
                                        Pelican_Db::$values["TEMPLATE_GROUP_ID"] = "2";
                                        if ($values['backend']) {
                                            Pelican_Db::$values["TEMPLATE_PATH"] = $path;
                                        }
                                        if ($values['frontend']) {
                                            Pelican_Db::$values["TEMPLATE_PATH_FO"] = $path;
                                        }
                                        $oConnection->insertQuery("#pref#_template");
                                        $code = strtoupper(trim(str_replace(strtolower(Pelican_Db::$values["PLUGIN_ID"].'_'.PLUGIN_ROUTE_CONTENT), '', strtolower($path)), '_'));
                                        Pelican_Db::$values["PLUGIN_CONTENT"][$code] = array(
                                            'id' => Pelican_Db::$values["TEMPLATE_ID"],
                                            'title' => $title,
                                        );
                                    }
                                    break;
                                }

                            case "plugin_module":
                                {
                                    foreach ($file as $path => $values) {
                                        $tmp = explode('_', $path);
                                        $name = array_pop($tmp);
                                        $title = $param["title"].(count($file) > 0 ? " ".$name : "");
                                        Pelican_Db::$values["TEMPLATE_ID"] = "-2";
                                        Pelican_Db::$values["TEMPLATE_LABEL"] = "[plugin] ".$title;
                                        Pelican_Db::$values["TEMPLATE_TYPE_ID"] = "1";
                                        // administration
                                        Pelican_Db::$values["TEMPLATE_GROUP_ID"] = "1";
                                        if ($values['backend']) {
                                            Pelican_Db::$values["TEMPLATE_PATH"] = $path;
                                        }
                                        if ($values['frontend']) {
                                            Pelican_Db::$values["TEMPLATE_PATH_FO"] = $path;
                                        }
                                        $oConnection->insertQuery("#pref#_template");
                                        $code = strtoupper(trim(str_replace(strtolower(Pelican_Db::$values["PLUGIN_ID"].'_'.PLUGIN_ROUTE_MODULE), '', strtolower($path)), '_'));
                                        Pelican_Db::$values["PLUGIN_MODULE"][$code] = array(
                                            'id' => Pelican_Db::$values["TEMPLATE_ID"],
                                            'title' => $title,
                                        );
                                    }
                                    break;
                                }
                            case "plugin_navigation":
                                {
                                    foreach ($file as $path => $values) {
                                        $title = $param["title"]." (navigation)";
                                        Pelican_Db::$values["TEMPLATE_ID"] = "-2";
                                        Pelican_Db::$values["TEMPLATE_LABEL"] = "[plugin] ".$title;
                                        Pelican_Db::$values["TEMPLATE_TYPE_ID"] = "2";
                                        // navigation
                                        Pelican_Db::$values["TEMPLATE_GROUP_ID"] = "3";
                                        Pelican_Db::$values["TEMPLATE_PATH"] = $path;
                                        Pelican_Db::$values["TEMPLATE_PATH_FO"] = "";
                                        $oConnection->insertQuery("#pref#_template");
                                        $code = strtoupper(trim(str_replace(strtolower(Pelican_Db::$values["PLUGIN_ID"].'_'.PLUGIN_ROUTE_NAVIGATION), '', strtolower($path)), '_'));
                                        Pelican_Db::$values["PLUGIN_NAVIGATION"][$code] = array(
                                            'id' => Pelican_Db::$values["TEMPLATE_ID"],
                                            'title' => $title,
                                        );
                                    }
                                    break;
                                }
                        }
                    }

                    $this->install();
                    Pelican_Cache::clean("Template", array());
                    Pelican_Cache::clean("Backend/Template", array());
                    Pelican_Cache::clean("Database/Describe/Table", array());
                    // Génération du fichier de configuration des plugins (gestion des inclusions des fichier de conf des plugins)
                    // Pelican_Plugin::setConf();
                    $return = array(
                        "navigation" => Pelican_Db::$values["PLUGIN_NAVIGATION"],
                        "bloc" => Pelican_Db::$values["PLUGIN_BLOC"],
                        "module" => Pelican_Db::$values["PLUGIN_MODULE"],
                        "content" => Pelican_Db::$values["PLUGIN_CONTENT"],
                    );

                    Pelican_Db::$values["PLUGIN_NAVIGATION"] = (Pelican_Db::$values["PLUGIN_NAVIGATION"] ? 1 : 0);
                    Pelican_Db::$values["PLUGIN_BLOC"] = (Pelican_Db::$values["PLUGIN_BLOC"] ? 1 : 0);
                    Pelican_Db::$values["PLUGIN_MODULE"] = (Pelican_Db::$values["PLUGIN_MODULE"] ? 1 : 0);
                    Pelican_Db::$values["PLUGIN_CONTENT"] = (Pelican_Db::$values["PLUGIN_CONTENT"] ? 1 : 0);
                    $oConnection->replaceQuery("#pref#_plugin", "PLUGIN_ID='".$this->id."'");

                    return $return;
                }
            }
        } else {

            /*
             * désinstallation
             */
            $oConnection = Pelican_Db::getInstance();
            $aBind[":PLUGIN_ID"] = $oConnection->strToBind($this->id);
            $infos = $oConnection->queryRow("select * from #pref#_plugin where PLUGIN_ID=:PLUGIN_ID", $aBind);

            // table Zone
            $oConnection->query("select * from #pref#_zone where PLUGIN_ID=:PLUGIN_ID", $aBind);
            if (is_array($oConnection->data["ZONE_ID"])) {
                $zones_id = implode(",", $oConnection->data["ZONE_ID"]);
                if ($zones_id) {
                    $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID in (select ZONE_TEMPLATE_ID from #pref#_zone_template where ZONE_ID in (".$zones_id."))");
                    $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID in (select ZONE_TEMPLATE_ID from #pref#_zone_template where ZONE_ID in (".$zones_id."))");
                    $oConnection->query("delete from #pref#_zone_template where ZONE_ID in (".$zones_id.")");
                    // Cette table n'existe pas sur l'éducation
                    $oConnection->query("delete from #pref#_zone_area where ZONE_ID in (".$zones_id.")");
                    $oConnection->query("delete from #pref#_zone_description where ZONE_ID in (".$zones_id.")");
                    $oConnection->query("delete from #pref#_zone where ZONE_ID in (".$zones_id.")");
                }
            }
            // table Template
            $oConnection->query("select * from #pref#_template where PLUGIN_ID=:PLUGIN_ID", $aBind);
            if (is_array($oConnection->data["TEMPLATE_ID"])) {
                $templates_id = implode(",", $oConnection->data["TEMPLATE_ID"]);
                if ($templates_id) {
                    /*
                     * champs mis à vide
                     */
                    $oConnection->query("update #pref#_content_template set TEMPLATE_ID=NULL where TEMPLATE_ID in (".$templates_id.")");
                    $oConnection->query("update #pref#_content_type set TEMPLATE_ID=NULL where TEMPLATE_ID in (".$templates_id.")");

                    /*
                     * tables à vider
                     */
                    $oConnection->query("update #pref#_directory set DIRECTORY_PARENT_ID = null where TEMPLATE_ID in (".$templates_id.")");
                    $oConnection->query("delete from #pref#_profile_directory where DIRECTORY_ID in (select DIRECTORY_ID from #pref#_directory where TEMPLATE_ID in (".$templates_id."))");
                    $oConnection->query("delete from #pref#_directory_site where DIRECTORY_ID in (select DIRECTORY_ID from #pref#_directory where TEMPLATE_ID in (".$templates_id."))");
                    $oConnection->query("delete from #pref#_directory where TEMPLATE_ID in (".$templates_id.")");
                    $oConnection->query("delete from #pref#_template_site where TEMPLATE_ID in (".$templates_id.")");
                    $oConnection->query("delete from #pref#_template where TEMPLATE_ID in (".$templates_id.")");
                }
            }
            // table plugin
            $oConnection->query("delete from #pref#_plugin where PLUGIN_ID=:PLUGIN_ID", $aBind);
            // Suppression du répertoire média -- Ajouté par Gildas le 25/01/2008
            if ($media_dir) {
                system("rm -f ".$this->getMediaRoot($this->id));
            }
            $this->uninstall();
            Pelican_Cache::clean("Template", array());
            Pelican_Cache::clean("Backend/Template.php", array());
            Pelican_Cache::clean("Database/Describe/Table", array());
            // Génération du fichier de configuration des plugins (gestion des inclusions des fichier de conf des plugins)
            // Pelican_Plugin::setConf();
        }
        // Pelican_Cache::clean("Plugins");

        return true;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function install()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function uninstall()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function update()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function decache()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function processus()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __destruct()
    {
    }
    /*
     * * Méthode d'inclusion des Css en Front
     */

    /**
     * Méthode d'hameçonnage pour gérer les cas spéciaux du Pelican_Plugin pour les
     * médias.
     *
     * @access public
     *
     * @param __TYPE__ $element
     *                          __DESC__
     * @param __TYPE__ $oForm
     *                          __DESC__
     * @param __TYPE__ $value
     *                          __DESC__
     *
     * @return string
     */
    public function hook($element, &$oForm, $value)
    {
        $form = '';
        if (is_array(Pelican::$config['PLUGIN_HOOK'][$element])) {
            foreach (Pelican::$config['PLUGIN_HOOK'][$element] as $pluginName => $isUsed) {
                if ($isUsed) {
                    include_once Pelican::$config["PLUGIN_ROOT"].'/'.$pluginName.'/'.$pluginName.'.php';
                    $form .= call_user_method($element.'PluginHook', $pluginName, $oForm, $value); // PLA20130118 : suppression du & à &$oForm // $form.= call_user_method($element . 'PluginHook', $pluginName, &$oForm, $value);
                }
            }
        }

        return $form;
    }
}
