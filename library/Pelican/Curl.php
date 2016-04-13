<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Curl
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Curl
 * @author __AUTHOR__
 */
class Pelican_Curl {
    
    /**
     * Retrieve the cookies as a string cookiename=cookievalue; or as an array
     *
     * @access public
     * @param string $type (option) The type
     * @return string
     */
    public function buildCookie($type = 'string') {
        switch ($type) {
            case 'array':
                return $_COOKIE;
            break;
            case 'string':
            default:
                return self::implodeCookies($_COOKIE, ';');
            break;
        }
        return false;
    }
    
    /**
     * Can implode an array of any dimension
     * Uses a few basic rules for implosion:
     *
     * 1. Replace all instances of delimeters in strings by '/' followed by delimeter
     * 2. 2 Delimeters in between keys
     * 3. 3 Delimeters in between key and value
     * 4. 4 Delimeters in between key-value pairs
     *
     * @access public
     * @param array $array Array
     * @param string $delimeter Delemeter
     * @param string $keyssofar (option) Keyssofar
     * @return string
     */
    public function implodeCookies($array, $delimeter, $keyssofar = '') {
        $output = '';
        foreach($array as $key => $value) {
            if (!is_array($value)) {
                if ($keyssofar) {
                    $pair = $keyssofar . '[' . $key . ']=' . urlencode($value) . $delimeter;
                } else {
                    $pair = $key . '=' . urlencode($value) . $delimeter;
                }
                if ($output != '') {
                    $output.= ' ';
                }
                $output.= $pair;
            } else {
                if ($output != '') {
                    $output.= ' ';
                }
                $output.= self::implodeCookies($value, $delimeter, $key . $keyssofar);
            }
        }
        return $output;
    }
    
    /**
     * Curl redir exec
     *
     * @access public
     * @staticvar $curlLoops 0
     * @staticvar xx $curlMaxLoops 20
     * @param string $ch Ch
     * @return string
     */
    public function curlRedirExec($ch) {
        static $curlLoops = 0;
        static $curlMaxLoops = 20;
        if ($curlLoops++ >= $curlMaxLoops) {
            $curlLoops = 0;
            return false;
        }
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $lastData = $data;
        $data = str_replace("\r", '', $data);
        list($header, $data) = explode("\n\n", $data, 2);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            $matches = array();
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $url = @parse_url(trim(array_pop($matches)));
            if (!$url) {
                //couldn't process the url to redirect to
                $curlLoops = 0;
                return $data;
            }
            $lastUrl = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
            /*      if (!$url['scheme'])
            $url['scheme'] = $lastUrl['scheme'];
            if (!$url['host'])
            $url['host'] = $lastUrl['host'];
            if (!$url['path'])
            $url['path'] = $lastUrl['path'];
            */
            $newUrl = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query'] ? '?' . $url['query'] : '');
            curl_setopt($ch, CURLOPT_URL, $newUrl);
            return self::curlRedirExec($ch);
        } else {
            $curlLoops = 0;
            return $lastData;
        }
    }
    
    /**
     * Function readHeader
     * Basic  code was found on Svetlozar Petrovs website
     *
     * Http://svetlozar.net/layout/free-code.html.
     *
     * The code is free to use and similar code can be found on other places on the
     * net.
     *
     * @access public
     * @param string $ch Ch
     * @param string $string String
     * @return string
     */
    public function readHeader($ch, $string) {
        global $location;
        global $cookiearr;
        global $ch;
        global $cookiesToSet;
        global $cookiesToSetIndex;
        $length = strlen($string);
        if (!strncmp($string, "Location:", 9)) {
            $location = trim(substr($string, 9, -1));
        }
        if (!strncmp($string, "Set-Cookie:", 11)) {
            header($string, false);
            $cookiestr = trim(substr($string, 11, -1));
            $cookie = explode(';', $cookiestr);
            $cookiesToSet[$cookiesToSetIndex] = $cookie;
            $cookiesToSetIndex++;
            $cookie = explode('=', $cookie[0]);
            $cookiename = trim(array_shift($cookie));
            $cookiearr[$cookiename] = trim(implode('=', $cookie));
        }
        $cookie = "";
        if (!empty($cookiearr) && (trim($string) == "")) {
            foreach($cookiearr as $key => $value) {
                $cookie.= "$key=$value; ";
            }
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        return $length;
    }
    
    /**
     * Function parseURL
     * out[0] = full url
     *
     * Out[1] = scheme or '' if no scheme was found
     * out[2] = username or '' if no Pelican_Auth username was found
     * out[3] = Pelican_Security_Password or '' if no Pelican_Auth password was found
     * out[4] = domain name or '' if no domain name was found
     * out[5] = port number or '' if no port number was found
     * out[6] = path or '' if no path was found
     * out[7] = query or '' if no query was found
     * out[8] = fragment or '' if no fragment was found
     *
     * @access public
     * @param string $url Url
     * @return array
     */
    public function parseUrl($url) {
        $r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?';
        $r.= '(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';
        preg_match($r, $url, $out);
        return $out;
    }
    
    /**
     * Parses cookies
     *
     * @access public
     * @param array $cookielines Cookies
     * @return array
     */
    public function parsecookies($cookielines) {
        $line = array();
        $cookies = array();
        foreach($cookielines as $line) {
            $cdata = array();
            $data = array();
            foreach($line as $data) {
                $cinfo = explode('=', $data);
                $cinfo[0] = trim($cinfo[0]);
                if (!isset($cinfo[1])) {
                    $cinfo[1] = '';
                }
                if (strcasecmp($cinfo[0], 'expires') == 0) {
                    $cinfo[1] = strtotime($cinfo[1]);
                }
                if (strcasecmp($cinfo[0], 'secure') == 0) {
                    $cinfo[1] = "true";
                }
                if (strcasecmp($cinfo[0], 'httponly') == 0) {
                    $cinfo[1] = "true";
                }
                if (in_array(strtolower($cinfo[0]), array('domain', 'expires', 'path', 'secure', 'comment', 'httponly'))) {
                    $cdata[trim($cinfo[0]) ] = $cinfo[1];
                } else {
                    $cdata['value']['key'] = $cinfo[0];
                    $cdata['value']['value'] = $cinfo[1];
                }
            }
            $cookies[] = $cdata;
        }
        return $cookies;
    }
    
    /**
     * Adds a cookie to the php header
     *
     * @access public
     * @param string $name Cookie name
     * @param string $value (option) Cookie value
     * @param int $expires (option) Cookie expiry time
     * @param string $cookiepath (option) Cookie path
     * @param string $cookiedomain (option) Cookie domain
     * @param string $secure (option) Secure
     * @param string $httponly (option) Is the cookie http only
     * @param string $crossdomain_url (option) Cross domain url
     * @return string
     */
    public function addCookie($name, $value = '', $expires = 0, $cookiepath = '', $cookiedomain = '', $secure = 0, $httponly = 0, $crossdomain_url = '') {
        // Versions of PHP prior to 5.2 do not support HttpOnly cookies
        // IE is buggy when specifying a blank domain so set the cookie manually
        // solve the empty cookiedomain IE problem by specifying a domain in the plugin's parameters. <------
        if (version_compare(phpversion(), "5.2.0", ">=")) {
            setcookie($name, $value, $expires, $cookiepath, $cookiedomain, $secure, $httponly);
        } else {
            setcookie($name, $value, $expires, $cookiepath, $cookiedomain, $secure);
        }
        if ($crossdomain_url) {
            //$jc = Factory::getCookies();
            $jc->addCookie($crossdomain_url, $name, $value, $expires, $cookiepath, $cookiedomain, $secure, $httponly);
        }
    }
    
    /**
     * Sets my cookies
     *
     * @access public
     * @param string $status Cookie name
     * @param string $mycookiesToSet Cookie value
     * @param string $cookiedomain Cookie domain
     * @param string $cookiepath Cookie path
     * @param string $expires (option) Expires
     * @param string $secure (option) Secure
     * @param string $httponly (option) Is the cookie http only
     * @param string $crossdomain_url (option) Cross domain url
     * @return string
     */
    public function setMyCookies($status, $mycookiesToSet, $cookiedomain, $cookiepath, $expires = 0, $secure = 0, $httponly = 1, $crossdomain_url = '') {
        $cookies = array();
        $cookies = self::parsecookies($mycookiesToSet);
        foreach($cookies as $cookie) {
            $name = "";
            $value = "";
            if ($expires == 0) {
                $expires_time = 0;
            } else {
                $expires_time = time() + $expires;
            }
            if (isset($cookie['value']['key'])) {
                $name = $cookie['value']['key'];
            }
            if (isset($cookie['value']['value'])) {
                $value = $cookie['value']['value'];
            }
            if (isset($cookie['expires'])) {
                $expires_time = $cookie['expires'];
            }
            if (!$cookiepath) {
                if (isset($cookie['path'])) {
                    $cookiepath = $cookie['path'];
                }
            }
            if (!$cookiedomain) {
                if (isset($cookie['domain'])) {
                    $cookiedomain = $cookie['domain'];
                }
            }
            self::addCookie($name, urldecode($value), $expires_time, $cookiepath, $cookiedomain, $secure, $httponly, $crossdomain_url);
            if (($expires_time) == 0) {
                $expires_time = 'Session_cookie';
            } else {
                $expires_time = date('d-m-Y H:i:s', $expires_time);
            }
            $status['debug'][] = t('CREATED') . ' ' . t('COOKIE') . ': ' . t('NAME') . '=' . $name . ', ' . t('VALUE') . '=' . urldecode($value) . ', ' . t('EXPIRES') . '=' . $expires_time . ', ' . t('COOKIE_PATH') . '=' . $cookiepath . ', ' . t('COOKIE_DOMAIN') . '=' . $cookiedomain . ', ' . t('COOKIE_SECURE') . '=' . $secure . ', ' . t('COOKIE_HTTPONLY') . '=' . $httponly;
            if ($name == 'MOODLEID_') {
                $status['cURL']['moodle'] = urldecode($value);
            }
        }
        return $status;
    }
    
    /**
     * Delete my cookies
     *
     * @access public
     * @param string $status Cookie name
     * @param string $mycookiesToSet Cookie value
     * @param string $cookiedomain Cookie domain
     * @param string $cookiepath Cookie path
     * @param string $leavealone Leavealone
     * @param string $secure (option) Secure
     * @param string $httponly (option) Is the cookie http only
     * @param string $crossdomain_url (option) Cross domain url
     * @return string
     */
    public function deleteMyCookies($status, $mycookiesToSet, $cookiedomain, $cookiepath, $leavealone, $secure = 0, $httponly = 1, $crossdomain_url = '') {
        $cookies = array();
        $cookies = self::parsecookies($mycookiesToSet);
        // leavealone keys/values while deleting
        // the $leavealone is an array of key=value that controls cookiedeletion
        // key = value
        // if key is an existing cookiename then that cookie will be affected depending on the value
        // if value = '>' then the 'name' cookies with an expiration date/time > now() will not be deleted
        // if value = '0' then  the 'name' cookies will never be deleted at all
        // if name is a string than the cookie with that name will be affected
        // if name = '0' then all cookies will be affected according to the value
        // thus
        // MOODLEID_=> keeps the cookie with the name MOODLEID_ if expirationtime lies after now()
        // 0=> will keep all cookies that are not sessioncookies
        // 0=0 will keep all cookies
        if ($leavealone) {
            $leavealonearr = array();
            $lines = array();
            $line = array();
            $lines = explode(',', $leavealone);
            $i = 0;
            foreach($lines as $line) {
                $cinfo = explode('=', $line);
                $leavealonearr[$i]['name'] = $cinfo[0];
                $leavealonearr[$i]['value'] = $cinfo[1];
                $i++;
            }
        }
        foreach($cookies as $cookie) {
            // check if we schould leave the cookie alone
            $leaveit = false;
            if ($leavealone) {
                for ($i = 0;$i < count($leavealonearr);$i++) {
                    if (isset($cookie['value']['key'])) {
                        if (($cookie['value']['key'] == $leavealonearr[$i]['name']) || ($leavealonearr[$i]['name'] == '0')) {
                            if (($leavealonearr[$i]['value'] == '0') || ($cookie['expires'] > time())) {
                                $leaveit = true;
                            }
                        }
                    }
                }
            }
            $name = "";
            $value = "";
            if (isset($cookie['value']['key'])) {
                $name = $cookie['value']['key'];
            }
            if (isset($cookie['expires'])) {
                $expires_time = $cookie['expires'];
            }
            if (!$cookiepath) {
                if (isset($cookie['path'])) {
                    $cookiepath = $cookie['path'];
                }
            }
            if (!$cookiedomain) {
                if (isset($cookie['domain'])) {
                    $cookiedomain = $cookie['domain'];
                }
            }
            if ($name == 'MOODLEID_') {
                $status['cURL']['moodle'] = urldecode($cookie['value']['value']);
            }
            if (!$leaveit) {
                $expires_time = time() - 30 * 60;
                $value = '';
                self::addCookie($name, urldecode($value), $expires_time, $cookiepath, $cookiedomain, $secure, $httponly, $crossdomain_url);
                if (($expires_time) == 0) {
                    $expires_time = 'Session_cookie';
                } else {
                    $expires_time = date('d-m-Y H:i:s', $expires_time);
                }
                $status['debug'][] = t('DELETED') . ' ' . t('COOKIE') . ': ' . t('NAME') . '=' . $name . ', ' . t('VALUE') . '=' . urldecode($value) . ', ' . t('EXPIRES') . '=' . $expires_time . ', ' . t('COOKIE_PATH') . '=' . $cookiepath . ', ' . t('COOKIE_DOMAIN') . '=' . $cookiedomain . ', ' . t('COOKIE_SECURE') . '=' . $secure . ', ' . t('COOKIE_HTTPONLY') . '=' . $httponly;
            } else {
                self::addCookie($name, urldecode($cookie['value']['value']), $expires_time, $cookiepath, $cookiedomain, $secure, $httponly, $crossdomain_url);
                if (($expires_time) == 0) {
                    $expires_time = 'Session_cookie';
                } else {
                    $expires_time = date('d-m-Y H:i:s', $expires_time);
                }
                $status['debug'][] = t('LEFT_ALONE') . ' ' . t('COOKIE') . ': ' . t('NAME') . '=' . $name . ', ' . t('VALUE') . '=' . urldecode($cookie['value']['value']) . ', ' . t('EXPIRES') . '=' . $expires_time . ', ' . t('COOKIE_PATH') . '=' . $cookiepath . ', ' . t('COOKIE_DOMAIN') . '=' . $cookiedomain . ', ' . t('COOKIE_SECURE') . '=' . $secure . ', ' . t('COOKIE_HTTPONLY') . '=' . $httponly;
            }
        }
        return $status;
    }
    
    /**
     * *
     * Function readPage
     *
     * This function will read a page of an integration
     *
     * Caller should make sure that the Curl extension is loaded
     *
     * @access public
     * @param array $curl_options Curl options
     * @param __TYPE__ $status __DESC__
     * @param bool $curlinit (option) __DESC__
     * @return string
     */
    public function readPage($curl_options, &$status, $curlinit = true) {
        global $ch;
        global $cookiearr;
        global $cookiesToSet;
        global $cookiesToSetIndex;
        $cookiesToSet = array();
        $cookiesToSetIndex = 0;
        $open_basedir = ini_get('open_basedir');
        $safe_mode = ini_get('safe_mode');
        // read the page
        if ($curlinit) {
            $ch = curl_init();
        }
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_URL, $curl_options['post_url']);
        $ckfile = tempnam("/tmp", "CURLCOOKIE");
        curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
        if (count($_POST) > 0) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        }
        curl_setopt($ch, CURLOPT_REFERER, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curl_options['verifyhost']);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, $curl_options['debug']); // Display commonication with server
        if (empty($open_basedir) && empty($safe_mode)) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array('Pelican_Curl', 'readHeader'));
        if (empty($curl_options['brute_force'])) {
            curl_setopt($ch, CURLOPT_COOKIE, self::buildCookie());
        }
        if (!empty($curl_options['httpauth'])) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$curl_options['httpauth_username']}:{$curl_options['httpauth_password']}");
            switch ($curl_options['httpauth']) {
                case "basic":
                    $curl_options['httpauth'] = CURLAUTH_BASIC;
                break;
                case "gssnegotiate":
                    $curl_options['httpauth'] = CURLAUTH_GSSNEGOTIATE;
                break;
                case "digest":
                    $curl_options['httpauth'] = CURLAUTH_DIGEST;
                break;
                case "ntlm":
                    $curl_options['httpauth'] = CURLAUTH_NTLM;
                break;
                case "anysafe":
                    $curl_options['httpauth'] = CURLAUTH_ANYSAFE;
                break;
                case "any":
                default:
                    $curl_options['httpauth'] = CURLAUTH_ANY;
            }
            curl_setopt($ch, CURLOPT_HTTPAUTH, $curl_options['httpauth']);
        }
        if (empty($open_basedir) && empty($safe_mode)) {
            $remotedata = curl_exec($ch);
        } else {
            $remotedata = self::curlRedirExec($ch);
        }
        if ($curl_options['debug']) {
            $status['cURL']['data'][] = $remotedata;
            $status['debug'][] = 'CURL_INFO' . ': ' . print_r(curl_getinfo($ch), true);
        }
        if (curl_error($ch)) {
            $status['error'][] = t('CURL_ERROR_MSG') . ": " . curl_error($ch);
            curl_close($ch);
            return null;
        }
        if ($curl_options['integrationtype'] == 1) {
            curl_close($ch);
        }
        return $remotedata;
    }
    
    /**
     * Function remoteLogin
     * Smart function to programatically login to an  integration
     *
     * Will determine what to post (including, optionally, hidden form inputs) and
     * what cookies to set.
     * Will then login.
     * In addition to username and password the function only needs an URL to a page
     * with a loginform
     * and the ID of the loginform.
     * Including button information and hidden input posts is optionally
     *
     * @access public
     * @param array $curl_options Curl options
     * @return string
     */
    public function remoteLogin($curl_options) {
        global $ch;
        global $cookiearr;
        global $cookiesToSet;
        global $cookiesToSetIndex;
        $status = array();
        $tmpurl = array();
        $overridearr = array();
        $newhidden = array();
        $lines = array();
        $line = array();
        $cookiesToSet = array();
        $status['debug'] = array();
        $status['error'] = array();
        $status['cURL'] = array();
        $status['cURL']['data'] = array();
        $cookiesToSetIndex = 0;
        $open_basedir = ini_get('open_basedir');
        $safe_mode = ini_get('safe_mode');
        // check parameters and set defaults
        if (!isset($curl_options['integrationtype'])) {
            $curl_options['integrationtype'] = 1;
        }
        if (!isset($curl_options['relpath'])) {
            $curl_options['relpath'] = false;
        }
        if (!isset($curl_options['hidden'])) {
            $curl_options['hidden'] = false;
        }
        if (!isset($curl_options['buttons'])) {
            $curl_options['buttons'] = false;
        }
        if (!isset($curl_options['override'])) {
            $curl_options['override'] = null;
        }
        if (!isset($curl_options['cookiedomain'])) {
            $curl_options['cookiedomain'] = '';
        }
        if (!isset($curl_options['cookiepath'])) {
            $curl_options['cookiepath'] = '';
        }
        if (!isset($curl_options['expires'])) {
            $curl_options['expires'] = 1800;
        }
        if (!isset($curl_options['input_username_id'])) {
            $curl_options['input_username_id'] = '';
        }
        if (!isset($curl_options['input_password_id'])) {
            $curl_options['input_password_id'] = '';
        }
        if (!isset($curl_options['secure'])) {
            $curl_options['secure'] = 0;
        }
        if (!isset($curl_options['httponly'])) {
            $curl_options['httponly'] = 0;
        }
        if (!isset($curl_options['verifyhost'])) {
            $curl_options['verifyhost'] = 1;
        }
        if (!isset($curl_options['crossdomain_url'])) {
            $curl_options['crossdomain_url'] = '';
        }
        if (!isset($curl_options['debug'])) {
            $curl_options['debug'] = false;
        }
        // find out if we have a SSL enabled website
        if (strpos($curl_options['post_url'], 'https://') === false) {
            $ssl_string = 'http://';
        } else {
            $ssl_string = 'https://';
        }
        // check if curl extension is loaded
        if (!isset($curl_options['post_url']) || !isset($curl_options['formid']) || !isset($curl_options['username']) || !isset($curl_options['password'])) {
            $status['error'][] = t('CURL_FATAL');
            return null;
        }
        if (!extension_loaded('curl')) {
            $status['error'][] = t('CURL_NOTINSTALLED');
            return $status;
        }
        $status['debug'][] = t('CURL_POST_URL_1') . " " . $curl_options['post_url'];
        $remotedata = self::readPage($curl_options, $status, true);
        if (!empty($status['error'])) {
            return $status;
        }
        $status['debug'][] = t('CURL_PHASE_1');
        $status1 = self::setMyCookies($status, $cookiesToSet, $curl_options['cookiedomain'], $curl_options['cookiepath'], $curl_options['expires'], $curl_options['secure'], $curl_options['httponly'], $curl_options['crossdomain_url']);
        $status = array_merge($status, $status1);
        //find out if we have the form with the name/id specified
        $parser = new Pelican_Curl_Form($remotedata);
        $result = $parser->parseForms();
        $frmcount = count($result);
        $myfrm = - 1;
        $i = 0;
        do {
            if (isset($result[$i]['form_data']['name'])) {
                if ($result[$i]['form_data']['name'] == $curl_options['formid']) {
                    $myfrm = $i;
                    break;
                }
            }
            if (isset($result[$i]['form_data']['id'])) {
                if ($result[$i]['form_data']['id'] == $curl_options['formid']) {
                    $myfrm = $i;
                    break;
                }
            }
            $i+= 1;
        }
        while ($i < $frmcount);
        if ($myfrm == - 1) {
            $helpthem = '';
            if ($frmcount > 0) {
                $i = 0;
                $helpthem = 'I found';
                do {
                    if (isset($result[$i]['form_data']['id'])) {
                        $helpthem = $helpthem . ' -- Name=' . $result[$i]['form_data']['name'] . ' &ID=' . $result[$i]['form_data']['id'];
                    }
                    $i+= 1;
                }
                while ($i < $frmcount);
            }
            $status['error'][] = t('CURL_NO_LOGINFORM') . " " . $helpthem;
            return $status;
        }
        $status['debug'][] = t('CURL_VALID_FORM');
        // by now we have the specified  login form, lets get the data needed to login
        // we went to all this trouble to get to the hidden input entries.
        // The stuff is there to enhance Pelican_Security and is, yes, hidden
        $form_action = htmlspecialchars_decode($result[$myfrm]['form_data']['action']);
        $form_method = $result[$myfrm]['form_data']['method'];
        $elements_keys = array_keys($result[$myfrm]['form_elements']);
        $elements_values = array_values($result[$myfrm]['form_elements']);
        $elements_count = count($result[$myfrm]['form_elements']);
        // override keys/values from hidden inputs
        // the $override is an array of keys/values that override existing keys/values
        if ($curl_options['override']) {
            $lines = explode(',', $curl_options['override']);
            foreach($lines as $line) {
                $cinfo = explode('=', $line);
                $overridearr[$cinfo[0]]['value'] = $cinfo[1];
                $overridearr[$cinfo[0]]['type'] = 'hidden';
            }
            $newhidden = array_merge($result[$myfrm]['form_elements'], $overridearr);
            $elements_keys = array_keys($newhidden);
            $elements_values = array_values($newhidden);
            $elements_count = count($newhidden);
        }
        // now construct the action parameter
        // we have 4 possible options:
        // case 0 Pelican_Form action is without httpo.. and relpath = 0 , special case
        // case 1 Pelican_Form action is without http.. and relpath = 1 , just construct the action
        // case 2 form_action is a full url, eg http..... and relpath = 0 This is easy, we do nothing at all
        // case 3 form_action is a full url, eg http..... and relpath = 1 special case
        $rel = (int)($curl_options['relpath']);
        //      if (substr($form_action,0,strlen($ssl_string))== $ssl_string) $hashttp = 2; else $hashttp = 0;
        if (substr($form_action, 0, strlen('http')) == 'http') {
            $hashttp = 2;
        } else {
            $hashttp = 0;
        }
        switch ($rel + $hashttp) {
            case 0:
                //add a / in front of form_action
                if (substr($form_action, 0, 1) != "/") {
                    $form_action = '/' . $form_action;
                }
                // we need to correct various situations like
                // relative url from basedir, relative url from postdir etc
                $tmpurl = self::parseUrl($curl_options['post_url']);
                $pathinfo1 = pathinfo($form_action);
                $pathinfo = pathinfo($tmpurl[6]);
                //$status['debug'][] = 'post_url   : '.print_r($curl_options['post_url'],true);
                //$status['debug'][] = 'tmpurl     : '.print_r($tmpurl,true);
                //$status['debug'][] = 'form_action: '.print_r($form_action,true);
                //$status['debug'][] = 'pathinfo1  : '.print_r($pathinfo1,true);
                //$status['debug'][] = 'pathinfo   : '.print_r($pathinfo,true);
                if ($pathinfo['dirname'] == $pathinfo1['dirname']) {
                    $pathinfo['dirname'] = '';
                } //prevent double directory
                // replace windows DS bt unix DS
                $pathinfo['dirname'] = str_replace("\\", "/", $pathinfo['dirname']);
                // get rid of the trailing /  in dir
                rtrim($pathinfo['dirname'], '/');
                $port = !empty($tmpurl[5]) ? ":" . $tmpurl[5] : '';
                $form_action = $ssl_string . $tmpurl[4] . $port . $pathinfo['dirname'] . $form_action;
                //$status['debug'][] = 'form_action_final: '.print_r($form_action,true);
                
            break;
            case 1:
                //add a / in front of form_action
                if (substr($form_action, 0, 1) != "/") {
                    $form_action = '/' . $form_action;
                }
                $curl_options['post_url'] = rtrim($curl_options['post_url'], '/');
                $form_action = $curl_options['post_url'] . $form_action;
            break;
            case 2:
                //do nothing at all
                
            break;
            case 3:
                // reserved, maybe something pops up, then we use this
                
            break;
        }
        $input_username_name = "";
        for ($i = 0;$i <= $elements_count - 1;$i++) {
            if ($curl_options['input_username_id']) {
                if (strtolower($elements_keys[$i]) == strtolower($curl_options['input_username_id'])) {
                    $input_username_name = $elements_keys[$i];
                    break;
                }
            }
            if ($input_username_name == "") {
                if (strpos(strtolower($elements_keys[$i]), 'user') !== false) {
                    $input_username_name = $elements_keys[$i];
                }
                if (strpos(strtolower($elements_keys[$i]), 'name') !== false) {
                    $input_username_name = $elements_keys[$i];
                }
            }
        }
        if ($input_username_name == "") {
            $status['error'][] = t('CURL_NO_NAMEFIELD');
            return $status;
        }
        $input_password_name = "";
        for ($i = 0;$i <= $elements_count - 1;$i++) {
            if ($curl_options['input_password_id']) {
                if (strtolower($elements_keys[$i]) == strtolower($curl_options['input_password_id'])) {
                    $input_password_name = $elements_keys[$i];
                    break;
                }
            }
            if (strpos(strtolower($elements_keys[$i]), 'pass') !== false) {
                $input_password_name = $elements_keys[$i];
            }
        }
        if ($input_password_name == "") {
            $status['error'][] = t('CURL_NO_PASSWORDFIELD');
            return $status;
        }
        $status['debug'][] = t('CURL_VALID_USERNAME');
        // we now set the submit parameters. These are:
        // all form_elements name=value combinations with value != '' and type hidden
        $strParameters = "";
        if ($curl_options['hidden']) {
            for ($i = 0;$i <= $elements_count - 1;$i++) {
                if (($elements_values[$i]['value'] != '') && ($elements_values[$i]['type'] == 'hidden')) {
                    $strParameters.= '&' . $elements_keys[$i] . '=' . urlencode($elements_values[$i]['value']);
                }
            }
        }
        // code for buttons submitted by Daniel Baur
        if ($curl_options['buttons']) {
            if (isset($result[$myfrm]['buttons'][0]['type'])) {
                if ($result[$myfrm]['buttons'][0]['type'] == 'submit') {
                    if ($result[$myfrm]['buttons'][0]['name']) {
                        $strParameters.= '&' . $result[$myfrm]['buttons'][0]['name'] . '=' . urlencode($result[$myfrm]['buttons'][0]['value']);
                    } else {
                        $strParameters.= '&' . 'submit' . '=' . urlencode($result[$myfrm]['buttons'][0]['value']);
                    }
                }
            }
        }
        // extra post parameter to avoid endless loop when more then one  is installed
        if (isset($curl_options['jnodeid'])) {
            $strParameters.= '&jnodeid=' . urlencode($curl_options['jnodeid']);
        }
        // extra post parameter to signal a host calling
        if (isset($curl_options['jhost'])) {
            $strParameters.= '&jhost=true';
        }
        $post_params = $input_username_name . "=" . urlencode($curl_options['username']) . "&" . $input_password_name . "=" . urlencode($curl_options['password']);
        $post_params_debug = $input_username_name . "=" . urlencode($curl_options['username']) . "&" . $input_password_name . "=xxxxxx";
        $status['debug'][] = t('CURL_STARTING_LOGIN') . " " . $form_action . " parameters= " . $post_params_debug . $strParameters;
        // finally submit the login form:
        if ($curl_options['integrationtype'] == 1) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_REFERER, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curl_options['verifyhost']);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array('Pelican_Curl', 'readHeader'));
            if (empty($curl_options['brute_force'])) {
                curl_setopt($ch, CURLOPT_COOKIE, self::buildCookie());
            }
            curl_setopt($ch, CURLOPT_VERBOSE, $curl_options['debug']); // Display commonication with server
            
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params . $strParameters);
        if (!empty($curl_options['httpauth'])) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$curl_options['httpauth_username']}:{$curl_options['httpauth_password']}");
            curl_setopt($ch, CURLOPT_HTTPAUTH, $curl_options['httpauth']);
        }
        $remotedata = curl_exec($ch);
        if ($curl_options['debug']) {
            $status['cURL']['data'][] = $remotedata;
            $status['debug'][] = 'CURL_INFO' . ': ' . print_r(curl_getinfo($ch), true);
        }
        if (curl_error($ch)) {
            $status['error'][] = t('CURL_ERROR_MSG') . ": " . curl_error($ch);
            curl_close($ch);
            return $status;
        }
        curl_close($ch);
        //we have to set the cookies now
        $status['debug'][] = t('CURL_LOGIN_FINISHED');
        $status = self::setMyCookies($status, $cookiesToSet, $curl_options['cookiedomain'], $curl_options['cookiepath'], $curl_options['expires'], $curl_options['secure'], $curl_options['httponly']);
        $cookiesToSetIndex = 0;
        return $status;
    }
    
    /**
     * RemoteLogout
     *
     * @access public
     * @param array $curl_options Curl options
     * @return string
     */
    public function remoteLogout($curl_options) {
        $status = array();
        global $ch;
        global $cookiearr;
        global $cookiesToSet;
        global $cookiesToSetIndex;
        $tmpurl = array();
        $cookiesToSet = array();
        $cookiesToSetIndex = 0;
        $status['debug'] = array();
        $status['error'] = array();
        $status['cURL'] = array();
        $status['cURL']['moodle'] = '';
        $status['cURL']['data'] = array();
        // check parameters and set defaults
        if (!isset($curl_options['post_url'])) {
            $status['error'][] = 'Fatal programming error : no post_url!';
            return $status;
        }
        if (!isset($curl_options['cookiedomain'])) {
            $curl_options['cookiedomain'] = '';
        }
        if (!isset($curl_options['cookiepath'])) {
            $curl_options['cookiepath'] = '';
        }
        if (!isset($curl_options['leavealone'])) {
            $curl_options['leavealone'] = null;
        }
        if (!isset($curl_options['secure'])) {
            $curl_options['secure'] = 0;
        }
        if (!isset($curl_options['httponly'])) {
            $curl_options['httponly'] = 0;
        }
        if (!isset($curl_options['verifyhost'])) {
            $curl_options['verifyhost'] = 1;
        }
        if (!isset($curl_options['crossdomain_url'])) {
            $curl_options['crossdomain_url'] = '';
        }
        if (!isset($curl_options['debug'])) {
            $curl_options['debug'] = false;
        }
        // prevent usererror by not supplying trailing backslash.
        // make sure that when parameters are sent we do not add a backslash
        if (strpos($curl_options['post_url'], '?') === false) {
            if (!(substr($curl_options['post_url'], -1) == "/")) {
                $curl_options['post_url'] = $curl_options['post_url'] . "/";
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curl_options['verifyhost']);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array('Pelican_Curl', 'readHeader'));
        curl_setopt($ch, CURLOPT_URL, $curl_options['post_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, $curl_options['debug']); // Display commonication with server
        if (!empty($curl_options['httpauth'])) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$curl_options['httpauth_username']}:{$curl_options['httpauth_password']}");
            switch ($curl_options['httpauth']) {
                case "basic":
                    $curl_options['httpauth'] = CURLAUTH_BASIC;
                break;
                case "gssnegotiate":
                    $curl_options['httpauth'] = CURLAUTH_GSSNEGOTIATE;
                break;
                case "digest":
                    $curl_options['httpauth'] = CURLAUTH_DIGEST;
                break;
                case "ntlm":
                    $curl_options['httpauth'] = CURLAUTH_NTLM;
                break;
                case "anysafe":
                    $curl_options['httpauth'] = CURLAUTH_ANYSAFE;
                break;
                case "any":
                default:
                    $curl_options['httpauth'] = CURLAUTH_ANY;
            }
            curl_setopt($ch, CURLOPT_HTTPAUTH, $curl_options['httpauth']);
        }
        $remotedata = curl_exec($ch);
        if ($curl_options['debug']) {
            $status['cURL']['data'][] = $remotedata;
            $status['debug'][] = 'CURL_INFO' . ': ' . print_r(curl_getinfo($ch), true);
        }
        if (curl_error($ch)) {
            $status['error'][] = t('CURL_ERROR_MSG') . ": " . curl_error($ch);
            curl_close($ch);
            return $status;
        }
        curl_close($ch);
        //we have to delete the cookies now
        $status = self::deleteMyCookies($status, $cookiesToSet, $curl_options['cookiedomain'], $curl_options['cookiepath'], $curl_options['leavealone'], $curl_options['secure'], $curl_options['httponly'], $curl_options['crossdomain_url']);
        $cookiesToSetIndex = 0;
        return $status;
    }
    
    /**
     * Remote logout url
     *
     * @access public
     * @param array $curl_options Curl options
     * @return string
     */
    public function remoteLogoutUrl($curl_options) {
        $status = array();
        global $ch;
        global $cookiearr;
        global $cookiesToSet;
        global $cookiesToSetIndex;
        $tmpurl = array();
        $cookiesToSet = array();
        $cookiesToSetIndex = 0;
        $status['debug'] = array();
        $status['error'] = array();
        $status['cURL'] = array();
        $status['cURL']['moodle'] = '';
        $status['cURL']['data'] = array();
        $open_basedir = ini_get('open_basedir');
        $safe_mode = ini_get('safe_mode');
        // check parameters and set defaults
        if (!isset($curl_options['post_url'])) {
            $status['error'][] = 'Fatal programming error : no post_url!';
            return $status;
        }
        if (!isset($curl_options['cookiedomain'])) {
            $curl_options['cookiedomain'] = '';
        }
        if (!isset($curl_options['cookiepath'])) {
            $curl_options['cookiepath'] = '';
        }
        if (!isset($curl_options['leavealone'])) {
            $curl_options['leavealone'] = null;
        }
        if (!isset($curl_options['secure'])) {
            $curl_options['secure'] = 0;
        }
        if (!isset($curl_options['httponly'])) {
            $curl_options['httponly'] = 0;
        }
        if (!isset($curl_options['verifyhost'])) {
            $curl_options['verifyhost'] = 1;
        }
        if (!isset($curl_options['crossdomain_url'])) {
            $curl_options['crossdomain_url'] = '';
        }
        if (!isset($curl_options['debug'])) {
            $curl_options['debug'] = false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curl_options['verifyhost']);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array('Pelican_Curl', 'readHeader'));
        curl_setopt($ch, CURLOPT_URL, $curl_options['post_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, self::buildCookie());
        curl_setopt($ch, CURLOPT_VERBOSE, $curl_options['debug']); // Display commonication with server
        if (empty($open_basedir) && empty($safe_mode)) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array('Pelican_Curl', 'readHeader'));
        curl_setopt($ch, CURLOPT_COOKIE, self::buildCookie());
        if (!empty($curl_options['httpauth'])) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$curl_options['httpauth_username']}:{$curl_options['httpauth_password']}");
            switch ($curl_options['httpauth']) {
                case "basic":
                    $curl_options['httpauth'] = CURLAUTH_BASIC;
                break;
                case "gssnegotiate":
                    $curl_options['httpauth'] = CURLAUTH_GSSNEGOTIATE;
                break;
                case "digest":
                    $curl_options['httpauth'] = CURLAUTH_DIGEST;
                break;
                case "ntlm":
                    $curl_options['httpauth'] = CURLAUTH_NTLM;
                break;
                case "anysafe":
                    $curl_options['httpauth'] = CURLAUTH_ANYSAFE;
                break;
                case "any":
                default:
                    $curl_options['httpauth'] = CURLAUTH_ANY;
            }
            curl_setopt($ch, CURLOPT_HTTPAUTH, $curl_options['httpauth']);
        }
        if (empty($open_basedir) && empty($safe_mode)) {
            $remotedata = curl_exec($ch);
        } else {
            $remotedata = self::curlRedirExec($ch);
        }
        if ($curl_options['debug']) {
            $status['cURL']['data'][] = $remotedata;
            $status['debug'][] = 'CURL_INFO' . ': ' . print_r(curl_getinfo($ch), true);
        }
        $status['debug'][] = t('CURL_LOGOUT_URL') . ': ' . $curl_options['post_url'];
        if (curl_error($ch)) {
            $status['error'][] = t('CURL_ERROR_MSG') . ": " . curl_error($ch);
            curl_close($ch);
            return $status;
        }
        curl_close($ch);
        $status = self::setMyCookies($status, $cookiesToSet, $curl_options['cookiedomain'], $curl_options['cookiepath'], $curl_options['expires'], $curl_options['secure'], $curl_options['httponly']);
        $cookiesToSetIndex = 0;
        return $status;
    }
}
