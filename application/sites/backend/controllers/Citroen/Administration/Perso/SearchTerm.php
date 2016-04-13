<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_SearchTerm_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_product_term";
    protected $field_id = "PRODUCT_TERM_ID";
    protected $defaultOrder = "PRODUCT_TERM_ID";

    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/SearchTermPro',
            array('SITE_ID')
        )
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/Perso/SearchTermPro',
            array('SITE_ID')
        )
    );

    protected function setListModel()
    {   
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $sqlList = "SELECT 
                        *,
                        CASE PRODUCT_TERM_PRO
                        WHEN 1 THEN '".t('OUI')."'
                        ELSE '".t('NON')."' END TYPE_PRO,
                        PRODUCT_LABEL
                    FROM 
                        #pref#_perso_product_term
                    LEFT JOIN
                        #pref#_perso_product
                        ON (#pref#_perso_product_term.PRODUCT_ID = #pref#_perso_product.PRODUCT_ID)
                    WHERE 
                        #pref#_perso_product_term.SITE_ID = :SITE_ID ";

                    if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            PRODUCT_TERM_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            OR PRODUCT_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
            }

        $sqlList.= "   ORDER BY " . $this->listOrder;
        $this->listModel = $oConnection->queryTab($sqlList,$aBind);
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
                                * 
                            FROM 
                                #pref#_perso_product_term 
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

        $table->setValues($this->getListModel(), "PRODUCT_TERM_ID");
        //$table->addColumn(t('ID'), "PRODUCT_TERM_ID", "10", "left", "", "tblheader", "PRODUCT_TERM_ID");
        $table->addColumn(t('TERME_RECHERCHE'), "PRODUCT_TERM_LABEL", "90", "left", "", "tblheader", "PRODUCT_TERM_LABEL");
        $table->addColumn(t('PRODUIT_ASSOCIE'), "PRODUCT_LABEL", "10", "left", "", "tblheader", "PRODUCT_LABEL");
        $table->addColumn(t('PRO_RECHERCHE'), "TYPE_PRO", "90", "left", "", "tblheader", "TYPE_PRO");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "PRODUCT_TERM_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "PRODUCT_TERM_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {   
        $oConnection = Pelican_Db::getInstance();
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createInput("PRODUCT_TERM_LABEL", t('TERME_RECHERCHE'), 255, "", true, $this->values['PRODUCT_TERM_LABEL'], $this->readO, 75);
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
        $form .= $this->oForm->createCheckBoxFromList("PRODUCT_TERM_PRO", t('PRO_RECHERCHE'), array(1 => ""), $this->values['PRODUCT_TERM_PRO'], false, $this->readO);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        parent::saveAction();
    }

}