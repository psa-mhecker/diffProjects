<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

pelican_import('Mail', 'Zend');

//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

/**
 * Gestion des traductions de referentiels
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Hervé Lechevallier
 * @since 23/03/2015
 */

class BoForms_Administration_TraductionReferentiels_Controller extends Pelican_Controller_Back
{
	protected $administration = true;
    protected $form_name = "boforms_traductions_referentiel";
    protected $defaultOrder = "TRAD_REF_KEY";
    

	protected function setListModel ()
    {
    	$sfilter="";
    	if($_GET['filter_search_keyword'])
    	{
    		$word = $_GET['filter_search_keyword'];
    		$sfilter = "WHERE UPPER(TRAD_REF_KEY) like UPPER('%$word%')";
    	}
    	
        $this->listModel = "SELECT TRAD_REF_ID, TRAD_REF_KEY FROM #pref#_boforms_traductions_referentiel " . $sfilter . " ORDER BY ". $this->listOrder;		
    }
    
	public function listAction() {
		$head = $this->getView()->getHead();
		//$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", array(/*"TRAD_REF_KEY"*/));
        $table->setFilterField();
        $table->getFilter(3);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "TRAD_REF_KEY");
                
        $table->addColumn(t('ID'), "TRAD_REF_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_TRAD_REF_KEY'), "TRAD_REF_KEY", "20", "left", "", "tblheader");
        
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "TRAD_REF_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "TRAD_REF_ID","" => "readO=true"), "center");
        
		$html = '<script type="text/javascript">';
		$html .= "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRADUCTIONREFERENTIELS') . "'); 
		    	$('#body_child div.form_title').html('" . t('BOFORMS_TRADUCTIONREFERENTIELS_LIST') . "');
		     });";
		$html .= '</script>';
        
        $this->setResponse($table->getTable() . $html);
	}

	protected function setEditModel ()
    {        
            $this->editModel = "SELECT TRAD_REF_ID, TRAD_REF_KEY FROM #pref#_boforms_traductions_referentiel WHERE TRAD_REF_ID='" . $this->id . "'";
    }
	
	public function editAction() {
		$oConnection = Pelican_Db::getInstance ();
    	
    	$head = $this->getView()->getHead();
		//$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	    	
    	$form_plus =  '<script type="text/javascript">';
		$form_plus .=  "$( document ).ready(function() {
							window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRADUCTIONREFERENTIELS') . "'); 
					    });";
		$form_plus .= '</script>';
    	
		$aBind = array();
    	$aBind[':TRAD_REF_ID'] = $_GET['id'];
    	
    	parent::editAction();
        
    	$this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
               
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        
        $form .= $this->oForm->beginFormTable();
        
		$form .= $this->oForm->createHidden("trad_ref_id", $_GET['id']);
		
        if($_GET['id'] > 0)
        {
        	$form .= $this->oForm->createLabel(t("BOFORMS_TRAD_REF_KEY"), $this->values["TRAD_REF_KEY"]);
        	$form .= $this->oForm->createHidden("trad_ref_key", $this->values["TRAD_REF_KEY"]);
        } else {
        	$form .= $this->oForm->createInput("trad_ref_key", t('BOFORMS_TRAD_REF_KEY'), 80, "", true, $this->values["TRAD_REF_KEY"], $this->readO, 50);	
        }
        
        // tableau avec les traductions
        $result = $oConnection->queryTab('select TRAD_REF_ID, TRAD_REF_LOCALE, SITE_CODE_PAYS, TRAD_REF_VALUE from #pref#_boforms_traductions_referentiel_datas where TRAD_REF_ID = :TRAD_REF_ID', $aBind);  
		$tbl_trads = array();
		for ($i = 0; $i < count($result); $i++) {
			$tbl_trads[$result[$i]['TRAD_REF_LOCALE'] . '___' . $result[$i]['SITE_CODE_PAYS']] = $result[$i]['TRAD_REF_VALUE']; 
		}
		
		
        // gets languages for site
    	$sqlLangue = "SELECT c.langue_code, c.langue_label,c.langue_id , a.SITE_CODE_PAYS FROM #pref#_site_code a
					  INNER JOIN #pref#_site_language b ON a.site_id = b.site_id
					  INNER JOIN #pref#_language c ON c.langue_id = b.langue_id
					  WHERE a.SITE_CODE_PAYS != 'AA' ";
    	
    	// si pas site admin on filtre sur le site_id
    	if ($_SESSION[APP]['SITE_ID'] != '1') {
    		  $sqlLangue .= ' and a.site_id = :SITE_ID';
    	}
    	
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
   		$aLangues = $oConnection->queryTab($sqlLangue, $aBind);
   		
   		// afficher les clefs de trad du site courant
   		if (count($aLangues) > 0) {
	       	$form .= $this->oForm->createLabel(t('BOFORMS_TRADUCTIONREFERENTIELS_FOR_THIS_SITE'), '');
        
        	for ($i = 0; $i < count($aLangues); $i++) {
	        	$strLocale = $aLangues[$i]['langue_code'] . '-' . $aLangues[$i]['SITE_CODE_PAYS'];
        		$locale = $aLangues[$i]['langue_id'] . '___' . $aLangues[$i]['SITE_CODE_PAYS'];
	        		        	
	        	$trad = (isset($tbl_trads[$locale])) ? $tbl_trads[$locale] : ''; 
				$form .= $this->oForm->createInput("trad_ref_value_$locale", $strLocale, 255, "", false, $trad, $this->readO, 80);        		
	        }
        } else {
        	$form .= $this->oForm->createLabel(t('BOFORMS_TRADUCTIONREFERENTIELS_FOR_THIS_SITE'), 'An error occured: no locales found for this site.');
        }	        
        
        $form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();
        
		$form = formToString($this->oForm, $form);
	
        // Zend_Form stop
		$this->setResponse($form_plus . $form);		
	}

	public function saveAction() {
		$oConnection = Pelican_Db::getInstance ();
    	
		if (Pelican_Db::$values['trad_ref_id'] == -2) {
			Pelican_Db::$values['trad_ref_id'] = 0;
		}
		
		parent::saveAction();
		if(Pelican_Db::$values['form_action'] == Pelican_Db::DATABASE_DELETE) {
	    	echo " delete ";
	    	die('delete');
	    } else {
			// SEUL LE SUPER ADMIN PEUT MODIFIER UN ENREGISTREMENT
	    	$allowed = true;
			/*if (Pelican_Db::$values['form_action'] == 'UPD' && (! in_array($_SESSION[APP]['backoffice']['USER_LOGIN'], Pelican::$config['BOFORMS_USER_SUPER_ADMIN']))) {
				$allowed = false;
			}*/
	    	
	    	if ($allowed) {	    	
		    	$aBind[':TRAD_REF_KEY'] = $oConnection->strToBind(Pelican_Db::$values['trad_ref_key']); 
				$trad_ref_id = $oConnection->queryItem('select TRAD_REF_ID from #pref#_boforms_traductions_referentiel where TRAD_REF_KEY = :TRAD_REF_KEY', $aBind);
				if ($trad_ref_id > 0) {
					$aBind[':TRAD_REF_ID'] = $trad_ref_id;		
					
					// recherche des locales dans les paramètres et enregistrement des traductions
					foreach(Pelican_Db::$values as $key => $traduction) {
						if (substr($key, 0, 15) == 'trad_ref_value_') {
							$locale_and_country = substr($key, 15);
							$tbl_locale = explode('___', $locale_and_country);
							$aBind[':TRAD_REF_LOCALE'] = $tbl_locale[0];
							$aBind[':SITE_CODE_PAYS'] = $oConnection->strToBind($tbl_locale[1]); 
							$aBind[':TRAD_REF_VALUE'] =  $oConnection->strToBind($traduction);
							
							$oConnection->query('replace into #pref#_boforms_traductions_referentiel_datas (TRAD_REF_ID, TRAD_REF_LOCALE, SITE_CODE_PAYS, TRAD_REF_VALUE) values (:TRAD_REF_ID, :TRAD_REF_LOCALE, :SITE_CODE_PAYS, :TRAD_REF_VALUE) ', $aBind);
						}
					}
				}
	    	} else {
	    		die('You should have administrator rights in order to modify referential items');
	    	}
	    }
	}
   
    
}
