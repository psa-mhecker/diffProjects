<?php
require_once 'Zend/Db/Adapter/Oracle.php';
require_once 'Pelican/Db/Oracle.php';

class Pelican_Db_Adapter_Oracle_Oracle extends Zend_Db_Adapter_Oracle
{
     public function queryTab($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
       $result = parent::fetchAll($query,$param);

       return $result;
    }

}
