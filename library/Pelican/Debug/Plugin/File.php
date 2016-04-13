<?php
/**
 * @package    Pelican
 * @subpackage Pelican_Debug
 */
class Pelican_Debug_Plugin_File implements Pelican_Debug_Plugin_Interface
{

    /**
     * Contains Pelican_Plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'file';

    /**
     * Base path of this application
     * String is used to strip it from filenames
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Stores included files
     *
     * @var array
     */
    protected $_includedFiles = null;

    /**
     * Stores name of own extension library
     *
     * @var string
     */
    protected $_library;

    /**
     * Setting Options
     *
     * basePath:
     * This will normally not your document root of your webserver, its your
     * application root directory with /application, /library and /public
     *
     * library:
     * Your own library extension(s)
     *
     * @param  array $options
     * @return void
     */
    public function __construct()
    {
        isset($options['base_path']) || $options['base_path'] = $_SERVER['DOCUMENT_ROOT'];
        isset($options['library']) || $options['library'] = null;

        $this->_basePath = $options['base_path'];
        is_array($options['library']) || $options['library'] = array($options['library']);
        $this->_library = array_merge($options['library'], array('controllers', 'caches', 'configs', 'Zend', 'Pelican', 'view_compiles'));
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
        return count($this->_getIncludedFiles()) . ' Fichiers';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $included = $this->_getIncludedFiles();
        $val = count($included) . ' Fichiers inclus<br />';
        $size = 0;
        foreach ($included as $file) {
            $size += filesize($file);
        }
        $val .= 'Taille totale : ' . round($size / 1024, 1) . 'K<br />';
        $val .= 'Document Root : ' . $this->_basePath . '<br />';
        $html = Pelican_Debug::getFieldset('Information sur les fichiers',$val);

        $libraryFiles = array();
        foreach ($this->_library as $key => $value) {
            if ('' != $value) {
                $libraryFiles[$key] = '<legend>' . $value . '</legend>';
            }
        }

        $html .= '<fieldset><legend>Fichiers d\'application</legend>';
        foreach ($included as $file) {
            $file = str_replace($this->_basePath, '', $file);
            $inUserLib = false;
            foreach ($this->_library as $key => $library) {
                if ('' != $library && false !== strstr($file, $library)) {
                    $libraryFiles[$key] .= $file . '<br />';
                    $inUserLib = TRUE;
                }
            }
            if (!$inUserLib) {
                $html .= $file . '<br />';
            }
        }
        $html .= '</fieldset>';
        $html .= '<fieldset>'.implode('</fieldset><fieldset>', $libraryFiles).'</fieldset>';

        return $html;
    }

    /**
     * Gets included files
     *
     * @return array
     */
    protected function _getIncludedFiles()
    {
        if (null !== $this->_includedFiles) {
            return $this->_includedFiles;
        }

        $this->_includedFiles = get_included_files();
        sort($this->_includedFiles);

        return $this->_includedFiles;
    }
}
