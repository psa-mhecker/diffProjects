<?php
/**
 * Classe de gestion des v�rifications de fichiers du Pelican_Index_Backoffice
 *
 * @package Pelican_BackOffice
 * @subpackage Lib
 * @author Rapha�l Carles <rcarles@businessdecision.com>
 * @since 10/01/2006
 */
class Backoffice_File_Helper
{
	/**
     * Valide le format d'un fichier CSV
     *
     * @param Boolean $return true si format valide
     */
    public static function isCSV($type)
    {
        $mimes = array('application/vnd.ms-excel','application/csv','text/plain','text/csv','text/tsv');
		if(in_array($type,$mimes)){
		  $return = true;
		} else {
		  $return = false;
		}
		return $return;
    }
}