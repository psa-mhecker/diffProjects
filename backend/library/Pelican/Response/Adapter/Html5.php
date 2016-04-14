<?php
/**
 * __DESC__.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter.Mobile');

/**
 * __DESC__.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Html5 extends Pelican_Response_Adapter_Mobile
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_docType = '<!DOCTYPE html PUBLIC "-//OMA//DTD XHTML Mobile 1.2//EN"
"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'html5';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $this->setConfigValue('data_theme', "b");
        $this->setConfigValue('data_transition', "");

        /* remove styles */
        $text = $this->_processRemoveTag($text, 'link');
        $text = $this->_processRemoveTag($text, 'style');
        $text = preg_replace('# class="(.*?)"#is', '', $text);
        $text = preg_replace('# style="(.*?)"#is', '', $text);
        $text = str_replace('<span></span>', '', $text);

        // submenu
        $text = str_replace('<!-- jqm-ul -->', '</li></ul>', $text);
        $text = str_replace('<!-- /jqm-ul -->', '<ul data-role="listview" data-inset="true" >', $text);

        // mobile standard
        parent::process($text);
        if (strpos($this->getHead(), '/jquery') === false) {
            $this->addHead('<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>');
        }
        $version = '1.0a1';
        $version = '1.0a4.1';
        $version = '1.3.2';
        $structure = '';//.structure';

        $this->addHead('<link rel="stylesheet" href="http://code.jquery.com/mobile/'.$version.'/jquery.mobile'.$structure.'-'.$version.'.min.css" />');
        $this->addHead('<script src="http://code.jquery.com/mobile/'.$version.'/jquery.mobile-'.$version.'.min.js"></script>');
        $this->addHead('<meta name="apple-mobile-web-app-capable" content="yes" />');
        $this->addHead('<link rel="apple-touch-icon-precomposed" href="iphone_icon.png" />');
        $this->addHead('<script>$(document).bind("mobileinit", function () {$.extend(  $.mobile , { defaultTransition: "flip", loadingMessage: "Chargement..."});});</script>');

        /* HEAD **/
        $head = $this->getHead();

        /* BODY **/
        $body = $this->getBody();

        $body = str_replace('<ul', '<ul data-role="listview" data-inset="true" ', $body);
        $body = str_replace('type="button"', 'type="button" data-role="button" data-theme="'.$this->getConfig('data_theme').'"', $body);
        $body = str_replace('type="submit"', 'type="submit" data-role="button" data-theme="'.$this->getConfig('data_theme').'"', $body);
        $body = str_replace('type="reset"', 'type="reset" data-role="button" data-theme="'.$this->getConfig('data_theme').'"', $body);
        if ($this->getConfig('data_transition')) {
            $body = str_replace('<a ', '<a data-transition="'.$this->getConfig('data_transition').'" ', $body);
        }
        $body = str_replace('</a>, <a', '</a><a', $body);
        $body = '<div data-role="page" data-theme="'.$this->getConfig('data_theme').'" id="jqm-home">
<div data-role="header" data-theme="'.$this->getConfig('data_theme').'">
<h1>'.$this->_title.'</h1>'.$this->_navbar.'
</div><!-- /header -->
<div data-role="content"><a name="top"></a>
'.$body.'</div><!-- /content -->
<div data-role="footer" data-theme="'.$this->getConfig('data_theme').'" class="ui-bar">'.$this->footer.'</div><!-- /header -->
</div><!-- /layout -->';

        // set the content response
        $this->_setResponse($head, $body);
        // force input type="hidden" to not be displayed
        $this->addHead('<style>input[type="hidden"] {visibility: hidden;display: none;}</style>');
    }
}
