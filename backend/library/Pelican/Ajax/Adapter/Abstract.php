<?php
abstract class Pelican_Ajax_Adapter_Abstract
{
    abstract public static function getJsCall();

    abstract public static function getHead();

    abstract public static function getResponse();
}
