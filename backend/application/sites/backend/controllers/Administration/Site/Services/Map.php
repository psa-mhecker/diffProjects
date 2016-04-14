<?php
require_once Pelican::$config ["APPLICATION_CONTROLLERS"]."/Administration/Directory.php";

/**
 * Formulaire de gestion de la configuration de la cartographie.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 27/05/2015
 */
class Administration_Site_Services_Map_Controller extends Ndp_Controller
{

    protected $administration = true;
    protected $form_name = "site_service";
    protected $field_id = 'SITE_ID';

    const GOOGLE_SERVICE_CODE = 'GOOGLEMAP4WORK';

    protected function init()
    {
        parent::init();
        $params = $this->getParams();
        $this->id = $params['SITE_ID'];
    }

    protected function setEditModel()
    {

        $this->aBind[':SITE_ID'] = (int) $this->id;
        $this->aBind[':SERVICE_CODE'] = self::GOOGLE_SERVICE_CODE;

        $this->editModel = "SELECT * "
            ." FROM #pref#_".$this->form_name." s"
            ." WHERE s.SITE_ID=:SITE_ID"
            ." AND SERVICE_CODE = ':SERVICE_CODE'";
    }

    public function listAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        self::init();
        parent::editAction();

        // Si site PAS ADMIN
        if ($this->getParam('tc') !== 'admin') {
            $this->form_retour = '/_/Index/child?tid='.$this->iTemplateId.'&tc='.$this->getParam('tc').'&view=';
        }

        $oForm = $this->getParam('oForm');

        $form = $oForm->createTitle(t('MAP'));
        if (! valueExists($oForm->_inputName, 'SITE_ID')) {
            $form .= $oForm->createHidden('SITE_ID', $this->values['SITE_ID'], true);
        }
        $form .= $oForm->createHidden('SITE_SERVICE_ID', $this->values['SITE_SERVICE_ID'], true);
        $form .= $oForm->createHidden('SERVICE_CODE', self::GOOGLE_SERVICE_CODE, true);

//        $form .= $oForm->createInput('CONSUMER_KEY', t('NDP_SERVICE_MAP_GOOGLE_CONSUMER_KEY'), 100, "", false, $this->values['CONSUMER_KEY'], false, 20);
        $form .= $oForm->createInput('CLIENT_ID', t('NDP_SERVICE_MAP_GOOGLE_CLIENT_ID'), 100, "", false, $this->values['CLIENT_ID'], false, 20);

        $this->setResponse($form);
    }

    public function saveAction()
    {
        self::init();
        $connection = Pelican_Db::getInstance();     
        
        Pelican_Db::$values['SERVICE_CODE'] = self::GOOGLE_SERVICE_CODE;
        if (empty(Pelican_Db::$values['SITE_SERVICE_ID'])) {
            $connection->insertQuery('#pref#_'.$this->form_name);
        } else {
            $connection->updateQuery('#pref#_'.$this->form_name);
        }
       
    } 
}
