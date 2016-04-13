<?php
/**
 * Response adapter for Microsoft Word
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter');

/**
 * Response adapter for Microsoft Word
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Doc extends Pelican_Response_Adapter
{

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_charset = 'utf-8';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = 'application/msword'; //'application/octet-stream'

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_docType = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'doc';
    }

        /**
     * Return header of MS Doc
     *
     * @access private
     * @return String
     */
    public function _getOfficeHead()
    {
        $XmlHead = <<<EOH
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="ProgId" content="Word.Document">
            <meta name="Generator" content="Microsoft Word 9">
            <meta name="Originator" content="Microsoft Word 9">
            <!--[if !mso]>
            <style>
            v\:* {behavior:url(#default#VML);}
            o\:* {behavior:url(#default#VML);}
            w\:* {behavior:url(#default#VML);}
            .shape {behavior:url(#default#VML);}
            </style>
            <![endif]-->
            <title>$this->title</title>
            <!--[if gte mso 9]><xml>
             <w:WordDocument>
              <w:View>Print</w:View>
              <w:DoNotHyphenateCaps/>
              <w:PunctuationKerning/>
              <w:DrawingGridHorizontalSpacing>9.35 pt</w:DrawingGridHorizontalSpacing>
              <w:DrawingGridVerticalSpacing>9.35 pt</w:DrawingGridVerticalSpacing>
             </w:WordDocument>
            </xml><![endif]-->
            <style>
            <!--
             /* Font Definitions */
            @font-face
                {font-family:Verdana;
                panose-1:2 11 6 4 3 5 4 4 2 4;
                mso-font-charset:0;
                mso-generic-font-family:swiss;
                mso-font-pitch:variable;
                mso-font-signature:536871559 0 0 0 415 0;}
             /* Style Definitions */
            p.MsoNormal, li.MsoNormal, div.MsoNormal
                {mso-style-parent:"";
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                font-size:7.5pt;
                    mso-bidi-font-size:8.0pt;
                font-family:"Verdana";
                mso-fareast-font-family:"Verdana";}
            p.small
                {mso-style-parent:"";
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                font-size:1.0pt;
                    mso-bidi-font-size:1.0pt;
                font-family:"Verdana";
                mso-fareast-font-family:"Verdana";}
            @page Section1
                {size:8.5in 11.0in;
                margin:1.0in 1.25in 1.0in 1.25in;
                mso-header-margin:.5in;
                mso-footer-margin:.5in;
                mso-paper-source:0;}
            div.Section1
                {page:Section1;}
            -->
            </style>
            <!--[if gte mso 9]><xml>
             <o:shapedefaults v:ext="edit" spidmax="1032">
              <o:colormenu v:ext="edit" strokecolor="none"/>
             </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>
             <o:shapelayout v:ext="edit">
              <o:idmap v:ext="edit" data="1"/>
             </o:shapelayout></xml><![endif]-->
EOH;

        return $XmlHead;
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function setHttpHeader()
    {
        header("Cache-Control: "); // leave blank to avoid IE errors
        header("Pragma: "); // leave blank to avoid IE errors
        header("Content-type: " . $this->getContentType());
        header("Content-Disposition: attachment; filename=\"$this->title\".doc");
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getRootTag($start = true)
    {
        if ($start) {
            return '<html xmlns:v="urn:schemas-microsoft-com:vml"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:w="urn:schemas-microsoft-com:office:word"
            xmlns="http://www.w3.org/TR/REC-html40">' . $this->_getOfficeHead();
        } else {
            return '</html>';
        }
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        // title is extracted to buid the toolbar
        preg_match_all('#<title>([^<]*?)</title>#is', $text, $matches);
        if ($matches) {
            $this->title = strip_tags($matches[1][0]);
        }

        $text = $this->_processRemoveTag($text, 'script');
        $text = $this->_processRemoveTag($text, 'meta');
        $text = $this->_processRemoveTag($text, 'iframe');
        $text = $this->_processRemoveTag($text, 'object');
        $text = $this->_processRemoveTag($text, 'embed');
        $text = $this->_processRemoveTag($text, 'applet');
        $text = $this->_processRemoveTag($text, 'input');
        $text = $this->_processRemoveTag($text, 'form');
        $text = $this->_processRemoveTag($text, 'select');
        parent::process($text);
        $this->addHead($this->getXmlHead());
    }
}
