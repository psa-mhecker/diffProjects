<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';
require_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Webservice.php';


class Ndp_ConfigurationWS_Controller extends Ndp_Controller
{
    protected $administration = true;
    protected $form_name = "site_webservice";
    protected $field_id = "SITE_ID";

    /**
     *
     */
    public function listAction()
    {
        parent::_initBack();
        $this->_forward('edit');
    }

    /**
     * @return bool
     */
    protected function isMaster()
    {
        return ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_MASTER']);
    }

    /**
     *
     * @return array
     */
    protected function getSites()
    {
        $connection = Pelican_Db::getInstance();
        $query = "select s.SITE_ID,s.SITE_LABEL,sc.SITE_CODE_PAYS from #pref#_site s LEFT JOIN #pref#_site_code sc ON sc.SITE_ID=s.SITE_ID WHERE s.SITE_ID > 1";

        if (!$this->isMaster()) {
            $query .= ' AND s.SITE_ID='.$_SESSION[APP]['SITE_ID'];
        }

        return $connection->queryTab($query);
    }

    protected function getDecacheUrl($ws_name, $country)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri);
        $fields = array_merge($this->getParams() ,array('decache'=>'1','ws_name' => $ws_name, 'country' => $country));

        $url = $path['path'].'?'. http_build_query($fields, '', "&");

        return $url;

    }

    protected function decacheWebservice()
    {
        $params = $this->getParams();
        $wsName = $params['ws_name'];
        $country = $params['country'];
        /** @var \PsaNdp\WebserviceConsumerBundle\Adapter\PsaRedis $cacheManager */
        $cacheManager = $this->getContainer()->get('psa_ndp_ws_cache');
        $redis = $cacheManager->getConnection();

        // on recupere les clef des WS qui ne gere pas le country
        $keys = $redis->getKeys(sprintf('WEBSERVICE_%s_ALL_*',$wsName));
        // on recupere toute les clef pour le country fourni
        $keys = array_merge($keys,$redis->getKeys(sprintf('WEBSERVICE_%s_%s_*',$wsName,$country)));
        // on supprime les clef
        $redis->delete($keys);

    }


    /**
     *
     */
    public function editAction()
    {
        parent::editAction();

        $params = $this->getParams();
        if (isset($params['decache']))  {
            $this->decacheWebservice();
        }

        /** @var Ndp_Webservice $webservice */
        $webservice = Pelican_Factory::getInstance('Webservice');
        $webservices = $webservice->getValues();
        $sites = $this->getSites();
        $sitesWsConf = $webservice->getSitesWsConf();

        if (isset($sitesWsConf) && !empty($sitesWsConf)) {
            $sitesWsConfIndexed = array();
            foreach ($sitesWsConf as $oneSiteWsConf) {
                $sitesWsConfIndexed[$oneSiteWsConf['site_id'].'_'.$oneSiteWsConf['ws_id']] = $oneSiteWsConf['status'];
            }
        }

        $this->aButton['add'] = '';
        //------------ Begin startStandardForm ----------
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $tableHtml = $this->oForm->open(Pelican::$config['DB_PATH']);
        $tableHtml .= $this->beginForm($this->oForm);
        $tableHtml .= $this->oForm->beginFormTable('0','0','liste');
        //------------ End startStandardForm ----------

        if (count($webservices)) {
            $tableHtml .= sprintf('<thead><tr><th class="tblheader" style="width:300px;">&nbsp;</th><th class="tblheader" style="width: 250px;">URL</th>');

            foreach ($sites as $oneSite) {
                $tableHtml .= sprintf('<th class="tblheader" style="width: 130px;" colspan="2">%s %s</th>', $oneSite['SITE_LABEL'], $this->getLinkDecache('*',$oneSite['SITE_CODE_PAYS']));
            }

            $tableHtml .= '</tr></thead>';
            $tableHtml .= '<tbody>';

            foreach ($webservices as $oneWebservice) {

                $sUrlFieldName = 'URL['.$oneWebservice['ws_id'].']';

                $urlFormField = $this->oForm->createInput($sUrlFieldName, $oneWebservice['ws_name'], 255, "", false, $oneWebservice['ws_url'], $this->readO || !$this->isMaster(), 30, true);
                $tableHtml .= '<tr>';
                $tableHtml .= sprintf('<td>%s</td><td>%s</td>', t($oneWebservice['ws_name']), $urlFormField);
                $tableHtml .= $this->addCheckBoxesWebserviceActivationForSites($sites, $oneWebservice, $sitesWsConfIndexed);
                $tableHtml .= '</tr>';
            }
            $tableHtml .= '</tbody>';

            //------------ Begin stopStandardForm ----------
            $tableHtml .= $this->oForm->endFormTable();
            $tableHtml .= $this->endForm($this->oForm);
            $tableHtml .= $this->oForm->close();
            //------------ End stopStandardForm ----------
        }

        $tableHtml .= Pelican_Html::script(array(
            type => "text/javascript",
        ), "
            $(function() {
                $('input[name^=URL]').keyup(function(){
                var Urlid =  this.name.replace('URL', '');
                    if (this.value != ''){
                        $('input.STATUS_'+Urlid).removeAttr('disabled', 'checked');
                    }
                    else{
                        $('input.STATUS_'+Urlid).attr('disabled', true).attr('checked', false);
                    }
                })
            });
        ");

      $this->setResponse( $tableHtml);
    }

    /**
     *
     * @param array $sites
     * @param array $oneWebservice
     * @param array $sitesWsConfIndexed
     *
     * @return string
     */
    public function addCheckBoxesWebserviceActivationForSites($sites = [], $oneWebservice = [], $sitesWsConfIndexed = []){
        $tableHtml = "";
        $status = array(
            '1' => '',
        );

        foreach ($sites as $oneSite) {

            $combinedKey = $oneSite['SITE_ID'].'_'.$oneWebservice['ws_id'];
            if (isset($sitesWsConfIndexed) && isset($sitesWsConfIndexed[$combinedKey])) {
                $checkBoxfieldValue = $sitesWsConfIndexed[$combinedKey];
            } else {
                $checkBoxfieldValue = '';
            }

            $fieldName = 'STATUS['.$oneWebservice['ws_id'].']['.$oneSite['SITE_ID'].']';

            $checkboxAttributes = "class='STATUS_'".$oneWebservice['ws_id'];
            if (!$checkBoxfieldValue && !$oneWebservice['ws_url']) {
                $checkboxAttributes .= ' disabled';
            }

            $checkBoxfield = $this->oForm->createCheckBoxFromList($fieldName, '', $status, $checkBoxfieldValue, false, false, 'h', true, $checkboxAttributes);
            $tableHtml .= sprintf('<td style="text-align: center">%s</td>', $checkBoxfield);
            $tableHtml .='<td style="text-align: center">'.$this->getLinkDecache($oneWebservice['ws_name'], $oneSite['SITE_CODE_PAYS']).'</td>';
        }

        return $tableHtml;
    }

    protected function getLinkDecache($wsName, $codePays) {

        return '<a href="'.$this->getDecacheUrl($wsName, $codePays ).'" onclick="return confirm(\''.t('NDP_ASK_CLEAR_CACHE').'\')"><img src="/library/Pelican/Debug/public/images/reload.png" /></a>';
    }

    /**
     *
     */
    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();

        if (isset(Pelican_Db::$values['URL'])) {
            foreach (Pelican_Db::$values['URL'] as $key => $oneUrl) {
                Pelican_Db::$values['ws_id'] = $key;
                Pelican_Db::$values['ws_url'] = $oneUrl;
                $connection->updateTable(Pelican_Db::DATABASE_UPDATE, '#pref#_liste_webservices', '', array('ws_name'), array('ws_id', 'ws_url'));
            }
        }

        $truncateTableQuery = 'TRUNCATE #pref#_site_webservice';
        if (!$this->isMaster()) {
            $truncateTableQuery = 'DELETE FROM #pref#_site_webservice WHERE SITE_ID='.$_SESSION[APP]['SITE_ID'];
        }
        $connection->query($truncateTableQuery);
        if (isset(Pelican_Db::$values['STATUS']) && !empty(Pelican_Db::$values['STATUS'])) {

            foreach (Pelican_Db::$values['STATUS'] as $key => $status) {
                if (is_array($status) && count($status)) {
                    foreach ($status as $site_id => $state) {
                        Pelican_Db::$values['status'] = $state;
                        Pelican_Db::$values['site_id'] = $site_id;
                        Pelican_Db::$values['ws_id'] = $key;
                        $connection->updateTable(Pelican_Db::DATABASE_DELETE, '#pref#_site_webservice', '', array(), array('ws_id', 'site_id'));
                        if ($state != null || $state != '') {
                            $connection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_site_webservice', '', array(), array('ws_id', 'site_id', 'status'));
                        }
                    }
                }
            }
        }

        Pelican_Db::$values['form_retour'] = "/_/Index/child?tid=".$this->tid."tc=&id=1&view=O_1";
    }
}
