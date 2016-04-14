<?php
/**
 * Classe de gestion de l'indexation et de la sélection des résultats de recherche  via lucene.
 *
 * Intégration des documents pdf, doc, xls, ppt et rtf pour windows et linus
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 * @since 24/03/2008
 *
 * @version 1.0
 */
require_once Pelican::$config['LIB_ROOT'].'/Zend/Search/Lucene.php';
pelican_import('Search.Interface');

/**
 * Classe de gestion de l'indexation et de la sélection des résultats de
 * recherche  via lucene.
 *
 * Intégration des documents pdf, doc, xls, ppt et rtf pour windows et linus
 *
 * @author Patrick Deroubaix <patrick.deroubaix@businessdecision.fr>
 *
 * @since 24/03/2008
 *
 * @version 1.0
 */
class Pelican_Search_Lucene implements Pelican_Search_Interface
{
    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $current_page    (option) __DESC__
     * @param __TYPE__ $nbResultPerPage (option) __DESC__
     * @param __TYPE__ $order           (option) __DESC__
     * @param __TYPE__ $fields          (option) __DESC__
     *
     * @return Research
     */
    public function getResult($current_page = 1, $nbResultPerPage = 10, $order = "date", $fields = array())
    {
        global $aScore;
        if (!$current_page) {
            $current_page = 1;
        }
        if ($_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order]) {
            $return = $_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order];
        }
        $query = (isset($_GET['recMot']) ? $_GET['recMot'] : '');
        $query = trim($query);
        $indexPath = Pelican::$config['LIB_ROOT'].'/_work/test/luceneindex';
        Zend_Search_Lucene::setResultSetLimit(100);
        $index = Zend_Search_Lucene::open($indexPath);
        if (strlen($query) > 0) {
            $hits = $index->find($query);
            $numHits = count($hits);
            $_SESSION[APP]["current_search"]["statistics"]["count"] = $numHits;
        }
        $resultRech = array();
        foreach ($hits as $hit) {
            $rows = array();
            $rows["PERTINENCE"] = round($hit->score, 2);
            $rows["RESEARCH_TITLE"] = $hit->RESEARCH_TITLE;
            $rows["RESEARCH_URL"] = $hit->RESEARCH_URL;
            $rows["RESEARCH_DESCRIPTION"] = $hit->RESEARCH_DESCRIPTION;
            $rows["RESEARCH_ID"] = $hit->RESEARCH_ID;
            array_push($resultRech, $rows);
        }
        //$_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order] = $return;
        return $resultRech;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $site    __DESC__
     * @param __TYPE__ $langue  __DESC__
     * @param __TYPE__ $fields  __DESC__
     * @param __TYPE__ $filters __DESC__
     *
     * @return __TYPE__
     */
    public function getStatistics($site, $langue, $fields, $filters)
    {
        $_SESSION[APP]["research"][$research["hash"]]["statistics"] = 1;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $aField  __DESC__
     * @param __TYPE__ $BindKey __DESC__
     * @param __TYPE__ $aBind   __DESC__
     * @param __TYPE__ $join    (option) __DESC__
     * @param __TYPE__ $order   (option) __DESC__
     *
     * @return __TYPE__
     */
    public function containsClause($aField, $BindKey, &$aBind, $join = "OR", $order = 1)
    {
    }
}
