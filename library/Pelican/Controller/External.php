<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
pelican_import('Controller');
pelican_import('Curl');
include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_OTHER'] . '/Simplehtmldom/simple_html_dom.php');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Controller
 * @author __AUTHOR__
 */
class Pelican_Controller_External extends Pelican_Controller {
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function soapAction() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function wsdlAction() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function restAction() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function xmlrpcAction() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function ftpAction() {
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function httpAction() {
        $url = $this->getParam('param');
        $host = $this->getParam('host');
        $alias = trim($this->getParam('alias'), '/');
        $protocole = $this->_action;
        $data = new stdClass();
        $data->buffer = null;
        $data->url = $host . '/' . $url . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
        self::getBuffer($data);
        $data->buffer = $this->parseLink($data->buffer, $host, $url2, $alias, ($this->_action == 'https'));
        self::extractTags($data);
        $this->getView()->getHead()->setAddon($data->head);
        $this->setResponse($data->body);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function httpsAction() {
        $this->_forward('http');
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getAction() {
        $this->httpAction();
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function postAction() {
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public static function getBuffer($data) {
        $curl_options['post_url'] = $data->url;
        $curl_options['verifyhost'] = 1;
        $curl_options['debug'] = 0;
        $curl_options['brute_force'] = 0;
        
        /**
         "basic",
         "gssnegotiate",
         "digest",
         "ntlm",
         "anysafe",
         "any"
         $curl_options['httpauth'] = $data->httpauth;
         $curl_options['httpauth_username'] = $data->username;
         $curl_options['httpauth_password'] = $data->password;
         $curl_options['integrationtype'] = 1;
         */
        $data->buffer = Pelican_Curl::readPage($curl_options, $status, true);
        /*if (! Pelican_Text::isUTF8 ( $data->buffer )) {
        $data->buffer = utf8_encode ( $data->buffer );
        }*/
        return $data;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $url __DESC__
     * @return __TYPE__
     */
    public function getIntegratedURL($url) {
        return $url;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $content __DESC__
     * @param __TYPE__ $url __DESC__
     * @param string $path (option) __DESC__
     * @param string $alias (option) __DESC__
     * @param bool $https (option) __DESC__
     * @return __TYPE__
     */
    public function parseLink($content, $url, $path = '', $alias = '', $https = false) {
        $return = $content;
        $protocole = 'http';
        if ($https) {
            $protocole = 'https';
        }
        $pathinfo = pathinfo($path);
        $parse = parse_url($protocole . '://' . $url);
        if ($pathinfo['extension']) {
            $lightpath = dirname($path);
        } else {
            $lightpath = trim($path, '/');
        }
        $host = $parse['host'];
        
        if (! $alias) {
            $alias = '_/' . $protocole . '/' . $host;
        }
        
        $return = str_replace(array(
            'url(./' , 
            'href="./' , 
            'src="./' , 
            'action="./' 
        ), array(
            'url(' . $protocole . '://' . $host . '/' . ($lightpath ? $lightpath . '/' : '') , 
            'href="' . $protocole . '://' . $host . '/' . ($lightpath ? $lightpath . '/' : '') , 
            'src="' . $protocole . '://' . $host . '/' . ($lightpath ? $lightpath . '/' : '') , 
            'action="' . $protocole . '://' . $host . '/' . ($lightpath ? $lightpath . '/' : '') 
        ), $return);
        $return = str_replace(array(
            'url(//' , 
            'href="//' , 
            'src="//' , 
            'action="//' , 
            'href=\'//' , 
            'src=\'//' , 
            'action=\'//'
        ), array(
            'url(' . $protocole . '://' , 
            'href="' . $protocole . '://' , 
            'src="' . $protocole . '://' , 
            'action="' . $protocole . '://' , 
            'href=\'' . $protocole . '://' , 
            'src=\'' . $protocole . '://' , 
            'action=\'' . $protocole . '://'
        ), $return);
        $return = str_replace(array(
            'url(/' , 
            'url("/',
            'href="/' , 
            'src="/' , 
            'src=\'/' , 
            'action="/' , 
            'href="' , 
            'src="' , 
            'action="'
        ), array(
            'url(' . $protocole . '://' . $host . '/' , 
            'url("' . $protocole . '://' . $host . '/',
            'href="' . $protocole . '://' . $host . '/' , 
            'src="' . $protocole . '://' . $host . '/' , 
            'src=\'' . $protocole . '://' . $host . '/' , 
            'action="' . $protocole . '://' . $host . '/' , 
            'href="' . $protocole . '://' . $host . ($path ? '/' . $path : '') . '/' , 
            'src="' . $protocole . '://' . $host . ($path ? '/' . $path : '') . '/' , 
            'action="' . $protocole . '://' . $host . ($path ? '/' . $path : '') . '/'
        ), $return);
        $return = str_replace(array(
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '//' , 
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '/' . $protocole , 
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '/#' , 
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '/javascript' , 
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '/mailto'
        ), array(
            $protocole . '://' . $host . ($path ? '/' . $path : '') . '/' , 
            $protocole , 
            '#' , 
            'javascript' , 
            'mailto'
        ), $return);
        
        //citroen
        $return = str_replace(array(
            "'src', '/",
            '"/Images',
            "'/Images",
            '"/Resultat',
            '"/resultat',
            'files/'
        ), array(
            "'src', '" . $protocole . '://' . $host . "/",
            '"' . $protocole . '://' . $host . '/Images',
            "'" . $protocole . '://' . $host . '/Images',
            '"/' . $alias . '/Resultat',
            '"/' . $alias . '/resultat',
            '/promotions-citroen/files/'
        ), $return);
        // end citroen

        // if ($alias) {
            $return = str_replace('action="' . $protocole . '://' . $host . '/', 'action="/' . $alias . '/', $return);
        // } else {
        // $return = str_replace('action="' . $protocole . '://' . $host . '/', 'action="/_/' . $protocole . '/' . $host . '/', $return);
        // }
        $regexp = "/<a(.+?)href=[\"'`]" . $protocole . "\:\/\/" . str_replace("/", "\\/", $host) . "([^\"'`]*?)[\"'`]([^>]*?)>/i";
        // if ($alias) {
            $return = preg_replace($regexp, '<a$1href="/' . $alias . '$2"$3>', $return);
        // } else {
        // $return = preg_replace($regexp, '<a$1href="/_/' . $protocole . '/' . $host . '$2"$3>', $return);
        // }
        
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $data __DESC__
     * @return __TYPE__
     */
    public function extractTags($data) {
        pelican_import('Html.Extract');
        $temp = new Pelican_Html_Extract($data->buffer);
        $data->head = $temp->getTags('head');
        $data->body = $temp->getTags('body');
        if (! $data->body & ! $data->head) {
            $data->body = $data->buffer;
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sourceUrl __DESC__
     * @param string $baseUrl (option) __DESC__
     * @return __TYPE__
     */
    public function encapsulate($sourceUrl, $baseUrl = "") {
        if (empty($localUri)) {
            $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        }
        $data = new stdClass();
        $data->buffer = null;
        $data->header = null;
        $data->body = null;
        $data->baseURL = null;
        $data->sourceUrl = $sourceUrl;
        $data->fullURL = $targetUri;
        $data->fullURL = str_replace('&', '&amp;', $data->fullURL);
        $data->integratedURL = self::$data->integratedURL($data->sourceUrl);
        self::getBuffer($data);
        if (!$data->buffer) {
            $result = false;
            return $result;
        }
        //we set the backtrack_limit to twice the buffer length just in case!
        $backtrack_limit = ini_get('pcre.backtrack_limit');
        ini_set('pcre.backtrack_limit', strlen($data->buffer) * 2);
        $pattern = '#<head[^>]*>(.*?)<\/head>.*?<body[^>]*>(.*)<\/body>#si';
        preg_match($pattern, $data->buffer, $temp);
        if (count($temp) == 3) {
            $data->header = $temp[1];
            $data->body = $temp[2];
        }
        unset($temp);
        // Check if we found something
        if (!strlen($data->header) || !strlen($data->body)) {
            if (!empty($data->buffer)) {
                //non Pelican_Html output, return without parsing
                die($data->buffer);
            } else {
                unset($data->buffer);
                //no output returned
                error(500, t('NO_HTML'));
            }
        } else {
            unset($data->buffer);
            // Add the header information
            if (isset($data->header)) {
                $regex_header = array();
                $replace_header = array();
                //change the page title
                $pattern = '#<title>(.*?)<\/title>#si';
                preg_match($pattern, $data->header, $page_title);
                $this->getView()->head->setTitle(html_entity_decode($page_title[1], ENT_QUOTES, "utf-8"));
                $regex_header[] = $pattern;
                $replace_header[] = '';
                //set meta data to that of softwares
                $meta = array('keywords', 'description', 'robots');
                foreach($meta as $m) {
                    $pattern = '#<meta name=["|\']' . $m . '["|\'](.*?)content=["|\'](.*?)["|\'](.*?)>#Si';
                    if (preg_match($pattern, $data->header, $page_meta)) {
                        if ($page_meta[2]) {
                            $this->getView()->head->setMetaData($m, $page_meta[2]);
                        }
                        $regex_header[] = $pattern;
                        $replace_header[] = '';
                    }
                }
                $pattern = '#<meta name=["|\']generator["|\'](.*?)content=["|\'](.*?)["|\'](.*?)>#Si';
                if (preg_match($pattern, $data->header, $page_generator)) {
                    if ($page_generator[2]) {
                        /****** generator ******/
                    }
                    $regex_header[] = $pattern;
                    $replace_header[] = '';
                }
                $regex_header[] = '#<meta http-equiv=["|\']Content-Type["|\'](.*?)>#Si';
                $replace_header[] = '';
                //remove above set meta data from software's header
                $data->header = preg_replace($regex_header, $replace_header, $data->header);
                /**** parsing du head *****/
                /**** chemin de fer *****/
            }
            //restore the backtrack_limit
            ini_set('pcre.backtrack_limit', $backtrack_limit);
        }
        return $data;
    }
}
