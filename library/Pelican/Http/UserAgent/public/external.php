<?php
include_once 'config.php';
include_once 'Pelican/Curl.php';
include_once 'Pelican/Http/UserAgent/Mobile.php';

Pelican::$config['SHOW_DEBUG'] = true;

//var_dump($_SERVER['HTTP_USER_AGENT']);
$data = new stdClass();

if ($_GET['useragent']) {
    $_SERVER['HTTP_USER_AGENT'] = $_GET['useragent'];
    $_SESSION['HTTP_USER_AGENT'] = $_GET['useragent'];
}

if ($_SESSION['HTTP_USER_AGENT']) {
    if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
        $_SERVER['HTTP_USER_AGENT'] = $_SESSION['HTTP_USER_AGENT'];
    }
}

if ($_GET['url']) {
    $data->url = str_replace('http://', '', rawurldecode($_GET['url']));
    $parseurl = parse_url($data->url);

    getBuffer($data);

    $return = postRender($data->buffer, "mobile");
    $redirect = str_replace('&external='.$_GET['url'],'',$_SERVER['REQUEST_URI']);

    $return = str_replace('a href="/', 'a href="' . $redirect.'&external=http://'.$parseurl['path'], $return);
    $return = str_replace('a href="'.$parseurl['path'], 'a href="/' . $redirect.'&external=http://'.$parseurl['path'], $return);

    //$return = Pelican_Response_Adapter::_processRemoveTag($return, 'script');
    echo $return;
}

function getBuffer($data)
{
    $curl_options['post_url'] = 'http://' . $data->url;
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
    if (!Pelican_Text::isUTF8($data->buffer)) {
        $data->buffer = utf8_encode($data->buffer);
    }

    return $data;
}

function postRender($buffer, $mode = "")
{

    require_once(pelican_path('Response.Adapter'));

    Pelican_Response_Adapter::$simulation = true;

    $return = $buffer;

    if (!$markup) {
        $markup = $mode;
    }

    if ($mode == 'mobile') {
        // WURFL options
        set_time_limit(3000);
        ini_set("memory_limit", '250M');

        $config['wurflapi'] = Pelican::$config['wurflapi'];

        require_once($config['wurflapi']['wurfl_lib_dir'] . 'Application.php');

        $wurflConfig = WURFL_Configuration_ConfigFactory::create($config['wurflapi']['wurfl_config_file']);
        $wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
        $wurflManager = $wurflManagerFactory->create();
        $device = $wurflManager->getDeviceForHttpRequest($_SERVER);
        $options = $device->getAllCapabilities();
        $options['image_host'] = Pelican::$config['MEDIA_HTTP'];

        // preferred markup
        if (Pelican_Http_UserAgent_Mobile::getMarkupLanguage($options['preferred_markup'])) {
            $markup = Pelican_Http_UserAgent_Mobile::getMarkupLanguage($options['preferred_markup']);
        } else {
            $markup = 'xhtmlmp';
        }
        // webkit & iphone
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'iphone') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipod') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipad') !== false) {
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera') === false) {
                $markup = 'apple';

     //$markup = 'html5');
            }
        } elseif (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'applewebkit') !== false) {
            $markup = 'html5';
        }
    }

    // response transormation
    if ($markup && $mode != $markup) {

        $adapter = Pelican_Response_Adapter::getInstance($markup, $options);
        if ($adapter) {
            Pelican::$config["SHOW_DEBUG"] = false;

            $adapter->process($return);

            $return = $adapter->getOutput();

        }
    }

    return $return;
}
