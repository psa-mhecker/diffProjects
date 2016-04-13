<?php
/**
 * Gestion de pagination
 *
 * @package Pelican
 * @subpackage Pagination
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * Gestion de pagination
 *
 * @package Pelican
 * @subpackage Pagination
 * @author Patrick Deroubaix <patrick.deroubaix@businessdecision.com>
 */
class Pelican_Pagination {
    
    /**
     * The page we are linking to
     *
     * @access public
     * @var __TYPE__
     */
    public $baseUrl = '';
    
    /**
     * Total number of items (database results)
     *
     * @access public
     * @var __TYPE__
     */
    public $totalRows = '';
    
    /**
     * Max number of items you want shown per page
     *
     * @access public
     * @var __TYPE__
     */
    public $perPage = 10;
    
    /**
     * Number of "digit" links to show before/after the currently viewed page
     *
     * @access public
     * @var __TYPE__
     */
    public $numLinks = 2;
    
    /**
     * The current page being viewed
     *
     * @access public
     * @var __TYPE__
     */
    public $curPage = 0;
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $firstLink = '&lsaquo; First';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $nextLink = '&gt;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $prevLink = '&lt;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $lastLink = 'Last &rsaquo;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $fullTagOpen = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $fullTagClose = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $firstTagOpen = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $firstTagClose = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $lastTagOpen = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $lastTagClose = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $curTagOpen = '&nbsp;<strong>';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $curTagClose = '</strong>';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $nextTagOpen = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $nextTagClose = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $prevTagOpen = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $prevTagClose = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $numTagOpen = '&nbsp;';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $numTagClose = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $pageQueryString = TRUE;
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $queryStringSegment = 'perPage';
    
    /**
     * Constructor
     *
     * @access public
     * @param __TYPE__ $params (option) initialization parameters
     * @return __TYPE__
     */
    public function Pelican_Pagination($params = array()) {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }
    // --------------------------------------------------------------------
    
    
    /**
     * Initialize Preferences
     *
     * @access public
     * @param __TYPE__ $params (option) initialization parameters
     * @return void
     */
    public function initialize($params = array()) {
        if (count($params) > 0) {
            foreach($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }
    // --------------------------------------------------------------------
    
    
    /**
     * Generate the pagination links
     *
     * @access public
     * @return string
     */
    public function create_links() {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->totalRows == 0 or $this->perPage == 0) {
            return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRows / $this->perPage);
        // Is there only one page? Hm... nothing more to do here then.
        if ($numPages == 1) {
            return '';
        }
        // Determine the current page number.
        if ($_GET[$this->queryStringSegment] != 0) {
            // Prep the current page - no funny business!
            $this->curPage = (int)$_GET[$this->queryStringSegment];
        }
        $this->numLinks = (int)$this->numLinks;
        if ($this->numLinks < 1) {
            show_error('Your number of links must be a positive number.');
        }
        if (!is_numeric($this->curPage)) {
            $this->curPage = 0;
        }
        // Is the page number beyond the result range?
        // If so we show the last page
        if ($this->curPage > $this->totalRows) {
            $this->curPage = ($numPages - 1) * $this->perPage;
        }
        $uriPageNumber = $this->curPage;
        $this->curPage = floor(($this->curPage / $this->perPage) + 1);
        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->curPage - $this->numLinks) > 0) ? $this->curPage - ($this->numLinks - 1) : 1;
        $end = (($this->curPage + $this->numLinks) < $numPages) ? $this->curPage + $this->numLinks : $numPages;
        if ($this->pageQueryString === TRUE) {
            $pattern = '/(&' . $this->queryStringSegment . '=[0-9]*)/i';
            $this->baseUrl = preg_replace($pattern, '', $this->baseUrl);
            $interogation = preg_match('/(\?)/i', $this->baseUrl);
            if ($interogation) {
                $this->baseUrl = rtrim($this->baseUrl) . '&amp;' . $this->queryStringSegment . '=';
            } else {
                $this->baseUrl = rtrim($this->baseUrl) . '?' . $this->queryStringSegment . '=';
            }
        }
        // And here we go...
        $output = '';
        // Render the "First" link
        if ($this->curPage > ($this->numLinks + 1)) {
            $output.= $this->firstTagOpen . '<a href="' . $this->baseUrl . '">' . $this->firstLink . '</a>' . $this->firstTagClose;
        }
        // Render the "previous" link
        if ($this->curPage != 1) {
            $i = $this->curPage - 1;
            if ($i == 0) $i = '';
            $output.= $this->prevTagOpen . '<a href="' . $this->baseUrl . $i . '">' . $this->prevLink . '</a>' . $this->prevTagClose;
        }
        // Write the digit links
        for ($loop = $start;$loop <= $end;$loop++) {
            //$i = ($loop * $this->perPage) - $this->perPage;
            $i = $loop;
            if ($i >= 0) {
                if ($this->curPage == $loop) {
                    $output.= $this->curTagOpen . $loop . $this->curTagClose; // Current page
                    
                } else {
                    $n = ($i == 0) ? '' : $i;
                    $output.= $this->numTagOpen . '<a href="' . $this->baseUrl . $n . '">' . $loop . '</a>' . $this->numTagClose;
                }
            }
        }
        // Render the "next" link
        if ($this->curPage < $numPages) {
            $output.= $this->nextTagOpen . '<a href="' . $this->baseUrl . ($this->curPage * $this->perPage) . '">' . $this->nextLink . '</a>' . $this->nextTagClose;
        }
        // Render the "Last" link
        if (($this->curPage + $this->numLinks) < $numPages) {
            $output.= $this->lastTagOpen . '<a href="' . $this->baseUrl . $end . '">' . $this->lastLink . '</a>' . $this->lastTagClose;
        }
        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper Pelican_Html if exists
        $output = $this->fullTagOpen . $output . $this->fullTagClose;
        return $output;
    }
}
