<?php
/**
 * @package    Pelican
 * @subpackage Pelican_Debug
 */
class Pelican_Debug_Plugin_Html implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'html';

    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Html
     *
     * @param  string $tab
     * @paran string $panel
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Gets identifier for this plugin
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu Pelican_Index_Tab for the Debugbar
     *
     * @return string
     */
    public function getTab()
    {
        return 'Pelican_Html';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $body = "";
        $panel = '<h4>HTML Information</h4>';
        $panel .= '
        <script type="text/javascript" charset="utf-8">
            var ZFHtmlLoad = window.onload;
            window.onload = function () {
                if (ZFHtmlLoad) {
                    ZFHtmlLoad();
                }
                jQuery("#ZFDebug_Html_Tagcount").html(document.getElementsByTagName("*").length);
                jQuery("#ZFDebug_Html_Stylecount").html(jQuery("link[rel*=stylesheet]").length);
                jQuery("#ZFDebug_Html_Scriptcount").html(jQuery("script[src]").length);
                jQuery("#ZFDebug_Html_Imgcount").html(jQuery("img[src]").length);
            };
        </script>';
        $panel .= '<span id="ZFDebug_Html_Tagcount"></span> Tags<br />'
                . 'HTML Size: '.round(strlen($body)/1024, 2).'K<br />'
                . '<span id="ZFDebug_Html_Stylecount"></span> Stylesheet Files<br />'
                . '<span id="ZFDebug_Html_Scriptcount"></span> Javascript Files<br />'
                . '<span id="ZFDebug_Html_Imgcount"></span> Images<br />'
                . '<form method="POST" action="http://validator.w3.org/check" target="_blank"><input type="hidden" name="fragment" value="'.htmlentities($body).'"><input type="submit" value="Validate With W3"></form>';

        return $panel;
    }
}
