<?php
/**
 * Smarty plugin
 * @package View
 * @subpackage plugins
 */

 /**
 * Smarty {modeAffichage} function plugin
 *
 * Type:     function
 * Name:     modeAffichage
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
/* Inclusion du Helper qui fait le boulot */
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Design.php');
/**
 * 
 * @param type $params  Utilisation de la clé src pour distinguer si le mode d'affichage
 *                      provient de la zone ou de la page. Par défaut c'est celui
 *                      de la zone qui est utilisé.
 *                      
 * @param type $view
 * @return string       Classe CSS à utilisation en fonction du mode d'affichage
 *                      Ligne DS, Ligne C, Neutre
 */
function smarty_function_modeAffichage($params, &$view)
{
    /* Initialisation des variables */
    $sSrc = 'zone';
    
    /* Recherche de paramètre éventuel */
    if ( is_array($params) && isset($params['src']) && !empty($params['src']) ) {
        $sSrc = $params['src'];
    }
 
    return Frontoffice_Design_Helper::getModeAffichage($view->parent->tpl_vars['aParams']->value,$sSrc);
}