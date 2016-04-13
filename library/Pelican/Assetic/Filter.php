<?php

include_once(Pelican::$config['LIB_ROOT'].'/External/assetic/src/Assetic/Filter/FilterInterface.php');
include_once(Pelican::$config['LIB_ROOT'].'/External/assetic/src/Assetic/Asset/AssetInterface.php');

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 * 
 * Classe d'application du filtre minify PHP Factory
 */
class Pelican_Assetic_Filter implements FilterInterface
{
    /**
     * Constructeur
     *
     */
	public function __construct()
	{
	
	}

	/**
     * Filters an asset after it has been loaded.
     *
     * @param AssetInterface $asset An asset
     */
    function filterLoad(AssetInterface $asset)
    {
        
        
    }

    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    function filterDump(AssetInterface $asset)
    {
        
    }
}