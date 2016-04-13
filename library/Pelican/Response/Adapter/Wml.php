<?php
/**
 * Response adapter for WML
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once(pelican_path('Response.Adapter'));

/**
 * Response adapter for WML
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Wml extends Pelican_Response_Adapter
{

    /**
     * @see Pelican_Response_Adapter
     */
    private $_doctypes = array(
        '<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
"http://www.wapforum.org/DTD/wml_1.1.xml">',
        '<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.2//EN"
"http://www.wapforum.org/DTD/wml_1.2.xml">'
    );

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_root = 'wml';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '<?xml version="1.0" encoding="UTF-8"?>';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlnsString = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'wml';
    }

    /**
     *
     * Enter description here ...
     * @var unknown_type
     */
    protected $_title = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function setContentType()
    {
        $this->_contentType = 'text/vnd.wap.wml';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function setDocType()
    {
        $this->_docType = $this->_doctypes[$this->getConfig('wml_doctype')];
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $this->setConfigValue('wml_doctype', 1);
        $this->setConfigValue('wml_entity_decode', 1);
        $this->setConfigValue('wml_remove_image', 0);

        $this->setContentType();
        $this->setDocType();
        $text = preg_replace('#<meta[^>]+?>#is', '', $text);
        $text = $this->_processRemoveTag($text, 'iframe');
        $text = $this->_processRemoveTag($text, 'object');
        $text = $this->_processRemoveTag($text, 'embed');
        $text = $this->_processRemoveTag($text, 'applet');
        $text = $this->_processRemoveTag($text, 'script');
        $text = $this->_processRemoveTag($text, 'style');
        $text = $this->_processTableToText($text);

        // title is extracted to buid the toolbar
        preg_match_all('#<title>([^<]*?)</title>#is', $text, $matches);
        if ($matches) {
            $this->_title = strip_tags($matches[1][0]);
        }

        parent::process($text);

        /** HEAD */
        $head = $this->getHead();

        /** BODY */
        $body = $this->getBody();

        /** clean images */
        if (self::isTrue($this->getConfig('remove_image'))) {
            $body = $this->_processRemoveTag($body, 'img');
        } else {
            if (!$this->getConfig('max_image_width')) {
                $this->setConfigValue('max_image_width', $this->_maxImageWidth);
            }
            if (!$this->getConfig('max_image_height')) {
                $this->setConfigValue('max_image_height', $this->_maxImageHeight);
            }
            // correctif pour les marges
            if ($this->getConfig('max_image_width')) {
                $this->setConfigValue('max_image_width', $this->getConfig('max_image_width') - 40);
            }
            if ($this->getConfig('max_image_height')) {
                $this->setConfigValue('max_image_height', $this->getConfig('max_image_height') - 40);
            }
            $body = $this->_processResizeImage($body);
        }

        /** Wml tags */
        $head = trim(strip_tags($head, '<access><meta>'));
        $body = preg_replace('#<h(.*?)>#is', '<big>', $body);
        $body = preg_replace('#</h(.*?)>#is', '</big><br/>', $body);
        $body = preg_replace('#<(ol|ul|dl|div)(.*?)>#i', '<br/>', $body);
        $body = preg_replace('#</(ol|ul|dl)>#i', '', $body);
        $body = preg_replace('#</div>#i', '<br/>', $body);
        $body = preg_replace('#<(dd|li|span)(.*?)>#is', '', $body);
        $body = preg_replace('#</(dd|li)>#i', '<br/>', $body);
        $body = str_ireplace('</span>', '', $body);
        $body = str_replace(' | <br/>', '<br/>', $body);
        $body = preg_replace('#<dt(.*?)>#is', '<strong>', $body);
        $body = str_ireplace('</dt>', '</strong><br/>', $body);
        $body = preg_replace('# link="(.*?)"#is', '', $body);
        $body = preg_replace('# class="(.*?)"#is', '', $body);
        $body = preg_replace('# rel="(.*?)"#is', '', $body);
        $body = preg_replace('# id="(.*?)"#is', '', $body);
        $body = preg_replace('# style="(.*?)"#is', '', $body);
        $body = preg_replace('# title="(.*?)"#is', '', $body);
        $body = preg_replace('# target="(.*?)"#is', '', $body);
        $body = preg_replace('# type="radio"#is', '', $body); /// input type radio n'existe pas => que text et Pelican_Security_Password
        if (self::isTrue($this->getConfig('wml_remove_image'))) {
            $body = $this->_processRemoveTag($body, 'img');
        }
        $body = str_replace('<br/>', '<br>', $body);
        $body = strip_tags($body, '<a><access><anchor><b><big><br><card><do><em><fieldset><go><head><i><img><input><meta><noop><onevent><optgroup><option><p><postfield><prev><refresh><select><setvar><small><strong><table><td><tr><template><timer><u><wml>');
        $body = str_replace('<br />', '<br/>', $body);
        $body = str_replace('<br>', '<br/>', $body);
        $body = preg_replace('#\s\s+#', ' ', $body);
        $body = preg_replace("#(\n|\r)+#", "\n", $body);
        $body = preg_replace('#<br/>( <br/>)+#i', '<br/>', $body);
        $body = preg_replace('#<br/>(<br/>)+#i', '<br/>', $body);
        if ($this->getConfig('wml_entity_decode')) {
            $body = $this->_processEntityDecode($body);
        }
        $body = '<card id="main" title="' . $this->_title . '"><p> ' . "\n" . $body . "\n</p></card>\n";

        /** response */
        $this->_setResponse($head, $body);
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getOutput()
    {
        $this->setHttpHeader();
        $tmp[] = $this->getXmlHead();
        $tmp[] = $this->getDocType();
        $tmp[] = $this->getRootTag(true);
        if ($this->getHead()) {
            $tmp[] = Pelican_Html::head($this->getHead());
        }
        $tmp[] = $this->getBody();
        $tmp[] = $this->getRootTag(false);
        $return = implode("\n", $tmp);
        $return = self::reduce($return);

        return $return;
    }
}
