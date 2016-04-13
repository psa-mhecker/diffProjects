<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_Score_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_product_page";
    protected $field_id = "PRODUCT_PAGE_ID";
    protected $defaultOrder = "PRODUCT_PAGE_ID";

    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/ProductPage',
            array('SITE_ID')
        )
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/Perso/ProductPage',
            array('SITE_ID')
        )
    );

    protected function setListModel()
    {	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
						*,
						PRODUCT_LABEL
					FROM 
						#pref#_perso_product_page
					INNER JOIN
						#pref#_perso_product
						ON (#pref#_perso_product_page.PRODUCT_ID = #pref#_perso_product.PRODUCT_ID)
					WHERE 
						#pref#_perso_product_page.SITE_ID = :SITE_ID ";
            if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            PRODUCT_PAGE_URL like '%" . $_GET['filter_search_keyword'] . "%' 
            OR PRODUCT_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
            }
		$sqlList.= "ORDER BY " . $this->listOrder;
		$results	=	$oConnection->queryTab($sqlList,$aBind);
		$resultsList = array();
		foreach($results as $key => $result){
				
			if(isset(Pelican::$config['PERSO']['AJAX_LIST'][$result['PRODUCT_PAGE_URL']])){
				$result['PRODUCT_PAGE_URL']	=	Pelican::$config['PERSO']['AJAX_LIST'][$result['PRODUCT_PAGE_URL']]['lib'];
			}
			$resultsList[$key] = $result;
		}
        $this->listModel = $resultsList;
    }

    protected function setEditModel()
    {
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
								* 
							FROM 
								#pref#_perso_product_page 
							WHERE 
								SITE_ID = :SITE_ID 
							AND ".$this->field_id." = :" . $this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "PRODUCT_PAGE_ID");
        //$table->addColumn(t('ID'), "PRODUCT_PAGE_ID", "10", "left", "", "tblheader", "PRODUCT_PAGE_ID");
        $table->addColumn(t('URL'), "PRODUCT_PAGE_URL", "90", "left", "", "tblheader", "PRODUCT_PAGE_URL");
        $table->addColumn(t('PRODUIT_ASSOCIE'), "PRODUCT_LABEL", "90", "left", "", "tblheader", "PRODUCT_LABEL");
        $table->addColumn(t('PRODUIT_SCORE'), "PRODUCT_PAGE_SCORE", "90", "left", "", "tblheader", "PRODUCT_PAGE_SCORE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "PRODUCT_PAGE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "PRODUCT_PAGE_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {	
		$oConnection = Pelican_Db::getInstance();
        parent::editAction();

        $aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
                        *,
                        PRODUCT_LABEL
                    FROM 
                        #pref#_perso_product_page
                    INNER JOIN
                        #pref#_perso_product
                        ON (#pref#_perso_product_page.PRODUCT_ID = #pref#_perso_product.PRODUCT_ID)
                    WHERE 
                        #pref#_perso_product_page.SITE_ID = :SITE_ID
                    ORDER BY " . $this->listOrder;
        $aScores = $oConnection->queryTab($sqlList,$aBind);
        $form = $this->startStandardForm();
        if($this->values['PRODUCT_PAGE_AJAX'] == 1){
            $this->values['PRODUCT_PAGE_URL_AJAX'] = $this->values['PRODUCT_PAGE_URL'];
            $this->values['PRODUCT_PAGE_URL'] = '';
        }
        $form .= $this->oForm->createInput("PRODUCT_PAGE_URL", t('URL'), 255, "internallink", false, $this->values['PRODUCT_PAGE_URL'], $this->readO, 75);
		$aVehicules = array();
        /* Initialisation du tableau de Bind */
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					PRODUCT_ID,
					PRODUCT_LABEL
				FROM
					#pref#_perso_product
				WHERE
					SITE_ID = :SITE_ID";
        $aResults = $oConnection->queryTab($sSQL, $aBind);

        if( is_array($aResults ) && count($aResults ) > 0 ){
            foreach($aResults as $aOneResult){
                $aVehicules[$aOneResult['PRODUCT_ID']] = "({$aOneResult['PRODUCT_ID']}) {$aOneResult['PRODUCT_LABEL']}";
            }
        }
        $form .= $this->oForm->createComboFromList("PRODUCT_ID", t("PRODUIT_ASSOCIE"), $aVehicules, $this->values['PRODUCT_ID'], false, $this->readO);
		
                foreach(Pelican::$config['PERSO']['AJAX_LIST'] as $k=>$v){
                   $aAjax[$k]=$v['lib'];
                }
		
		
		$form .= $this->oForm->createComboFromList("PRODUCT_PAGE_URL_AJAX", t("AJAX"), $aAjax, $this->values['PRODUCT_PAGE_URL_AJAX'], false, $this->readO);
		$form .= $this->oForm->createInput("PRODUCT_PAGE_SCORE", t('SCORE'), 4, "", true, $this->values['PRODUCT_PAGE_SCORE'], $this->readO, 4);
		  $form .= $this->oForm->createJS("
            var aScores = ".json_encode($aScores).";
            var PPID = $('#PRODUCT_PAGE_ID').val()
            var existe = false;
            
            var score = $('#PRODUCT_PAGE_SCORE').val();
            var scoreCleaned = score.replace(',','.');
            if(scoreCleaned > 1 || scoreCleaned == 0 || scoreCleaned < 0 || isNaN(scoreCleaned)){
                alert('" . t("ALERT_SCORE_PERSO", "js") . "');
                return false;
            }
            if($('#PRODUCT_PAGE_URL_AJAX').val() == '' && $('#PRODUCT_PAGE_URL').val() == ''){
                alert('" . t("ALERT_URL_PERSO", "js") . "');
                return false;
            }

            aScores.forEach(function(oneScore) {
            if(!existe)
              { 
                if(oneScore['PRODUCT_PAGE_ID'] != PPID && oneScore['PRODUCT_ID'] == $('#PRODUCT_ID').val())
                  {
                    if(oneScore['PRODUCT_PAGE_URL'] == $('#PRODUCT_PAGE_URL').val() || (oneScore['PRODUCT_PAGE_AJAX'] == 1 && oneScore['PRODUCT_PAGE_URL'] == $('#PRODUCT_PAGE_URL_AJAX').val()))
                    {   
                       existe = true;
                       alert('" . t("SCORE_DOUBLON", "js") . "');
                    }
                  }
              }
            });

            if(existe)
              {
                return false;
              }

            
        ");
		$form .= $this->oForm->createHidden('PRODUCT_PAGE_DATE_MAJ', date("Y-m-d"));
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        Pelican_Db::$values['PRODUCT_PAGE_AJAX'] = 0;
		if(Pelican_Db::$values['PRODUCT_PAGE_URL_AJAX'] != ""){
			Pelican_Db::$values['PRODUCT_PAGE_URL'] = Pelican_Db::$values['PRODUCT_PAGE_URL_AJAX'];
			Pelican_Db::$values['PRODUCT_PAGE_AJAX'] = 1;
		}
		Pelican_Db::$values['PRODUCT_PAGE_SCORE'] = str_replace(",",".",Pelican_Db::$values['PRODUCT_PAGE_SCORE']);
        parent::saveAction();
        Pelican_Cache::clean('Frontend/Citroen/Perso/ProductPage', array($_SESSION[APP]['SITE_ID'],Pelican_Db::$values['PRODUCT_PAGE_URL']));
        Pelican_Cache::clean('Frontend/Citroen/Perso/ProductPageTrack', array($_SESSION[APP]['SITE_ID'],Pelican_Db::$values['PRODUCT_PAGE_URL']));
    }

}