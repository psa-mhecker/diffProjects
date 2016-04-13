<?php
/**
 * Helper de gestion du header Pelican_Html
 *
 * @package Pelican
 * @subpackage Pelican_Index
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 */


pelican_import('Index.Pack');
pelican_import('Assetic');

/**
 * Helper de gestion du header Pelican_Html
 *
 * @package Pelican
 * @subpackage Pelican_Index
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 * @since 06/01/2006
 * @version 1.0
 */
class Pelican_Index
{

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $skin;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $targetBlank;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $link = array();

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $script = array();

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $include = array();

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $browserMode = 'desktop';

    public static $iphone = false;

    public $addon = '';

    protected $UserAgentSimulation;

    protected $charset;

    protected $title;

    public $skinPath;

    protected $jqueryPath;

    protected $jqueryRoot;

    protected $jqueryIni;

    protected $jquery;

    protected $pack;

    public $sTitle;

    public $output;

    protected $_doctype;

    protected $packer;

    protected $bPack = false;
    /**
     * Constructeur
     *
     * @access public
     * @return __TYPE__
     */
    public function __construct ()
    {

        $this->setCharset();

        $this->packer = new Pelican_Assetic(Pelican::$config['DESIGN_PACK_HTTP'], Pelican::$config['DESIGN_PACK_ROOT']);

        return true;
    }

    /**
     * Méthode permettant l'ajout de target="_blank" à tous les liens répondant au
     * critère passé en paramètre
     *
     * @access public
     * @param string $link Début d'url à cibler
     * @return __TYPE__
     */
    public function setTarget ($link)
    {
        $this->targetBlank = $link;
    }

    /**
     * Définition d'une feuille de style
     *
     * @access public
     * @param string $css Chemin relatif de la feuille de style
     * @param __TYPE__ $media (option) __DESC__
     * @param string $aAttribs (option) __DESC__
     * @param string $condition (option) __DESC__
     * @param string $sGroup Libell? du groupe ? packer
     * @return __TYPE__
     */
    public function setCss ($css, $media = "screen", $aAttribs = "", $condition = "", $sGroup = false)
    {
            $css = self::normalizeProtocol($css);

            if($sGroup && $this->bPack) {
                 $this->packer->addCss($css, $sGroup);
            }

            if (! isset($this->exists['css'][$css])) {
            if (! is_array($aAttribs)) {
                $aAttribs = array();
            }
			if(!preg_match('/^http/', $css) && !preg_match('#library/Pelican#', $css)) {
				$aAttribs["href"] = Pelican::$config['CSS_FRONT_HTTP'].$css;
			}else {
				$aAttribs["href"] = $css;
			}
            $aAttribs["media"] = $media;
            if ($condition) {
                $aAttribs['condition'] = $condition;
            }
            $this->css[] = $aAttribs;
            $this->exists['css'][$css] = true;

        }
    }

    /**
     * Définition d'une feuille de style
     *
     * @access public
     * @param __TYPE__ $rel __DESC__
     * @param __TYPE__ $href __DESC__
     * @param string $title (option) __DESC__
     * @param string $type (option) __DESC__
     * @param string $media (option) __DESC__
     * @return __TYPE__
     */
    public function setLink ($rel, $href, $title = "", $type = "", $media = "")
    {
        $this->link[] = array(
            "rel" => $rel ,
            "href" => $href ,
            "title" => $title ,
            "type" => $type ,
            "media" => $media
        );
    }

    /**
     * @param $js le fichier jS
     * @param $condition
     * @param $sGroup Le libell? du groupe de pack
     */
    public function endJs ($js, $condition = "", $sGroup = '')
    {
        $condition = "";
        $this->setJs($js, "", 'foot', $sGroup);
    }

    /**
     * Définition d'un fichier javascript externe
     *
     * @access public
     * @param string $js Chemin relatif du fichier javascript
     * @param string $condition (option) __DESC__
     * @param string $sGroup Libell? du groupe de pack (sera prefix? par la position pour diff?rencier 2 pack appartenant ? un meme groupe mais devant etre inclus a des endroits diff?rents)
     * @return __TYPE__
     */
    public function setJs ($js, $condition = "", $position = 'head', $sGroup = '')
    {

        $js = self::normalizeProtocol($js);

        if (empty($position)) {
            $position = 'head';
        }

        if($sGroup && $this->bPack) {
            $this->packer->addJs($js, $position.$sGroup);
        }else {
            if (! isset($this->exists['js'][$js])) {
                if(!preg_match('/^http/', $js) && !preg_match('#library/#', $js)  && !preg_match('#^/js#', $js)) {
                    $js = Pelican::$config['JS_FRONT_HTTP'].$js;
                }
                $this->js[$position][] = array(

                    "js" => $js ,
                    "condition" => $condition
                );
                $this->exists['js'][$js] = true;
            }
        }
    }

    public function endJQuery ($name)
    {
        $this->setJQuery($name, 'foot');
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $name __DESC__
     * @return __TYPE__
     */
    public function setJQuery ($name, $position = 'head')
    {

        if (empty($position)) {
            $position = 'head';
        }
        if (empty($this->jqueryIni)) {
            $this->getJqueryIni();
        }
        if (isset($this->jqueryIni[$name])) {

            if (! isset($this->jqueryIni[$name]['condition'])) {
                $this->jqueryIni[$name]['condition'] = "";
            }

            /** s'il y a des dépendances */
            if (isset($this->jqueryIni[$name]['linked'])) {
                $this->setJQuery($this->jqueryIni[$name]['linked']);
            }
            if (isset($this->jqueryIni[$name]['js'])) {
                if (! is_array($this->jqueryIni[$name]['js'])) {
                    $this->jqueryIni[$name]['js'] = array(
                        $this->jqueryIni[$name]['js']
                    );
                }
                foreach ($this->jqueryIni[$name]['js'] as $jqueryJs) {
                    $aName = explode('.', $name);
                    $path = "";
                    if (! substr_count($jqueryJs, 'http://')) {
                        $path = $this->jqueryPath . $aName[0] . '/js/';
                    }
                    $this->setJs($path . $jqueryJs, $this->jqueryIni[$name]['condition'], $position);
                }
            }
            if (isset($this->jqueryIni[$name]['css'])) {
                if (! is_array($this->jqueryIni[$name]['css'])) {
                    $this->jqueryIni[$name]['css'] = array(
                        $this->jqueryIni[$name]['css']
                    );
                }
                foreach ($this->jqueryIni[$name]['css'] as $jqueryCss) {
                    $path = "";
                    if (! substr_count($jqueryCss, 'http://')) {
                        $path = $this->jqueryPath . $name . '/css/';
                    }
                    $this->setCss($path . $jqueryCss, "screen", "", $this->jqueryIni[$name]['condition']);
                }
            }
            /* s'il y a des includes à faire en fin de fichier */
            if (isset($this->jqueryIni[$name]['include'])) {
                $path = $this->jqueryRoot . $name . '/include/';
                $this->setJqueryInclude($path . $this->jqueryIni[$name]['include']);
            }
            //$this->jqueryLibrary[] = $name;
            self::registerJquery($name);
        }
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @staticvar xx $jquery
     * @param __TYPE__ $name __DESC__
     * @param string $action (option) __DESC__
     * @param string $value (option) __DESC__
     * @return __TYPE__
     */
    static public function registerJquery ($name, $action = '', $value = "")
    {
        static $jquery;

        /** est déjà utilisé */
        $used = isset($jquery[$name]);

        /* est connu dans les paramètres */
        if (isset($jquery['param_ini'])) {
            $exists = $jquery['param_ini'][$name];
        }

        if ($action == 'control') {
            return $used;
        } elseif ($action == 'param') {
            if (isset($jquery['param_ini'][$name])) {
                $jquery[$name] = true;
                return $jquery['param_ini'][$name];
            }
        } elseif ($action == 'path') {
            return $jquery['param_path'];
        } else {
            /** référencement uniquement */
            $jquery[$name] = ($value ? $value : true);
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $include __DESC__
     * @return __TYPE__
     */
    public function setJqueryInclude ($include)
    {
        if (! isset($this->exists['jqueryInclude'][$include])) {
            $this->jqueryInclude[] = array(
                "path" => $include
            );
            $this->exists['jqueryInclude'][$include] = true;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $js __DESC__
     * @return __TYPE__
     */
    public function setJqueryFunction ($js)
    {
        $this->jqueryFunction[] = $js;
    }

    /**
     * Permet d'inclure un Pelican_Plugin jquery en cours de code, même si le header a déjà
     * été affiché
     * => le contrôle d'existance est fait avant l'inclusion
     *
     * => idéal pour les plugins
     *
     * @static
     * @access public
     * @param __TYPE__ $name __DESC__
     * @param bool $directOutput (option) __DESC__
     * @return __TYPE__
     */
    static public function directJquery ($name, $directOutput = false)
    {
        $return = "";
        if (! self::registerJquery($name, 'control')) {
            $jquerypath = self::registerJquery($name, 'path');
            $param = self::registerJquery($name, 'param');
            if ($param) {
                if (isset($param['js'])) {
                    $aName = explode('.', $name);
                    $path = "";
                    if (! substr_count($param['js'], 'http://')) {
                        $path = $jquerypath . $aName[0] . '/js/';
                    }
                    $js['src'] = $path . $param['js'];
                    $js['type'] = "text/javascript";
                    $aReturn[] = Pelican_Html::script($js);
                }
                if (isset($param['css'])) {
                    $path = "";
                    if (! substr_count($param['css'], 'http://')) {
                        $path = $jquerypath . $name . '/css/';
                    }
                    $css["href"] = $path . $param['css'];
                    $css["rel"] = "stylesheet";
                    $css["type"] = "text/css";
                    $aReturn[] = $begin . Pelican_Html::link($css) . $end;
                }
                if ($aReturn) {
                    $return = implode('', $aReturn);
                }
            }
        }
        if ($directOutput) {
            echo $return;
        } else {
            return $return;
        }
    }

    public function endSwfObject ()
    {
        $this->setSwfObject('foot');
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function setSwfObject ($position = 'head')
    {

        if (empty($position)) {
            $position = 'head';
        }
        $this->setJs(Pelican::$config["MEDIA_HTTP"] . "/library/External/swfobject/swfobject.js", '', $position);
    }

    public function endScript ($js, $defer = false)
    {
        $this->setScript($js, 'foot', $defer);
    }

    /**
     * Définition d'un bout de code javascript
     *
     * @access public
     * @param string $js Code Javascript
     * @return __TYPE__
     */
    public function setScript ($js, $position = 'head', $defer = false)
    {
        if (empty($position)) {
            $position = 'head';
        }
        $data = ($defer ? 1 : 0);
        $this->script[$position][$data][] = $js;
    }

    /**
     * Définition d'un tag META
     *
     * @access public
     * @param string $typelabel Type (name ou http-equiv)
     * @param string $type Valeur du Type
     * @param string $content Contenu de l'attribut "content"
     * @return __TYPE__
     */
    public function setMeta ($typelabel, $type, $content)
    {
        $this->meta[] = array(
            $typelabel ,
            $type ,
            $content
        );
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $value __DESC__
     * @return __TYPE__
     */
    public function setMetaRobots ($value)
    {
        $this->setMeta("name", "robots", $value);
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function setMetaIe7Compatibility ()
    {
        $this->setMeta("http-equiv", "X-UA-Compatible", "IE=EmulateIE7");
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function setCharset ()
    {

        $this->charset = (Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1");

        $this->setMeta("http-equiv", "Content-Type", "text/html; charset=" . $this->charset);
        $this->setMeta("http-equiv", "Content-Script-Type", "text/javascript; charset=" . $this->charset);
        $this->setMeta("http-equiv", "Content-Style-Type", "text/css; charset=" . $this->charset);

    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $doctype __DESC__
     * @param bool $output (option) __DESC__
     * @return __TYPE__
     */
    public function setDocType ($doctype, $output = false)
    {
        switch ($doctype) {
            case "XHTML 1.0 Strict":
                {
                    $this->_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
                    break;
                }
            case "XHTML 1.0 Frameset":
                {
                    $this->_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
                    break;
                }
            case "XHTML 1.0 Transitional":
                {
                    $this->_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
                    break;
                }
            case "HTML 5":
                {
                    $this->_doctype = '<!DOCTYPE html>';
                    break;
                }
        }
        if ($output) {
            echo $this->_doctype;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getDocType ()
    {
        if (empty($this->_doctype)) {
            $this->setDocType("XHTML 1.0 Transitional");
        }
        return $this->_doctype;
    }

    /**
     * Inclusion PHP d'un fichier dans le HEADER de la page
     *
     * @access public
     * @param string $include Chemin complet du fichier à inclure
     * @param string $var (option) __DESC__
     * @return __TYPE__
     */
    public function setIncludeHeader ($include, $var = "")
    {
        $this->include[] = array(
            $include ,
            $var
        );
    }

    /**
     * Définition du titre de page
     *
     * @access public
     * @param string $title __DESC__
     * @return __TYPE__
     */
    public function setTitle ($title)
    {
        $this->title = $title;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $content __DESC__
     * @return __TYPE__
     */
    public function setAddon ($content)
    {
        $this->addon .= $content;
    }

    /**
     * Formatage du titre
     *
     * @access public
     * @return string
     */
    public function getTitle ()
    {
        return Pelican_Html::title($this->title);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @param __TYPE__ $relPath __DESC__
     * @param __TYPE__ $rootPath __DESC__
     * @return __TYPE__
     */
    /*   public function setSkin($id, $relPath, $rootPath)
    {
        $this->setBackofficeSkin($id, $relPath, $rootPath);
    }*/
    public function setSkin ($type, $id, $relPath = '', $rootPath = '')
    {
        switch ($type) {
            case 'artisteer':
                {
                    $this->setArtisteerSkin($id, $relPath);
                    break;
                }
            case 'backend':
                {
                    $this->setBackofficeSkin($id, $relPath, $rootPath);
                    break;
                }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $skin (option) __DESC__
     * @param __TYPE__ $version (option) __DESC__
     * @return __TYPE__
     */
    public function setArtisteerSkin ($skin = "", $version = "artisteer/2.4")
    {

        if (empty($_SESSION[APP]['skin']) && empty($skin)) {
            $temp = Pelican_Cache::fetch('Frontend/Skins');
            $temp2 = array_keys($temp);
            $path = $temp2[0];
            $path = 'artisteer/2.4/Elfes';
            $skin = basename($path);
        } elseif (! empty($_SESSION[APP]['skin']) && empty($skin)) {
            $skin = $_SESSION[APP]['skin'];
        }
        if ($skin) {
            $_SESSION[APP]['skin'] = $skin;
            $this->skins = str_replace('..', '', Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/skins/" . $version . "/" . $skin);
            Pelican::$config['SKIN_HTTP'] = $this->skins;

            switch ($version) {
                case "2.0":
                    {
                        $this->endJs(Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/skins/js/script.js");
                        if ($skin) {
                            $this->skins = Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/skins/" . $skin;
                        }
                        $this->setCss($this->skins . "/style.css");
                        $this->setCss($this->skins . "/style.ie6.css", "screen", "", "if IE 6");
                        break;
                    }
                case "artisteer/2.4":
                    {
                        $this->setCss($this->skins . "/style.css");
                        $this->setCss($this->skins . "/style.ie6.css", "screen", "", "if IE 6");
                        $this->setCss($this->skins . "/style.ie7.css", "screen", "", "if IE 7");
                        $this->endJs($this->skins . "/script.js");
                        break;
                    }
            }
        }
    }

    /**
     * Définition du skins
     *
     * @access public
     * @param string $id __DESC__
     * @param string $relPath Chemin relatif
     * @param string $rootPath Chemin Root
     * @return __TYPE__
     */
    public function setBackofficeSkin ($id, $relPath, $rootPath)
    {
        $css = str_replace("library/library", "library", $rootPath . $relPath . "/" . $id . "/css/style.css.php");

        $urlinfo = parse_url($_SERVER['REQUEST_URI']);
        $page = substr($urlinfo['path'], 1, strlen($urlinfo['path']));
        if (file_exists($css)) {
            $this->setCss($relPath . "/" . $id . "/css/style.css.php?page=" . $page);
            $this->setCss($relPath . "/" . $id . "/css/pelican.css");
            $this->skinPath = $relPath . "/" . $id;
            pelican_import('Index.Tab');
            Pelican_Index_Tab::$imgPath = $this->skinPath;
        }
    }

    /**
     * Formatage du lien vers la feuille de style associée au skins
     *
     * @return string
     */
    /*public function getSkin() {
	$return = "";
	if ($this->skins) {
	$return = Pelican_Html::link(array (media => "screen", rel => "stylesheet", href => $this->skins, type => "text/css"));
	}
	return $return;
	}*/

    /**
     * Formatage des liens vers les feuilles de styles
     *
     * @access public
     * @param $bPack D?fini si le pack doit ?tre mis en place ou non (false pour le dev)
     * @return string
     */
    public function getCss ()
    {
        $aReturn = array();
        $return = "";

        if($this->bPack) {
            $aReturn[] = $this->packer->packCss();

        }

        if ($this->css) {

            /** Pelican_Index_Pack */
            if (empty($this->pack)) {
                $this->pack = Pelican_Factory::getInstance('Index.Pack', str_replace('index', 'conf', $_SERVER['DOCUMENT_ROOT']) . "/pack.ini");
            }
            $this->css = $this->pack
               ->process($this->css, 'css');

            foreach ($this->css as $css) {
                if(!$this->bPack || !$this->packer->hasCss($css)) {
                    if (! empty($css)) {
                        if (! empty($css["condition"])) {
                            $begin = "<!--[" . $css["condition"] . "]>";
                            $end = "<![endif]-->";
                        } else {
                            $begin = "";
                            $end = "";
                        }
						$css["href"] = str_replace('/css/css', '/css', $css["href"]);
                        $css["condition"] = "";
                        $pathinfo = pathinfo($css["href"]);
                        $css["rel"] = "stylesheet";
                        $css["type"] = "text/css";
                        $aReturn[] = $begin . Pelican_Html::link($css) . $end;
                    }
                }
            }
        }

        if ($aReturn) {
            $return = implode("\n", $aReturn);
        }
        return $return;
    }

    /**
     * Formatage des liens
     *
     * @access public
     * @return string
     */
    public function getLink ()
    {
        $aReturn = array();
        $return = "";

        if ($this->link) {
            foreach ($this->link as $link) {
                if (! empty($link)) {
                    $aReturn[] = Pelican_Html::link(array(
                        rel => $link["rel"] ,
                        href => $link["href"] ,
                        title => $link["title"] ,
                        type => $link["type"] ,
                        media => $link["media"]
                    ));
                }
            }
        }
        if ($aReturn) {
            $return = implode("\n", $aReturn);
        }
        return $return;
    }

    /**
     * Formatage des liens vers le fichier javascript
     *
     * @access public
     * @param $position
     * @param $sGroup libell? du groupe de pack (si d?fini)
     * @param $bPack D?fini si le pack doit ?tre effectu? (a mettre a false pour le dev)
     * @return string
     */
    public function getJs ($position = 'head')
    {
        $aReturn = array();
        $return = "";
        if (empty($position)) {
            $position = 'head';
        }

        if($this->bPack) {
            $aReturn[] = $this->packer->packJs($position);
        }
        if ($this->js[$position]) {

            /** Pelican_Index_Pack */
            /* if (empty($this->pack)) {
                $this->pack = Pelican_Factory::getInstance('Index.Pack', str_replace('index', 'conf', $_SERVER['DOCUMENT_ROOT']) . "/pack.ini");
            }

            $this->js[$position] = $this->pack
                ->process($this->js[$position], 'js'); */

            foreach ($this->js[$position] as $js) {
                if ($js) {
                    if ($js["condition"]) {
                        $begin = "<!--[" . $js["condition"] . "]>";
                        $end = "<![endif]-->";
                    } else {
                        $begin = "";
                        $end = "";
                    }
                    $pathinfo = pathinfo($js['js']);
                    $aReturn[] = $begin . Pelican_Html::script(array(
                        src => $js["js"] ,
                        type => "text/javascript"
                    )) . $end;
                }
            }
            if($bPack) {

                $aReturn[] = $packer->getJavaScripts();
            }
        }
        if ($aReturn) {
            $return = implode("\n", $aReturn);
        }

        return $return;
    }

    /**
     * Formatage du code javascript inclu dans le tag HEAD
     *
     * @access public
     * @return string
     */
    public function getScript ($position = 'head', $defer = false)
    {
        $aReturn = array();
        $return = "";
        $content = "";
        $data = ($defer ? 1 : 0);
        if (empty($position)) {
            $position = 'head';
        }

        if (! empty($this->script[$position][$data])) {
            foreach ($this->script[$position][$data] as $script) {
                $aReturn[] = $script;
            }
            if ($aReturn) {
                $content = implode("\n", $aReturn);
            }
            $return = Pelican_Html::script(array(
                type => "text/javascript"
            ), $content);
        }
        return $return;
    }

    /**
     * Formatage des tags META
     *
     * @access public
     * @return string
     */
    public function getMeta ()
    {
        $aReturn = array();
        $return = "";

        if ($this->meta) {
            foreach ($this->meta as $meta) {
                if ($meta[2]) {
                    $aReturn[] = Pelican_Html::meta(array(
                        $meta[0] => $meta[1] ,
                        content => $meta[2]
                    ));
                }
            }
        }
        if ($aReturn) {
            $return = implode("\n", $aReturn);
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getAddon ()
    {
        $return = "";
        if (isset($this->addon)) {
            $return = $this->addon;
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getJqueryInclude ()
    {
        $return = "";
        if (isset($this->jqueryInclude)) {
            foreach ($this->jqueryInclude as $include) {
                if (file_exists($include['path'])) {
                    include ($include['path']);
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getJqueryFunction ()
    {
        $return = "";
        if (isset($this->jqueryFunction)) {
            $js = implode("\n", $this->jqueryFunction);
            $return = Pelican_Html::script("$(function() {\n" . $js . "\n});");
        }
        return $return;
    }

    /**
     * Inclusion des fichiers externe dans le tag HEAD
     *
     * @access public
     * @return string
     */
    public function getIncludeHeader ()
    {

        $return = "";
        if ($this->include) {
            ob_start();
            foreach ($this->include as $include) {
                if ($include[1]) {
                    if (! is_array($include[1]))
                        $include[1] = array(
                            $include[1]
                        );
                    $global = array_map("variable", $include[1]);
                    eval("global " . implode(",", $global) . ";");
                }
                include ($include[0]);
            }
            $return = ob_get_contents();
            ob_end_clean();
        }
        return $return;
    }

    /**
     * Formatage du tag HEAD
     *
     * @access public
     * @param bool $showTag (option) __DESC__
     * @return string
     */
    public function getHeader ($showTag = false)
    {

        /** cas du changement de markup */
        if (! empty($_SERVER['REDIRECT_URL'])) {
            $pathinfo = pathinfo($_SERVER['REDIRECT_URL']);
            $extension = '';
            if (isset($pathinfo['extension'])) {
                $extension = strtolower($pathinfo['extension']);
            }

            if (substr($extension, 0, 3) != 'htm' && $extension != 'html5') {
                Pelican_Request::$userAgentFeatures['markup'] = $extension;
            }
        }

        $aReturn = array();
        $return = "";

        $aReturn[] = $this->getTitle();
        $aReturn[] = $this->getMeta();
        $aReturn[] = $this->getCss();
        $aReturn[] = $this->getLink();
        $aReturn[] = $this->getAjax();
        $aReturn[] = $this->getScript('head');
        $aReturn[] = $this->getJs('head');
        $aReturn[] = $this->getIncludeHeader();
        $aReturn[] = $this->getAddon();
        $aReturn[] = $this->getScript('head', true); //defer
        $return = implode("\n", $aReturn);
        if ($showTag) {
            $return = Pelican_Html::head($return);
        }
        return $return;
    }

    public function getFooter ()
    {
        $aReturn = array();
        $return = "";

        $aReturn[] = $this->getJs('foot');
        $aReturn[] = $this->getScript('foot');
        $aReturn[] = $this->getScript('foot', true); //defer
        $return = implode("\n", $aReturn);

        $this->getJqueryInclude();
        $return .= $this->getJqueryFunction();

        return $return;
    }

    public function getAjax ()
    {

        if (Pelican::$config['AJAX_ADAPTER']) {
            $ajaxEngine = Pelican::getAjaxEngine();
            $return = Pelican_Factory::staticCall($ajaxEngine, 'getHead');
            $this->setScript('callAjax = ' . Pelican_Factory::staticCall($ajaxEngine, 'getJsCall') . ';', 'head', true);
        }
        $this->setScript("function loaderAjax(id, msg) {document.getElementById(id).innerHTML = '" . Pelican_Html::img(array(
            src => Pelican::$config['LIB_PATH'] . "/public/images/ajax/ajax-loader.gif" ,
            border => 0
        )) . " '+(msg?msg:'Chargement...');}", 'head', true);
        return $return;
    }

    public function isHtml ($markup = '')
    {
        $return = (! $markup || substr($markup) == 'htm');
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getJqueryIni ()
    {
        $temp = self::jqueryPath();

        $this->jqueryPath = $temp['path'];
        $this->jqueryRoot = $temp['root'];
        $this->jqueryIni = $temp['ini'];
        if (empty($this->jquery)) {
            $path = "";
            if (! substr_count($this->jqueryIni['jquery']['js'], 'http://')) {
                $path = $this->jqueryPath;
            }
            $this->setJs($path . $this->jqueryIni['jquery']['js']);
            $this->jquery = true;
            self::registerJquery('jquery');
        }
        self::registerJquery('param_path', false, $temp['path']);
        self::registerJquery('param_root', false, $temp['root']);
        self::registerJquery('param_ini', false, $temp['ini']);
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function jqueryPath ()
    {

        $path = Pelican::$config["MEDIA_HTTP"] . "/library/External/jquery/";
        $root = Pelican::$config['LIB_ROOT'] . "/External/jquery/";
        //$ini = parse_ini_file($root . 'jquery.ini', true);
        //debug($ini);
        include_once ('Zend/Config/Ini.php');
        $config = new Zend_Config_Ini($root . 'jquery.ini');
        $ini = $config->toArray();
        return array(
            'path' => $path ,
            'root' => $root ,
            'ini' => $ini
        );

    }

    private function normalizeProtocol ($path)
    {
        return str_replace('http://', Pelican::$config["SERVER_PROTOCOL"] . '://', $path);
    }

    public function activatePack() {
        $this->bPack = true;
    }
    
    public function getPackStatus(){
        return $this->bPack;
    }

}

/**
 * __DESC__
 *
 * @param __TYPE__ $valeur __DESC__
 * @return __TYPE__
 */
function variable ($valeur)
{
    return "\$" . $valeur;
}