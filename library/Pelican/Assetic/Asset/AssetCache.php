<?php

use Assetic\Asset\AssetCache;
use Assetic\Filter\FilterInterface; 
use Assetic\Asset\AssetInterface;
use Assetic\Cache\CacheInterface;

/**
 * Gestion du cache des assets
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Pelican_Assetic_Asset_AssetCache extends AssetCache 
{
    /**
     * Clé de cache courante
     *
     * @static
     * @var string
     */
    protected static $_sCurrentKey;
    
    /**
     * Chemin http vers le pack
     *
     * @var string
     */
    protected $_sWebPath;
    
    /**
     * Extension du fichier du pack
     *
     * @var string
     */
    protected static $_sExtension;
    
    /**
     * Groupe de pack
     *
     * @var string
     */
    protected static $_sGroup;
    
    /**
     * Nombre d'assets
     * Permet la prise en compte de l'ajout/supression d'assets
     * pour la génération du cache 
     * @var unknown_type
     */
    protected static $_countAssets = 0;
    
    /**
     * Constructeur
     *
     * @param AssetInterface $asset
     * @param CacheInterface $cache
     * @param string $sWebPath
     * @param string $sExtension
     */
    public function __construct(AssetInterface $asset, CacheInterface $cache, $sWebPath = '/', $sExtension = 'js', $sGroup = 'default')
    {
        
        $this->asset = $asset;
        $this->cache = $cache;
        $this->_sWebPath = $sWebPath;
        self::$_sExtension = $sExtension;
        self::$_sGroup = $sGroup;
        self::$_countAssets = sizeof($asset->all());
        parent::__construct($this->asset, $this->cache);
    }
    
    /**
     * Crée le pack et génère la clé de cache
     *
     * @param FilterInterface $additionalFilter
     * @return string Le contenu du pack
     */
    public function dump(FilterInterface $additionalFilter = null)
    {
        
        $cacheKey = self::getCacheKey($this->asset, $additionalFilter, 'dump');
        self::$_sCurrentKey = $cacheKey;
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $content = $this->asset->dump($additionalFilter);
        $this->cache->set($cacheKey, $content);

        return $content;
    }
    
    /**
     * Returns a cache key for the current asset.
     *
     * The key is composed of everything but an asset's content:
     *
     *  * source root
     *  * source path
     *  * target url
     *  * last modified
     *  * filters
     *
     * @param AssetInterface  $asset            The asset
     * @param FilterInterface $additionalFilter Any additional filter being applied
     * @param string          $salt             Salt for the key
     *
     * @return string A key for identifying the current asset
     */
    static private function getCacheKey(AssetInterface $asset, FilterInterface $additionalFilter = null, $salt = '')
    {
        if ($additionalFilter) {
            $asset = clone $asset;
            $asset->ensureFilter($additionalFilter);
        }

        $cacheKey  = $asset->getSourceRoot();
        $cacheKey .= $asset->getSourcePath();
        $cacheKey .= $asset->getTargetPath();
        $cacheKey .= $asset->getLastModified();
        
        foreach ($asset->getFilters() as $filter) {
            $cacheKey .= serialize($filter);
           
        }
        return md5($cacheKey.self::$_countAssets.self::$_sGroup.$salt).'.'.self::$_sExtension;
    }
    
    /**
     * Renvoie l'url du pack
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_sWebPath.'/'.self::$_sCurrentKey;
    }
}
