<?php
include_once dirname(__FILE__).'.php';

class Cms_Page_Module_Navigation_1level extends Cms_Page_Module_Navigation
{
    public static function render(Pelican_Controller $controller)
    {
        self::$second = false;

        return parent::render($controller);
    }
}
