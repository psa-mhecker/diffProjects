<?php
/**
 */
class Pelican_Debug_Plugin_Gpcs implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'variables';

    /**
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;

    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Variables.
     */
    public function __construct()
    {
    }

    /**
     * Gets identifier for this Pelican_Plugin.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu Pelican_Index_Tab for the Debugbar.
     *
     * @return string
     */
    public function getTab()
    {
        return ' GPCS';
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {

        /*$vars .= '<h4>$_GET</h4>' . '<div id="ZFDebug_post">' . debug($_GET, "GET", false) . '</div>';
        $vars .= '<h4>$_POST</h4>' . '<div id="ZFDebug_post">' . debug($_POST, "POST", false) . '</div>';
        $vars .= '<h4>$_COOKIE</h4>' . '<div id="ZFDebug_post">' . debug($_COOKIE, "COOKIE", false) . '</div>';
        $vars .= '<h4>$_SERVER</h4>' . '<div id="ZFDebug_post">' . debug($_SERVER, "SERVER", false) . '</div>';
        if (! empty(Pelican::$config["SITE"])) {
            $vars .= '<h4>Pelican::$config["SITE"]</h4>' . '<div id="ZFDebug_post">' . debug(Pelican::$config['SITE'], "SITE", false) . '</div>';
        }
        if (! empty(Pelican::$config["ENV"])) {
            $vars .= '<h4>Pelican::$config["ENV"]</h4>' . '<div id="ZFDebug_post">' . debug(Pelican::$config['ENV'], "ENV", false) . '</div>';
        }*/

        $panel = Pelican_Debug::getFieldset('$_GET', debug($_GET, "GET", false, '', false));
        $panel .= Pelican_Debug::getFieldset('$_POST', debug($_POST, "POST", false, '', false));
        $panel .= Pelican_Debug::getFieldset('$_COOKIE', debug($_COOKIE, "COOKIE", false, '', false));
        $panel .= Pelican_Debug::getFieldset('$_SERVER', debug($_SERVER, "SERVER", false, '', false));
        if (! empty(Pelican::$config["SITE"])) {
            $panel .= Pelican_Debug::getFieldset('Pelican::$config["SITE"]', debug(Pelican::$config['SITE'], "SITE", false, '', false));
        }
        if (! empty(Pelican::$config["ENV"])) {
            $panel .= Pelican_Debug::getFieldset('Pelican::$config["ENV"]', debug(Pelican::$config['ENV'], "ENV", false, '', false));
        }

        return $panel;
    }
}
