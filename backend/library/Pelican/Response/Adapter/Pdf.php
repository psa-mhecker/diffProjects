<?php
/**
 * Response adapter for PDF output.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once pelican_path('Response.Adapter');
define("WK_CONVERTER", 1);
define("ABC_CONVERTER", 2);
define("EASYW_CONVERTER", 3);
define('PDFCROWD_CONVERTER', 4);

/**
 * Response adapter for PDF output.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Pdf extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_charset = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_contentType = 'application/pdf';

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
        return 'pdf';
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function setUrl()
    {
        $url = str_replace('.pdf', '.html', 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI']);
        $parse = parse_url($url);
        parse_str($parse['query'], $query);
        $parse['query'] = http_build_query(array(
            'markup' => 'true',
        ) + $query);
        $this->sourceUrl = http_build_url('', $parse);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getUrl()
    {
        return $this->sourceUrl;
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

        $this->setUrl();
        $text = $this->_convertWK();
        //$text = $this->_convertABC();
        //$text = $this->_convertEASYW();
        //$text = $this->_convertPDFCROWD();
        $this->setBody($text);
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    public function _convertWK()
    {
        //http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf-0.9.9-doc.html
        $this->remoteHost = "phputil.dev4";
        $this->remoteUrl = '/convertpdf/convert.php';
        $this->port = '80';
        //--book
        $s_POST_DATA = 'options='.urlencode('--title "'.$this->title.'" --forms --print-media-type --no-background').'&url='.urlencode($this->getUrl());
        $pdfdata = $this->_sendRequest($s_POST_DATA);
        //--no-background
        return $pdfdata;
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    public function _convertABC()
    {
        $this->remoteHost = "64.39.14.230";
        $this->remoteUrl = "/pdf-net/cleardoc.aspx";
        $this->_sendRequest($s_POST_DATA);
        $s_POST_DATA = "url=".urlencode($this->sourceUrl);
        $s_POST_DATA .= "&PagedOutput=on";
        $s_POST_DATA .= "&AddLinks=on";
        $s_POST_DATA .= "&x=30";
        $s_POST_DATA .= "&y=30";
        $s_POST_DATA .= "&w=550";
        $s_POST_DATA .= "&h=704";
        $s_POST_DATA .= "&UserName=";
        $s_POST_DATA .= "&Password=";
        $s_POST_DATA .= "&Timeout=15550";
        $s_POST_DATA .= "&Submit=Add URL";
        var_dump($s_POST_DATA);
        $this->remoteUrl = "/pdf-net/addurl.aspx";
        $this->_sendRequest($s_POST_DATA);
        $this->remoteUrl = "/pdf-net/showdoc.aspx";
        $s_POST_DATA = "";
        $pdfdata = $this->_sendRequest($s_POST_DATA);
        if ($pdfdata === false) {
            return false;
        }

        return $pdfdata;
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    public function _convertEASYW()
    {
        //http://www.easysw.com/htmldoc/pdf-o-matic.php
        $this->remoteUrl = "/htmldoc/pdf-o-matic.php";
        $this->remoteHost = "www.easysw.com";
        $s_POST_DATA = "URL=".urlencode($this->sourceUrl);
        $s_POST_DATA .= "&FORMAT=.pdf";
        $pdfdata = @file_get_contents("http://".$this->remoteHost.$this->remoteUrl."?".$s_POST_DATA);

        return $pdfdata;
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    public function _convertPDFCROWD()
    {
        //http://pdfcrowd.com/
        require 'Pdf/pdfcrowd.php';

        try {
            // create an API client instance
            $client = new Pdfcrowd("interakting", "67560851766830a033edc20f8b0ae8c6");
            $client->enableBackgrounds(false);
            //$client->setInitialPdfZoomType(FIT_WIDTH);
            //$client->setUserPassword('qwx632');

            // convert a web page and store the generated PDF into a $pdf variable
            $pdfdata = $client->convertURI($this->sourceUrl);

            // send the generated PDF
            return $pdfdata;
        } catch (PdfcrowdException $e) {
            echo "Pdfcrowd Error: ".$e->getMessage();
        }
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @param __TYPE__ $s_POST_DATA __DESC__
     *
     * @return __TYPE__
     */
    public function _sendRequest($s_POST_DATA)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$this->remoteHost.":".$this->port.$this->remoteUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $s_POST_DATA);
        if ($this->_useurl == ABC_CONVERTER && !empty($this->_cookie)) {
            curl_setopt($ch, CURLOPT_COOKIE, $this->_cookie);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $this->GatewayResponse = curl_exec($ch);
        if (curl_error($ch) != "") {
            $this->error = "ERROR: ".curl_error($ch)."<br />\n";

            return false;
        }
        curl_close($ch);
        if (empty($this->_cookie)) {
            @preg_match("/ASP.NET_SessionId[^;]*/s", $this->GatewayResponse, $match);
            $this->_cookie = $match[0];
        }
        @preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $this->GatewayResponse, $match);
        if ($this->_useurl == ABC_CONVERTER) {
            @preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $match[2], $match);
        }
        $this->GatewayResponse = $match[2];

        return $this->GatewayResponse;
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function setHttpHeader()
    {
        header("Cache-Control: "); // leave blank to avoid IE errors
        header("Pragma: "); // leave blank to avoid IE errors
        header("Content-type: ".$this->getContentType());
        header("Content-Disposition: attachment; filename=\"$this->downloadFile\"");
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function getOutput()
    {
        $this->setHttpHeader();
        $return = $this->getBody();

        return $return;
    }
}
