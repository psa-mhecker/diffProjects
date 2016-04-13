<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter.Html5');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Tablet extends Pelican_Response_Adapter_Html5
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
        return 'tablet';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {

        // mobile standard
        parent::process($text);

        /** HEAD **/
        $head = $this->getHead();

        /** BODY **/
        $body = $this->getBody();
        $body = str_replace('data-theme="b"', 'data-theme="a"', $body);
        /*$body = str_replace('data-role="navbar">', 'data-role="navbar"><div><a class="shareme" href="http://www.russellbeattie.com/blog"
    onclick="showMenu(this);return false">Menu</a></div>', $body);

        $body .= '<ul id="tablet_menu">
<li><a href="#">Tweet</a></li>
<li><a href="#">Facebook</a></li>
<li><a href="#">Email</a></li>
</ul><script>document.body.onload = "document.body.onload = function () {
                initMenu();alert(1);
            }"</script>';*/

        // set the content response
        $this->_setResponse($head, $body);
        $this->addHead('<link rel="stylesheet" type="text/css" href="/library/Pelican/Response/Adapter/Tablet/public/tablet.css" />
<script src="/library/Pelican/Response/Adapter/Tablet/public/tablet.js" type="text/javascript"></script>
<style>
    @media all and (orientation:portrait) { /* Your style here */ }
    @media all and (orientation:landscape) { /* Your style here */ }
</style>');
    }
}
