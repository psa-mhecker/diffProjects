<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Ajax
 * @author __AUTHOR__
 */
pelican_import('Ajax.Adapter.Abstract');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Ajax
 * @author __AUTHOR__
 */
class Pelican_Ajax_Adapter_Xajax extends Pelican_Ajax_Adapter_Abstract {

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @var __TYPE__
     */
    public static $debugMode = false;

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @var __TYPE__
     */
    public static $syncMode = 'synchronous';

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @var __TYPE__
     */
    public static $ajaxMode = 'GET';

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function getJsCall() {
        return 'xajax_callhmvc';
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function getJsLoading() {
        return 'xajax_loading';
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function init() {
        global $xajax;
        
        require_once(Pelican::$config['LIB_ROOT'] . '/Xajax/xajax_core/xajaxAIO.inc.php');
        $xajax = new xajax(Pelican::$config["LIB_PATH"] . '/Pelican/Ajax/Adapter/Xajax/public/');
        if (Pelican::$config['CHARSET'] != 'UTF-8') {
            $xajax->configure('decodeUTF8Input', true);
        } else {
            $xajax->configure('decodeUTF8Input', false);
        }
        $xajax->registerFunction("callhmvc");
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param bool $debug (option) __DESC__
     * @return __TYPE__
     */
    public static function getHead($debug = false) {
        global $xajax;
        
        self::init();
        //$debug = true;
        $xajax->configure('debug', $debug);
        $xajax->configure('defaultMode', self::$syncMode);
        //$xajax->configure( 'defaultMethod', self::$ajaxMode );
        $xajax->configure('statusMessages', true);
        
        $return = $xajax->getJavascript('/library/Xajax/'); // output the xajax javascript. This must be called between the head tags
        
        return $return;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param string $aCommand (option) __DESC__
     * @return __TYPE__
     */
    public static function getResponse($aCommand = '') {
        
        if (is_array($aCommand)) {
            $objResponse = new xajaxResponse();
            foreach ($aCommand as $command) {
                switch ($command['cmd']) {
                    case 'assign':
                    case 'append':
                    case 'prepend':
                        {
                            $objResponse->$command['cmd']($command['id'], $command['attr'], $command['value']);
                            break;
                        }
                    case 'replace':
                        {
                            $objResponse->replace($command['id'], $command['attr'], $command['search'], $command['value']);
                            break;
                        }
                    case 'clear':
                        {
                            $objResponse->clear($command['id'], $command['attr']);
                            break;
                        }
                    case 'remove':
                        {
                            $objResponse->remove($command['id']);
                            break;
                        }
                    case 'redirect':
                        {
                            $objResponse->redirect($command['url'], $command['delay']);
                            break;
                        }
                    case 'script':
                    case 'alert':
                    case 'debug':
                        {
                            $objResponse->$command['cmd']($command['value']);
                            break;
                        }
                }
            }
            return $objResponse;
        } else {
            return null;
        }
    }
}
?>