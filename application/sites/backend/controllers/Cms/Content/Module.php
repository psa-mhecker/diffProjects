<?php

abstract class Cms_Content_Module
{

    public static $decachePublication;

    public static $decacheBack;

    abstract static function render(Pelican_Controller $controller);

    public static function save(Pelican_Controller $controller)
    {}

    public static function addCache(Pelican_Controller $controller)
    {
        if (isset(self::$decacheBack)) {
            if (isset($controller->decacheBack)) {
                $controller->decacheBack = self::$decacheBack;
            } else {
                $controller->decacheBack = array_merge($controller->decacheBack, self::$decacheBack);
            }
        }
        
        if (isset(self::$decachePublication)) {
            if (isset($controller->decachePublication)) {
                $controller->decachePublication = self::$decachePublication;
            } else {
                $controller->decachePublication = array_merge($controller->decachePublication, self::$decachePublication);
            }
        }
    }

}