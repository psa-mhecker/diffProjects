<?php
/**
 */
class Pelican_Debug_Plugin_Security implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'security';

    /**
     * @var array
     */
    protected $_memory = array();

    /**
     * Creating time Pelican_Plugin.
     */
    public function __construct()
    {
    }

    /**
     * Gets identifier for this Pelican_Plugin.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu Pelican_Index_Tab for the Debugbar.
     *
     * @return string
     */
    public function getTab()
    {
        return 'Securité';
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        require_once dirname(__FILE__).'/PhpSecInfo/PhpSecInfo.php';
        ob_start();
        phpsecinfo();
        $content = ob_get_contents();
        ob_clean();

        $pattern = '#<head[^>]*>(.*?)<\/head>.*?<body[^>]*>(.*)<\/body>#si';
        preg_match($pattern, $content, $temp);
        if (count($temp) == 3) {
            $header = $temp[1];
            $body = $temp[2];
        }
        unset($temp);

        $panel = '<h4>Etat de la sécurité</h4>';
        $panel .= '<style type="text/css">
        .p {text-align: left;}
        .e {background-color: #ccccff; font-weight: bold; color: #000000;}
        .h {background-color: #9999cc; font-weight: bold; color: #000000;}
        .v {background-color: #cccccc; color: #000000;}
        .vr {background-color: #cccccc; text-align: right; color: #000000;}
        .v-ok {background-color:#009900;color:#ffffff;} .v-notice {background-color:orange;color:#000000;}
        .v-warn {background-color:#990000;color:#ffffff;}
        .v-notrun {background-color:#cccccc;color:#000000;}
        .v-error {background-color:#F6AE15;color:#000000;font-weight:bold;}
        } </style>';
        $panel .= $body;

        return $panel;
    }

    /**
     * Sets a memory mark identified with $name.
     *
     * @param string $name
     */
    public function mark($name)
    {
        if (!function_exists('memory_get_peak_usage')) {
            return;
        }
        if (isset($this->_memory['user'][$name])) {
            $this->_memory['user'][$name] = memory_get_peak_usage() - $this->_memory['user'][$name];
        } else {
            $this->_memory['user'][$name] = memory_get_peak_usage();
        }
    }
}
