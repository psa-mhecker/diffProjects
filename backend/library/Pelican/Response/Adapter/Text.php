<?php
/**
 * Response adapter for Text Browser.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once pelican_path('Response.Adapter');

/**
 * Response adapter for Text Browser.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Text extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlnsString = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_docType = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'text';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process2($text)
    {
        $search = array(
            '@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',  // Strip out Pelican_Html tags
            '@<style[^>]*?>.*?</style>@siU',  // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@',
        ); // Strip multi-line comments including CDATA
        $return = preg_replace($search, '', $text);

        return $return;
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $text = $this->_processRemoveTag($text, 'iframe');
        $text = $this->_processRemoveTag($text, 'object');
        $text = $this->_processRemoveTag($text, 'embed');
        $text = $this->_processRemoveTag($text, 'applet');
        $text = $this->_processRemoveTag($text, 'script');
        $text = $this->_processRemoveTag($text, 'style');
        $text = $this->_processTableToText($text);
        $text = preg_replace('# link="(.*?)"#is', '', $text);
        $text = preg_replace('# class="(.*?)"#is', '', $text);
        $text = preg_replace('# target="(.*?)"#is', '', $text);
        $text = preg_replace('# rel="(.*?)"#is', '', $text);
        $text = preg_replace('# id="(.*?)"#is', '', $text);
        $text = preg_replace('# style="(.*?)"#is', '', $text);
        $text = preg_replace('# title="(.*?)"#is', '', $text);
        parent::process($text);
    }
}
