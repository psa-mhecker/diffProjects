<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_Produit_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_product";
    protected $field_id = "PRODUCT_ID";
    protected $defaultOrder = "PRODUCT_ID";
    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/Lcdv6ByProduct', 
            array('SITE_ID') 
        ),
        array('Frontend/Citroen/Perso/ProductMedia',
            array('SITE_ID')
        )
    );

    protected function setListModel()
    {	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
						* 
					FROM 
						#pref#_perso_product
					WHERE 
						SITE_ID = :SITE_ID ";
  if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            PRODUCT_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
            }
					$sqlList.= "ORDER BY " . $this->listOrder;
        $this->listModel = $oConnection->queryTab($sqlList,$aBind);
    }

    protected function setEditModel()
    {
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
								* 
							FROM 
								#pref#_perso_product 
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
        $table->setValues($this->getListModel(), "PRODUCT_ID");
        //$table->addColumn(t('ID'), "PRODUCT_ID", "10", "left", "", "tblheader", "PRODUCT_ID");
        $table->addColumn(t('LIBELLE_PRODUCT'), "PRODUCT_LABEL", "90", "left", "", "tblheader", "PRODUCT_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "PRODUCT_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "PRODUCT_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {	
		$oConnection = Pelican_Db::getInstance();
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createInput("PRODUCT_LABEL", t('LIBELLE_PRODUCT'), 255, "", true, $this->values['PRODUCT_LABEL'], $this->readO, 75);
		$aVehicules = array();
        /* Initialisation du tableau de Bind */
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					VEHICULE_ID,
					VEHICULE_LABEL,
					CASE WHEN (VEHICULE_GAMME_CONFIG <> '' OR  VEHICULE_GAMME_CONFIG IS NOT NULL)
						THEN VEHICULE_GAMME_CONFIG
						ELSE VEHICULE_GAMME_MANUAL
					END GAMME
				FROM
					#pref#_vehicule
				WHERE
					SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID";
        $aResults = $oConnection->queryTab($sSQL, $aBind);

        if( is_array($aResults ) && count($aResults ) > 0 ){
            foreach($aResults as $aOneResult){
                $aVehicules[$aOneResult['VEHICULE_ID']] = "({$aOneResult['GAMME']}) {$aOneResult['VEHICULE_LABEL']}";
            }
        }
        $form .= $this->oForm->createComboFromList("VEHICULE_ID", t("VEHICULE_ASSOCIE"), $aVehicules, $this->values['VEHICULE_ID'], false, $this->readO);

        $productZones = Pelican::$config['PERSO_MEDIA_ZONE'];
        if(is_array($productZones) && count($productZones)>0){
            $results = array();
            if($this->values["PRODUCT_ID"] != '-2'){
                $oConnection = Pelican_Db::getInstance();
                $aBind[":PRODUCT_ID"] = $this->values["PRODUCT_ID"];
                $sSQL = "
                    SELECT
                        *
                    FROM
                        #pref#_perso_product_media
                    WHERE
                        PRODUCT_ID = :PRODUCT_ID
                    ORDER BY ORDER_MEDIA
                ";
                $res = $oConnection->queryTab($sSQL,$aBind);
                if(is_array($res) && count($res)>0){
                    foreach($res as $key=>$res){
                        $results[$res['ORDER_MEDIA']] = $res;
                    }
                }
            }
            foreach($productZones as $key=>$productZone){
                $form .= $this->oForm->createHidden("PRODUCT_MEDIA_TYPE".$key, $productZone['type']);
                $form .= $this->oForm->createMedia("MEDIA_ID".$key, $productZone['label'], true, "image", "", $results[$key]['MEDIA_ID'], $this->readO, true, false,  $productZone['format']);
            }
        }
		$form .= $this->oForm->createHidden('PRODUCT_DATE_MAJ', date("Y-m-d"));
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function saveAction()
    {
  //  	var_dump(Pelican_Db::$values);
//    	die;
        parent::saveAction();
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "DELETE FROM #pref#_perso_product_media WHERE PRODUCT_ID = :PRODUCT_ID";
        $oConnection->query($sSQL, array(':PRODUCT_ID'=>Pelican_Db::$values['PRODUCT_ID']));
        $productZones = Pelican::$config['PERSO_MEDIA_ZONE'];
        if(is_array($productZones) && count($productZones)>0 && Pelican_Db::$values['form_action']!='DEL'){
            for($i=1;$i<=count($productZones);$i++){
                Pelican_Db::$values['MEDIA_ID'] = Pelican_Db::$values['MEDIA_ID'.$i];
                Pelican_Db::$values['PRODUCT_MEDIA_TYPE'] = Pelican_Db::$values['PRODUCT_MEDIA_TYPE'.$i];
                Pelican_Db::$values['ORDER_MEDIA'] = $i;
                $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_perso_product_media");
            }
        }
    }

}