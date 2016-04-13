<?php
abstract class Pelican_Ajax_Adapter_Abstract
{

    abstract static function getJsCall();

    abstract static function getHead();

    abstract static function getResponse();
}
?>