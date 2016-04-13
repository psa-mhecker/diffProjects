<?php
/**
 * Fichier de Citroen_ServicePDV: 
 *
 *
 * 
 * @package Citroen
 * @subpackage Administration
 * @author Joseph Franclin <Joseph.Franclin@businessdecision.com>
 * @since 20/08/2014
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');

class Citroen_Administration_ServicePDV_Controller extends Citroen_Controller
{
    
    protected $administration = true; //false  
    protected $multiLangue = true;
    protected $form_name = "ws_services_pdv";  
    protected $defaultOrder = "ORDER_SERVICE";  
    protected $field_id = "CODE_ID";
    protected $decacheBack = array(
        array('Frontend/Citroen/Annuaire/Services', 
            array('SITE_ID', 'LANGUE_ID') 
        )
    );
  

   protected function setListModel()
    {
		$oConnection = Pelican_Db::getInstance ();
		
		$sSqlCodePays = Pelican_Cache::fetch('Citroen/CodePaysWithSiteId', array(
            $_SESSION[APP]['SITE_ID']
		));
		
        $aBusinessList = Pelican_Cache::fetch('Frontend/Citroen/Annuaire/Services', array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
                ));
        
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] =  $_SESSION[APP]['LANGUE_ID'];

         $sSQL = "
            SELECT
                CODE_SERVICE,
                LABEL_SERVICE,
                TYPE_SERVICE,
                ORDER_SERVICE,
                ACTIF_SERVICE,
                CODE_ID
            FROM
                #pref#_ws_services_pdv
            WHERE 
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID  
            ORDER BY " . $this->listOrder . "
        ";
        $aRes = $oConnection->queryTab($sSQL, $aBind);
			
         foreach ($aRes as $keyBu => $valueBu) {
		 if(in_array($valueBu['CODE_SERVICE']."_".$sSqlCodePays, Pelican::$config['CAS_SPECIAL_IMAGE'])){
			$valueBu['CODE_SERVICE'] = $valueBu['CODE_SERVICE']."_".$sSqlCodePays;
		 }
         $aRes[$keyBu]['Picto'] =  '<img src="'.Pelican::$config['MEDIA_HTTP']."/design/frontend/images/picto/services/".$valueBu['CODE_SERVICE'].".png".'" />';
        }

        $this->listModel = $aRes;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");   
        $table->navLimitRows = 50; 
        $table->setTableOrder ( "#pref#_ws_services_pdv", "CODE_ID", "ORDER_SERVICE" , "", "SITE_ID = " . $_SESSION[APP]['SITE_ID']." AND LANGUE_ID = " .$_SESSION[APP]['LANGUE_ID'], array("Frontend/Citroen/Annuaire/ServicesOrder"));
        $table->setValues($this->getListModel(), "");
        $table->addColumn(t('PICTO'), "Picto", "5", "center", "", "tblheader", "");
        $table->addColumn(t('CODE'), "CODE_SERVICE", "5", "center", "", "tblheader", "CODE_SERVICE");
        $table->addColumn(t('LABEL'), "LABEL_SERVICE", "5", "center", "", "tblheader", "LABEL_SERVICE");
        $table->addColumn(t('TYPE'), "TYPE_SERVICE", "5", "center", "", "tblheader", "TYPE_SERVICE");
        $table->addInput(t('ACTIF'), "checkbox", array("_value_field_" => "CODE_SERVICE", "SITE_ID" => $_SESSION[APP]['SITE_ID'], "LANGUE_ID" => $_SESSION[APP]['LANGUE_ID'], "_javascript_" => ""), "center");

        $aServiceList = $this->getListModel();
        $countService = count($aServiceList);
        $aListActive = array();
        if(is_array($aServiceList))
        {
        foreach ($aServiceList as $keyActif => $valueActif) {
            if($valueActif['ACTIF_SERVICE'] == 1)
            {
                $aListActive[] = $valueActif['CODE_SERVICE'];
            }
        }
        }
       


        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->createHidden("NB_PDV_CHECK", "0");

         if($countService > 0 && !empty($aListActive))
        {
            $script = "
            <script type=\"text/javascript\">
            var nb = 0;
                for (i = 1; i < ".$countService." + 1 ; i++) { 
                    if(jQuery.inArray($('#checkbox_'+ i + '_5').val(), ".json_encode($aListActive).")!==-1)
                    {
                       $('#checkbox_'+ i + '_5').prop('checked', true);
                       nb++;
                    }
               }
               $('#NB_PDV_CHECK').val(nb);

               $('.checkbox').change(function() {
                var nbCoche = $('#NB_PDV_CHECK').val();
                if(this.checked) {
                    if(nbCoche == 15)
                    {
                        alert('".t('max_services')."');
                        $(this).prop('checked', false);
                        
                    }
                    else
                    {
                    nbCoche++;
                    $('#NB_PDV_CHECK').val(nbCoche);
                    }
                }
                else
                {
                     if(nbCoche > 0)
                    {
                    nbCoche--;
                    $('#NB_PDV_CHECK').val(nbCoche);
                    }
                }
            });

           </script>";
         }

        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $this->form_action = "save";
        $form .= $this->beginForm($this->oForm);
        $form .= $table->getTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $this->aButton["back"] = "";
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $this->setResponse($form.$script);
    }
  
  
    
    public function saveAction() {

        $oConnection = Pelican_Db::getInstance();
     

        if(!empty(Pelican_Db::$values)) {
            foreach(Pelican_Db::$values as $key => $value) {
                if (strpos($key, 'checkbox') === 0) {
                    Pelican_Db::$values['save_checkbox'][str_replace('checkbox', '', $key)] = $value;
                }
            }
        }

        if (!empty(Pelican_Db::$values['save_checkbox'])) {

                $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];

               $updateServiceActif = "
                update #pref#_ws_services_pdv
                set ACTIF_SERVICE = case
                  when CODE_SERVICE IN ( '" . implode(Pelican_Db::$values['save_checkbox'], "', '") . "') then '1'
                  else 0
                  end
                WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";
                $oConnection->query($updateServiceActif, $aBind);

           
        }
        Pelican_Cache::clean('Frontend/Citroen/Annuaire/ServicesOrder');
    }
}
?>