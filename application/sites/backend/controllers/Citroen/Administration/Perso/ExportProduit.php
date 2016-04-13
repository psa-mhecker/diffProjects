<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_ExportProduit_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_product";
    protected $field_id = "PRODUCT_ID";
    protected $defaultOrder = "PRODUCT_ID";

    protected function setListModel()
    {	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
						pp.PRODUCT_LABEL,
						v.VEHICULE_LABEL,
						CASE WHEN (VEHICULE_GAMME_CONFIG <> '' OR  VEHICULE_GAMME_CONFIG IS NOT NULL)
							THEN VEHICULE_GAMME_CONFIG
							ELSE VEHICULE_GAMME_MANUAL
						END GAMME,					
						pp.PRODUCT_DATE_MAJ
					FROM 
						#pref#_perso_product pp
					INNER JOIN
						#pref#_vehicule v
					ON pp.VEHICULE_ID = v.VEHICULE_ID
					WHERE 
						pp.SITE_ID = :SITE_ID ";
		if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " 
				AND (pp.PRODUCT_LABEL like '%" . $_GET['filter_search_keyword'] . "%'
				OR v.VEHICULE_LABEL like '%" . $_GET['filter_search_keyword'] . "%')";
		}
		$sqlList.= "ORDER BY " . $this->listOrder;
		$results	=	$oConnection->queryTab($sqlList,$aBind);			
		
		$resultsList = array();
		if(is_array($results)){
			foreach($results as $result){			
				if($result['PRODUCT_DATE_MAJ'] == '0000-00-00'){
					$result['PRODUCT_DATE_MAJ'] = '';				
				}
				$result['VEHICULE_LABEL'] = '(' . $result['GAMME'] . ') - ' . $result['VEHICULE_LABEL'];
				$resultsList[] = $result;
			}					
		}
		$_SESSION['datasProduit'] = $resultsList;
        $this->listModel = $resultsList;		
    }

    public function listAction()
    {
        parent::listAction();
		$this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $form .= '<form name="formExport" id="formExport" action="/_/Citroen_Administration_Perso_ExportProduit/exportProduit" method="post">
					<input name="submitExport" type="submit" class="button" value="' . t("EXPORT_PRODUIT") . '"/>
					<br /><br />			
				</form>';		
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
		$table->navLimitRows = 40; 	
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "PRODUCT_ID");
        $table->addColumn(t('LIBELLE_PRODUCT'), "PRODUCT_LABEL", "90", "left", "", "tblheader", "PRODUCT_LABEL");        
		$table->addColumn(t('VEHICULE_ASSOCIE'), "VEHICULE_LABEL", "90", "left", "", "tblheader", "VEHICULE_LABEL");
		$table->addColumn(t('DATE_MAJ'), "PRODUCT_DATE_MAJ", "90", "center", "", "tblheader", "PRODUCT_DATE_MAJ");
        $this->setResponse($form . $table->getTable());
    }
	
	public function exportProduitAction()
	{
	    $filename = 'Export_produit_'. date('Y-m-d');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		if(is_array($_SESSION['datasProduit'])){
			$this->outputCSV($_SESSION['datasProduit']);
		}
	}
	
	public function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
		$entete[] = utf8_decode(t('PRODUIT'));
		$entete[] = utf8_decode(t('VEHICULE_ASSOCIE'));
		$entete[] = t('DATE_MAJ');
		
		fputcsv($outputBuffer, $entete, ';');				
        foreach($data as $val) {
			$val['PRODUCT_LABEL'] = utf8_decode($val['PRODUCT_LABEL']);
			$val['VEHICULE_LABEL'] = utf8_decode($val['VEHICULE_LABEL']);
			unset($val['GAMME']);
            fputcsv($outputBuffer, $val, ';');
        }
        fclose($outputBuffer);
    }	
}