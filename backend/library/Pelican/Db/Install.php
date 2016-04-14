<?php
/**
 * Classe de génération de scripts de base de données.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Classe de génération de scripts de base de données.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Db_Install
{
    /**
     * Constructeur.
     *
     * @access public
     *
     * @param string $type Type de base de données
     */
    public function __construct($type)
    {
        $this->type = ucfirst($type);
        pelican_import('Db.'.$this->type);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $text  __DESC__
     * @param __TYPE__ $title __DESC__
     *
     * @return __TYPE__
     */
    public function error($text, $title)
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function is_available()
    {
        return function_exists('pg_connect');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $type __DESC__
     * @param __TYPE__ $host __DESC__
     * @param __TYPE__ $name __DESC__
     * @param __TYPE__ $user __DESC__
     * @param __TYPE__ $pwd  __DESC__
     *
     * @return __TYPE__
     */
    public function testDb($type, $host, $name, $user, $pwd)
    {
        Pelican::$config['db']['djc_pqsql']["DATABASE_HOST"] = $host;
        Pelican::$config['db']['test']["DATABASE_TYPE"] = $type;
        Pelican::$config['db']['test']["DATABASE_NAME"] = $name;
        Pelican::$config['db']['test']["DATABASE_USER"] = $user;
        Pelican::$config['db']['test']["DATABASE_PASS"] = $pwd;
        if (!self::is_available()) {
            self::error(t('PHP '.$type.' support not enabled.'), 'error');

            return false;
        }
        $connection = Pelican_Db::getInstance('test');
        if (!$connection) {
            self::error(t('Failed to connect to your '.$type.' Pelican_Db server. '.$type), 'error');

            return false;
        }
        $success = array('CONNECT');
        // Test CREATE.
        $sql = 'CREATE TABLE test_install (ID integer NOT NULL)';
        $result = $oConnection->query($connection, $query);
        if (!$result) {
            self::error(t('Failed to create a test table on your '.$type.' Pelican_Db server'), 'error');

            return false;
        }
        $err = false;
        $success[] = 'SELECT';
        $success[] = 'CREATE';
        // Test INSERT.
        $query = 'INSERT INTO test_install (id) VALUES (1)';
        $result = $oConnection->query($connection, $query);
        if (!$result) {
            self::error(t('Failed to insert a value into a test table on your '.$type.' Pelican_Db server.'), 'error');
            $err = true;
        } else {
            $success[] = 'INSERT';
        }
        // Test UPDATE.
        $query = 'UPDATE test_install SET id = 2';
        $result = $oConnection->query($connection, $query);
        if (!$result) {
            self::error(t('Failed to update a value in a test table on your '.$type.' Pelican_Db server.'), 'error');
            $err = true;
        } else {
            $success[] = 'UPDATE';
        }
        // Test DELETE.
        $query = 'DELETE FROM test_install';
        $result = $oConnection->query($connection, $query);
        if ($error = pg_result_error()) {
            self::error(t('Failed to delete a value from a test table on your '.$type.' Pelican_Db server.'), 'error');
            $err = true;
        } else {
            $success[] = 'DELETE';
        }
        // Test DROP.
        $query = 'DROP TABLE test_install';
        $result = $oConnection->query($connection, $query);
        if ($error = pg_result_error()) {
            self::error(t('Failed to drop a test table from your '.$type.' Pelican_Db server.'), 'error');
            $err = true;
        } else {
            $success[] = 'DROP';
        }
        if ($err) {
            return false;
        }

        return true;
    }
}
