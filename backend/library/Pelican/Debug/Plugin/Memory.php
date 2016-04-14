<?php
/**
 */
class Pelican_Debug_Plugin_Memory implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'memory';

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
        $tab = '';
        if ($this->memory) {
            $tab .= $this->memory;
        } else {
            $tab .= 'MemUsage n.a.';
        }

        return $tab;
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        //memoire
        if (function_exists('memory_get_peak_usage')) {
            $this->memory = round(memory_get_peak_usage() / 1024).' Ko sur '.ini_get("memory_limit");
        }

        $panel = '<fieldset><legend>Usage memoire</legend>';
        $panel .= $this->memory;
        $panel .= '</fieldset>';

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
