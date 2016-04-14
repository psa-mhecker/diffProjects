<?php
/**
 */
class Pelican_Debug_Plugin_Debug implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'debug';

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
        return ($this->count ? $this->count : '0').' Debug'.($this->count > 1 ? 's' : '');
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        $this->count = count(Pelican_Debug::$debugItem);
        $panel = '<h4>Debug</h4>';
        $panel .= implode('', Pelican_Debug::$debugItem);

        return $panel;
    }
}
