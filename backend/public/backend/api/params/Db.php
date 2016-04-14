<?php
/**
 * Permet de créer une connexion à la base de données
 * 
 * @version 1
 */

namespace Api\Params;

use PDO;
use Luracast\Restler\RestException;

class Db
{
    /**
     * Retourne une connexion PDO à la base de données
     *
     * @return PDO
     */
    public static function connect()
    {
        if (!class_exists('PDO')) {
            throw new RestException(500, "PDO is not available on this server, can't connect to database");
        }
        
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s',
            \Pelican::$config['DATABASE_HOST'],
            \Pelican::$config['DATABASE_NAME']
        );
        $user = \Pelican::$config['DATABASE_USER'];
        $pass = \Pelican::$config['DATABASE_PASS'];
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;');
        
        try {
            $dbh = new PDO($dsn, $user, $pass, $options);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $ex) {
            throw new RestException(500, "Can't connect to mysql server");
        }
        
        return $dbh;
    }
}
