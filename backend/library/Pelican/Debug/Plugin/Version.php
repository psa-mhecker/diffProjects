<?php
/**
 */
require_once 'Zend/Version.php';

class Pelican_Debug_Plugin_Version implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'version';

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
        return ' '.Zend_Version::VERSION.'/'.phpversion();
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        $panel = Pelican_Debug::getFieldset('Zend Framework', Zend_Version::VERSION);
        $panel .= Pelican_Debug::getFieldset('PHP', phpversion().'<br />['.php_ini_loaded_file().']');
        $extensions = get_loaded_extensions();
        natcasesort($extensions);
        $panel .= Pelican_Debug::getFieldset('Extensions chargées', '<li>'.implode('</li><li>', $extensions).'</li>');

        return $panel;
    }

    /**
     * Sets a memory mark identified with $name.
     *
     * @param string $name
     */
    public function mark($name)
    {
        if (! function_exists('memory_get_peak_usage')) {
            return;
        }
        if (isset($this->_memory['user'][$name])) {
            $this->_memory['user'][$name] = memory_get_peak_usage() - $this->_memory['user'][$name];
        } else {
            $this->_memory['user'][$name] = memory_get_peak_usage();
        }
    }
}
