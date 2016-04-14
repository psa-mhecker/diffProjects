<?php
/**
 * Response adapter for Microsoft Excel.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once pelican_path('Response.Adapter');

/**
 * Response adapter for Microsoft Excel.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Xls extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_charset = 'utf-8';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = 'application/msexcel'; //'application/octet-stream'

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
     * @see Pelican_Response_Adapter
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Return header of MS Doc.
     *
     * @access private
     *
     * @return String
     */
    public function _getOfficeHead()
    {
        $XmlHead = <<<EOH
                <meta name=ProgId content=Excel.Sheet>
                <!--[if gte mso 9]><xml>
                 <o:DocumentProperties>
                  <o:LastAuthor>Sriram</o:LastAuthor>
                  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
                  <o:Version>10.2625</o:Version>
                 </o:DocumentProperties>
                 <o:OfficeDocumentSettings>
                  <o:DownloadComponents/>
                 </o:OfficeDocumentSettings>
                </xml><![endif]-->
                <style>
                <!--table
                    {mso-displayed-decimal-separator:"\.";
                    mso-displayed-thousand-separator:"\,";}
                @page
                    {margin:1.0in .75in 1.0in .75in;
                    mso-header-margin:.5in;
                    mso-footer-margin:.5in;}
                tr
                    {mso-height-source:auto;}
                col
                    {mso-width-source:auto;}
                br
                    {mso-data-placement:same-cell;}
                .style0
                    {mso-number-format:General;
                    text-align:general;
                    vertical-align:bottom;
                    white-space:nowrap;
                    mso-rotate:0;
                    mso-background-source:auto;
                    mso-pattern:auto;
                    color:windowtext;
                    font-size:10.0pt;
                    font-weight:400;
                    font-style:normal;
                    text-decoration:none;
                    font-family:Arial;
                    mso-generic-font-family:auto;
                    mso-font-charset:0;
                    border:none;
                    mso-protection:locked visible;
                    mso-style-name:Normal;
                    mso-style-id:0;}
                td
                    {mso-style-parent:style0;
                    padding-top:1px;
                    padding-right:1px;
                    padding-left:1px;
                    mso-ignore:padding;
                    color:windowtext;
                    font-size:10.0pt;
                    font-weight:400;
                    font-style:normal;
                    text-decoration:none;
                    font-family:Arial;
                    mso-generic-font-family:auto;
                    mso-font-charset:0;
                    mso-number-format:General;
                    text-align:general;
                    vertical-align:bottom;
                    border:none;
                    mso-background-source:auto;
                    mso-pattern:auto;
                    mso-protection:locked visible;
                    white-space:nowrap;
                    mso-rotate:0;}
                .xl24
                    {mso-style-parent:style0;
                    white-space:normal;}
                -->
                </style>
                <!--[if gte mso 9]><xml>
                 <x:ExcelWorkbook>
                  <x:ExcelWorksheets>
                   <x:ExcelWorksheet>
                    <x:Name>srirmam</x:Name>
                    <x:WorksheetOptions>
                     <x:Selected/>
                     <x:ProtectContents>False</x:ProtectContents>
                     <x:ProtectObjects>False</x:ProtectObjects>
                     <x:ProtectScenarios>False</x:ProtectScenarios>
                    </x:WorksheetOptions>
                   </x:ExcelWorksheet>
                  </x:ExcelWorksheets>
                  <x:WindowHeight>10005</x:WindowHeight>
                  <x:WindowWidth>10005</x:WindowWidth>
                  <x:WindowTopX>120</x:WindowTopX>
                  <x:WindowTopY>135</x:WindowTopY>
                  <x:ProtectStructure>False</x:ProtectStructure>
                  <x:ProtectWindows>False</x:ProtectWindows>
                 </x:ExcelWorkbook>
                </xml><![endif]-->
EOH;

        return $XmlHead;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Response_Adapter::setHeader()
     *
     * @return __TYPE__
     */
    public function setHttpHeader()
    {
        header("Cache-Control: "); // leave blank to avoid IE errors
        header("Pragma: "); // leave blank to avoid IE errors
        header("Content-type: ".$this->getContentType());
        header("Content-Disposition: attachment; filename=\"".$this->title.".xls\"");
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getRootTag($start = true)
    {
        if ($start) {
            return '<html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">'.$this->_getOfficeHead();
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
