<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_AccessoiresSite_Controller extends Ndp_Controller
{
    protected $form_name    = "accessoires_site";
    protected $field_id     = "SITE_ID";
    protected $defaultOrder = "SITE_ID";

    const CTA_TYPE = "CTA_FOR_REF";
    const CTA      = "CTA_ERREUR";
    const DISABLE  = 0;
    const ENABLE   = 1;
    const AOA      = 6;

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :ID AND LANGUE_ID = :LANGUE_ID
SQL;
        $this->editModel = $sql;
    }

    /**
     *
     */
    public function listAction()
    {
        $this->id = $_SESSION[APP]['SITE_ID'];
        $this->_initBack();
        $this->_forward('edit');
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm                = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->createHeadOfForm();
        $form .= $this->createMaxForm();
        $form .= $this->createCtaForm();
        $form .= $this->createLinkForm();
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        if (!$this->getWsState(self::AOA)) {
            $form .= "<script type='text/javascript'>$(document).ready(function(){ $('input[type=radio][name=\"CTA_FOR_REF[PAGE_ZONE_CTA_STATUS]\"]').prop('disabled', 1)});</script>";
        }
        $this->setResponse($form);
    }

    public function createHeadOfForm()
    {
        $form  = $this->oForm->createHidden('SITE_ID', $this->id);
        $form .= $this->oForm->createHidden('LANGUE_ID', $_SESSION[APP]['LANGUE_ID']);
        $form .= $this->oForm->createComment(t('NDP_PRESENTATION_ACCESSORIES'), ['noBold' => true]);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function createMaxForm()
    {
        $form = '';
        $options = [];
        if (!$this->getWsState(self::AOA)) {
            $form .= $this->oForm->createComment(t('NDP_PRESENTATION_ACCESSORIES_AOA_NEEDED'), ['noBold' => true]);
            $options['attributes'] = ['disabled'=>'disabled', 'readonly'=>'readonly'];
        }

        $form .= $this->oForm->createInput('MAX_ACCESSOIRES', t('NDP_MAX_ACCESSOIRES'), 5, 'numerique', true, $this->values['MAX_ACCESSOIRES'], false, 5, false, '', 'text', [], false, '', $options);
        $form .= $this->oForm->createInput('MAX_ACCESSOIRES_UNIVERS', t('NDP_MAX_ACCESSOIRES_UNIVERS'), 5, 'numerique', true, $this->values['MAX_ACCESSOIRES_UNIVERS'], false, 5, false, '', 'text', [], false, '', $options);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('NDP_ACCESORIES_VISU_DEFAULT'), true, "image", "", $this->getDefaultMedia(), true, true, false);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function createLinkForm()
    {
        $form = '';
        $readO = false;
        if (!$this->getLinkOfDerivaties()) {
            $form .= $this->oForm->createComment(t('NDP_LINK_DERIVAIES_NEEDED'), ['noBold' => true]);
            $readO = true;
        }
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $this->setDefaultValueTo('PRODUITS_DERIVES', self::DISABLE);
        $form .= $this->oForm->createRadioFromList(
            "PRODUITS_DERIVES",
            t('NDP_LINK_STORE_DERIVATES'),
            $targetsAffichage,
            $this->values['PRODUITS_DERIVES'],
            true,
            $readO,
            'h',
            false
        );

        return $form;
    }

    /**
     *
     * @return boolean
     */
    public function getLinkOfDerivaties()
    {
        $isSet      = false;
        $connection = Pelican_Db::getInstance();
        $bind       = [':SITE_ID' => $this->id];
        $sql        = "SELECT ZONE_URL_PRODUIT_DERIVES FROM #pref#_sites_et_webservices_psa WHERE SITE_ID = :SITE_ID";
        $result     = $connection->queryRow($sql, $bind);
        if ($result['ZONE_URL_PRODUIT_DERIVES']) {
            $isSet = true;
        }

        return $isSet;
    }

    /**
     *
     *
     * @return string
     */
    public function createCtaForm()
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $this->buildValuesForCta();
        $ctaComposite->setCta($this->oForm, $this->values, false, self::CTA_TYPE);
        $ctaComposite->setLabel(t('NDP_CTA_ERROR_ACCESSORIE'));
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addStyleAvailable('style_niveau4', t('NDP_STYLE_NIVEAU4'))->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    public function buildValuesForCta()
    {
        $this->values['PAGE_ZONE_CTA_STATUS'] = $this->values[self::CTA];
        switch ($this->values[self::CTA]) {
            case Ndp_Cta::SELECT_CTA:
                $this->values['CTA_ID'] = $this->values['CTA_ERREUR_ID'];
                $this->values['TARGET'] = $this->values['CTA_ERREUR_TARGET'];
                $this->values['STYLE'] = $this->values['CTA_ERREUR_STYLE'];
                break;
            default:
                //Nothing
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function getDefaultMedia()
    {
        $media      = '';
        $connection = Pelican_Db::getInstance();
        $bind       = [':SITE_ID' => $this->id];
        $sql        = "SELECT MEDIA_ID FROM #pref#_accessoires WHERE SITE_ID = :SITE_ID";
        $result     = $connection->queryRow($sql, $bind);
        if ($result['MEDIA_ID']) {
            $media = $result['MEDIA_ID'];
        }

        return $media;
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $this->saveCta();
        $connection->query($this->getSaveQuery(), $this->getBindForSave());
        //Fix du form retour pour ne pas être redigiré ailleurs.
        Pelican_Db::$values['form_retour'] = '/_/Index/child?tid='.$this->getParam('tid').';tc=&amp;view=O_1&amp;toprefresh=1&amp;toprefresh=1';
    }

    /**
     *
     * @return array
     */
    public function getBindForSave()
    {
        $bind = [
            ':MAX_ACCESSOIRES' => Pelican_Db::$values['MAX_ACCESSOIRES'],
            ':MAX_ACCESSOIRES_UNIVERS' => Pelican_Db::$values['MAX_ACCESSOIRES_UNIVERS'],
            ':PRODUITS_DERIVES' => Pelican_Db::$values['PRODUITS_DERIVES'],
            ':CTA_ERREUR' => Pelican_Db::$values['CTA_ERREUR'],
            ':CTA_ERREUR_ID' => Pelican_Db::$values['CTA_ERREUR_ID'],
            ':CTA_ERREUR_ACTION' => Pelican_Db::$values['CTA_ERREUR_ACTION'],
            ':CTA_ERREUR_TITLE' => Pelican_Db::$values['CTA_ERREUR_TITLE'],
            ':CTA_ERREUR_STYLE' => Pelican_Db::$values['CTA_ERREUR_STYLE'],
            ':CTA_ERREUR_TARGET' => Pelican_Db::$values['CTA_ERREUR_TARGET'],
            ':SITE_ID' => Pelican_Db::$values['SITE_ID'],
            ':LANGUE_ID' => Pelican_Db::$values['LANGUE_ID']
        ];

        return $bind;
    }

    /**
     *
     * @return string
     */
    public function getSaveQuery()
    {
        $query = "REPLACE INTO  #pref#_".$this->form_name."(
                MAX_ACCESSOIRES,
		MAX_ACCESSOIRES_UNIVERS,
		PRODUITS_DERIVES,
		CTA_ERREUR,
		CTA_ERREUR_ID,
		CTA_ERREUR_ACTION,
		CTA_ERREUR_TITLE,
		CTA_ERREUR_STYLE,
		CTA_ERREUR_TARGET,
		SITE_ID,
		LANGUE_ID) VALUES (
                ':MAX_ACCESSOIRES',
		':MAX_ACCESSOIRES_UNIVERS',
		':PRODUITS_DERIVES',
		':CTA_ERREUR',
		':CTA_ERREUR_ID',
		':CTA_ERREUR_ACTION',
		':CTA_ERREUR_TITLE',
		':CTA_ERREUR_STYLE',
		':CTA_ERREUR_TARGET',
		':SITE_ID',
		':LANGUE_ID')";

        return $query;
    }

    /**
     * Méthode pour generer la sauvegarde des CTA du référentiel
     */
    public function saveCta()
    {
        switch (Pelican_Db::$values[self::CTA_TYPE]['PAGE_ZONE_CTA_STATUS']) {
            case Ndp_Cta::SELECT_CTA:
                Pelican_Db::$values['CTA_ERREUR_ID'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['CTA_ID'];
                Pelican_Db::$values['CTA_ERREUR_TARGET'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['TARGET'];
                Pelican_Db::$values['CTA_ERREUR_STYLE'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['STYLE'];
                Pelican_Db::$values[self::CTA] = Ndp_Cta::SELECT_CTA;
                break;
            default:
                Pelican_Db::$values[self::CTA] = Ndp_Cta::DISABLE_CTA;
                break;
        }
    }
}
