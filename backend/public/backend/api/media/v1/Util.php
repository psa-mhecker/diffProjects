<?php
/**
 * Classe utilitaire
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1;

use PDO;
use Pelican;
use Exception;
use Luracast\Restler\RestException;

class Util
{
    /**
     * Retourne une connexion à la base de données
     *
     * @return PDO
     */
    public static function getDbh()
    {
        if (!class_exists('PDO')) {
            throw new RestException(500, "PDO is not available on this server, can't connect to database");
        }
        
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s',
            Pelican::$config['DATABASE_HOST'],
            Pelican::$config['DATABASE_NAME']
        );
        $user = Pelican::$config['DATABASE_USER'];
        $pass = Pelican::$config['DATABASE_PASS'];
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;');
        
        try {
            $dbh = new PDO($dsn, $user, $pass, $options);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw new RestException(500, "Can't connect to mysql server", array('dbError' => true));
        }
        
        return $dbh;
    }
    
    /**
     * Fonction récursive qui limite la profondeur de la hiérarchie des dossiers médiathèque
     *
     * @param MediaNode $node Noeud de la hiérarchie à limiter
     * @param int $depthLevel Niveau de profondeur courant
     * @param int $maxLevel Niveau de profondeur maximal
     */
    public static function depthLimit(Type\MediaNode $node, $depthLevel, $maxLevel)
    {
        // Shrink des enfants du noeud courant (condition d'arrêt)
        if ($depthLevel >= $maxLevel) {
            $node->MediaNode = array();
        }
        
        // Appel récursif sur les noeuds enfants
        foreach ($node->MediaNode as $key => $val) {
            self::depthLimit($val, $depthLevel + 1, $maxLevel);
        }
    }
}
