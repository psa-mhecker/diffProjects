<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
require_once (Pelican::$config["PLUGIN_ROOT"].'/boforms/library/FunctionsUtils.php');

/**
 * Génération des services PDV
 *
 */
class Ndp_DealerLocServicePdv_Controller extends Ndp_Controller
{

    protected $administration = true;
    protected $multiLangue = true;
    protected $form_name = "pdv_service";
    protected $defaultOrder = "PDV_SERVICE_ORDER";
    protected $field_id = "PDV_SERVICE_ID";
    protected $wsBusinessList = array();

    public function indexAction()
    {
        parent::indexAction();
        if ($_POST['id'] > 0) {
            $this->_forward('edit');
        } elseif ($_POST['form_action'] == 'saveServicesActif') {
             $this->saveServicesActifsAction();
        }
    }

    protected function saveServicesActifsAction()
    {
        $connection = Pelican_Db::getInstance();

        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $sql = "update #pref#_pdv_service
                set PDV_SERVICE_ACTIF = 0
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID";
        $connection->query($sql, $bind);

        $serviceIds = array();

        if (is_array(Pelican_Db::$values['service'])) {
            foreach (Pelican_Db::$values['service'] as $key => $value) {
                $serviceIds[] = $value['PDV_SERVICE_ACTIF'];
            }
        }


        if (!empty($serviceIds)) {
            $serviceIdsList = implode($serviceIds, "', '");
            $sql = "update #pref#_pdv_service
                set PDV_SERVICE_ACTIF = 1
                WHERE SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID
                AND PDV_SERVICE_ID IN ( '" .$serviceIdsList."')";

            $connection->query($sql, $bind);
        }
    }

    public function listAction()
    {
        $form = '';
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        // appel du WS Annuaire
        try {
           $this->wsBusinessList = $this->getStoreServices();
           $this->saveNewStoreServices();
        } catch(\Exception $e) {
            //@todo alert use and add log
            $form .= $this->oForm->createDescription(t('NDP_ERROR_WS_BO_ANNUAIRE_PDV'));
        }
        // insertion en bdd

        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "service", "", 0, 0, 0, "liste");
        $table->navLimitRows = 50;
        $table->setTableOrder("#pref#_pdv_service", "PDV_SERVICE_ID", "PDV_SERVICE_ORDER", "", "SITE_ID = ".$_SESSION[APP]['SITE_ID']." AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']);
        $table->setValues($this->getListModel(), "");
        $table->addImage(t('PICTO'), array("_width_" => 50, "_folder_" => "", "_extension_" => ""), "MEDIA_PATH");
        $table->addColumn(t('CODE'), "PDV_SERVICE_CODE", "5", "center", "", "tblheader");
        $table->addColumn(t('LABEL'), "PDV_SERVICE_LABEL", "5", "center", "", "tblheader");
        $table->addColumn(t('NDP_LABEL_CUSTOMISED'), "PDV_SERVICE_LABEL_PERSO", "5", "center", "", "tblheader");
        $table->addColumn(t('TYPE'), "PDV_SERVICE_TYPE", "5", "center", "", "tblheader");
        $table->addInput('PDV_SERVICE_ACTIF', "checkbox", array("_value_field_" => 'PDV_SERVICE_ID', "_javascript_" => ""), "center", "", "tblheader", 0, 1, 1, t("ACTIF"));
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => 'PDV_SERVICE_ID', "_javascript_" => "editRow"), "center");

        $this->getListModel();

        parent::editAction();

        $form .= $this->oForm->open('/_/Index/child?tid='.$this->tid);

        $this->form_action = 'saveServicesActif';
        $form .= $this->oForm->createHidden('id');
        $form .= $this->beginForm($this->oForm);
        $form .= $table->getTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $javascript = "<script type='text/javascript'>
             function editRow(serviceId) {
                document.forms['fForm'].elements['id'].value = serviceId;
            }
            </script>";
        $form .= $javascript;

        $this->aButton["back"] = "";
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $this->setResponse($form);
    }

    protected function getStoreServices()
    {
        $service = $this->getContainer()->get('annuaire_pdv');

        $pays = FunctionsUtils::getCodePays();
        $locale = Pelican_Translate::getLang()."-".$pays;

        $serviceParams = array(
            'country' => $pays,
            'culture' => $locale,
            'consumer' => 'DSW.DEALER.TEST',
            'brand' => 'AP',
            'ViewActivities'=>true,
            'ViewLicences'=>true,
            'ViewIndicators'=>true,
            'ViewServices'=>false
        );
        $businessList = [];

        $infos = $service->getBusinessList($serviceParams);

        if ($infos) {
            $businessList = $infos['BusinessList'];
        }

        return $businessList;
    }

    protected function saveNewStoreServices()
    {
        $codes = [];
        if (!empty($this->wsBusinessList)) {
            $order = 1;
            foreach ($this->wsBusinessList as $businessService) {
                $businessService['order'] = $order;
                $codes[] = $this->addOrUpdateService($businessService);
                $order++;
            }
        }
        $this->deleteOldService($codes);
    }

    protected function addOrUpdateService($businessService)
    {
        $connection = Pelican_Db::getInstance();

        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[":CODE_SERVICE"] = $businessService['Code'];
        $bind[":LABEL_SERVICE"] = addslashes($businessService['Label']);
        $bind[":TYPE_SERVICE"] = $businessService['Type'];
        $bind[":PDV_SERVICE_ORDER"] = $businessService['order'];
        $bind[":PDV_SERVICE_ACTIF"] = 1;

        $sql = "INSERT INTO #pref#_pdv_service (
                        SITE_ID,
                        LANGUE_ID,
                        PDV_SERVICE_CODE,
                        PDV_SERVICE_LABEL,
                        PDV_SERVICE_TYPE,
                        PDV_SERVICE_ORDER,
                        PDV_SERVICE_ACTIF
                        )
                        VALUES(
                        :SITE_ID,
                        :LANGUE_ID,
                        ':CODE_SERVICE',
                        ':LABEL_SERVICE',
                        ':TYPE_SERVICE',
                        ':PDV_SERVICE_ORDER',
                        ':PDV_SERVICE_ACTIF'
                        )
                ON DUPLICATE KEY UPDATE PDV_SERVICE_LABEL = ':LABEL_SERVICE' , PDV_SERVICE_TYPE = ':TYPE_SERVICE'";

        $connection->query($sql, $bind);

        return $businessService['Code'];
    }

    protected function deleteOldService($codes)
    {
        $connection = Pelican_Db::getInstance();

        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $sql = 'DELETE FROM #pref#_pdv_service
              WHERE
              SITE_ID = :SITE_ID
              AND LANGUE_ID = :LANGUE_ID';

        if (!empty($codes)) {
            $sql .= ' AND PDV_SERVICE_CODE NOT IN ( "'.implode('","', $codes).'" ) ';
        }
        $connection->query($sql, $bind);

    }

    public function editAction()
    {
        $this->isHmvcContext = true;
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('PDV_SERVICE_ORDER', $this->values['PDV_SERVICE_ORDER']);
        $form .= $this->oForm->createLabel('ID', $this->values['PDV_SERVICE_ID']);
        $form .= $this->oForm->createInput('PDV_SERVICE_CODE', t('CODE'), 10, '', false, $this->values['PDV_SERVICE_CODE'], 1, 10);
        $form .= $this->oForm->createInput('PDV_SERVICE_LABEL', t('LABEL'), 10, '', false, $this->values['PDV_SERVICE_LABEL'], 1, 10);
        $form .= $this->oForm->createInput('PDV_SERVICE_TYPE', t('TYPE'), 10, '', false, $this->values['PDV_SERVICE_TYPE'], 1, 10);
        $form .= $this->oForm->createMedia('MEDIA_ID', t('PICTO'), false, 'image', '', $this->values['MEDIA_ID'], $this->readO);
        $form .= $this->oForm->createCheckBoxFromList('PDV_SERVICE_ACTIF', t('NDP_ACTIVATE_FILTER'), array(1 => ''), $this->values['PDV_SERVICE_ACTIF'], false, $this->readO);

        $form .= $this->oForm->createInput("PDV_SERVICE_LABEL_PERSO", t('NDP_LABEL_CUSTOMISED'), 255, "", false, $this->values['PDV_SERVICE_LABEL_PERSO'], $this->readO, 75);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);

    }

    protected function setListModel()
    {
        $sSQL = '
            SELECT
                PDV_SERVICE_ID,
                PDV_SERVICE_CODE,
                PDV_SERVICE_LABEL,
                PDV_SERVICE_LABEL_PERSO,
                PDV_SERVICE_TYPE,
                PDV_SERVICE_ORDER,
                CONCAT("http://'.Pelican::$config["HTTP_MEDIA"].'", MEDIA_PATH) as MEDIA_PATH,
                PDV_SERVICE_ACTIF,
                media.MEDIA_ID
            FROM
                #pref#_pdv_service as serv LEFT JOIN #pref#_media as media on serv.MEDIA_ID = media.MEDIA_ID
            WHERE
                SITE_ID = '.$_SESSION[APP]['SITE_ID'].'
            AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
            ORDER BY PDV_SERVICE_ORDER
        ';

        $this->listModel = $sSQL;
    }

    protected function setEditModel()
    {
         $this->editModel = '
            SELECT *
            FROM #pref#_pdv_service
            WHERE
                SITE_ID = '.$_SESSION[APP]['SITE_ID'].'
                AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
                AND PDV_SERVICE_ID = '.$this->id;
    }

}
