<?php
/**
 * Response adapter for XHTML MP.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */

/**
 * Response adapter for XHTML MP.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_CeHtml extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = 'application/ce-html+xml';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'cehtml';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '<?xml version="1.0" encoding="UTF-8"?>';

    protected $_charset = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function setDocType()
    {
        $this->_docType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"ce-html-1.0-transitional.dtd">';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        //$options['cehtml_mime_type']
        $this->setConfigValue('cehtml_content_type', '');
        $this->setConfigValue('cehtml_doctype', 3);
        $this->setConfigValue('cehtml_entity_decode', true);
        $this->setConfigValue('cehtml_remove_scripts', false);
        $this->setConfigValue('cehtml_remove_tags', true);

        $text = preg_replace('# border="(.*?)"#is', '', $text);
        $text = preg_replace('#<meta[^>]+?>#is', '', $text);
        parent::process($text);
        $this->setContentType();
        $this->setDocType();

        /* HEAD **/
        $this->addHead('<style>bodyxx {margin-left : 5px;margin-top : 5px;text-decoration:none;}</style>');

        $head = $this->getHead();

        /* BODY **/
        $body = $this->getBody();
        $head = preg_replace('#<!DOCTYPE\s[^>]+?>#is', '', $head);
        /*        $head = preg_replace('#<html[^>]+?>#is', '', $head);*/
        if ($this->getConfig('cehtml_remove_tags')) {
            $body = $this->_processRemoveTag($body, 'iframe');
            $body = $this->_processRemoveTag($body, 'object');
            $body = $this->_processRemoveTag($body, 'embed');
            $body = $this->_processRemoveTag($body, 'applet');
        }
        if ($this->getConfig('cehtml_remove_scripts')) {
            $head = $this->_processRemoveTag($head, 'script');
            $body = $this->_processRemoveTag($body, 'script');
        }
        if ($this->getConfig('cehtml_entity_decode')) {
            $body = $this->_processEntityDecode($body);
        }

        /*  if ((substr($this->getConfig('browser_token'), 0, 6) == 'MSIE 4') || (substr($this->getConfig('browser_token'), 0, 6) == 'MSIE 5')) {
            $body = preg_replace('#<span([^>]*?)>(\s)?</span>#is', '', $body);
        }*/

        $body = strip_tags($body, '<a>,<script>,<abbr>,<acronym>,<address>,<b>,<base>,<big>,<blockquote>,<body>,<br>,<caption>,<cite>,<code>,<dd>,<dfn>,<div>,<dl>,<dt>,<em>,<fieldset>,<form>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<head>,<hr>,<html>,<i>,<img>,<input>,<kbd>,<label>,<li>,<link>,<meta>,<object>,<ol>,<optgroup>,<option>,<p>,<param>,<pre>,<q>,<samp>,<select>,<small>,<span>,<strong>,<style>,<table>,<td>,<textarea>,<th>,<title>,<tr>,<ul>,<var>');

        $this->_setResponse($head, $body);
    }
}
