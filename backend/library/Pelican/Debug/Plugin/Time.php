<?php
/**
 */
class Pelican_Debug_Plugin_Time implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'time';

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
        list($time, $clearTime, $memory) = Pelican_Profiler::cumul();

        return $clearTime;
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        $panel = '';
        if (Pelican_Profiler::$marks) {
            foreach (Pelican_Profiler::$marks as $group => $val) {
                $total = sprintf(PROFILE_FORMAT_TIME, Pelican_Profiler::$_cumul[$group]);
                $panel .= Pelican_Debug::getFieldset($group.' : '.$total, debug(Pelican_Profiler::summary($group, array(
                    'time',
                    'percent',
                )), $group, false, '', false));
            }
        }
        // $panel = '<h4>Temps d\'execution</h4>';
        // $panel .= debug(Pelican_Profiler::summary(), 'Time', false);
        return $panel;
    }
}
