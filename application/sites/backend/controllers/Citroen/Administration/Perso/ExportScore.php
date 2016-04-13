<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_ExportScore_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_product_page";
    protected $field_id = "PRODUCT_PAGE_ID";
    protected $defaultOrder = "PRODUCT_PAGE_ID";

    protected function setListModel()
    {	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT ppp.PRODUCT_PAGE_URL,
						pp.PRODUCT_LABEL,
						ppp.PRODUCT_PAGE_SCORE,						
						ppp.PRODUCT_PAGE_DATE_MAJ
					FROM 
						#pref#_perso_product_page ppp
					INNER JOIN
						#pref#_perso_product pp
						ON (ppp.PRODUCT_ID = pp.PRODUCT_ID)
					WHERE 
						ppp.SITE_ID = :SITE_ID ";
            if ($_GET['filter_PRODUIT_ASSOCIE'] != '') {
				$sqlList.= " AND ppp.PRODUCT_ID = '" . $_GET['filter_PRODUIT_ASSOCIE'] . "'";
            }
            if ($_GET['filter_URL'] != '') {
				$sqlList.= " AND (
					ppp.PRODUCT_PAGE_URL like '%" . $_GET['filter_URL'] . "%' 
					OR pp.PRODUCT_LABEL like '%" . $_GET['filter_URL'] . "%' 
				)
				";
            }			
		$sqlList.= "ORDER BY " . $this->listOrder;

		$results	=	$oConnection->queryTab($sqlList,$aBind);			
		
		$resultsList = array();
		if(is_array($results)){
			foreach($results as $result){			
				if(isset(Pelican::$config['PERSO']['AJAX_LIST'][$result['PRODUCT_PAGE_URL']])){
					$result['PRODUCT_PAGE_URL']	=	Pelican::$config['PERSO']['AJAX_LIST'][$result['PRODUCT_PAGE_URL']]['lib'];
				}
				if($result['PRODUCT_PAGE_DATE_MAJ'] == '0000-00-00'){
					$result['PRODUCT_PAGE_DATE_MAJ'] = '';				
				}				
				$resultsList[] = $result;
			}					
		}
		$_SESSION['datasScore'] = $resultsList;
        $this->listModel = $resultsList;
    }

    public function listAction()
    {
        parent::listAction();
		$this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $form .= '<form name="formExport" id="formExport" action="/_/Citroen_Administration_Perso_ExportScore/exportScore" method="post">
					<input name="submitExport" type="submit" class="button" value="' . t("EXPORT_SCORE") . '"/>
					<br /><br />			
				</form>';		
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
		$table->navLimitRows = 40; 		
		$table->setFilterField('PRODUIT_ASSOCIE', t("PRODUIT_ASSOCIE"), array(), $this->produitAssocie(), array(), "1", false);
		$table->setFilterField('URL', "<b>" . t('URL') . " :</b>", "");
        $table->getFilter(2);
        $table->setValues($this->getListModel(), "PRODUCT_PAGE_ID");		
        $table->addColumn(t('URL'), "PRODUCT_PAGE_URL", "90", "left", "", "tblheader", "PRODUCT_PAGE_URL");
        $table->addColumn(t('PRODUIT_ASSOCIE'), "PRODUCT_LABEL", "90", "center", "", "tblheader", "PRODUCT_LABEL");
        $table->addColumn(t('PRODUIT_SCORE'), "PRODUCT_PAGE_SCORE", "90", "center", "", "tblheader", "PRODUCT_PAGE_SCORE");
		$table->addColumn(t('DATE_MAJ'), "PRODUCT_PAGE_DATE_MAJ", "90", "center", "", "tblheader", "PRODUCT_PAGE_DATE_MAJ");
        $this->setResponse($form . $table->getTable());
    }
	
	public function produitAssocie()
	{
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
						PRODUCT_ID as id, PRODUCT_LABEL as lib
					FROM 
						#pref#_perso_product
					WHERE 
						SITE_ID = :SITE_ID 
						ORDER BY PRODUCT_LABEL";

		return	$oConnection->queryTab($sqlList,$aBind);		
	}
	
	public function exportScoreAction()
	{	    
		header("Content-type: text/csv");
		$filename = 'Export_score_'. date('Y-m-d');
		header("Content-Disposition: attachment; filename={$filename}.csv");
		header("Pragma: no-cache");
		header("Expires: 0");		
		if(is_array($_SESSION['datasScore'])){
			$this->outputCSV($_SESSION['datasScore']);
		}
	}
	
	public function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
		$entete[] = t('URL');
		$entete[] = utf8_decode(t('PRODUIT_ASSOCIE'));
		$entete[] = t('PRODUIT_SCORE');
		$entete[] = t('DATE_MAJ');
		fputcsv($outputBuffer, $entete, ';');				
        foreach($data as $val) {
			$val['PRODUCT_PAGE_URL'] = utf8_decode($val['PRODUCT_PAGE_URL']);
			$val['PRODUCT_LABEL'] = utf8_decode($val['PRODUCT_LABEL']);
            fputcsv($outputBuffer, $val, ';');
        }
		
        fclose($outputBuffer);
    }
}