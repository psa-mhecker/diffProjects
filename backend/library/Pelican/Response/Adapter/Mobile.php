<?php
/**
 * Generic Response adapter dedicated to mobile devices.
 *
 * Params : remove_image, remove_applet, image_host
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter');
ini_set('lib.output_compression', 1);

/**
 * Generic Response adapter dedicated to mobile devices.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Mobile extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = 'application/xhtml+xml';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_maxImageWidth = "360";

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_maxImageHeight = "310";

    /**
     * Enter description here ...
     *
     * @var string
     */
    protected $_footer = '';

    protected $_navbar = '';

    /**
     * Enter description here ...
     *
     * @var string
     */
    protected $_title = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'mobile';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        //////onclick="document.location.href=
        // remplacement des liens "#"
        //$this->setConfig('remove_image', false);
        //$this->setConfig('remove_applet', true);
        //$text = preg_replace('#<a(.*?)href="\#"([^>]*?)>(.*?)<\/a>#i', '$3', $text);
        $text = str_replace('href="#"', '', $text);
        // footer : must have <!-- jqm-navbar --> and <!-- /jqm-navbar --> around
        preg_match_all('#<\!-- jqm-footer -->(.*)<\!-- \/jqm-footer -->#is', $text, $matches);
        if ($matches) {
            $this->_footer = strip_tags($matches[1][0]);
        }

        // title is extracted to buid the toolbar
        preg_match_all('#<title>([^<]*?)</title>#is', $text, $matches);
        if ($matches) {
            $this->_title = strip_tags($matches[1][0]);
        }

        // navbar : must have <!-- jqm-navbar --> and <!-- /jqm-navbar --> around
        preg_match_all('#<\!-- jqm-navbar -->(.*)<\!-- \/jqm-navbar -->#is', $text, $matches);
        if ($matches) {
            $this->_navbar = '<div data-role="navbar">'.$matches[1][0].'</div>';
        }

        parent::process($text);

        /* HEAD **/
        $head = $this->getHead();

        /* BODY **/
        $body = $this->getBody();

        /* clean Meta */
        $head = $this->_processCleanMeta($head);

        /* clean BR */
        $body = $this->_processBr($body);

        /* clean target */
        $body = $this->_processRemoveTargetBlank($body);

        /* clean images */
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
                $this->setConfigValue('max_image_width', ($this->getConfig('max_image_width') > 40 ? $this->getConfig('max_image_width') - 40 : $this->getConfig('max_image_width')));
            }
            if ($this->getConfig('max_image_height')) {
                $this->setConfigValue('max_image_height', ($this->getConfig('max_image_height') > 40 ? $this->getConfig('max_image_height') - 40 : $this->getConfig('max_image_height')));
            }
            $body = $this->_processResizeImage($body);
        }

        /* @todo */
        //$body = str_replace('"document.location.href', '"void()" href', $body);


        /* clean columns */
        $body = $this->_processColumns($body);

        /* clean iframe */
        $body = $this->_processIframe($body);

        /* clean flash */
        $body = $this->_processFlash($body);
        //xhtml_file_upload : not_supported, supported, supported _user_intervention
        //dual_orientation
        //xhtml_file_upload 	not_supported
        $body = $this->_processTableToText($body);
        if (self::isTrue($this->getConfig('remove_applet')) || !self::isTrue($this->getConfig('j2me_midp_2_0'))) {
            $body = $this->_processRemoveTag($body, 'applet');
        }

        $this->_setResponse($head, $body);
        $this->addHead('<meta name="HandheldFriendly" content="true"></meta>');
        $this->addHead('<meta name="MobileOptimized" content="'.$this->getConfig('resolution_width').'"></meta>');
        $this->addHead('<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"></meta>');
        $this->addHead('<meta name="viewport" content="target-densitydpi=device-dpi"></meta>');
    }
}
