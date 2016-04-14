<?php
/**
 * Interface des classes de Pelican_Cache.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
interface Pelican_Cache_Interface
{
    /**
     * Récupération de la liste des clés de Pelican_Cache générées.
     */
    public function getKeyList();

    /**
     * Création de la syntaxe d'un nom de fichier de Pelican_Cache en fonction de ses paramètres.
     *
     * @param string  $script          Nom du script appelant (un "/" sera transformer en "_")
     * @param mixed   $params          Paramètres du Pelican_Cache
     * @param boolean $binaryCache     Pelican_Cache de type binaire ou non
     * @param string  $complementCache Complément du Pelican_Cache
     *
     * @return string
     */
    public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "");

    /**
     * Création de la syntaxe d'un répertoire de stockage d'un Pelican_Cache en fonction de ses paramètres
     * hashage à 2 niveaux.
     *
     * @param mixed $params Paramètres du Pelican_Cache
     *
     * @return string
     */
    public function getPath($params, $object = "");

    /**
     * Vérification de l'existence d'un Pelican_Cache.
     *
     * @param string $path Chemin physique
     *
     * @return boolean
     */
    public function isAlive($path = "");

    /**
     * Lecture d'un Pelican_Cache.
     *
     * @param string $path Chemin physique du fichier
     *
     * @return string le contenu du fichier
     */
    public function readCache($path);

    /**
     * Supprime les application/caches répondant au pattern recherché.
     *
     * @param string  $name
     * @param string  $dir
     * @param string  $root
     * @param boolean $defer
     * @param boolean $log
     *
     * @return null|int DB Pelican_Cache ID
     */
    public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false);

    /**
     * Insertion dans la liste des clés de Pelican_Cache d'une entrée.
     *
     * @param string $key
     */
    public function setKeyList($key);

    /**
     * Affectation de la date d'expiration au fichier de Pelican_Cache en fonction de la propriété $lifeTime.
     *
     * @param integer $lifeTime
     */
    public function setLifeTime($lifeTime);

    /**
     * Ecriture d'un fichier de Pelican_Cache sur le disque.
     *
     * @param string $path    Chemin physique du fichier
     * @param string $content Contenu du fichier à écrire
     *
     * @return boolean
     */
    public function storeValue($path, $content = "", $time = "");
    /*
    public function cleanKeyList($aKeys = array());
    public function findKeys($pattern);
    public function close();
    public function clean();
    */
}
