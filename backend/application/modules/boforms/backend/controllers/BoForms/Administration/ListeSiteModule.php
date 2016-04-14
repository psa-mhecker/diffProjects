<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialResponse.php');
//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');
/**
    *
    * Controller Backend
    *
    */
class BoForms_Administration_ListeSiteModule_Controller extends Pelican_Controller_Back
{
	protected $administration = true;

    protected $form_name = "boforms_groupe";

    protected $field_id = "GROUPE_ID";

    protected $defaultOrder = "GROUPE_LABEL";

 protected function setListModel ()
    {
    	$sfilter="";
    	if($_GET['filter_search_keyword'])
    	{
    		
    		$aField = array("GROUPE_LABEL","FORMSITE_LABEL");
    		
    		$word = $_GET['filter_search_keyword'];
    		foreach ($aField as $j=>$field)
    		{
    			if($j>0)
    				$sfilter .= " OR ";
    		
    			$sfilter .= "UPPER($field) like UPPER('%$word%')";
    		}
    	}
    	if ($sfilter != '') {
    		$sfilter = ' AND ( ' . $sfilter . ' ) ';
    	}
    	
        $this->listModel = "SELECT bg.GROUPE_ID, bg.GROUPE_LABEL, bf.FORMSITE_KEY,bf.FORMSITE_ID
        					FROM #pref#_boforms_groupe bg
							LEFT JOIN #pref#_boforms_formulaire_site bf ON (bg.FORMSITE_ID_MASTER=bf.FORMSITE_ID) 
        					WHERE bg.SITE_ID = " . $_SESSION[APP]['SITE_ID'] . " $sfilter
        					ORDER BY ".$this->listOrder;
        
    }

    protected function setEditModel ()
    {
        
            $this->editModel = "SELECT * from #pref#_boforms_groupe WHERE GROUPE_ID='" . $this->id . "'";
       
    }

    public function listAction ()
    {
    	/* update reference */
    	FunctionsUtils::updateReferences();
    	/**/
    	
    	$head = $this->getView()->getHead();
		//$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", array(/*"GROUPE_LABEL2","FORMSITE_LABEL"*/));
        $table->setFilterField();
        $table->setFilterField();
        $table->getFilter(3);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "bg.GROUPE_ID");
        
        
        $oConnection = Pelican_Db::getInstance ();
        
        $aBind = array();
        
        /*
        $sSqlSite = "Select FORMSITE_KEY 
        			 From #pref#_boforms_formulaire_site 
        			 Where GROUPE_ID = :GROUPE_ID
        			 AND FORMSITE_ID != :SITE_REFERENT
        			 ORDER BY FORMSITE_ID";
        */
        
        $sSqlSite = "SELECT fs.FORMSITE_KEY 
        			 FROM #pref#_boforms_groupe_formulaire gf 
        			 INNER JOIN #pref#_boforms_formulaire_site fs on gf.FORMSITE_ID = fs.FORMSITE_ID 
        			 WHERE gf.GROUPE_ID = :GROUPE_ID
        			 AND fs.FORMSITE_ID != :SITE_REFERENT
        			 ORDER BY fs.FORMSITE_ID";
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		 
        		$aBind[':GROUPE_ID']=$row['GROUPE_ID'];
        		$aBind[':SITE_REFERENT']=$row['FORMSITE_ID'];
        		 
        		$aSites = $oConnection->queryTab($sSqlSite,$aBind);
        		
        		if (empty($row['FORMSITE_KEY'])) {
        			$table->aTableValues[$k]['LIST_SITES'] = '';
        		} else {
        			$table->aTableValues[$k]['LIST_SITES'] = t('BOFORMS_FORMSITE_LABEL_' . $row['FORMSITE_KEY']); 
        		}
        		
        		if(is_array($aSites))
        		{
        			foreach ($aSites as $site)
        			{
        				$table->aTableValues[$k]['LIST_SITES'] .= ', '. t('BOFORMS_FORMSITE_LABEL_' . $site['FORMSITE_KEY']);
        			}
        		}
        		 
        	}
        }
        
        
        $table->addColumn(t('ID'), "GROUPE_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_LABEL'), "GROUPE_LABEL", "50", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_SITES_ASSOCIES'), "LIST_SITES", "50", "left", "", "tblheader");
        /*
         * @TODO $table->addColumn(t('BOFORMS_MODE'), "BOFORMS_MODE", "30", "left", "", "tblheader"); $table->addInput(t('BOFORMS_VALUES'), "button", array( "id" => "BOFORMS_ID", "" => "values=true" ), "center");
         */
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "GROUPE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "GROUPE_ID",
            "" => "readO=true"
        ), "center");
        
		$html = '<script type="text/javascript">';
		$html .= "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_SITE_GROUP') . "'); 
		    	$('#body_child div.form_title').html('" . t('BOFORMS_TRANSLATE_LIST_SITE_GROUP') . "');
		     });";
		$html .= '</script>';
        
        $this->setResponse($table->getTable() . $html);
    }
    
    
    public function editAction ()
    {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$head = $this->getView()->getHead();
		//$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	    	
    		$form_plus =  '<script type="text/javascript">';
			$form_plus .=  "$( document ).ready(function() {
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_SITE_GROUP') . "'); 
			     });";
			$form_plus .= '</script>';
    	
    	if($_GET['readO'])
    	{
    		$bNoDelete=false;
    		
    		$aBind = array();
    		$aBind[':GROUPE_ID'] = $_GET['id'];
    		
    		/*
    		$sSqlSite = "Select count(FORMSITE_ID) as nb_site
        			 From #pref#_boforms_formulaire_site
        			 Where GROUPE_ID = :GROUPE_ID";
    		*/
    		
    	  	$sSqlSite = "SELECT count(fs.FORMSITE_ID) as nb_site 
        			 FROM #pref#_boforms_formulaire_site fs 
        			 INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID
        			 INNER JOIN #pref#_boforms_groupe bg on bg.GROUPE_ID = gf.GROUPE_ID 
        			 WHERE gf.GROUPE_ID = :GROUPE_ID AND bg.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
        	
    		$nbSites=$oConnection->queryItem($sSqlSite,$aBind);
    		
    		if((int)$nbSites>0)
    		{
    			$bNoDelete=true;
    			$form_plus .=  "<h3 style='font-weight:bold;color: red;'>Cet élément n'est pas supprimable car des sites y sont associés</h3>";
    		}
    		
    	}
    	
    	// js array used to check that groupe_label does not exists
    	
    	$sqlGroupeLabel = "SELECT UPPER(bg.GROUPE_LABEL) as GROUPE_LABEL
        					FROM #pref#_boforms_groupe bg
    						WHERE GROUPE_ID != " . $_GET['id'] . ' AND SITE_ID = ' . $_SESSION[APP]['SITE_ID'];
    	
    	$result = $oConnection->queryTab($sqlGroupeLabel);
    	
    	$js_tbl_groupe = 'var js_tbl_groups = [];';
    	foreach($result as $key => $tbl) {
    		// exclude current groupe_label if existing
    		if($_GET['id'] <= 0 || $tbl['GROUPE_ID'] != $_GET['id']) {
    			$js_tbl_groupe .= "js_tbl_groups.push('" . addslashes(strtoupper(trim($tbl['GROUPE_LABEL']))) . "');";
    		}
    	}
    	
    
    	$js_tbl_groupe .= "var str_error_groupe_label = '" . t('BOFORMS_VALIDATION_ERROR_GROUP_LABEL') . "';";
    	    	
    	parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
               
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        
        $form .= $this->oForm->beginFormTable();
        //$form = $this->startStandardForm();
		
        if($_GET['id'] > 0)
        {
        	$form .= $this->oForm->createLabel("id",$this->values["GROUPE_ID"]);
        }
        
       
        $form .= $this->oForm->createInput("GROUPE_LABEL", t('BOFORMS_LABEL'), 255, "", true, $this->values["GROUPE_LABEL"], $this->readO, 50);
        $form .= $this->oForm->createTextArea("GROUPE_TEXT", t('BOFORMS_COMMENTAIRE'), false, $this->values["GROUPE_TEXT"], 1000, $this->readO, 10, 75);
        
        // les sites associes a ce groupe ou les sites qui ne sont pas associés à un autre groupe du site courant
        /*
        $strSQLList = "SELECT FORMSITE_ID as id, FORMSITE_KEY as lib
				FROM #pref#_boforms_formulaire_site
				WHERE (GROUPE_ID IS NULL OR GROUPE_ID = " . $this->id . ")
				ORDER BY id";
		*/
        
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        
        $strSQLList = "Select fs.FORMSITE_ID as id, fs.FORMSITE_KEY as lib
		FROM psa_boforms_formulaire_site fs
		WHERE fs.FORMSITE_ID not in (
		        		
			SELECT fs.FORMSITE_ID
			FROM psa_boforms_formulaire_site fs
			INNER JOIN psa_boforms_groupe_formulaire gf ON fs.FORMSITE_ID=gf.FORMSITE_ID
			INNER JOIN psa_boforms_groupe g ON gf.GROUPE_ID=g.GROUPE_ID
			WHERE SITE_ID = :SITE_ID AND g.GROUPE_ID!=".$_GET['id']."
		        		
		)";

		$sqlListTab = $oConnection->queryTab($strSQLList, $aBind);
		$listForAssoc = array();
        for ($i = 0; $i < count($sqlListTab); $i++) {
       		$listForAssoc[$sqlListTab[$i]['id']] = t('BOFORMS_FORMSITE_LABEL_' .  $sqlListTab[$i]['lib']);
        }
        
        // les sites associes a ce groupe
		
		$strSQLSelectedList = "SELECT fs.FORMSITE_ID as id
			     		FROM #pref#_boforms_formulaire_site fs 
			        	INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID 
			        	WHERE gf.GROUPE_ID = " . $this->id . " ORDER BY id";
        
		$strSQLSelectedList = "SELECT fs.FORMSITE_ID as id
	FROM psa_boforms_formulaire_site fs
	INNER JOIN psa_boforms_groupe_formulaire gf ON fs.FORMSITE_ID=gf.FORMSITE_ID
	INNER JOIN psa_boforms_groupe g ON gf.GROUPE_ID=g.GROUPE_ID
	WHERE g.SITE_ID = :SITE_ID AND g.GROUPE_ID=".$_GET['id'];
		
		$sqlSelectedListTab = $oConnection->queryTab($strSQLSelectedList,$aBind);

		$listSelectedForAssoc = array();
		for ($i = 0; $i < count($sqlSelectedListTab); $i++) {
        	$listSelectedForAssoc[] = $sqlSelectedListTab[$i]['id'];
        }
		
		
        $form .= $this->oForm->createAssocFromList ($oConnection, "assoc_site_id", t ( 'BOFORMS_SITES_ASSOCIES' ), $listForAssoc, $listSelectedForAssoc, false, true, $this->readO, 8, 250, false, "" );
        
        /*    
        $strSQLSelectedList = "SELECT FORMSITE_ID as id, FORMSITE_KEY as lib
				FROM #pref#_boforms_formulaire_site
				WHERE GROUPE_ID=" . $this->id . "
				ORDER BY id";
        */
        $strSQLSelectedList = "SELECT fs.FORMSITE_ID as id, fs.FORMSITE_KEY as lib
			     		FROM #pref#_boforms_formulaire_site fs 
			        	INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID 
			        	WHERE gf.GROUPE_ID = " . $this->id . " ORDER BY id";
        
        
        $selectedListTab = $oConnection->queryTab($strSQLSelectedList);
        $tblForCombo = array();
        for($i = 0; $i < count($selectedListTab ); $i++) {
        	$tblForCombo[$selectedListTab[$i]['id']] = t('BOFORMS_FORMSITE_LABEL_' .  $selectedListTab[$i]['lib']);	
        }
        $form .= $this->oForm->createComboFromList("FORMSITE_ID_MASTER", t ( 'BOFORMS_GROUPE_REFERENT' ), $tblForCombo, $this->values ["FORMSITE_ID_MASTER"], false, $this->readO);
        
        if(!$_GET['readO'])
        {
        	$this->oForm->createJs("if (check_group_label(obj)) { alert(str_error_groupe_label); return false; }");	
        }
        
        $form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

	
		
        
	$form = formToString($this->oForm, $form);
	
	// mise à jour de la liste référent
	$form_plus .= '<script type="text/javascript">' . $js_tbl_groupe . 
	'
	function check_group_label(obj) {
		for (ii = 0; ii < js_tbl_groups.length; ii++) {
			
			label = $("#GROUPE_LABEL").val();
			
			if (js_tbl_groups[ii] == label.trim().toUpperCase()) {
				return true;
			}
		}	
		return false;
	}
	
	function updateListRef(){
	
		
		$("#FORMSITE_ID_MASTER option").each(function(){
			if($(this).attr("value")!="")
			{
				$(this).remove()
			}
		});
		
	
		$("#assoc_site_id option").each(function(){
											
			$("#FORMSITE_ID_MASTER")
	         .append($("<option></option>")
	         .attr("value",$(this).attr("value"))
	         .text($(this).html())); 
	         	         		
		});
		
		var selected_val = "'.$this->values ["FORMSITE_ID_MASTER"].'";
			
		$("#FORMSITE_ID_MASTER option").each(function(){
			if(selected_val)
			{	
				if($(this).attr("value")==selected_val)
				{
					$(this).attr("selected","selected");
				}
			}
		});
			
	}
		
	$( document ).ready(function() {
					
		$( "a[onclick^=\'assoc\']" ).click(function() {
			
			updateListRef();
		});
				
	});
	
	</script>';
	
		if($_GET['readO'] && $bNoDelete)
		{
		
			$this->aButton["delete"]="";
			Backoffice_Button_Helper::init($this->aButton);
		}
        // Zend_Form stop
		$this->setResponse($form_plus . $form);
    }
    
    
    
    public function saveAction ()
    {
    	// controle du groupe label
    	// les autres groupes du site courant, ne doivent pas avoir le meme label
    	
    	$aBind[':GROUPE_ID'] = Pelican_Db::$values["GROUPE_ID"];
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    	
    	$oConnection = Pelican_Db::getInstance ();
    	$sqlGroupeLabel = "SELECT UPPER(bg.GROUPE_LABEL) as GROUPE_LABEL
        					FROM #pref#_boforms_groupe bg
    						WHERE GROUPE_ID != :GROUPE_ID AND SITE_ID = :SITE_ID";
    	$result = $oConnection->queryTab($sqlGroupeLabel, $aBind);
    	
    	foreach($result as $key => $tbl) {
    		if (trim($tbl['GROUPE_LABEL']) == trim(strtoupper(Pelican_Db::$values['GROUPE_LABEL']))) {
    			return;    		
    		}
    	}   	
    	
    	// si groupe label ok
    	
    	parent::saveAction();
	   
	    Pelican_Db::$values['GROUPE_LABEL'] = trim(Pelican_Db::$values['GROUPE_LABEL']);
	     	
	    if(Pelican_Db::$values['form_action'] != Pelican_Db::DATABASE_DELETE)
	    {
		     	     		     		
	     	/*	
		    	$oConnection->query("update #pref#_boforms_formulaire_site set GROUPE_ID = NULL where GROUPE_ID = ".Pelican_Db::$values["GROUPE_ID"]);
		   	
		    	if(array(Pelican_Db::$values["assoc_site_id"]) && !empty(Pelican_Db::$values["assoc_site_id"]))
		     	{     		
		     		foreach (Pelican_Db::$values["assoc_site_id"] as $id)
		     		{
		     			Pelican_Db::$values['FORMSITE_ID']=$id;
		     			$oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "#pref#_boforms_formulaire_site","","",array("FORMSITE_ID","GROUPE_ID"));
		     		}
		     	}
		   	*/
	     		
			$aBind[':GROUPE_ID'] = Pelican_Db::$values["GROUPE_ID"];
			$aBind[':FORMSITE_ID_MASTER'] = Pelican_Db::$values["FORMSITE_ID_MASTER"];

			// mise a jour du champ FORMSITE_ID_MASTER dans la table boforms_groupe
	    	$oConnection->query("UPDATE #pref#_boforms_groupe set FORMSITE_ID_MASTER = :FORMSITE_ID_MASTER WHERE GROUPE_ID = :GROUPE_ID", $aBind);
	     		
	    	// mise a jour des sites dans la table boforms_groupe_formulaire
	    	if(array(Pelican_Db::$values["assoc_site_id"])) {			
		    	$sql_plus = "";
				if (count($list_form_site_id) > 0) {
			    	$list_form_site_id = implode(',', Pelican_Db::$values["assoc_site_id"]);
		     		$sql_plus = " AND FORMSITE_ID NOT IN ($list_form_site_id) ";
		     	}
			     			
			    $oConnection->query("DELETE FROM #pref#_boforms_groupe_formulaire 
			     								 WHERE GROUPE_ID = :GROUPE_ID $sql_plus", $aBind);
			     			
				foreach (Pelican_Db::$values["assoc_site_id"] as $id)
				{
					$aBind[':FORMSITE_ID'] = $id;
				    $oConnection->query("INSERT IGNORE INTO #pref#_boforms_groupe_formulaire (GROUPE_ID, FORMSITE_ID)  
				     								 VALUES (:GROUPE_ID, :FORMSITE_ID)", $aBind);
				}
	     	}			     	
	     }else{
	     	$oConnection->query("delete from #pref#_boforms_groupe where GROUPE_ID = ".Pelican_Db::$values["GROUPE_ID"]);
			$oConnection->query("delete from #pref#_boforms_groupe_formulaire where GROUPE_ID = ".Pelican_Db::$values["GROUPE_ID"]);
	    }
    }
    
}
