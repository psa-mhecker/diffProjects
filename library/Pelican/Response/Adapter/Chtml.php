<?php
/**
 * Response adapter for Chtml (iMode)
 *
 * Params : imode_removetags, imode_entitydecode
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter.Mobile');

/**
 * Response adapter for Chtml (iMode)
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Chtml extends Pelican_Response_Adapter_Mobile
{

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'chtml';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getCharset()
    {
        return 'utf-8';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getContentType()
    {
        return 'text/html';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getContentString()
    {
        return 'text/html; charset=utf-8';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function showXMLheader()
    {
        return '';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function showDocType()
    {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD Compact Pelican_Html 1.0 Draft//EN">';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getXmlnsString()
    {
        return '';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function showHead($showstylesheet = true)
    {
        echo '<title>' . $this->getPageTitle() . "</title>\n";
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $this->setConfigValue('imode_removetags', true);
        $this->setConfigValue('imode_entitydecode', true);
        parent::process($text);

        /** HEAD **/
        $head = $this->getHead();

        /** BODY **/
        $body = $this->getBody();
        // remove xhtml syntaxe for closing tag
        $body = preg_replace('#<([^>]) ?/>#s', '<\1>', $body);
        // TODO: remove colors
        // TODO: remove stylesheet
        // allowable tags: a base blockquote body br center dd dir div dl dt Pelican_Form head h... hr Pelican_Html img input(exept type=image&file) li menu meta(refresh only) ol option(selected, but not value) p plaintext pre select textarea title ul
        if (self::isTrue($this->getConfig('imode_removetags'))) {
            // remove iframe
            $body = preg_replace('#<iframe\s[^>]+ ?/>#is', '', $body);
            $body = preg_replace('#<iframe.+</iframe>#is', '', $body);
            // remove object
            $body = preg_replace('#<object\s[^>]+ ?/>#is', '', $body);
            $body = preg_replace('#<object\s.+</object>#is', '', $body);
            // remove embed
            $body = preg_replace('#<embed\s[^>]+ ?/>#is', '', $body);
            $body = preg_replace('#<embed.+</embed>#is', '', $body);
            // remove applet
            $body = preg_replace('#<applet\s[^>]+ ?/>#is', '', $body);
            $body = preg_replace('#<applet\s.+</applet>#is', '', $body);
            // remove script
            $head = preg_replace('#<script\s[^>]+ ?/>#is', '', $head);
            $head = preg_replace('#<script\s.+</script>#is', '', $head);
            $body = preg_replace('#<script\s[^>]+ ?/>#is', '', $body);
            $body = preg_replace('#<script\s.+</script>#is', '', $body);
        }
        // entity decode
        if (self::isTrue($this->getConfig('imode_entitydecode'))) {
            $body = $this->_processEntityDecode($body);
        }
        $this->_setResponse($head, $body);
    }
}
