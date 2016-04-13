<?php
/**
 * Response adapter for Pelican_Rss output
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once(pelican_path('Response.Adapter'));

/**
 * Response adapter for Pelican_Rss output
 *
 * @package Pelican
 * @subpackage Response
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Rss extends Pelican_Response_Adapter
{

    /**
     * @see Pelican_Response_Adapter
     * @todo si user_agent contient  "Mozilla" : "text/xml" sinon "application/rss+xml">
     */
    protected $_contentType = 'application/rss+xml';

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
        return 'rss';
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function setUrl()
    {
        $url = str_replace('.rss', '.html', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI']);
        $parse = parse_url($url);
        parse_str($parse['query'], $query);
        $parse['query'] = http_build_query(Array(
            'markup' => 'true'
        ) + $query);
        $this->sourceUrl = http_build_url('', $parse);
    }

    /**
     * __DESC__
     *
     * @access public
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
        $text = $this->_convertHTML2RSS();
        $this->setBody($text);
    }

    /**
     * __DESC__
     *
     * @access private
     * @return __TYPE__
     */
    public function _convertHTML2RSS()
    {
        $this->remoteHost = "balluche.webou.net";
        $this->remoteUrl = '/html2rss.php';
        $this->port = '80';
        //--book
        $s_DATA = 'extract=on&linkpatterntitle=\s.%2B\s&url=' . urlencode($this->getUrl()) . '&title=' . $this->title;
        $rssdata = $this->_sendRequest($s_DATA, 'get');

        return $rssdata;
    }

    /**
     * __DESC__
     *
     * @access private
     * @return __TYPE__
     */
    public function _convertrssCROWD()
    {
        //http://rsscrowd.com/
        require 'rss/rsscrowd.php';

        try {
            // create an API client instance
            $client = new rsscrowd("interakting", "67560851766830a033edc20f8b0ae8c6");
            $client->enableBackgrounds(false);
            //$client->setInitialrssZoomType(FIT_WIDTH);
            //$client->setUserPassword('qwx632');

            // convert a web page and store the generated Pelican_Rss into a $rss variable
            $rssdata = $client->convertURI($this->sourceUrl);

            // send the generated Pelican_Rss
            return $rssdata;

        } catch (rsscrowdException $e) {
            echo "rsscrowd Error: " . $e->getMessage();
        }

    }

    /**
     * __DESC__
     *
     * @access private
     * @param  __TYPE__ $s_POST_DATA __DESC__
     * @return __TYPE__
     */
    public function _sendRequest($s_DATA, $type = 'post')
    {
        if ($type == 'get') {
            $this->remoteUrl .= '?' . $s_DATA;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . $this->remoteHost . ":" . $this->port . $this->remoteUrl);
        curl_setopt($ch, CURLOPT_POST, ($type == 'post'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $s_DATA);
        if ($this->_useurl == ABC_CONVERTER && !empty($this->_cookie))
            curl_setopt($ch, CURLOPT_COOKIE, $this->_cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $this->GatewayResponse = curl_exec($ch);
        if (curl_error($ch) != "") {
            $this->error = "ERROR: " . curl_error($ch) . "<br />\n";

            return false;
        }
        curl_close($ch);
        if (empty($this->_cookie)) {
            @preg_match("/ASP.NET_SessionId[^;]*/s", $this->GatewayResponse, $match);
            $this->_cookie = $match[0];
        }
        @preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $this->GatewayResponse, $match);
        if ($this->_useurl == ABC_CONVERTER)
            @preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $match[2], $match);
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
        header("Content-type: " . $this->getContentType());
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
