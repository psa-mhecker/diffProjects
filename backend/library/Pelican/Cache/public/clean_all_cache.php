<?php
/**
 * Suppression de tous les application/caches.
 */
$_GET["param"] = "*";

/**
 * Script générique de nettoyage du Pelican_Cache.
 */
include 'clean_cache.php';

/*
 * A décommenter pour un décache redis $cache = new Pelican_Cache_Redis(); $cache->deleteAll();
 */
