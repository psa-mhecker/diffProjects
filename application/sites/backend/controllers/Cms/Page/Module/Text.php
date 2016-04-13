<?php

require_once(dirname(__FILE__) . '/../../../Cms/Page/Module.php');

class Cms_Page_Module_Text extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('TEXTE'), false, $controller->zoneValues["ZONE_TEXTE"], 16000, $controller->readO, 3, 75);
        return $return;
    }
    
    public static function pageAddForm($oForm, $values, $readO, $multi) {
		$return .= $oForm->createInput($multi."CONTENT_ZONE_TITLE", t('TITRE'), 255 , "" , true , $values['CONTENT_ZONE_TITLE'] , $readO, 100, false, "" , "text");
        $return .= $oForm->createTextArea($multi."CONTENT_ZONE_TEXT", t('CONTENU'), true , $values['CONTENT_ZONE_TEXT'] , 0 , $readO , 5, 100, false, "", true, "" );
        //$return .= $oForm->createMultiHmvc($multi . 'test', "pushtoto", array('path'=>'/home/projects/dev/cppv2/application/sites/backend/controllers/Cms/Page/Module/Text.php', 'class'=>'Cms_Page_Module_Text', 'method'=>'pageAdd2Form'), array(), 'toto');
        
        return $return;
	} 
	
	public static function pageAdd2Form($oForm, $values, $readO, $multi) {
		$return .= $oForm->createInput($multi."CONTENT_ZONE_TITLE", t('TITRE'), 255 , "" , true , $values['CONTENT_ZONE_TITLE'] , $readO, 100, false, "" , "text");
        
        return $return;
	} 
}