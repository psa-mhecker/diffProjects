<?php
/**
 */
class Pelican_Debug_Plugin_Xdebug implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'xdebug';

    /**
     * @var array
     */
    protected $_memory = array();

    /**
     * Creating time plugin.
     */
    public function __construct()
    {
    }

    /**
     * Gets identifier for this plugin.
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
        return 'XDebug';
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        include_once 'Zend/Uri.php';
        $vhost = 'http://'.$_SERVER['HTTP_HOST'];

        $uri = Zend_Uri::factory($vhost.$_SERVER['REQUEST_URI']);
        $uri->addReplaceQueryParameters(array('XDEBUG_PROFILE' => 1));

        $xdebugUri = $vhost.'/xdebug/index.php?dataFile='.'xdebug.'.str_replace('.', '_', $_SERVER['HTTP_HOST']).'.'.session_id().'&costFormat=msec&showFraction=1&hideInternals=0&op=function_list';

        $panel = Pelican_Html::a(array(onclick => 'document.getElementById(\'xdebugTmp\').src = \''.$uri->getUri().'\';document.getElementById(\'xdebug\').src=\''.$xdebugUri.'\''), 'Effectuer le profiling');
        $panel .= Pelican_Html::iframe(array(src => '', name => 'xdebugTmp', id => 'xdebugTmp', width => 0, height => 0));
        $panel .= Pelican_Html::iframe(array(src => '', name => 'xdebug', id => 'xdebug', width => 600, height => 200, scrolling => 'auto', frameborder => 1));

        return $panel;
    }
}
