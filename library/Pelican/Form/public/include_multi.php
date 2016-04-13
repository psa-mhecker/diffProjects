<?php
	/** Include de champs récurrents des bouts de formulaires multiples utilisés par createMulti
	*
	* @version 1.0
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/01/2003
	* @package Pelican
	* @subpackage Pelican_Form
	*/
	if (!isset($readO)) {
		$readO = false;
	}
	if ($bAllowDeletion) {
		$oForm->createLabel(" n° ".($compteur+1), ($readO?"":"<input type=\"button\" class=\"buttonmulti\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\" onclick=\"delMulti('".$multi."')\" />"));
	}
	$oForm->createHidden($multi."multi_display", "1");
?>