<?php
require_once Pelican::$config ["APPLICATION_CONTROLLERS"]."/Administration/Directory.php";

/**
 * Formulaire de gestion de la configuration Tag.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 27/05/2015
 */
class Administration_Site_Services_Tag_Controller extends Ndp_Controller
{

    protected $administration = true;
    protected $form_name = "tag_type";
    protected $field_id = 'TAG_TYPE_ID';
    protected $decacheBack = array(
        "Tag/Type",
    );

    /**
     * 
     */
    protected function init()
    {
        parent::init();
        $params = $this->getParams();
        $this->id = $params['TAG_TYPE_ID'];       
    }

    /**
     * 
     */
    protected function setEditModel()
    {
        $this->editModel = "SELECT
                    TAG_TYPE_JS_LINK,
                    TAG_TYPE_HTTP,
                    TAG_TYPE_HTTPS,
                    TAG_TYPE_ID
                FROM
                    #pref#_".$this->form_name."
                WHERE TAG_TYPE_ID = ".$this->id;
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

        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;

        $oConnection = Pelican_Db::getInstance();
        $form = $oForm->createTitle(t('NDP_TAG_JAVASCRIPT'));

        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            $sTagTypeSql = "select TAG_TYPE_ID as id, TAG_TYPE_LABEL as lib
                            from #pref#_tag_type
                            order by TAG_TYPE_LABEL";
            $form .= $oForm->createComboFromSql(
                $oConnection, "TAG_TYPE_ID", t('Tag type'), $sTagTypeSql, $this->values ["TAG_TYPE_ID"], false, $this->readO
            );
            $form .= $oForm->createHidden("TAG_TYPE_JS_LINK", $this->values["TAG_TYPE_JS_LINK"], true);
            $form .= $oForm->createHidden("TAG_TYPE_HTTP", $this->values["TAG_TYPE_HTTP"], true);
            $form .= $oForm->createHidden("TAG_TYPE_HTTPS", $this->values["TAG_TYPE_HTTPS"], true);
        } else {
            if(empty($this->values["TAG_TYPE_ID"])){
                $form .= $oForm->createLabel('',t('NDP_MSG_WARNING_TAG_HTTP_ET_HTTPS'));
            }else{
                $form .= $oForm->createHidden("TAG_TYPE_ID", $this->values["TAG_TYPE_ID"], true);
                $form .= $oForm->createInput(
                    "TAG_TYPE_JS_LINK", t('Javascript a inclure'), 255, "", false, $this->values["TAG_TYPE_JS_LINK"], $this->readO, 100
                );
                $form .= $oForm->createTextArea("TAG_TYPE_HTTP", t('Tag http').' (?)', false, $this->values["TAG_TYPE_HTTP"], 2000, $this->readO, 20, 75);
                $form .= $oForm->createTextArea("TAG_TYPE_HTTPS", t('Tag https').' (?)', false, $this->values["TAG_TYPE_HTTPS"], 2000, $this->readO, 20, 75);
            }
        }

        $this->setResponse($form);
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $aBind [':TAG_TYPE_ID'] = Pelican_Db::$values['TAG_TYPE_ID'];
        if ($this->getParam('tc') == 'admin') {            
            $aBind [':SITE_ID'] = Pelican_Db::$values ['SITE_ID'];            
            $connection->query('update #pref#_site set TAG_TYPE_ID=:TAG_TYPE_ID where SITE_ID=:SITE_ID', $aBind);
        }
        // Si site PAS ADMIN
        if ($this->getParam('tc') !== 'admin') {            
            // mise a jour Service pour psa_type_tag            
            $sSQL = "SELECT * FROM #pref#_tag_type WHERE TAG_TYPE_ID = :TAG_TYPE_ID";
            $aTag = $connection->queryRow($sSQL, $aBind);
            $aBind [':TAG_TYPE_JS_LINK'] = $connection->strToBind(Pelican_Db::$values['TAG_TYPE_JS_LINK']);
            $aBind [':TAG_TYPE_HTTP'] = $connection->strToBind(Pelican_Db::$values['TAG_TYPE_HTTP']);
            $aBind [':TAG_TYPE_HTTPS'] = $connection->strToBind(Pelican_Db::$values['TAG_TYPE_HTTPS']);
            if ($aTag) {
                //on update le champs
                $connection->query(
                    'update #pref#_tag_type set TAG_TYPE_JS_LINK=:TAG_TYPE_JS_LINK, TAG_TYPE_HTTP=:TAG_TYPE_HTTP, TAG_TYPE_HTTPS=:TAG_TYPE_HTTPS where TAG_TYPE_ID=:TAG_TYPE_ID', $aBind
                );
            }
        }
    }
}
