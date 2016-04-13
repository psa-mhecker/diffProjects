<?php
/**
 * @package    Pelican
 * @subpackage Pelican_Debug
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * @package    Pelican
 * @subpackage Pelican_Debug
 */
class Pelican_Debug_Plugin_Db implements Pelican_Debug_Plugin_Interface
{

    /**
     * Contains Pelican_Plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'database';

    /**
     * @var array
     */
    protected $_db = array();

    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Variables
     *
     * @param  Zend_Db_Adapter_Abstract|array $adapters
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!isset($options['adapter']) || !count($options['adapter'])) {
            if (Zend_Db_Table_Abstract::getDefaultAdapter()) {
                $this->_db[0] = Zend_Db_Table_Abstract::getDefaultAdapter();
                $this->_db[0]->getProfiler()->setEnabled(true);
            }
        } else
            if ($options['adapter'] instanceof Zend_Db_Adapter_Abstract) {
                $this->_db[0] = $options['adapter'];
                $this->_db[0]->getProfiler()->setEnabled(true);
            } else {
                foreach ($options['adapter'] as $name => $adapter) {
                    if ($adapter instanceof Zend_Db_Adapter_Abstract) {
                        $adapter->getProfiler()->setEnabled(true);
                        $this->_db[$name] = $adapter;
                    }
                }
            }
    }

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
        if (!$this->_db)
            return 'No adapter';

        foreach ($this->_db as $adapter) {
            $profiler = $adapter->getProfiler();
            $adapterInfo[] = $profiler->getTotalNumQueries() . ' in ' . round($profiler->getTotalElapsedSecs() * 1000, 2) . ' ms';
        }
        $html = implode(' / ', $adapterInfo);

        return $html;
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        if (!$this->_db)
            return '';

        $html = '<h4>Db queries</h4>';
        if (Zend_Db_Table_Abstract::getDefaultMetadataCache()) {
            $html .= 'Metadata Pelican_Cache is ENABLED';
        } else {
            $html .= 'Metadata Pelican_Cache is DISABLED';
        }

        foreach ($this->_db as $name => $adapter) {
            if ($profiles = $adapter->getProfiler()->getQueryProfiles()) {
                $html .= '<h4>Adapter ' . $name . '</h4><ol>';
                foreach ($profiles as $profile) {
                    $html .= '<li><strong>[' . round($profile->getElapsedSecs() * 1000, 2) . ' ms]</strong> ' . htmlspecialchars($profile->getQuery()) . '</li>';
                }
                $html .= '</ol>';
            }
        }

        return $html;
    }

}
