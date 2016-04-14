<?php
/**
 * Response adapter dedicated to Apple devices : uses iWebKit Component.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter.Mobile');

/**
 * Response adapter dedicated to Apple devices : uses iWebKit Component.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Apple extends Pelican_Response_Adapter_Mobile
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_docType = '<!DOCTYPE html PUBLIC "-//OMA//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'Apple';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        //config
        $this->setConfigValue('data_theme', "b");
        $this->setConfigValue('data_transition', "");
        /// remove styles
        $text = $this->_processRemoveTag($text, 'link');
        $text = $this->_processRemoveTag($text, 'style');
        //$text = preg_replace('# class="(.*?)"#is', '', $text);
        $text = preg_replace('# style="(.*?)"#is', '', $text);
        // remove empty span
        $text = str_replace('<span></span>', '', $text);

        // navbar : must have <!-- jqm-navbar --> and <!-- /jqm-navbar --> around
        preg_match_all('#<\!-- jqm-navbar -->(.*)<\!-- \/jqm-navbar -->#is', $text, $matches);
        if ($matches) {
            $this->_navbar = str_replace('Accueil', '<img alt="home" src="/library/External/iwebkit/images/home.png" />', strip_tags($matches[1][0], '<a>'));
        }

        // parent adaptation (mobile)
        parent::process($text);
        // extract content
        $head = $this->getHead();
        $body = $this->getBody();
        // specific adaptation
        $body = str_replace('<ul', '<ul class="pageitem" ', $body);
        // <li> with <a>
        $body = preg_replace('#<li([^>]*?)>(\s)+<a(.*?)href="([^"]*)?"([^>]*?)>(.*?)<\/a>(\s)+<\/li>#i', '<li class="menu"><a href="$4"><span class="name">$6</span><span class="arrow"></span></a></li>', $body);
        $body = str_replace('<li>', '<li class="textbox">', $body);
        // external link
        $body = str_replace('target="_blank"', 'class="noeffect"', $body);
        $body = str_replace('</a>, <a', '</a><a', $body);
        //@todo <span class="header">A title</span>
        //@todo <li class="withimage">
        //@todo $body = str_replace(' type="search"', ' type="search" class="searchbox"', $body);
        //@todo <div id="rightnav"><a href="page.html" >text</a></div>

        /* specifique Artisteer */
        $body = str_replace('class="art-block"', 'class="textbox"', $body);
        $body = str_replace('class="active"', 'class="header"', $body);
        $body = str_replace('art-post-body', 'textbox', $body);
        $body = str_replace('art-vmenublockheader', 'textbox', $body);
        $body = str_replace('art-postheader', 'header', $body);
        $body = str_replace('art-post-', 'void-', $body);
        $body = str_replace('art-post', 'pageitem', $body);
        $body = str_replace('art-vmenu-separator', '', $body);
        // body contruction for iwebkit
        $body = '
<div id="topbar">
<div id="title">'.$this->title.'</div>
<div id="leftnav">'.$this->_navbar.'</div>
</div>
<div id="content">'.$body.'</div>';
        // set the content response
        $this->_setResponse($head, $body);
        // iphone and iwebkit head links and meta
        $this->addHead('<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport"></meta>');
        $this->addHead('<link href="/library/External/iwebkit/css/style.css" rel="stylesheet" media="screen" type="text/css" />');
        $this->addHead('<script src="/library/External/iwebkit/javascript/functions.js" type="text/javascript"></script>');
        $this->addHead('<meta name="apple-mobile-web-app-capable" content="yes"></meta>');
        $this->addHead('<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"></meta>');
        $this->addHead('<link rel="apple-touch-icon-precomposed" href="iphone_icon.png" />');
        $this->addHead('<style>.pageitem .header {margin-left : 5px;margin-top : 5px;text-decoration:none;}
    .textbox{font-weight:bold;}
    h2,h3,h4 {text-shadow: #ccc 2px 2px 4px;}
    input, textarea, select {-webkit-border-radius: 4px !important;}
    .pageitemcontent, .art-blockcontent-body{font-weight:normal;}
    .pathway{display:none;}</style>');
    }
}
