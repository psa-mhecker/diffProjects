<?php
include_once(dirname(__FILE__) . '.php');

class Cms_Page_Module_Navigation_2levels extends Cms_Page_Module_Navigation
{

    public static function render(Pelican_Controller $controller)
    {
        self::$second = true;
        
        $max = 5;
        
        $maxRow = 8; 
        
        return parent::render($controller);
    }
}
