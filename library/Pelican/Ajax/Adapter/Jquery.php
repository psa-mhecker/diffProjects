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
class Pelican_Ajax_Adapter_Jquery extends Pelican_Ajax_Adapter_Abstract
{

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
    public static function getJsCall ()
    {
        return 'doAjax';
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function getJsLoading ()
    {
        return 'loadingAjax';
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function init ()
    {


    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param bool $debug (option) __DESC__
     * @return __TYPE__
     */
    public static function getHead ($debug = false)
    {
        $head = Pelican_Factory::getView()->getHead();
        $return = '';
        
        $head->setJs('/library/Pelican/Ajax/Adapter/Jquery/public/ajax.js');
        
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
    public static function getResponse ($aCommand = '')
    {
        $return = array();
        if (is_array($aCommand)) {
            foreach ($aCommand as $command) {
                $index = count($return);
                $return[$index]['cmd'] = $command['cmd'];
                switch ($command['cmd']) {
                    case 'assign':
                    case 'append':
                    case 'prepend':
                        {
                            $return[$index]['id'] = $command['id'];
                            $return[$index]['attr'] = $command['attr'];
                            $return[$index]['value'] = $command['value'];
                            break;
                        }
                    case 'replace':
                        {
                            $return[$index]['id'] = $command['id'];
                            $return[$index]['attr'] = $command['attr'];
                            $return[$index]['search'] = $command['search'];
                            $return[$index]['value'] = $command['value'];
                            break;
                        }
                    case 'clear':
                        {
                            $return[$index]['id'] = $command['id'];
                            $return[$index]['attr'] = $command['attr'];
                            break;
                        }
                    case 'remove':
                        {
                            $return[$index]['id'] = $command['id'];
                            break;
                        }
                    case 'redirect':
                        {
                            $return[$index]['url'] = $command['url'];
                            $return[$index]['delay'] = $command['delay'];
                            break;
                        }
                    case 'script':
                    case 'alert':
                    case 'debug':
                        {
                            $return[$index]['value'] = $command['value'];
                            break;
                        }
                    case 'reload':
                        {
                            break;
                        }
                }
            }
            return Zend_Json::encode($return);
        } else {
            return null;
        }
    }
}
?>