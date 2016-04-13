<?php
/**
 * Response adapter for XHTML MP
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once(pelican_path('Response.Adapter.Mobile'));

/**
 * Response adapter for XHTML MP
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Xhtmlmp extends Pelican_Response_Adapter_Mobile
{

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_doctypes = array(
        '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD WML 2.0//EN"
 "http://www.wapforum.org/dtd/wml20.dtd">',
        '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
 "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">',
        '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN"
 "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">',
        '<!DOCTYPE html PUBLIC "-//OMA//DTD XHTML Mobile 1.2//EN"
 "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">',
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN"
 "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN"
 "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">',
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
        '<!DOCTYPE html SYSTEM "-//W3C//DTD Pelican_Html 4.0//EN"
 "html40-mobile.dtd">'
    );

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $_preferredDoctypes = array(
        'html4' => '<!DOCTYPE html PUBLIC "-//W3C//DTD Pelican_Html 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">',
        'html5' => '<!DOCTYPE HTML>',
        'xhtml_basic' => '<!DOCTYPE html PUBLIC  "-//W3C//DTD XHTML Basic 1.0//EN"
 "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
        'xhtml_mp1' => '<!DOCTYPE html PUBLIC  "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
 "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">',
        'xhtml_mp11' => '<!DOCTYPE html PUBLIC  "-//WAPFORUM//DTD XHTML Mobile 1.1//EN"
 "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">',
        'xhtml_mp12' => '<!DOCTYPE html PUBLIC  "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
 "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">',
        'xhtml_transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
    );

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentTypes = array(
        'application/vnd.wap.xhtml+xml',
        'application/xhtml+xml',
        'text/html',
        'text/xhtml'
    );

    /**
     * __DESC__
     *
     * @access protected
     */
    protected $_httpAccept = array(
        'xhtml' => 'application/xhtml+xml',
        'html' => 'text/html',
        'wml' => 'text/vnd.wap.wml',
        'mhtml' => 'application/vnd.wap.xhtml+xml'
    );

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '<?xml version="1.0" encoding="UTF-8"?>';

    protected $_charset = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'xhtmlmp';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function setContentType()
    {
        if ($this->getConfig('xhtmlmp_content_type'))
            $this->_contentType = $this->_setContentTypeAuto();
        else {
            // $item = $this->getConfig('xhtmlmp_content_type');
            $item = 0;
            $this->_contentType = $this->_contentTypes[$item];
        }
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function setDocType()
    {
        $this->_docType = $this->_doctypes[$this->getConfig('xhtmlmp_doctype')];
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        //$options['xhtmlmp_mime_type']
        $this->setConfigValue('xhtmlmp_content_type', '');
        $this->setConfigValue('xhtmlmp_doctype', 3);
        $this->setConfigValue('xhtmlmp_entity_decode', true);
        $this->setConfigValue('xhtmlmp_remove_scripts', false);
        $this->setConfigValue('xhtmlmp_remove_tags', true);

        $text = preg_replace('# border="(.*?)"#is', '', $text);
        $text = preg_replace('#<meta[^>]+?>#is', '', $text);
        parent::process($text);
        $this->setContentType();
        $this->setDocType();

        /** HEAD **/
        $this->addHead('<style>bodyxx {margin-left : 5px;margin-top : 5px;text-decoration:none;}</style>');

        $head = $this->getHead();

        /** BODY **/
        $body = $this->getBody();
        $head = preg_replace('#<!DOCTYPE\s[^>]+?>#is', '', $head);
        /*        $head = preg_replace('#<html[^>]+?>#is', '', $head);*/
        if ($this->getConfig('xhtmlmp_remove_tags')) {
            $body = $this->_processRemoveTag($body, 'iframe');
            $body = $this->_processRemoveTag($body, 'object');
            $body = $this->_processRemoveTag($body, 'embed');
            $body = $this->_processRemoveTag($body, 'applet');
        }
        if ($this->getConfig('xhtmlmp_remove_scripts')) {

            $head = $this->_processRemoveTag($head, 'script');
            $body = $this->_processRemoveTag($body, 'script');
        }
        if ($this->getConfig('xhtmlmp_entity_decode')) {
            $body = $this->_processEntityDecode($body);
        }

        /*  if ((substr($this->getConfig('browser_token'), 0, 6) == 'MSIE 4') || (substr($this->getConfig('browser_token'), 0, 6) == 'MSIE 5')) {
            $body = preg_replace('#<span([^>]*?)>(\s)?</span>#is', '', $body);
        }*/

        $body = strip_tags($body, '<a>,<script>,<abbr>,<acronym>,<address>,<b>,<base>,<big>,<blockquote>,<body>,<br>,<caption>,<cite>,<code>,<dd>,<dfn>,<div>,<dl>,<dt>,<em>,<fieldset>,<form>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<head>,<hr>,<html>,<i>,<img>,<input>,<kbd>,<label>,<li>,<link>,<meta>,<object>,<ol>,<optgroup>,<option>,<p>,<param>,<pre>,<q>,<samp>,<select>,<small>,<span>,<strong>,<style>,<table>,<td>,<textarea>,<th>,<title>,<tr>,<ul>,<var>');

        $this->_setResponse($head, $body);
    }

    /**
     * Determines th conten type based on http accept header
     *
     * @access protected
     * @return string
     */
    public function _setContentTypeAuto()
    {
        if ($this->getConfig('xhtmlmp_mime_type')) {
            $return = $this->getConfig('xhtmlmp_mime_type');
        } elseif (!isset($_SERVER['HTTP_ACCEPT'])) {
            $return = 'text/html';
        } else {
            $matches = array();
            $c = array();
            foreach ($this->_httpAccept as $mime_markup => $mime_type) {
                $c[$mime_markup] = 0;
                if (stristr($_SERVER['HTTP_ACCEPT'], $mime_type)) {
                    if (preg_match('|' . str_replace(array(
                        '/',
                        '.',
                        '+'
                    ), array(
                        '\/',
                        '\.',
                        '\+'
                    ), $mime_type) . ';q=(0\.\d+)|i', $_SERVER['HTTP_ACCEPT'], $matches))
                        $c[$mime_markup] += (float) $matches[1];
                    else
                        $c[$mime_markup] ++;
                }
            }
            $max = max($c);
            foreach ($c as $mime_markup => $val) {
                if ($val != $max) {
                    unset($c[$mime_markup]);
                }
            }
            $mime = 'html';
            if (array_key_exists('html', $c)) {
                if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'Profile/MIDP-2.0 Configuration/CLDC-1.1') && array_key_exists('xhtml', $c)) {
                    $mime = 'xhtml';
                } else {
                    $mime = 'html';
                }
            } elseif (array_key_exists('xhtml', $c)) {
                $mime = 'xhtml';
            } elseif (array_key_exists('mhtml', $c)) {
                $mime = 'mhtml';
            } elseif (array_key_exists('wml', $c)) {
                $mime = 'wml';
            }
            $return = $this->_httpAccept[$mime];
        }

        return $return;
    }
}
