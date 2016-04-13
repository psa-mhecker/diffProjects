<?php
/**
 * @package    Pelican
 * @subpackage Pelican_Debug
 */
class Pelican_Debug_Plugin_View implements Pelican_Debug_Plugin_Interface
{

    /**
     * Contains Pelican_Plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'view';

    /**
     * Creating time plugin
     * @return void
     */
    public function __construct()
    {}

    /**
     * Gets identifier for this plugin
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu Pelican_Index_Tab for the Debugbar
     *
     * @return string
     */
    public function getTab()
    {
        $assigns = Pelican_Factory::getView()->getTemplateVars();
        $count = count($assigns);

        return $count . ' variables de vue';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $panel = '';
        $assigns = Pelican_Factory::getView()->getTemplateVars();
        if ($assigns) {
            foreach ($assigns as $key => $value) {
                $panel .= Pelican_Debug::getFieldset($key, debug($value, $key, false, '', false));
                            }
        }

        return $panel;
    }

}
