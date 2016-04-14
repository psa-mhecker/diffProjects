<?php
/**
 */
class Pelican_Debug_Plugin_Cache implements Pelican_Debug_Plugin_Interface
{
    /**
     * Contains Pelican_Plugin identifier name.
     *
     * @var string
     */
    protected $_identifier = 'cache';

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
        if (Pelican_Profiler::$marks['cache']) {
            $count = count(Pelican_Profiler::$marks['cache']);
            $count .= ' cache'.($count > 1 ? 's' : '');
            $count .= ' ('.sprintf(PROFILE_FORMAT_TIME, Pelican_Profiler::$_cumul['cache']).')';
        }

        return $count;
    }

    /**
     * Gets content panel for the Debugbar.
     *
     * @return string
     */
    public function getPanel()
    {
        if (Pelican_Profiler::$marks['cache']) {
            $panel = Pelican_Html::button(array(onclick => "document.location.href='/library/Pelican/Cache/public/clean_all_cache.php';"), 'Vider tout le cache');

            $total = sprintf(PROFILE_FORMAT_TIME, Pelican_Profiler::$_cumul['cache']);
            $panel .= Pelican_Debug::getFieldset($total, debug(Pelican_Profiler::summary('cache', array(
                'time',
                'percent', )), 'cache', false, '', false));
            $panel .= Pelican_Debug::getFieldset('Requête(s) exécutée(s) '.$total, debug(Pelican_Profiler::summary('sql', array(
                'time',
                'percent', )), 'cache', false, '', false));
        }

        return $panel;
    }
}
