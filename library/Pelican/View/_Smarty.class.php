<?
class Pelican_View_Smarty implements Zend_View_Interface
{

    /**
     * Smarty object
     * @var Smarty
     */
    protected $_smarty;

    /**
     * Constructor
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct ($tmplPath = null, $extraParams = array())
    {
        // default values
        

        if (! isset($extraParams['compile_check'])) {
            $extraParams['compile_check'] = true;
        }
        if (! isset($extraParams['caching'])) {
            $extraParams['caching'] = true;
        }
        if (! isset($extraParams['cache_dir'])) {
            $extraParams['cache_dir'] = true;
        }
        
        $this->_smarty = new Smarty();
        
        if (null !== $tmplPath) {
            $this->setScriptPath($tmplPath);
        }
        
        foreach ($extraParams as $key => $value) {
            $this->_smarty->$key = $value;
        }
    }

    /**
     * Return the template engine object
     *
     * @return Smarty
     */
    public function getEngine ()
    {
        return $this->_smarty;
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath ($path)
    {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        }
        
        throw new Exception('Invalid path provided');
    }

    /**
     * Retrieve the current template directory
     *
     * @return string
     */
    public function getScriptPaths ()
    {
        return array($this->_smarty->template_dir);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function setBasePath ($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function addBasePath ($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * Assign a variable to the template
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set ($key, $val)
    {
        $this->_smarty->assign($key, $val);
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset ($key)
    {
        return (null !== $this->_smarty->get_template_vars($key));
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset ($key)
    {
        $this->_smarty->clear_assign($key);
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or
     * array of key => value pairs)
     * @param mixed $value (Optional) If assigning a named variable,
     * use this as the value.
     * @return void
     */
    public function assign ($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }
        
        $this->_smarty->assign($spec, $value);
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Zend_View either via
     * {@link assign()} or property overloading
     * ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars ()
    {
        $this->_smarty->clear_all_assign();
    }

    /**
     * Processes a template and returns the output.
     *
     * @param string $name The template to process.
     * @return string The output.
     */
    public function render ($name)
    {
        return $this->_smarty->fetch($name);
    }

    /**
     * Génération de l'id de Pelican_Cache de la vue
     *
     * @static
     * @access public
     * @param string $cacheId (option) Id de Pelican_Cache
     * @return string
     */
    static function getCacheId ($cacheId = "", $lang = "")
    {
        
        
        $return = $cacheId;
        
        if (is_array($return)) {
            ksort($return);
            $return = implode("|", $return);
        }
        $return .= "|" . $lang . "|" . ($_GET ? serialize($_GET) : "");
        
        $return = str_replace(array("||" , "{" , "}" , ";" , ":" , "\""), array("|" , "" , "" , "" , "" , ""), $return);
        
        return $return;
    }
}

/**
 * Fonction générique d'include d'un bloc utilisant un template Smarty (gestion
 * du Pelican_Cache ou non et des paramètres variants ou invariants permettant d'identifier le cache)
 *
 * @param string $file Chemin physique du script à inclure et à cacher si
 * nécessaire
 * @param string $enableCache (option) Mise en Pelican_Cache ou non
 * @param mixed $cacheId (option) Paramètres du Pelican_Cache
 * @param string $data (option) Valeurs des champs associés à la Pelican_Index_Frontoffice_Zone
 * @param int $lifetime (option) Durée de vie du Pelican_Cache
 * @param bool $directOutput (option) affiche sur l'écran direct ou non
 * @return void
 */
function partialView ($file, $enableCache = false, $cacheId = "", $data = "", $lifetime = 30, $directOutput = true)
{
    
    /** récuperation des objets smarty et connection */
    
    $indice = $file;
    Pelican::$trace['time'][$indice] = getmicrotime();
    $view = Pelican_Factory::getView();
    
    /**
     * parametrage de smarty pour le bloc
     * $caching true or false
     * $cache_lifetime durée en seconde du Pelican_Cache
     * -1 = Illimitée
     */
    $view->caching = ($enableCache ? 2 : 0);
    $view->cache_lifetime = $lifetime;
    
    /** recuperation du nom du template en fonction du nom du php */
    
    /** ajout plug in */
    if ($data["PLUGIN_ID"]) {
        $file = str_replace("//", "/", str_replace("/layout", "", $file));
        $template = Pelican::$config["PLUGIN_ROOT"] . "/" . str_replace(".php", ".tpl", $file);
        $file = Pelican::$config["PLUGIN_ROOT"] . "/" . $file;
    } elseif ($data["FORCED_ROOT"]) {
        $template = $data["FORCED_ROOT"] . "/views/scripts/" . str_replace(".php", ".tpl", $file);
        $file = $data["FORCED_ROOT"] . "/controllers/" . $file;
    } else {
        $template = Pelican::$config["VIEWS_ROOT"] . "/" . strtok($file, ".") . ".tpl";
        $file = Pelican::$config["CONTROLLERS_ROOT"] . "/" . $file;
    }
    
    /** traitement des param des application/caches // creation des groupes de Pelican_Cache
     * structure du tableau
     */
    $cacheId = Pelican_View::getCacheId($cacheId);
    if (Pelican::$config["SHOW_DEBUG"] && $_GET["template"]) {
        echo (Pelican_Html::pre($file));
    }
    
    /** appel du fichier php si le templates est pas en Pelican_Cache // avec test ou pas sur l'idcache */
    if ($cacheId) {
        $cacheId = md5($cacheId);
        if (! $view->is_cached($template, $cacheId)) {
            if (file_exists($file)) {
                include($file);
            }
        }
    } else {
        if (! $view->is_cached($template)) {
            include($file);
        }
    }
    
    /** renvois du contenus de la template et creation du fichier Pelican_Cache si smarty est a caching=true */
    if ($cacheId) {
        $output = $view->fetch($template, $cacheId);
    } else {
        $output = $view->fetch($template);
    }
    if ($directOutput) {
        echo $output;
    } else {
        return $output;
    }
    
    /** remise a false du Pelican_Cache pour le bloc suivant #peut etre inutile vus que le param de Pelican_Cache est par defaut false */
    $view->caching = false;
    
    Pelican::$trace['time'][$indice] = sprintf("%.4f", (getmicrotime() - Pelican::$trace['time'][$indice]));
}