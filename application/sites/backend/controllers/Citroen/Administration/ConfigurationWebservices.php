<?php

require_once (Pelican::$config['APPLICATION_CONTROLLERS'] . '/Citroen.php');

class Citroen_Administration_ConfigurationWebservices_Controller extends Citroen_Controller {

    protected $administration = true;
    protected $form_name = "site_webservice";

    /* protected function setEditModel()
      {
      debug(__FUNCTION__);
      /* Valeurs Bindées pour la requête */


    /* $this->aBind[':' . $this->field_id] = (int)$this->id;

      /* Requête remontant les données du véhicule sélectionnée pour un pays
     * et une langue donnée.
     */
    /* var_dump($this->field_id);
      $sSqlConfigurationWs = <<<SQL
      SELECT
     *
      FROM
      #pref#_{$this->form_name}

      ORDER BY {$this->listOrder}
      SQL;
      $sSqlConfigurationWs = '';
      $this->editModel = $sSqlConfigurationWs;
      } */

    /* public function indexAction() {
      $this->_forward('edit');
      } */

    public function listAction() {
        parent::_initBack();
        $this->_forward('edit');
    }

    protected function _getWSList() {
        $oConnection = Pelican_Db::getInstance();
        $sSQLWebservice = "select * from #pref#_liste_webservices";
        return $oConnection->queryTab($sSQLWebservice);
    }

    protected function _getSites() {
        $oConnection = Pelican_Db::getInstance();
        $sSQLSite = "select SITE_ID,SITE_LABEL from #pref#_site";
        return $oConnection->queryTab($sSQLSite);
    }

    protected function _getSitesWsConf() {
        $oConnection = Pelican_Db::getInstance();
        $sSQLSite = "select * from #pref#_site_webservice";
        return $oConnection->queryTab($sSQLSite);
    }

    public function editAction() {
        //var_dump(Pelican_Db::$values);
        $parse_url = parse_url($_SERVER["REQUEST_URI"]);
        $this->id = 12;
        parent::editAction();
        $aWebservices = $this->_getWSList();
        $aSites = $this->_getSites();
        $aSitesWsConf = $this->_getSitesWsConf();
        if (isset($aSitesWsConf) && !empty($aSitesWsConf)) {
            $aSitesWsConfIndexed = array();
            foreach ($aSitesWsConf as $aOneSiteWsConf) {
                $aSitesWsConfIndexed[$aOneSiteWsConf['site_id'] . '_' . $aOneSiteWsConf['ws_id']] = $aOneSiteWsConf['status'];
            }
        }

        $this->aButton['add'] = '';
        //------------ Begin startStandardForm ----------  
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $sTableHtml = $this->oForm->open(Pelican::$config['DB_PATH']);
        $sTableHtml .= $this->beginForm($this->oForm);
        $sTableHtml .= $this->oForm->beginFormTable();
        //------------ End startStandardForm ----------  

        /* if (!$this->readO) {
          $this->aButton['save'] = $this->oForm->sFormName;
          } */


        if (count($aWebservices)) {
            $sTableHtml .=sprintf('<thead><tr><th>&nbsp;</th><th>URL</th>');

            foreach ($aSites as $aOneSite) {
                $sTableHtml .=sprintf('<th>%s</th>', $aOneSite['SITE_LABEL']);
            }

            $aWsStatus = array(
                '1' => t('ON'),
            );

            $sTableHtml .='</tr></thead>';
            $sTableHtml .='<tbody>';

            foreach ($aWebservices as $aOneWebservice) {

                $sUrlFieldName = 'URL[' . $aOneWebservice['ws_id'] . ']';
                //public function createInput($strName, $strLib, $iMaxLength = "255", $strControl = "", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $strType = "text", $aSuggest = array(), $multiple = false) {                    
                $sUrlFormField = $this->oForm->createInput($sUrlFieldName, $aOneWebservice['ws_name'], 255, "", false, $aOneWebservice['ws_url'], $this->readO, 30, true);
                $sTableHtml .='<tr>';
                $sTableHtml .=sprintf('<td>%s</td><td>%s</td>', $aOneWebservice['ws_name'], $sUrlFormField);

                foreach ($aSites as $aOneSite) {
                    $sCombinedKey = $aOneSite['SITE_ID'] . '_' . $aOneWebservice['ws_id'];
                    if (isset($aSitesWsConfIndexed) && isset($aSitesWsConfIndexed[$sCombinedKey])) {
                        $sCheckBoxfieldValue = $aSitesWsConfIndexed[$sCombinedKey];
                    } else {
                        $sCheckBoxfieldValue = '';
                    }
                    //$sFormField = $this->oForm->createInput($sFieldName, $aOneWebservice['ws_name'], 255, "", false, $this->values[$sFieldName], $this->readO, 75);
                    $sFieldName = 'STATUS[' . $aOneWebservice['ws_id'] . '][' . $aOneSite['SITE_ID'] . ']';
                    
                    $sCheckBoxfield = $this->oForm->createCheckBoxFromList($sFieldName, '', $aWsStatus, $sCheckBoxfieldValue, false, false, 'h', true);
                    $sTableHtml .=sprintf('<td>%s</td>', $sCheckBoxfield);
                }


                $sTableHtml .='</tr>';
            }
            $sTableHtml .='</tbody>';


            //------------ Begin stopStandardForm ----------  
            $sTableHtml .= $this->oForm->endFormTable();
            $sTableHtml .= $this->endForm($this->oForm);
            $sTableHtml .= $this->oForm->close();
            //------------ End stopStandardForm ---------- 
        }
        $this->assign('html', $sTableHtml, false);

        //Backoffice_Button_Helper::init($this->aButton);
        $this->fetch();
    }

    public function saveAction() {
        $oConnection = Pelican_Db::getInstance();
        //$oConnection->query('rrrr');
        //if (Pelican_Db::$values['form_button'] == "save"){

        if (isset(Pelican_Db::$values['URL'])) {
            //$aWebservices = $this->_getWSList();
            //Pelican_Db::$values['ws_url'] = 'psa_liste_webservices';
            foreach (Pelican_Db::$values['URL'] as $key => $sOneUrl) {

                Pelican_Db::$values['ws_id'] = $key;
                Pelican_Db::$values['ws_url'] = $sOneUrl;
                $oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, '#pref#_liste_webservices', '', array('ws_name'), array('ws_id', 'ws_url'));
            }
        }
        $sTruncateTableQuery = 'TRUNCATE #pref#_site_webservice';
        $oConnection->query($sTruncateTableQuery);
        if (isset(Pelican_Db::$values['STATUS']) && !empty(Pelican_Db::$values['STATUS'])) {
            
     //       var_dump('ENTRED');
            foreach (Pelican_Db::$values['STATUS'] as $key => $status) {
                if (is_array($status) && count($status)) {
     
                    foreach ($status as $site_id => $state) {
                        Pelican_Db::$values['status'] = $state;
                        Pelican_Db::$values['site_id'] = $site_id;
                        Pelican_Db::$values['ws_id'] = $key;                         
                        $oConnection->updateTable(Pelican_Db::DATABASE_DELETE, '#pref#_site_webservice', '', array(), array('ws_id', 'site_id'));
                        if ($state != null || $state != '') {
                            $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_site_webservice', '', array(), array('ws_id', 'site_id', 'status'));
                        }
                    }
                }
            }
        }

        Pelican_Cache::clean('Frontend/Citroen/SiteWs');
        Pelican_Cache::clean('Frontend/Citroen/WsConfig');
        Pelican_Cache::clean('Frontend/Citroen/SiteWsIndexed');
        Pelican_Cache::clean('Frontend/Citroen/VehiculeShowroomById');
        $parse_url = parse_url($_SERVER["REQUEST_URI"]);
        Pelican_Db::$values['form_retour'] = "/_/Index/child?tid=".$this->tid."tc=&id=1&view=O_1";
        //parent::saveAction();
        //}
       // die();
    }

}

