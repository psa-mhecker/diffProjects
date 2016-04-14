<?php
/**
 * Class Minify_Controller_Page.
 */
require_once 'Minify/Controller/Base.php';

/**
 * Controller class for serving a single Pelican_Html page.
 *
 * @link http://code.google.com/p/minify/source/browse/trunk/web/examples/1/index.php#59
 *
 * @author Stephen Clay <steve@mrclay.org>
 */
class Minify_Controller_Page extends Minify_Controller_Base
{
    /**
     * Set up source of Pelican_Html content.
     *
     * @param array $options controller and Minify options
     *
     * @return array Minify options
     *
     * Controller options:
     *
     * 'content': (required) Pelican_Html markup
     *
     * 'id': (required) id of page (string for use in server-side caching)
     *
     * 'lastModifiedTime': timestamp of when this content changed. This
     * is recommended to allow both server and client-side caching.
     *
     * 'minifyAll': should all CSS and Javascript blocks be individually
     * minified? (default false)
     *
     * @todo Add 'file' option to read Pelican_Html file.
     */
    public function setupSources($options)
    {
        if (isset($options['file'])) {
            $sourceSpec = array(
                'filepath' => $options['file'],
            );
        } else {
            // strip controller options
            $sourceSpec = array(
                'content' => $options['content']
                ,'id' => $options['id'],
            );
            unset($options['content'], $options['id']);
        }
        if (isset($options['minifyAll'])) {
            // this will be the 2nd argument passed to Minify_Pelican_Html::minify()
            $sourceSpec['minifyOptions'] = array(
                'cssMinifier' => array('Minify_CSS', 'minify')
                ,'jsMinifier' => array('Minify_Javascript', 'minify'),
            );
            $this->_loadCssJsMinifiers = true;
            unset($options['minifyAll']);
        }
        $this->sources[] = new Minify_Source($sourceSpec);

        // may not be needed
        //$options['minifier'] = array('Minify_HTML', 'minify');

        $options['contentType'] = Minify::TYPE_HTML;

        return $options;
    }

    protected $_loadCssJsMinifiers = false;

    /**
     * @see Minify_Controller_Base::loadMinifier()
     */
    public function loadMinifier($minifierCallback)
    {
        if ($this->_loadCssJsMinifiers) {
            // Minify will not call for these so we must manually load
            // them when Minify/HTML.php is called for.
            require 'Minify/CSS.php';
            require 'Minify/Javascript.php';
        }
        parent::loadMinifier($minifierCallback); // load Minify/HTML.php
    }
}
