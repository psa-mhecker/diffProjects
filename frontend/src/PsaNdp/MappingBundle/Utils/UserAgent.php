<?php

namespace PsaNdp\MappingBundle\Utils;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Itkg\CombinedHttpCache\Client\RedisClient;

/**
 * Class UserAgent.
 */
class UserAgent
{
    /**
     * determines the version of features to store.
     */
    const LIGHT_FEATURES = true;

    /**
     * UserAgent Signatures.
     *
     * @var array
     */
    public static $uaSignatures = array(
        '(android|bb\d+|meego).+mobile',
        'android',
        'arm;',
        'au\.browser',
        'avantgo',
        'blackberry',
        'ericsson',
        'eudoraweb',
        'epoc',
        'htc',
        'hpwos',
        'icab',
        'iemobile',
        'ip(hone|od|ad)',
        'j\-phone',
        'kbrowser',
        'klondike',
        'lg( |\-)',
        'lge',
        'lge\-cx',
        'linux (armv|ventana)',
        'midp',
        'mini 9\.5',
        'mmp',
        'mobi',
        'mobile.+firefox',
        'mobilephone',
        'motorola',
        'netfront',
        'nintendo wii',
        'nintendo',
        'novarra\-vision',
        'openweb',
        'opera m(ob|in)i',
        'p(ixi|re)\/',
        'palm( os)?',
        'palmos',
        'palmsource',
        'phone',
        'playstation',
        'plucker',
        'polaris',
        'portalmmm',
        'qt embedded',
        'reqwirelessweb',
        'rim',
        'samsung',
        'sec\-sgh',
        'semc\-browser',
        'series(4|6|7|8|9)0',
        'silk',
        'smartphone',
        'sony cmd',
        'sonyericsson',
        'spv',
        'symbian os',
        'symbian',
        'symbianos',
        'tablet',
        'touch;',
        'treo',
        'ucbrowser',
        'up\.(browser|link)',
        'up\/4.1',
        'utec',
        'vodaphone',
        'wap',
        'wap\-browser',
        'windows ce',
        'windows phone',
        'wireless',
        'wm5 pie',
        'xiino',
    );
    /**
     * @var array
     */
    public static $haTerms = array(
        'midp',
        'wml',
        'vnd\.rim',
        'vnd\.wap',
        'j2me',
    );
    /**
     * first 4 letters of mobile User Agent chains.
     *
     * @var array
     */
    public static $uaBegin = array(
        '1207',
        '6310',
        '6590',
        '3gso',
        '4thp',
        '50[1-6]i',
        '770s',
        '802s',
        'a wa',
        'abac',
        'ac(er|oo|s\-)',
        'ai(ko|rn)',
        'al(av|ca|co)',
        'amoi',
        'an(ex|ny|yw)',
        'aptu',
        'ar(ch|go)',
        'as(te|us)',
        'attw',
        'au(di|\-m|r |s )',
        'avan',
        'be(ck|ll|nq)',
        'bi(lb|rd)',
        'bl(ac|az)',
        'br(e|v)w',
        'bumb',
        'bw\-(n|u)',
        'c55\/',
        'capi',
        'ccwa',
        'cdm\-',
        'cell',
        'chtm',
        'cldc',
        'cmd\-',
        'co(mp|nd)',
        'craw',
        'da(it|ll|ng)',
        'dbte',
        'dc\-s',//
        'devi',
        'dica',
        'dmob',
        'do(c|p)o',
        'ds(12|\-d)',
        'el(49|ai)',
        'em(l2|ul)',
        'er(ic|k0)',
        'esl8',
        'ez([4-7]0|os|wa|ze)',
        'fetc',
        'fly(\-|_)',
        'g1 u',
        'g560',
        'gene',
        'gf\-5',
        'g\-mo',
        'go(\.w|od)',
        'gr(ad|un)',
        'haie',
        'hcit',
        'hd\-(m|p',
        't)',
        'hei\-',
        'hi(pt|ta)',
        'hp( i|ip)',
        'hs\-c',
        'ht(c(\-| |_|a|g|p|s|t)|tp)',
        'hu(aw|tc)',
        'i\-(20|go|ma)',
        'i230',
        'iac( |\-|\/)',
        'ibro',
        'idea',
        'ig01',
        'ikom',
        'im1k',
        'inno',
        'ipaq',
        'iris',
        'ja(t|v)a',
        'jbro',
        'jemu',
        'jigs',
        'kddi',
        'keji',
        'kgt( |\/)',
        'klon',
        'kpt ',
        'kwc\-',
        'kyo(c|k)',
        'le(no|xi)',
        'lg( g|\/(k|l|u)',
        '50',
        '54',
        '\-[a-w])',
        'libw',
        'lynx',
        'm1\-w',
        'm3ga',
        'm50\/',
        'ma(te|ui|xo)',
        'mc(01|21|ca)',
        'm\-cr',
        'me(rc|ri)',
        'mi(o8|oa|ts)',
        'mmef',
        'mo(01|02|bi|de|do|t(\-| |o|v)|zz)',
        'mt(50|p1|v )',
        'mwbp',
        'mywa',
        'n10[0-2]',
        'n20[2-3]',
        'n30(0|2)',
        'n50(0|2|5)',
        'n7(0(0|1)|10)',
        'ne((c|m)\-|on|tf|wf|wg|wt)',
        'nok(6|i)',
        'nzph',
        'o2im',
        'op(ti|wv)',
        'oran',
        'owg1',
        'p800',
        'pan(a|d|t)',
        'pdxg',
        'pg(13|\-([1-8]|c))',
        'phil',
        'pire',
        'pl(ay|uc)',
        'pn\-2',
        'po(ck|rt|se)',
        'prox',
        'psio',
        'pt\-g',
        'qa\-a',
        'qc(07|12|21|32|60\-[2-7]|i\-)',
        'qtek',
        'r380',
        'r600',
        'raks',
        'rim9',
        'ro(ve|zo)',
        's55\/',
        'sa(ge|ma|mm|ms|ny|va)',
        'sc(01|h\-|oo|p\-)',
        'sdk\/',
        'se(c(\-|0|1)|47|mc|nd|ri)',
        'sgh\-',
        'shar',
        'sie(\-|m)',
        'sk\-0',
        'sl(45|id)',
        'sm(al|ar|b3|it|t5)',
        'so(ft|ny)',
        'sp(01|h\-|v\-|v )',
        'sy(01|mb)',
        't2(18|50)',
        't6(00|10|18)',
        'ta(gt|lk)',
        'tcl\-',
        'tdg\-',
        'tel(i|m)',
        'tim\-',
        't\-mo',
        'to(pl|sh)',
        'ts(70|m\-|m3|m5)',
        'tx\-9',
        'up(\.b|g1|si)',
        'utst',
        'v400',
        'v750',
        'veri',
        'vi(rg|te)',
        'vk(40|5[0-3]|\-v)',
        'vm40',
        'voda',
        'vulc',
        'vx(52|53|60|61|70|80|81|83|85|98)',
        'w3c(\-| )',
        'webc',
        'whit',
        'wi(g |nc|nw)',
        'wml(b|\-)',
        'wonu',
        'x700',
        'yas\-',
        'your',
        'zeto',
        'zte\-',
    );
    /**
     * stored (redis) ua identification results.
     *
     * @var array
     */
    private static $storedValue = array();

    /**
     * Wurfl config.
     *
     * @var array
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var RedisClient
     */
    private $cacheClient;

    /**
     * @param Request $request
     * @param array   $config
     * @param $cacheClient
     */
    public function __construct(Request $request, $config, RedisClient $cacheClient)
    {
        if (empty($config)) {
            throw new Exception(
                'UserAgent detection - Config empty, unable to identify device'
            );
        }
        $this->setRequest($request);
        $this->setConfig($config);
        $this->cacheClient = $cacheClient;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * set the request property.
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        // to simulate a user agent
        $newUserAgent = $request->query->get('useragent');
        if (!empty($newUserAgent)) {
            $this->setServerParameter('HTTP_USER_AGENT', $newUserAgent);
        }
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function isTablet()
    {
        return ($this->getDeviceType() == 'tablet');
    }

    /**
     * @return bool
     */
    public function isMobile()
    {
        return ($this->getDeviceType() == 'mobile');
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        $return = 'desktop';

        $device = $this->getDevice();
        if (!empty($device['client'])) {
            if ($device['client']['type'] == 'mobile' && !empty($device['client']['capabilities'])) {
                if ($size = $this->getScreenSize($device)) {
                    $return = ($size < 7 && $size > 0) ? 'mobile' : 'desktop';

                    // tablets are not considered as mobile
                    if (preg_match('/(tablet|yootab|touch)/i', $this->request->server->get('HTTP_USER_AGENT'))
                        && !preg_match('/(iemobile)/i', $this->request->server->get('HTTP_USER_AGENT'))
                    ) {
                        $return = 'tablet';
                    }
                    // hell thing of wurfl !! return 0, or string 'false' or string 'true'
                    if ($device['client']['capabilities']['is_tablet'] == 'true') {
                        $return = 'tablet';
                    }
                } else {
                    $return = ((bool) $device['client']['capabilities']['is_wireless_device']) ? 'mobile' : 'desktop';

                    if (strpos(strtolower($this->request->server->get('HTTP_USER_AGENT')), 'mobile')) {
                        $return = 'mobile';
                    }
                    // tablets are not considered as mobile
                    if ($device['client']['capabilities']['is_tablet'] == 'true') { // hell thing of wurfl !!
                        $return = 'tablet';
                    }
                }
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getDevice()
    {
        $feature = $this->getStoredValue();

        if (empty($feature)) {
            $feature = $this->getUserAgentFeatures();
            $feature['client']['type'] = 'desktop';
            if ($this->isMobileCompatible()) {
                if (empty($this->config['config_file'])) {
                    throw new Exception('The "config_file" parameter is not defined');
                }

                if (!empty($this->config['config_file'])) {
                    $wurflConfig = \WURFL_Configuration_ConfigFactory::create(
                        $this->config['config_file']
                    );

                    $wurflManagerFactory = new \WURFL_WURFLManagerFactory($wurflConfig);
                    $wurflManager = $wurflManagerFactory->create();
                    $device = $wurflManager->getDeviceForHttpRequest($this->request->server->all());
                    $feature['client']['capabilities'] = $device->getAllCapabilities();
                    $feature['client']['type'] = 'mobile';
                }
            }
            $this->setStoredValue($this->getFeatureValue($feature));
        }

        return $feature;
    }

    /**
     * returns full or light features array.
     *
     * @param $feature
     *
     * @return mixed
     */
    private function getFeatureValue($feature)
    {
        $return = array();

        if (self::LIGHT_FEATURES) {
            $return['client']['type'] = $feature['client']['type'];
            $return['client']['capabilities']['is_tablet'] = 0;
            $return['client']['capabilities']['physical_screen_height'] = 0;
            $return['client']['capabilities']['is_wireless_device'] = false;

            if (!empty($feature['client']['capabilities']['is_tablet'])) {
                $return['client']['capabilities']['is_tablet'] = $feature['client']['capabilities']['is_tablet'];
            }
            if (!empty($feature['client']['capabilities']['physical_screen_width'])) {
                $return['client']['capabilities']['physical_screen_width'] = $feature['client']['capabilities']['physical_screen_width'];
            };
            if (!empty($feature['client']['capabilities']['physical_screen_height'])) {
                $return['client']['capabilities']['physical_screen_height'] = $feature['client']['capabilities']['physical_screen_height'];
            }
            if (!empty($feature['client']['capabilities']['is_wireless_device'])) {
                $return['client']['capabilities']['is_wireless_device'] = $feature['client']['capabilities']['is_wireless_device'];
            }
        } else {
            $return = $feature;
        }

        return $return;
    }

    /**
     * @return array
     */
    private function getStoredValue()
    {
        $return = null;
        $key = $this->getKey();

        // to avoid multiple calls to redis : use of static variable
        if (empty(self::$storedValue[$key])) {
            $value = $this->cacheClient->get($key);
            if ($value !== null) {
                $return = unserialize($value);
                self::$storedValue[$key] = $return;
            }
        } else {
            $return = self::$storedValue[$key];
        }

        return $return;
    }

    /**
     * @return string
     */
    private function getKey()
    {
        return 'ua_'.md5($this->request->server->get('HTTP_USER_AGENT'));
    }

    /**
     * Sets all the standard features extracted from the User Agent chain and $this->request->server->get(
     * vars.
     *
     * @return mixed
     */
    private function getUserAgentFeatures()
    {
        /*
         * gets info from user agent chain
         */
        $uaExtract = $this->extractFromUserAgent($this->request->server->get('HTTP_USER_AGENT'));

        if (is_array($uaExtract)) {
            foreach ($uaExtract as $key => $info) {
                $feature['client']['info'][$key] = $info;
            }
        }

        /*
         * sets the client IP
         */
        $server['HTTP_CLIENT_IP'] = $this->request->server->get('HTTP_CLIENT_IP');
        $server['HTTP_X_FORWARDED_FOR'] = $this->request->server->get('HTTP_X_FORWARDED_FOR');
        $server['REMOTE_ADDR'] = $this->request->server->get('REMOTE_ADDR');
        $server['SERVER_SOFTWARE'] = $this->request->server->get('SERVER_SOFTWARE');

        if (isset($server['REMOTE_ADDR'])) {
            $feature['client']['info']['client_ip'] = $server['REMOTE_ADDR'];
        } elseif (isset($server['HTTP_X_FORWARDED_FOR'])) {
            $feature['client']['info']['client_ip'] = $server['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($server['HTTP_CLIENT_IP'])) {
            $feature['client']['info']['client_ip'] = $server['HTTP_CLIENT_IP'];
        }

        /*
         * sets the server infos
         */
        if (isset($server['SERVER_SOFTWARE'])) {
            if (strpos($server['SERVER_SOFTWARE'], 'Apache') !== false || strpos($server['SERVER_SOFTWARE'], 'LiteSpeed') !== false) {
                $server['VERSION'] = 1;
                if (strpos($server['SERVER_SOFTWARE'], 'Apache/2') !== false) {
                    $server['VERSION'] = 2;
                }
                $server['SERVER'] = 'apache';
            }

            if (strpos($server['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
                $server['SERVER'] = 'iis';
            }

            if (strpos($server['SERVER_SOFTWARE'], 'Unix') !== false) {
                $server['OS'] = 'unix';
                if (isset($_ENV['MACHTYPE'])) {
                    if (strpos($_ENV['MACHTYPE'], 'linux') !== false) {
                        $server['OS'] = 'linux';
                    }
                }
            } elseif (strpos($server['SERVER_SOFTWARE'], 'Win') !== false) {
                $server['OS'] = 'windows';
            }

            if (preg_match('/Apache\/([0-9\.]*)/', $server['SERVER_SOFTWARE'], $arr)) {
                if ($arr[1]) {
                    $server['VERSION'] = $arr[1];
                    $server['SERVER'] = 'apache';
                }
            }
        }

        $feature['server']['info']['php_version'] = phpversion();
        $feature['server']['info']['server_os'] = $server['SERVER'];
        $feature['server']['info']['server_os_version'] = $server['VERSION'];
        $feature['server']['info']['server_http_accept'] = $this->request->server->get('HTTP_ACCEPT');
        $feature['server']['info']['server_http_accept_language'] = $this->request->server->get('HTTP_ACCEPT_LANGUAGE');
        $feature['server']['info']['server_ip'] = $this->request->server->get('SERVER_ADDR');
        $feature['server']['info']['server_name'] = $this->request->server->get('SERVER_NAME');

        return $feature;
    }

    /**
     * Extract and sets informations from the User Agent chain.
     *
     * @param $userAgent UserAgent chain
     *
     * @return mixed
     */
    public function extractFromUserAgent($userAgent)
    {
        $userAgent = trim($userAgent);

        /*
         *
         * @see http://www.texsoft.it/index.php?c=software&m=sw.php.useragent&l=it
         */
        $pattern = '(([^/\s]*)(/(\S*))?)(\s*\[[a-zA-Z][a-zA-Z]\])?\s*(\\((([^()]|(\\([^()]*\\)))*)\\))?\s*';
        preg_match('#^'.$pattern.'#', $userAgent, $match);

        $comment = array();
        if (isset($match[7])) {
            $comment = explode(';', $match[7]);
        }

        // second part if exists
        $end = substr($userAgent, strlen($match[0]));
        if (!empty($end)) {
            $result['others']['full'] = $end;
        }

        $match2 = array();
        if (isset($result['others'])) {
            preg_match_all('/(([^\/\s]*)(\/)?([^\/\(\)\s]*)?)(\s\((([^\)]*)*)\))?/i', $result['others']['full'], $match2);
        }
        $result['user_agent'] = trim($match[1]);
        $result['product_name'] = isset($match[2]) ? trim($match[2]) : '';
        $result['browser_name'] = $result['product_name'];
        if (isset($match[4]) && trim($match[4])) {
            $result['product_version'] = trim($match[4]);
            $result['browser_version'] = trim($match[4]);
        }
        if (count($comment) && !empty($comment[0])) {
            $result['comment']['full'] = trim($match[7]);
            $result['comment']['detail'] = $comment;
            $result['compatibility_flag'] = trim($comment[0]);
            if (isset($comment[1])) {
                $result['browser_token'] = trim($comment[1]);
            }
            if (isset($comment[2])) {
                $result['device_os_token'] = trim($comment[2]);
            }
        }
        if (empty($result['device_os_token']) && !empty($result['compatibility_flag'])) {
            // some browsers do not have a platform token
            $result['device_os_token'] = $result['compatibility_flag'];
        }
        if ($match2) {
            $max = count($match2[0]);
            for ($i = 0; $i < $max; ++$i) {
                if (!empty($match2[0][$i])) {
                    $result['others']['detail'][] = array(
                        $match2[0][$i],
                        $match2[2][$i],
                        $match2[4][$i],
                    );
                }
            }
        }

        /* Security level */
        $security = array(
            'N' => 'no security',
            'U' => 'strong security',
            'I' => 'weak security',
        );
        if (!empty($result['browser_token'])) {
            if (isset($security[$result['browser_token']])) {
                $result['security_level'] = $security[$result['browser_token']];
                unset($result['browser_token']);
            }
        }

        $product = strtolower($result['browser_name']);

        // Mozilla : true && false
        $compatibleOrIe = false;
        if (isset($result['compatibility_flag']) && isset($result['comment'])) {
            $compatibleOrIe = ($result['compatibility_flag'] == 'compatible' || strpos($result['comment']['full'], 'MSIE') !== false);
        }
        if ($product == 'mozilla' && $compatibleOrIe) {
            if (!empty($result['browser_token'])) {
                // Classic Mozilla chain
                preg_match_all('/([^\/\s].*)(\/|\s)(.*)/i', $result['browser_token'], $real);
            } else {
                // MSIE specific chain with 'Windows' compatibility flag
                foreach ($result['comment']['detail'] as $v) {
                    if (strpos($v, 'MSIE') !== false) {
                        $real[0][1] = trim($v);
                        $result['browser_engine'] = 'MSIE';
                        $real[1][0] = 'Internet Explorer';
                        $temp = explode(' ', trim($v));
                        $real[3][0] = $temp[1];
                    }
                    if (strpos($v, 'Win') !== false) {
                        $result['device_os_token'] = trim($v);
                    }
                }
            }

            if (!empty($real[0])) {
                $result['browser_name'] = $real[1][0];
                $result['browser_version'] = $real[3][0];
            } else {
                if (isset($result['browser_token'])) {
                    $result['browser_name'] = $result['browser_token'];
                }
                $result['browser_version'] = '??';
            }
        } elseif ($product == 'mozilla' && isset($result['browser_version'])
            && $result['browser_version'] < 5.0
        ) {
            // handles the real Mozilla (or old Netscape if version < 5.0)
            $result['browser_name'] = 'Netscape';
        }

        /* windows */
        if ($result['browser_name'] == 'MSIE') {
            $result['browser_engine'] = 'MSIE';
            $result['browser_name'] = 'Internet Explorer';
        }
        if (isset($result['device_os_token'])) {
            if (strpos($result['device_os_token'], 'Win') !== false) {
                $windows = array(
                    'Windows NT 6.1' => 'Windows 7',
                    'Windows NT 6.0' => 'Windows Vista',
                    'Windows NT 5.2' => 'Windows Server 2003',
                    'Windows NT 5.1' => 'Windows XP',
                    'Windows NT 5.01' => 'Windows 2000 SP1',
                    'Windows NT 5.0' => 'Windows 2000',
                    'Windows NT 4.0' => 'Microsoft Windows NT 4.0',
                    'WinNT' => 'Microsoft Windows NT 4.0',
                    'Windows 98; Win 9x 4.90' => 'Windows Me',
                    'Windows 98' => 'Windows 98',
                    'Win98' => 'Windows 98',
                    'Windows 95' => 'Windows 95',
                    'Win95' => 'Windows 95',
                    'Windows CE' => 'Windows CE',
                );
                if (isset($windows[$result['device_os_token']])) {
                    $result['device_os_name'] = $windows[$result['device_os_token']];
                } else {
                    $result['device_os_name'] = $result['device_os_token'];
                }
            }
        }

        // iphone
        $appleDevice = array(
            'iPhone',
            'iPod',
            'iPad',
        );
        $result['browser_language'] = '';
        if (isset($result['compatibility_flag'])) {
            if (in_array($result['compatibility_flag'], $appleDevice)) {
                $result['device'] = strtolower($result['compatibility_flag']);
                $result['device_os_token'] = 'iPhone OS';
                if (!empty($comment[3])) {
                    $result['browser_language'] = trim($comment[3]);
                }
                if (isset($result['others']['detail'][1])) {
                    $result['browser_version'] = $result['others']['detail'][1][2];
                } elseif (isset($result['others']['detail']) && count($result['others']['detail'])) {
                    $result['browser_version'] = $result['others']['detail'][0][2];
                }
                if (!empty($result['others']['detail'][2])) {
                    $result['firmware'] = $result['others']['detail'][2][2];
                }
                if (!empty($result['others']['detail'][3])) {
                    $result['browser_name'] = $result['others']['detail'][3][1];
                    $result['browser_build'] = $result['others']['detail'][3][2];
                }
            }
        }

        // Safari
        if (isset($result['others'])) {
            if ($result['others']['detail'][0][1] == 'AppleWebKit') {
                $result['browser_engine'] = 'AppleWebKit';
                if (isset($result['others']['detail'][1]) && $result['others']['detail'][1][1] == 'Version') {
                    $result['browser_version'] = $result['others']['detail'][1][2];
                } else {
                    $result['browser_version'] = $result['others']['detail'][count($result['others']['detail']) - 1][2];
                }
                if (isset($comment[3])) {
                    $result['browser_language'] = trim($comment[3]);
                }

                $last = $result['others']['detail'][count($result['others']['detail']) - 1][1];

                if (empty($result['others']['detail'][2][1]) || $result['others']['detail'][2][1] == 'Safari') {
                    if (isset($result['others']['detail'][1])) {
                        $result['browser_name'] = ($result['others']['detail'][1][1] && $result['others']['detail'][1][1] != 'Version' ? $result['others']['detail'][1][1] : 'Safari');
                        $result['browser_version'] = ($result['others']['detail'][1][2] ? $result['others']['detail'][1][2] : $result['others']['detail'][0][2]);
                    } else {
                        $result['browser_name'] = ($result['others']['detail'][0][1] && $result['others']['detail'][0][1] != 'Version' ? $result['others']['detail'][0][1] : 'Safari');
                        $result['browser_version'] = $result['others']['detail'][0][2];
                    }
                } else {
                    $result['browser_name'] = $result['others']['detail'][2][1];
                    $result['browser_version'] = $result['others']['detail'][2][2];

                    // mobile version
                    if ($result['browser_name'] == 'Mobile') {
                        $result['browser_name'] = 'Safari '.$result['browser_name'];
                        if ($result['others']['detail'][1][1] == 'Version') {
                            $result['browser_version'] = $result['others']['detail'][1][2];
                        }
                    }
                }

                // For Safari < 2.2, AppleWebKit version gives the Safari version
                if (strpos($result['browser_version'], '.') > 2 || (int) $result['browser_version'] > 20) {
                    $temp = explode('.', $result['browser_version']);
                    $build = (int) $temp[0];
                    $awkVersion = array(
                        48 => '0.8',
                        73 => '0.9',
                        85 => '1.0',
                        103 => '1.1',
                        124 => '1.2',
                        300 => '1.3',
                        400 => '2.0',
                    );
                    foreach ($awkVersion as $k => $v) {
                        if ($build >= $k) {
                            $result['browser_version'] = $v;
                        }
                    }
                }
            }

            // Gecko (Firefox or compatible)
            if ($result['others']['detail'][0][1] == 'Gecko') {
                $searchRV = true;
                if (!empty($result['others']['detail'][1][1]) && !empty($result['others']['detail'][count($result['others']['detail']) - 1][2]) || strpos(strtolower($result['others']['full']), 'opera') !== false) {
                    $searchRV = false;
                    $result['browser_engine'] = $result['others']['detail'][0][1];

                    // the name of the application is at the end indepenently
                    // of quantity of information in $result['others']['detail']
                    $last = count($result['others']['detail']) - 1;

                    // exception : if the version of the last information is
                    // empty we take the previous one
                    if (empty($result['others']['detail'][$last][2])) {
                        --$last;
                    }

                    // exception : if the last one is 'Red Hat' or 'Debian' =>
                    // use rv: to find browser_version */
                    if (in_array($result['others']['detail'][$last][1], array(
                        'Debian',
                        'Hat',
                    ))) {
                        $searchRV = true;
                    }
                    $result['browser_name'] = $result['others']['detail'][$last][1];
                    $result['browser_version'] = $result['others']['detail'][$last][2];
                    if (isset($comment[4])) {
                        $result['browser_build'] = trim($comment[4]);
                    }
                    if (isset($comment[3])) {
                        $result['browser_language'] = trim($comment[3]);
                    }

                    // Netscape
                    if ($result['browser_name'] == 'Navigator' || $result['browser_name'] == 'Netscape6') {
                        $result['browser_name'] = 'Netscape';
                    }
                }
                if ($searchRV) {
                    // Mozilla alone : the version is identified by rv:
                    $result['browser_name'] = 'Mozilla';
                    if (isset($result['comment']['detail'])) {
                        foreach ($result['comment']['detail'] as $rv) {
                            if (strpos($rv, 'rv:') !== false) {
                                $result['browser_version'] = trim(str_replace('rv:', '', $rv));
                            }
                        }
                    }
                }
            }

            // Netscape
            if ($result['others']['detail'][0][1] == 'Netscape') {
                $result['browser_name'] = 'Netscape';
                $result['browser_version'] = $result['others']['detail'][0][2];
            }

            // Opera
            // Opera: engine Presto
            if ($result['others']['detail'][0][1] == 'Presto') {
                $result['browser_engine'] = 'Presto';
                if (!empty($result['others']['detail'][1][2])) {
                    $result['browser_version'] = $result['others']['detail'][1][2];
                }
            }

            // UA ends with 'Opera X.XX' or 'Opera/X.XX'
            if ($result['others']['detail'][0][1] == 'Opera') {
                $result['browser_name'] = $result['others']['detail'][0][1];
                // Opera X.XX
                if (isset($result['others']['detail'][1][1])) {
                    $result['browser_version'] = $result['others']['detail'][1][1];
                    // Opera/X.XX
                } elseif (isset($result['others']['detail'][0][2])) {
                    $result['browser_version'] = $result['others']['detail'][0][2];
                }
            }

            // Opera Mini
            if (isset($result['browser_token'])) {
                if (strpos($result['browser_token'], 'Opera Mini') !== false) {
                    $result['browser_name'] = 'Opera Mini';
                }
            }

            // Symbian
            if ($result['others']['detail'][0][1] == 'SymbianOS') {
                $result['device_os_token'] = 'SymbianOS';
            }
        }

        // UA ends with 'Opera X.XX'
        if (isset($result['browser_name']) && isset($result['browser_engine'])) {
            if ($result['browser_name'] == 'Opera' && $result['browser_engine'] == 'Gecko' && empty($result['browser_version'])) {
                $result['browser_version'] = $result['others']['detail'][count($result['others']['detail']) - 1][1];
            }
        }

        // cleanup
        if (isset($result['browser_version']) && isset($result['browser_build'])) {
            if ($result['browser_version'] == $result['browser_build']) {
                unset($result['browser_build']);
            }
        }

        // compatibility
        $compatibility['AppleWebKit'] = 'Safari';
        $compatibility['Gecko'] = 'Firefox';
        $compatibility['MSIE'] = 'Internet Explorer';
        $compatibility['Presto'] = 'Opera';
        if (!empty($result['browser_engine'])) {
            if (isset($compatibility[$result['browser_engine']])) {
                $result['browser_compatibility'] = $compatibility[$result['browser_engine']];
            }
        }

        ksort($result);

        return $result;
    }

    /**
     * Comparison of the UserAgent chain and User Agent signatures.
     *
     * @static
     *
     * @return bool
     */
    public function isMobileCompatible()
    {
        $server['ALL_HTTP'] = $this->request->server->get('ALL_HTTP');
        $server['HTTP_ACCEPT'] = $this->request->server->get('HTTP_ACCEPT');
        $server['HTTP_X_WAP_PROFILE'] = $this->request->server->get('HTTP_X_WAP_PROFILE');
        $server['HTTP_PROFILE'] = $this->request->server->get('HTTP_PROFILE');
        $server['HTTP_USER_AGENT'] = $this->request->server->get('HTTP_USER_AGENT');

        /*  to have a quick identification, make lighten tests first */
        if (!empty($server['ALL_HTTP'])) {
            if (strpos(strtolower(str_replace(' ', '', $server['ALL_HTTP'])), 'operam') !== false) {
                /* Opera Mini or Opera Mobi */
                return true;
            }
        }
        if (!empty($server['HTTP_X_WAP_PROFILE']) || !empty($server['HTTP_PROFILE'])) {
            return true;
        }
        if ($this->match($server['HTTP_ACCEPT'], self::$haTerms)) {
            return true;
        }
        if ($this->match(substr($server['HTTP_USER_AGENT'], 0, 4), self::$uaBegin)) {
            return true;
        }

        if ($this->match($server['HTTP_USER_AGENT'], self::$uaSignatures)) {
            return true;
        }

        return false;
    }

    /**
     * Comparison of the UserAgent chain and browser signatures.
     * The comparison is case-insensitive : the browser signatures must be in lower
     * case.
     *
     * @static
     *
     * @param string $userAgent  UserAgent chain
     * @param string $signatures (option) Browsers signatures (in lower case)
     *
     * @return bool
     */
    private function match($userAgent, $signatures = null)
    {
        if (!is_null($signatures)) {
            return (bool) preg_match('/'.implode('|', $signatures).'/i', $userAgent);
        }

        return false;
    }

    /**
     * @return mixed
     */
    private function setStoredValue($value = null)
    {
        $this->cacheClient->set($this->getKey(), serialize($value));
    }

    /**
     * Obtenir la taille d'un ecran en pouce.
     *
     * @static
     *
     * @return float diagonale de l'ecran en pouce ou false
     */
    private function getScreenSize($device)
    {
        $width = $device['client']['capabilities']['physical_screen_width'];
        $height = $device['client']['capabilities']['physical_screen_height'];

        $return = 0;
        if ($width != '' && $height != '') {
            $return = (sqrt(bcpow($width, 2) + bcpow($height, 2))) / 25.4;
        }

        return $return;
    }

    /**
     * @param      $key   Parameter to override
     * @param null $value Parameter value (can be null)
     */
    public function setServerParameter($key, $value = null)
    {
        $this->request->server->set($key, $value);
    }
}
