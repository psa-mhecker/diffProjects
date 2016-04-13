<?php
/**
 * Définition de l'encyclopedie des urls de redirections 301.
 * 
 * @package Citroen
 * @subpackage Administration
 * @author David Moaté <david.moate@businessdecision.com>
 * @since 12/02/2015
 */

use Citroen\Accessoires;

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_UrlEncyclopedique_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "url_encyclopedique"; // Table utilisée pour stocker la liste des caches
    protected $field_id = "URL_ENCYCLOPEDIQUE_ID";// Identifiant d'un élément de la liste
    protected $decacheBack = array(
        array('Frontend/Citroen/UrlEncyclopedique')
    );
    protected $defaultOrder = "URL_ENCYCLOPEDIQUE_ID";
    
    /** Sélection des données de la liste (listAction) */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $sql = '';
        // Récupération de la liste des accessoires
        $sql = "SELECT URL_ENCYCLOPEDIQUE_ID, URL_ENCYCLOPEDIQUE_SOURCE, URL_ENCYCLOPEDIQUE_DESTINATION
            FROM #pref#_url_encyclopedique
            WHERE SITE_ID = :SITE_ID ";
        
        if(!empty($_GET['URL_ENCYCLOPEDIQUE_SOURCE'])){
             $sql .= " AND URL_ENCYCLOPEDIQUE_SOURCE like '%" . $_GET['URL_ENCYCLOPEDIQUE_SOURCE'] . "%'";
             $bind[':URL_ENCYCLOPEDIQUE_SOURCE'] = $_GET['URL_ENCYCLOPEDIQUE_SOURCE'];
        }
        
        if(!empty($_GET['URL_ENCYCLOPEDIQUE_DESTINATION'])){
             $sql .= " AND URL_ENCYCLOPEDIQUE_DESTINATION like '%" . $_GET['URL_ENCYCLOPEDIQUE_DESTINATION'] . "%'";
             $bind[':URL_ENCYCLOPEDIQUE_DESTINATION'] = $_GET['URL_ENCYCLOPEDIQUE_DESTINATION'];
        }
        $sql.= " ORDER BY " . $this->listOrder;
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $listModel = $oConnection->queryTab($sql, $bind);        
        $this->listModel = $listModel;
        $_SESSION['dataExport'] = $listModel;
    }
    
    /** Sélection des données de l'élément a éditer (formulaire editAction) */
    protected function setEditModel()
    {
    }

    /** Tableau de liste des éléments à éditer */
    public function listAction()
    {
        parent::listAction();

        
        // Masquage bouton ajouter (il n'a aucun sens ici puisque les univers viennent d'un webservice, en lecture seule)
        $this->aButton["add"] = null;
        Backoffice_Button_Helper::init($this->aButton);
        
        $form = '</table><div id="globalImport" style="width:100%"><div id="exportFo" style="float:left;width:50%;">';
        $form .= '<span style="font-weight:bold;">' . Pelican_Html::b($sLibExportTrad) . '</span>' . Pelican_Html::br() . Pelican_Html::br();
        $form .= '<form name="formExport" id="formExport" action="/_/Citroen_Administration_UrlEncyclopedique/export" method="post">
                    <input name="submitExport" type="submit" class="button" value="' . t("EXPORT") . '"/>
                    <br /><br />			
                </form>'; 
        $form .= '</div>
		<div id="importFo" style="float:left;width:50%;">';
        $form .= '<form name="fFormImport" id="fFormImport" action="/_/Citroen_Administration_UrlEncyclopedique/import" method="post"  enctype="multipart/form-data">';
        $form .= '<input type="hidden" name="MAX_FILE_SIZE" value="2097152"> ';
        $form .= '<input type="hidden" name="tc" value="' . $this->getParam('tc') . '"> ';
        $form .= '<input type="file" name="FILE_IMPORT" id="FILE_IMPORT" size="40" />';
        $form .= Pelican_Html::br();
        $form .= Pelican_Html::br() . '<input name="submitUpload" type="submit" class="button" value="' . t('IMPORTER') . '"/>';
        $style = 'blue';
        if(!empty($_GET['status'])){
            $style = 'red';
        }
        if(!empty($_GET['msg'])){
            $form .= Pelican_Html::br() . '<p  style ="color:' . $style . '">' . $_GET['msg'] . '</p>';
        }        
        $form .= '</form>';
        $form .= Pelican_Html::br();
        $form .= '</div></div>';    
        
        // Initialisation de la liste
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");  
        
        $table->setFilterField("URL_ENCYCLOPEDIQUE_SOURCE", "<b>" . t('URL_ENCYCLOPEDIQUE_SOURCE') . " :</b>", "");
        $table->setFilterField("URL_ENCYCLOPEDIQUE_DESTINATION", "<b>" . t('URL_ENCYCLOPEDIQUE_DESTINATION') . " :</b>", "");
        $table->getFilter(2);
        
        // Tri des données de la liste
        $listModel = $this->getListModel();
        
        // Chargement des données dans la liste
        $table->setValues($listModel, 'URL_ENCYCLOPEDIQUE_ID');
        $table->addColumn(t('URL_ENCYCLOPEDIQUE_SOURCE'), "URL_ENCYCLOPEDIQUE_SOURCE", "45", "center", "", "tblheader", "URL_ENCYCLOPEDIQUE_SOURCE");
        $table->addColumn(t('URL_ENCYCLOPEDIQUE_DESTINATION'), "URL_ENCYCLOPEDIQUE_DESTINATION", "45", "center", "", "tblheader", "URL_ENCYCLOPEDIQUE_DESTINATION");
        
        // Affichage de la liste
        $this->setResponse($form . $table->getTable());
    }
    
    public function exportAction()
    {
        $filename = 'Export_url_Encyclopedique_'. date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        if(is_array($_SESSION['dataExport'])){
            $this->outputCSV($_SESSION['dataExport']);
        }
    }
    
    public function importAction()
    {
        $bCSV = Backoffice_File_Helper::isCSV($_FILES['FILE_IMPORT']['type']);
        $error = $this->isFormatNameValid($_FILES['FILE_IMPORT']['name']);
        if ($bCSV == true && $_FILES['FILE_IMPORT']['error'] == UPLOAD_ERR_OK && false === $error['STATUS']) {
            $sCheminDestination = Pelican::$config["DOCUMENT_INIT"] . '/var/urlEncyclopedique/';
            if (isset($_FILES['FILE_IMPORT']['tmp_name']) && $_FILES['FILE_IMPORT']['error'] == UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES['FILE_IMPORT']['tmp_name'], $sCheminDestination . $_FILES['FILE_IMPORT']['name']);
            }
            $filename = $sCheminDestination . $_FILES['FILE_IMPORT']['name'];
            $dataCsv = file($filename);
            
            // Suppresion des entetes du fichier
            unset($dataCsv[0]);
            if(is_array($dataCsv)){
                $oConnection = Pelican_Db::getInstance();
                $this->cleanUrlEncyclopediqueBySiteId();
                foreach($dataCsv as $lineCsv){
                    if (strpos($lineCsv, ';') !== false){
                        $lineCsv = explode(";", $lineCsv);
                    }elseif (strpos($lineCsv, ',') !== false){
                        $lineCsv = explode(",", $lineCsv);
                    }
                    Pelican_Db::$values['URL_ENCYCLOPEDIQUE_SOURCE'] = $lineCsv[0];
                    Pelican_Db::$values['URL_ENCYCLOPEDIQUE_DESTINATION'] = $lineCsv[1];
                    Pelican_Db::$values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                    $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_url_encyclopedique");                
                }
            }
        }
        
        echo '<script type="text/javascript">location.href = "/_/Index/child?tid=' . Pelican::$config['TEMPLATE_ADMIN_ENCYCLOPEDIE_URL'] . '&msg=' . $error['MSG'] . '&status=' . $error['STATUS'] . '&tc=' . $this->getParam('tc') . '"</script>';
    }
	
    public function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        $entete[] = utf8_decode(t('URL_ENCYCLOPEDIQUE_SOURCE'));
        $entete[] = utf8_decode(t('URL_ENCYCLOPEDIQUE_DESTINATION'));		
        fputcsv($outputBuffer, $entete, ';');
        foreach($data as $val) {
            $val['URL_ENCYCLOPEDIQUE_SOURCE'] = utf8_decode(trim ($val['URL_ENCYCLOPEDIQUE_SOURCE']));
            $val['URL_ENCYCLOPEDIQUE_DESTINATION'] = utf8_decode(trim ($val['URL_ENCYCLOPEDIQUE_DESTINATION']));
            unset($val['URL_ENCYCLOPEDIQUE_ID']);
            fputcsv($outputBuffer, $val, ';');
        }
        fclose($outputBuffer);
    }
    
    public function isFormatNameValid($name){
        $error = array();
        if(empty($name)){
            
            return false;
        }
        $pathinfo = pathinfo($name);
        $filename = $pathinfo['filename'];
        if(empty($filename)){
            $error['STATUS'] = true;
            $error['MSG'] = t('INVALID_FILE');
            
            return $error;
        }
        if ($this->isCodePaysExist($filename)) {
            $error['STATUS'] = false;
                $error['MSG'] = t('SUCCESS_IMPORT');
            
            return $error;
        }
        $erreur['STATUS'] = true;
        $erreur['MSG'] = t('KO') . ' ' .t('FORMAT_FILENAME_VALID_IS') . ' ' . $this->getCodePays() . '.csv';

        return $erreur;        
    }
    
    public function isCodePaysExist($codePays){
        $oConnection = Pelican_Db::getInstance();
        $sqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_CODE_PAYS = :SITE_CODE_PAYS AND SITE_ID = :SITE_ID", array(
            ":SITE_CODE_PAYS" => $oConnection->strToBind($codePays),
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        if(empty($sqlCodePays)){
            
            return false;
        }
        
        return true;
    }
    
    public function getCodePays(){
        $oConnection = Pelican_Db::getInstance();
        $sqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        if(empty($sqlCodePays)){
            
            return false;
        }
        
        return $sqlCodePays;
    }
    
    public function cleanUrlEncyclopediqueBySiteId(){
        $oConnection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sql = "
            delete from #pref#_url_encyclopedique
            where SITE_ID = :SITE_ID";
        $oConnection->query($sql, $bind);
    }
}
