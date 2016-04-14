<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
interface Pelican_Search_Interface
{
    /**
     **
     * Comptages du résultat de recherche + pour les champs demandés en paramètres.
     *
     * @static
     *
     * @param string $fields  liste des champs du tableau de retour pour lesquels on veut un comptage
     * @param mixed  $filters clauses de filtrage du type array("champ","valeur","type") où type peut être string, date, keyword, integer
     */
    public function getStatistics($site, $langue, $fields, $filters);

    /**
     * Retourne le résultat d'une recherche.
     *
     * @static
     *
     * @param string  $word
     * @param integer $current_page
     * @param integer $site_id
     * @param integer $langue_id
     *
     * @return mixed
     */
    public function getResult($current_page = 1, $nbResultPerPage = 10, $order = "date", $fields = array());

    /**
     * Enter description here...
     *
     * @static
     *
     * @param unknown_type $aField
     * @param unknown_type $BindKey
     * @param unknown_type $aBind
     * @param unknown_type $join
     *
     * @return unknown
     */
    public function containsClause($aField, $BindKey, &$aBind, $join = "OR", $order = 1);

    /*
     * Enter description here...
     *
     * @static
     * @return unknown
     */
}
