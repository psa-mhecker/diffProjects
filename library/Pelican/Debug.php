<?php
/**
 * Cette librairie permet d'afficher le contenu de n'importe quelle variable, dans
 * le cadre d'un debuggage.
 *
 * @package Pelican
 * @subpackage Debug
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 * @since 04/08/2000
 */
define('DEBUGDIRECT', 0);

include (dirname(__FILE__) . '/Debug/Plugin/Interface.php');

/**
 * Fonction permettant d'afficher la valeur d'une variable dans le cadre d'un
 * debuggage
 *
 * @param mixed $debugVar La variable à afficher
 * @param string $debugName (option) Le nom de la variable, ou tout autre valeur
 * permettant d'identifier le debug à l'affichage : "&nbsp;" par défaut
 * @param bool $directOutput (option) __DESC__
 * @param __TYPE__ $excludeFile (option) __DESC__
 * @param bool $full (option) __DESC__
 * @return void
 */
function debug($debugVar, $debugName = "", $directOutput = false, $excludeFile = array(), $full = true) {
    $return = Pelican_Debug::add($debugVar, $debugName, $directOutput, $excludeFile, $full);
    if ($directOutput) {
        echo $return;
    } else {
        return $return;
    }
}

/**
 * __DESC__
 *
 * @param __TYPE__ $debugVar __DESC__
 * @param string $debugName (option) __DESC__
 * @return __TYPE__
 */
function directdebug($debugVar, $debugName = "") {
    debug($debugVar, $debugName, true);
}

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Debug
 * @author Guillaume Gageonnet <ggageonnet@businessdecision.com>
 */
class Pelican_Debug {
    
    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    static $showDebug = false;
    
    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    static $nbDebug = 0;
    
    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    static $debugItem = array();

    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    static $excludeFile = array('Gpcs.php', 'Debug.php');
    
    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    static $style = "font:Verdana,Helvetica,Arial;font-size:11px;color:black";
    //'security'
    //'xdebug'
    
    
    /**
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $_options = array('plugins' => array('version', 'debug', 'gpcs', 'view', 'memory', 'sysinfo', 'file', 'time', 'cache'), 'z-index' => 255, 'image_path' => '/library/Pelican/Debug/public/images');
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $debugVar __DESC__
     * @param __TYPE__ $debugName __DESC__
     * @param __TYPE__ $origine __DESC__
     * @param bool $full (option) __DESC__
     * @return __TYPE__
     */
    static function buildDebug($debugVar, $debugName, $origine, $full = true) {
        if ($full) {
            $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#9999CC", align => "center", colspan => "2"), "DEBUG N&deg; " . self::$nbDebug));
            //nom
            if (!empty($debugName)) {
                $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#CCCCFF", align => "left"), 'Name') . Pelican_Html::td(array(bgcolor => "#CCCCCC", align => "left"), $debugName));
            }
            if (!empty($origine)) {
                //origine
                $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#CCCCFF", align => "left"), "Origine") . Pelican_Html::td(array(bgcolor => "#CCCCCC", align => "left"), $origine));
            }
            //type
            $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#CCCCFF", align => "left"), 'Type') . Pelican_Html::td(array(bgcolor => "#CCCCCC", align => "left"), self::getType(gettype($debugVar))));
            //valeur
            $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#CCCCFF", align => "left"), "Valeur") . Pelican_Html::td(array(bgcolor => "#CCCCCC", align => "left"), self::showVar($debugVar)));
            $table = Pelican_Html::table(array(style => self::$style, bgcolor => "#000", border => "0", cellspacing => "1", cellpadding => "4"), implode("", $tr));
        } else {
            $table = self::showVar($debugVar);
        }
        return $table;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $debugVar __DESC__
     * @param string $debugName (option) __DESC__
     * @param bool $directOutput (option) __DESC__
     * @param __TYPE__ $excludeFile (option) __DESC__
     * @param bool $full (option) __DESC__
     * @return __TYPE__
     */
    static function add($debugVar, $debugName = "", $directOutput = false, $excludeFile = array(), $full = true) {
        $origine = '';
        if ($excludeFile) {
            if (!is_array($excludeFile)) {
                $excludeFile = array($excludeFile);
            }
            
            /**
             * @static
             * @access public
             * @var __TYPE__ __DESC__
             */
            self::$excludeFile = array_merge(self::$excludeFile, $excludeFile);
        }
        if (self::$showDebug) {
            $backtrace = debug_backtrace();
            $i = 1;
            if (self::$excludeFile) {
                // on ne remonte pas plus de 4 niveaux, pas nécessaire
                for ($k = 1;$k < 4;$k++) {
                    if (!empty($backtrace[$k]["file"])) {
                        if (in_array(basename($backtrace[$k]["file"]), self::$excludeFile)) {
                            $i = $k + 1;
                        }
                    }
                }
            }
            if (!empty($backtrace[$i]["file"])) {
                if ($backtrace[$i]["file"] && $backtrace[$i]["file"] != __FILE__) {
                    $origine = $backtrace[$i]["file"];
                    $origine2 = array();
                    if (!empty($backtrace[$i + 1]["class"])) {
                        $origine2[] = $backtrace[$i + 1]["class"] . "::";
                    }
                    if (!empty($backtrace[$i + 1]["function"])) {
                        $origine2[] = $backtrace[$i + 1]["function"];
                    }
                    if (!empty($backtrace[$i + 1]["line"])) {
                        $origine2[] = " ligne " . $backtrace[$i + 1]["line"];
                    }
                    if (!empty($origine2)) {
                        $origine.= " (" . implode("", $origine2) . ")";
                    }
                }
            }
            self::$nbDebug++;
            // affichage du debut du tableau
            /* if ($debugName != 'POST' && $debugName != 'GET' && $debugName != 'COOKIE' && $debugName != 'SESSION') {
            $debugName = "DEBUG N&deg; " . self::$nbDebug . ($debugName ? ' - ' . $debugName : "");
            }*/
            $return = self::buildDebug($debugVar, $debugName, $origine, $full);
            if (!(DEBUGDIRECT || $directOutput)) {
                self::$debugItem[] = $return;
            }
            return $return;
        }
    }
    
    /**
     * Fonction permettant d'afficher une variable suivant son type
     *
     * @static
     * @access public
     * @param mixed $val La variable à afficher
     * @return void
     */
    static function showVar($val) {
        // suivant le type de la variable, on affiche celle-ci
        $return = "";
        switch (gettype($val)) {
            case "array": {
                        $return.= self::showArrayVar($val);
                    break;
                }
            case "object": {
                    $return.= self::showArrayVar((array)$val, "attribut");
                    break;
                }
            default: {
                    $color = "black";
                    if (!isset($val)) {
                        $color = "purple";
                        $ret = "---> La variable n'est pas d&eacute;finie";
                    } elseif ($val === false) {
                        $ret = "[false]";
                    } elseif ($val === true) {
                        $ret = "[true]";
                    } elseif ($val === "") {
                        $color = "purple";
                        $ret = "---> La variable est vide";
                    } elseif ($val == "") {
                        $color = "purple";
                        $ret = "---> Chaine de caractère vide";
                    } else {
                        if (is_resource($val)) {
                            $val = 'Ressource de type "' . get_resource_type($val) . '"';
                        }
                        $val = Pelican_Text::htmlentities((string)$val);
                        $ret = Pelican_Html::b($val);
                    }
                    $return.= Pelican_Html::span(array(style => "color:" . $color), $ret);
                }
            }
            return $return;
        }
        
        /**
         * Fonction permettant d'afficher un tableau
         *
         * @static
         * @access public
         * @param string $tab Le tableau à afficher
         * @param string $nomIndice (option) Intitulé à donner aux indices du tableau.
         * Cette fonction étant utilisé aussi pour l'affichage 'casté' des objets, on
         * peut ainsi demander à ce que cet intitulé soit "attribut" : "indice" par
         * défaut
         * @return void
         */
        static function showArrayVar($tab, $nomIndice = "indice") {
            $return = "";
            if (count($tab) > 0) {
                $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#9999CC", align => "center"), $nomIndice) . Pelican_Html::td(array(bgcolor => "#9999CC", align => "center"), "valeur"));
                // affichage de tous les indices
                reset($tab);
                while (list($key, $val) = each($tab)) {
                    $tr[] = Pelican_Html::tr(Pelican_Html::td(array(bgcolor => "#CCCCFF", align => "left"), $key) . Pelican_Html::td(array(bgcolor => "#CCCCCC", align => "left"), self::showVar($val)));
                }
                $table = Pelican_Html::table(array(border => "0", cellspacing => "1", cellpadding => "5"), implode("", $tr));
                $return = Pelican_Html::table(array(border => "0", cellspacing => "0", cellpadding => "0", bgcolor => "#000", style => self::$style), Pelican_Html::tr(Pelican_Html::td($table)));
            } else {
                $color = "purple";
                $ret = "---> Le tableau est vide";
                $return.= Pelican_Html::span(array(style => "color:" . $color), $ret);
            }
            return $return;
        }
        
        /**
         * Fonction permettant d'afficher le type d'une variable en français
         *
         * @static
         * @access public
         * @param string $type Le type de la variable (renvoyé par la fonction PHP
         * gettype())
         * @return string
         */
        static function getType($type) {
            switch ($type) {
                case "boolean": {
                            return "bool&eacute;en";
                        break;
                    }
                case "integer": {
                        return "entier";
                        break;
                    }
                case "double": {
                        return "double";
                        break;
                    }
                case "string": {
                        return "cha&icirc;ne de caract&egrave;res";
                        break;
                    }
                case "array": {
                        return "tableau";
                        break;
                    }
                case "object": {
                        return "objet";
                        break;
                    }
                case "resource": {
                        return "ressource";
                        break;
                    }
                case "user function": {
                        return "fonction utilisateur";
                        break;
                    }
                default: {
                        return "type inconnu : " . $type;
                    }
                }
            }
            
            /**
             * __DESC__
             *
             * @access public
             * @return __TYPE__
             */
            function __destruct() {
                flush();
                Pelican_Profiler::stop('[global]');
                if (Pelican::$config["SHOW_DEBUG"] && self::$showDebug && count(self::$debugItem)) {
                    $return = "";
                    $return = $this->_getHeader();
                    $return.= $this->_getBody();
                    echo $return;
                }
            }
            
            /**
             * __DESC__
             *
             * @access protected
             * @return __TYPE__
             */
            protected function _getBody() {
                $pluginPanel = '';
                foreach($this->_options['plugins'] as $plug) {
                    include (dirname(__FILE__) . '/Debug/Plugin/' . ucFirst($plug) . '.php');
                    $class = new ReflectionClass('Pelican_Debug_Plugin_' . ucFirst($plug));
                    // Create a new instance of the controller
                    $plugin[$plug] = $class->newInstance($this);
                    $panel = $plugin[$plug]->getPanel();
                    if ($panel == '') {
                        continue;
                    }
                    $pluginPanel.= '<div id="PDebug_' . $plugin[$plug]->getIdentifier() . '" class="PDebug_panel">' . $panel . '</div>';
                }
                $html = '<div id="PDebug">';
                $html.= '<span id="PDebug_plugins">';
                $html.= '<div class="PDebug_span clickable" onclick="document.location.href=document.location.href;">';
                $html.= '<img src="' . $this->_options['image_path'] . '/reload.png" style="vertical-align:middle" alt="Rechargement" title="Rechargement" /></div>';
                foreach($this->_options['plugins'] as $plug) {
                    $tab = $plugin[$plug]->getTab();
                    if ($tab == '') {
                        continue;
                    }
                    $html.= '<div class="PDebug_span clickable" onclick="PDebugPanel(\'PDebug_' . $plugin[$plug]->getIdentifier() . '\');">';
                    $html.= '<img src="' . $this->_options['image_path'] . '/' . ($plugin[$plug]->getIdentifier()) . '.png" style="vertical-align:middle" alt="' . $plugin[$plug]->getIdentifier() . '" title="' . $plugin[$plug]->getIdentifier() . '" /> ';
                    $html.= $tab . '</div>';
                }
                $html.= '</span>';
                $html.= '<div class="PDebug_span PDebug_last clickable" id="PDebug_toggler" onclick="PDebugSlideBar()">&#171;</div>';
                $html.= $pluginPanel;
                $html.= '</div>';
                return $html;
            }
            
            /**
             * Returns Pelican_Html header for the Debug Bar
             *
             * @access protected
             * @return string
             */
            protected function _getHeader() {
                return ('
            <style type="text/css" media="screen">
#PDebug{color:#000;margin-left:1px;margin-top:1px;position:absolute;top:0;z-index:99999;}
#PDebug_plugins{font:11px/1.4em Lucida Grande, Lucida Sans Unicode, sans-serif;white-space:nobr;}
#PDebug_toggler{background:#BFBFBF;border:1px solid #999;font-weight:700;max-height:25px;padding:8px 7px 8px 7px;}
#PDebug img {width:auto;height:auto;min-width:10px;}
.clickable{cursor:pointer;}
.PDebug_span{background:#DFDFDF;border-bottom:1px solid #999;border-left:1px solid #999;border-top:1px solid #999;float:left;max-height:25px;padding:8px 5px 5px 7px;}
.PDebug_panel{background:#E8E8E8;border:1px solid #999;clear:both;display:none;margin-left:1px;margin-top:1px;max-height:400px;overflow:auto;padding:5px;text-align:left;width:700px;}
.PDebug_panel .pre{font:11px/1.4em Monaco, Lucida Console, monospace;margin:0 0 0 25px;}
.PDebug_panel label{width:200px;}
.PDebug_panel .right{text-align:right;}
.PDebug_panel .alert{color:red;}
.PDebug_panel legend{font-weight:700;}
.PDebug_panel table{margin:0;padding:2px;width:100%;}
.PDebug_panel table th,.PDebug_panel table td{border:1px solid #ccc;}
.PDebug_panel table th{background-color:#bfbfbf;}
.PDebug_panel > fieldset{padding:5px;text-align:left;width:95%;}
#PDebug_exception{border:1px solid #CD0A0A;display:block;}
            </style>
            <script type="text/javascript">
            	var activeDebug;    
            	function PDebugPanel(name) {
            		if (activeDebug) {
                    	var old = document.getElementById(activeDebug);
                    	old.style.display = "none";
                    }
                    var obj = document.getElementById(name);
                    if (activeDebug != name) {
                    	activeDebug = name;
	
                    	if (obj.style.display == "block") {
	                    	obj.style.display = "none";
    					} else {
	                    	obj.style.display = "block";
                    	}
                     } else {
                     	activeDebug = "";
                     }      
                }

                function PDebugSlideBar() {
                	
                	var bar = document.getElementById("PDebug_plugins");
                	var toggle = document.getElementById("PDebug_toggler");
                	
                	if (activeDebug) {
                    	var old = document.getElementById(activeDebug);
                    	old.style.display = "none";
                    	activeDebug = null;
                    }
                	
                	if (bar.style.display == "none") {
                		bar.style.display = "";
                		toggle.innerHTML = "&#171;";
                	} else {
                		bar.style.display = "none";
                		toggle.innerHTML = "&#187;";
    				}
                }

                function PDebugToggleElement(name, whenHidden, whenVisible){

                	var div = document.getElementById(name);
                    var hidden = document.getElementById(whenHidden);
                    var visible = document.getElementById(whenVisible);
                                	
                	if (div.style.display == "none") {
                		visible.style.display = "";
                		hidden.style.display = "none";
    				} else {
                		visible.style.display = "none";
                		hidden.style.display = "";
    				}                
                }
            </script>');
            }
            
            /**
             * Sets options of the Debug Bar
             *
             * @access public
             * @param array $options (option) __DESC__
             * @return PDebug_Controller_Plugin_Debug
             */
            public function setOptions(array $options = array()) {
                if (isset($options['z-index'])) {
                    $this->_options['z-index'] = $options['z-index'];
                }
                if (isset($options['image_path'])) {
                    $this->_options['image_path'] = $options['image_path'];
                }
                if (isset($options['plugins'])) {
                    $this->_options['plugins'] = $options['plugins'];
                }
                return $this;
            }
            
            /**
             * __DESC__
             *
             * @static
             * @access public
             * @param bool $echo (option) __DESC__
             * @return __TYPE__
             */
            public static function debug_trace($echo = false) {
                $output = "<div style='text-align: left; font-family: monospace;'>\n";
                $output.= "<b>Traces:</b><br />\n";
                $backtrace = debug_backtrace();
                array_shift($backtrace);
                foreach($backtrace as $bt) {
                    if ($bt['class'] . $bt['type'] . $bt['function'] != 'Dbfw->error') {
                        $args = '';
                        foreach($bt['args'] as $a) {
                            if (!empty($args)) {
                                $args.= ', ';
                            }
                            switch (gettype($a)) {
                                case 'integer':
                                case 'double':
                                    $args.= $a;
                                break;
                                case 'string':
                                    $a = htmlspecialchars(substr($a, 0, 64)) . ((strlen($a) > 64) ? '...' : '');
                                    $args.= "\"$a\"";
                                break;
                                case 'array':
                                    $args.= 'Array(' . count($a) . ')';
                                break;
                                case 'object':
                                    $args.= 'Object(' . get_class($a) . ')';
                                break;
                                case 'resource':
                                    $args.= 'Resource(' . strstr($a, '#') . ')';
                                break;
                                case 'boolean':
                                    $args.= ($a ? 'True' : 'False');
                                break;
                                case 'NULL':
                                    $args.= 'Null';
                                break;
                                default:
                                    $args.= 'Unknown';
                            }
                        }
                        $output.= "<br />\n";
                        $output.= "<b>Ligne - Fichier:</b> {$bt['line']} - {$bt['file']}<br />\n";
                        $output.= "<b>Appel:</b> {$bt['class']}{$bt['type']}{$bt['function']}($args)<br />\n";
                    }
                }
                $output.= "</div>\n";
                if ($echo) {
                    echo $output;
                } else {
                    return $output;
                }
                /*$aParams = func_get_args();
                if ($aParams) {
                call_user_func_array("debug",$aParams);
                }*/
            }
            
            /**
             * __DESC__
             *
             * @static
             * @access public
             * @param __TYPE__ $legend __DESC__
             * @param __TYPE__ $text __DESC__
             * @return __TYPE__
             */
            public static function getFieldset($legend, $text) {
                $return = '<fieldset><legend>' . $legend . '</legend>';
                $return.= $text;
                $return.= '</fieldset>';
                return $return;
            }
            
            /**
             * __DESC__
             *
             * @static
             * @access public
             * @param __TYPE__ $values __DESC__
             * @return __TYPE__
             */
            public static function getTable($values) {
                $return = '';
                if (is_array($values)) {
                    $width = round(100 / count($values));
                    foreach($values as $key => $val) {
                        $th[] = '<th width="' . $width . '%">' . $key . '</th>';
                        $td[] = '<td>' . $val . '</td>';
                    }
                    $return = '<table><tr>' . implode('', $th) . '</tr><tr>' . implode('', $td) . '</tr></table>';
                }
                return $return;
            }
        }
        function initDebug() {
            global $oDEBUG;
            if (!isset($oDEBUG)) {
                $oDEBUG = new Pelican_Debug();
                return $oDEBUG;
            } else {
                return $oDEBUG;
            }
        }
        if (Pelican::$config["SHOW_DEBUG"]) {
            Pelican_Debug::$showDebug = true;
            initDebug();
        }
?>
