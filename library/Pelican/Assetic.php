<?php

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\httpAsset;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\AssetWriter;
use Pelican\Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\Worker\EnsureFilterWorker;
use Assetic\FilterManager;


pelican_import('Assetic_Filter_CssCompressorFilter');
pelican_import('Assetic_Filter_JsCompressorFilter');
pelican_import('Assetic_Asset_AssetCache');
/**
 * Pack et minifie les JS et CSS 
 */
class Pelican_Assetic
{
    
    /**
     * Liste des CSS
     *
     * @var array
     */
    protected $_aCss;
    
    /**
     * Liste des JS
     *
     * @var array
     */
	protected $_aJs;
	
    /**
     * Liste des assets javascripts
     *
     * @var Assetic\Asset\AssetCollection
     */
	protected $_aJsAssets;
	
	/**
	 * Liste des assets CSS
	 *
	 * @var Assetic\Asset\AssetCollection
	 */
	protected $_aCssAssets;
	
	/**
	 * Contenu courant du pack JS
	 *
	 * @var string
	 */
	protected $_jsContent;
	
	/**
	 * Contenu courant du pack CSS
	 *
	 * @var string
	 */
	protected $_cssContent;
	
	/**
	 * Libellé du groupe par défaut
	 * 
	 * @static
	 * @var string 
	 */
	public static $sDefaultGroup = 'default';
	
	/**
	 * Spécifie le mode à utiliser
	 * Si le debug est activer, le minify ne sera pas exécuté
	 * 
	 * @var boolean
	 */
    protected $_bDebugMode = false;
    
    /**
     * Factory utilisé pour manipuler les CSS
     *
     * @var Assetic\Factory\AssetFactory
     */
    protected $_oCssFactoryManager;
    
    /**
     * Factory utilisé pour manipuler les JS
     *
     * @var Assetic\Factory\AssetFactory
     */
    protected $_oJsFactoryManager;
    
    /**
     * Gestionnaire des filtres appliqués au pack
     *
     * @var Assetic\FilterManager
     */
    protected $_oFilterManager;
    
    /**
     * Chemin http le dossier du pack
     * 
     * @var string
     */
    protected $_sWebPath;
    
    /**
     * Chemin physique vers le dossier du pack
     * 
     * @var string
     */
    protected $_sWebRoot;
    
    /**
     * URL courante du pack js
     * 
     * @var string
     */
    protected $_sJsUrl;
    
    /**
     * URL courante du pack css
     * @var string
     */
    protected $_sCssUrl;
    
    /**
     * Liste des groupes de pack CSS
     *
     * @var array
     */
    protected $aCssGroups = array();
    
    /**
     * Liste des groupes de pack JS
     *
     * @var array
     */
    protected $aJsGroups = array();
    /**
     * Constructeur
     *
     * @param boolean $bDebugMode
     */
    public function __construct($sWebPath = '', $sWebRoot = '', $bDebugMode = false)
    {
          
        if($sWebPath != '') {
            $this->_sWebPath = $sWebPath;
        }else {
            $this->_sWebPath = Pelican::$config['MEDIA_PATH'];
        }
            
        if($sWebRoot != '') {
            $this->_sWebRoot = $sWebRoot;
        }else {
            $this->_sWebRoot = Pelican::$config['MEDIA_ROOT'];
        }
        
        // Création du répertoire d'origine
        if (!is_dir($this->_sWebRoot)) {
            mkdir($this->_sWebRoot, 0777, true);
        }
        
        // Création du lien symbolique des images
        if (isset(Pelican::$config["IMAGE_FRONT_HTTP"])) {
            $images = str_replace(Pelican::$config["DESIGN_HTTP"], Pelican::$config["DESIGN_ROOT"], Pelican::$config["IMAGE_FRONT_HTTP"]);
            $images_pack = str_replace('design', 'design_pack', $images);
            if (!is_link($images_pack)) {
            	symlink($images,$images_pack);
            }
        }

        if (isset(Pelican::$config["FONT_FRONT_ROOT"]) && is_dir(Pelican::$config["FONT_FRONT_ROOT"])) {
            $font_pack = str_replace('frontend/css/font','media/design_pack/frontend/css/font',Pelican::$config["FONT_FRONT_ROOT"]);
            $temp = explode('/',$font_pack);
            array_pop($temp);
            $init = implode('/',$temp);
            if (!is_dir($init)) {
            	mkdir($init, 0777, true);
            }
            if (!is_link($font_pack)) {
            	symlink(Pelican::$config["FONT_FRONT_ROOT"] , $font_pack);
            }
        }

        $this->bDebugMode = $bDebugMode; 
        $this->_oCssFactoryManager = new AssetFactory($this->_sWebRoot);
           
        $this->_oFilterManager = new FilterManager();
       
	    $this->_oFilterManager->set('css_minifyer', new Pelican_Assetic_Filter_CssCompressorFilter());
	    $this->_oFilterManager->set('js_minifyer',  new Pelican_Assetic_Filter_JsCompressorFilter());
	    
	    $oWorker = new EnsureFilterWorker(
	        '/\.css$/',
            $this->_oFilterManager->get('css_minifyer'),
	        $this->_bDebugMode
	    );
	    
	    $this->_oCssFactoryManager->addWorker($oWorker);
	    $this->_oCssFactoryManager->setFilterManager($this->_oFilterManager);
	    
	    $this->_oJsFactoryManager = new AssetFactory(realpath(sys_get_temp_dir()));
	    
	    $oWorker = new EnsureFilterWorker(
            '/\.js$/',
            $this->_oFilterManager->get('js_minifyer'),
	        $this->_bDebugMode
	    );
	    
	    $this->_oJsFactoryManager->addWorker($oWorker);
	    $this->_oJsFactoryManager->setFilterManager($this->_oFilterManager);
    }
    
    /**
     * Ajoute un script javascript à la pile courante
     * Si $sGroup est défini le script sera ajouté au groupe spécifié
     * sinon il sera ajouté au groupe par défaut
     * 
     * @param string $sJs (HTTP, Filesystem)
     * @param string $sGroup Le nom du groupe
     */
    public function addJs($sJs, $sGroup = '')
	{
	   $this->aJsGroups[$sGroup] = $sGroup;
	   
	   $this->_aJs[$sGroup][$sJs] = $this->_oJsFactoryManager->createAsset(
	       array(
	           Pelican::$config['DESIGN_ROOT'].'/js'.$sJs
           ),
           array(
                'js_minifyer'
            ) 
       );
	}
	
	/**
     * Ajoute un script CSS à la pile courante
     * Si $sGroup est défini le script sera ajouté au groupe spécifié
     * sinon il sera ajouté au groupe par défaut
     * 
     * @param string $sCss (HTTP, Filesystem)
     * @param string $sGroup Le nom du groupe
     */
	public function addCss($sCss, $sGroup = '')
	{
		if ($sGroup == '  ') {
			$sGroup = self::$sDefaultGroup;
		}
	    $this->_aCss[$sGroup][$sCss] = $this->_oCssFactoryManager->createAsset(
            array(
                Pelican::$config['DESIGN_ROOT'].'/css'.$sCss
            ),
            array(
                'css_minifyer'
            ),
            array(
                'output' => $sGroup    
            )
        );
 	}
	
 	/**
 	 * Supprime un script Css de la pile courante
 	 * Si sGroup est défini, seuls le script du groupe spécifié sera supprimé
 	 * Sinon le script sera supprimé du groupe par défaut
 	 * 
 	 * @param string $sCss (HTTP, Filesystem)
 	 * @param string $sGroup Le nom du groupe
 	 */
	public function removeCss($sCss, $sGroup = '')
	{
		if ($sGroup == '') {
			$sGroup = self::$sDefaultGroup;
		}
	    if(key_exists($sCss, $this->_aCss[$sGroup])) {
	        unset($this->_aCss[$sGroup][$sCss]);
	    }
	}
	
	/**
 	 * Supprime un script javascript de la pile courante
 	 * Si sGroup est défini, seuls le script du groupe spécifié sera supprimé
 	 * Sinon le script sera supprimé du groupe par défaut
 	 * 
 	 * @param string $sJs url du script (HTTP, Filesystem)
 	 * @param string $sGroup Le nom du groupe
 	 */
	public function removeJs($sJs, $sGroup = '')
	{
		if ($sGroup == '') {
			$sGroup = self::$sDefaultGroup;
		}
	    if(key_exists($sJs, $this->_aJs[$sGroup])) {
	        unset($this->_aJs[$sGroup][$sJs]);
	    }
	}
	
	/**
	 * Retourne le tableau des CSS
	 *
	 * @return array
	 */
	public function getCss()
	{
	    return $this->_aCss;
	}
	
	/**
	 * Retourne le tableau des javascripts
	 *
	 * @return array
	 */
	public function getJs()
	{
	    return $this->_aJs;
	}
	
	/**
	 * Retourne le contenu javascript défini après execution du pack
	 *
	 * @return string
	 */
	public function getJsContent()
	{
	    return $this->_jsContent;
	}
	
	/**
	 * Retourne le contenu CSS défini après exécution du pack
	 *
	 * @return string
	 */
	public function getCssContent()
	{
	    return $this->_cssContent;
	}
    
	/**
	 * Retourne la liste des assets javascripts
	 * Si la collection n'existe pas, une collection par défaut est initialisée
	 * 
	 * @return Assetic\Asset\AssetCollection
	 */
	public function getJsAssets($bReload = false)
	{
	    if($this->_aJsAssets === null || $bReload) 
	    {
	        $this->_aJsAssets = new AssetCollection(
                array(), 
                array(
                    new Pelican_Assetic_Filter_JsCompressorFilter(),
                ) 
            );
	    }
	    
	    return $this->_aJsAssets;
	}
	
	/**
	 * Retourne la liste des assets CSS
	 * Si la collection n'existe pas, une collection par défaut est initialisée
	 * 
	 * @return Assetic\Asset\AssetCollection
	 */
	public function getCssAssets($bReload = false)
	{
	    if($this->_aCssAssets === null || $bReload) {
	        $this->_aCssAssets = new AssetCollection(
				array(), 
				array(
					new Pelican_Assetic_Filter_CssCompressorFilter(),
				)
			);
	    }
	    
	    return $this->_aCssAssets;
	}		
	
	/**
	 * Retourne le contenu javascript du pack courant 
	 * Peut être directement ajouté au sein de la balise <head>
	 * 
	 * @return string
	 */
	public function getJavaScripts()
	{
		return '<script type="text/javascript" src="'.$this->getJsUrl().'"></script>';
	}
	
	/**
	 * Retourne le contenu Css du pack courant 
	 * Peux être directement ajouté au sein de la balise <head>
     *
	 * @return string
	 */
	public function getCssScripts()
	{
		return '<link rel="stylesheet" media="screen" type="text/css" href="'.$this->getCssUrl().'" />';
	}
	
	/**
	 * Retourne le FactoryManager des CSS
	 *
	 * @return Assetic\Factory\AssetFactory;
	 */
	public function getCssFactory()
	{
	    return $this->_oCssFactoryManager;
	}
	
	/**
	 * Retourne le FactoryManager des JS
	 *
	 * @return Assetic\Factory\AssetFactory;
	 */
	public function getJsFactory()
	{
	    return $this->_oJsFactoryManager;
	}
	
	/**
	 * Prépare les assets pour le sGroup spécifié
	 * Si $sGroup n'est pas défini, le groupe par défaut sera traité
	 * Méthode nécessaire au pack
	 * 
	 * @param string $sGroup
	 */
	protected function _createAssets($sGroup = '')
	{
	    
	    $this->_aJsAssets = $this->getJsAssets(true);
		$this->_aCssAssets = $this->getCssAssets(true);
		
		if($sGroup == '') {
			if(is_array($this->_aJs)) {
				foreach($this->_aJs as $key => $aGroup) {
					foreach($aGroup as $sJs => $oFile) {
						$this->_aJsAssets->add($oFile);
					}
				}
			}
			
			if(is_array($this->_aCss)) {
				foreach($this->_aCss as $key => $aGroup) {
					foreach($aGroup as $sCss => $oFile) {
						$this->_aCssAssets->add($oFile);
					}
				}
			}
		}else {
			if(is_array($this->_aJs) && key_exists($sGroup, $this->_aJs) && is_array($this->_aJs[$sGroup])) {
				foreach($this->_aJs[$sGroup] as $sJs => $oFile) {
					$this->_aJsAssets->add($oFile);
				}
			}
			
			
			if(is_array($this->_aCss) && key_exists($sGroup, $this->_aCss) && is_array($this->_aCss[$sGroup])) {
				foreach($this->_aCss[$sGroup] as $sCss => $oFile) {
					$this->_aCssAssets->add($oFile);
				}
			}
			
		}
	}
        
    protected function getJsUrl()
    {
        return $this->_sJsUrl;
    }
    
    protected function getCssUrl()
    {
        return $this->_sCssUrl;
    }
	
	/**
	 * Mise en cache des scripts appartenant au groupe sGroup
	 * Si $sGroup n'est pas spécifié, le groupe par défaut sera sélectionné
	 * Doit être exécuté après _createAssets
	 * 
	 * @param string $sPath Le chemin de destination du pack
	 * @param string $sGroup Le nom du groupe
	 */
	protected function _cache($sWebRoot, $sGroup = '') 
	{
		if($sGroup == '') {
			$sGroup = self::$sDefaultGroup;
		}
        
        $subDir = '';
        if (strpos($sGroup, '/') !== false) {
            $subDir = '/' . substr($sGroup, 0, strrpos($sGroup, '/'));
        }
		
		$oCacheJs = new Pelican_Assetic_Asset_AssetCache(
            $this->_aJsAssets,
            new FilesystemCache($sWebRoot.'/js'.$subDir),
            $this->_sWebPath.'/js'.$subDir,
            'js', 
            $sGroup
		);
	  
		$this->_jsContent = $oCacheJs->dump();
        $this->_sJsUrl = $oCacheJs->getCurrentUrl();
               
		$oCacheCss = new Pelican_Assetic_Asset_AssetCache(
		    $this->_aCssAssets,
		    new FilesystemCache($sWebRoot.'/css'.$subDir),
            $this->_sWebPath.'/css'.$subDir,
            'css', 
            $sGroup
		);

                
		$this->_cssContent = $oCacheCss->dump();
		$this->_sCssUrl = $oCacheCss->getCurrentUrl();
		
	}
	
	/**
	 * Exécution du pack pour le group sGroup
	 * Si sGroup n'est pas spécifié, le groupe par défaut sera sélectionné
	 * 
	 * @param string $sPath Le chemin de destination du pack
	 * @param string $sGroup Le nom du groupe
	 */
	public function pack($sWebRoot = '', $sGroup = '')
	{
        if($sWebRoot == '') {
            $sWebRoot = $this->_sWebRoot;
        }
       
	    $this->_createAssets($sGroup);
	    $this->_cache($sWebRoot, $sGroup);
	}
	
	
	/**
	 * Créé tous les packs CSS possibles et renvoie la concaténation des scripts
	 *
	 */
	public function packCss() 
	{
	  
	    $sCssScripts = '';
	    if(is_array($this->_aCss)) {
	     
            foreach($this->_aCss as $key => $aGroup) {    
                $this->pack('', $key);
                $sCssScripts.= $this->getCssScripts();     
                
            }
	    }
	    
	    return $sCssScripts;
	}
	
	public function packJs($pattern) 
	{
	    $sJsScripts = '';
	    if(is_array($this->_aJs)) {
	     
            foreach($this->_aJs as $key => $aGroup) {    
                if(preg_match('/^'.$pattern.'.*/', $key)) {
                    $this->pack('', $key);
                    $sJsScripts.= $this->getJavaScripts();     
                }
            }
	    }
	    
	    return $sJsScripts;
	}
	
	/**
	 * Vérifie qu'une CSS n'est pas concernée par un pack (pour ne pas l'inclure 2 fois)
	 *
	 * @param unknown_type $sCss
	 */
	public function hasCss($sCss) 
	{
	    if(is_array($this->_aCss)) {
	        
    	    foreach($this->_aCss as $key => $aGroup) {
    			foreach($aGroup as $_sCss => $oFile) {
    				if($sCss['href'] == Pelican::$config['CSS_FRONT_HTTP'].$_sCss) {
    				    return true;
    				}
    			}
    		}
	    }
		return false;
	}
	
	/**
	 * Vérifie qu'un JS n'est pas concerné par un pack (pour ne pas l'inclure 2 fois)
	 *
	 * @param unknown_type $sJs
	 */
	public function hasJs($sJs) 
	{
	    if(is_array($this->_aJs)) {
    	    foreach($this->_aJs as $key => $aGroup) {
    			foreach($aGroup as $_sJs => $oFile) {
    				if($sJs['href'] == Pelican::$config['JS_FRONT_HTTP'].$_sJs) {
    				    return true;
    				}
    			}
    		}
	    }
		return false;
	}
}
