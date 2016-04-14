<?php
/**
 * Fichier de Citroen_BarreOutils :.
 *
 * Classe Back-Office de contribution des éléments de la Barre d'Outils
 *
 * @author Patrice Chégard <patrice.chegard@businessdecision.com>
 * @update Mathieu Raiffé <mathieu.raiffe@businessdecision.com> Ajout de la notion de langue
 *
 * @since 17/07/2013
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';
class Citroen_Administration_BarreOutils_Controller extends Citroen_Controller
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "barre_outils";
    protected $field_id = "BARRE_OUTILS_ID";
    protected $defaultOrder = "BARRE_OUTILS_ID";
    protected $decacheBack = array(
        array('Frontend/Citroen/BarreOutils',
            array('SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/VehiculeOutil',
            array('SITE_ID', 'LANGUE_ID'),
        ),
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/BarreOutils',
            array('SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/VehiculeOutil',
            array('SITE_ID', 'LANGUE_ID'),
        ),
    );
    /**
     * Méthode protégées d'instanciation de la propriété listModel.
     * La méthode instancie listModel avec un tableau de données qui sera utilisé
     * pour afficher la liste de véhicule.
     */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindToolBarList[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBindToolBarList[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];

        /* Requête remontant l'ensemble des éléments du référentiel Barre d'outils  */
//        $sqlList = "select * from #pref#_barre_outils where SITE_ID = '" . $_SESSION[APP]['SITE_ID'] . "' order by " . $this->listOrder;
        $sqlToolBarList = "
                SELECT
                    BARRE_OUTILS_ID,
                    BARRE_OUTILS_LABEL
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID ";
        if ($_GET['filter_search_keyword'] != '') {
            $sqlToolBarList .= " AND (
            BARRE_OUTILS_LABEL like '%".$_GET['filter_search_keyword']."%'
            )
            ";
        }

        $sqlToolBarList  .= "ORDER BY {$this->listOrder} ";

        $this->listModel = $oConnection->queryTab($sqlToolBarList, $aBindToolBarList);
    }

    /**
     * Méthode protégées d'instanciation de la propriété editModel.
     * La méthode instancie editModel avec un tableau de données qui sera utilisé
     * l'instanciation de la propriété 'value'.
     */
    protected function setEditModel()
    {/* Valeurs Bindées pour la requête */
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int) $this->id;

        /* Requête remontant les données du véhicule sélectionnée pour un pays
         * et une langue donnée.
         */
        $sSqlToolBarForm = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
SQL;

        $this->editModel = $sSqlToolBarForm;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "BARRE_OUTILS_ID");
        $table->addColumn(t('ID'), "BARRE_OUTILS_ID", "20", "left", "", "tblheader", "BARRE_OUTILS_ID");
        $table->addColumn(t('LIBELLE_INTERNE'), "BARRE_OUTILS_LABEL", "80", "left", "", "tblheader", "BARRE_OUTILS_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "BARRE_OUTILS_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "BARRE_OUTILS_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        // Message d'information lors de la tentative de suppresssion d'un contenu utilis� dans des pages.
        $error = '';
        if ($this->form_action == 'DEL') {
            $aPagesConflictuelles = Backoffice_Form_Helper::verificationSuppressionReferentiel('OUTILS', $this->values['BARRE_OUTILS_ID'], $this->values['SITE_ID']);
            if ($aPagesConflictuelles) {
                foreach ($aPagesConflictuelles as $p) {
                    $error .= $p['PAGE_TITLE_BO'].' (pid : '.$p['PAGE_ID'].')'.Pelican_Html::br();
                }
                $error = Pelican_Html::div(
                    array("class" => t('ERROR')),
                    Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
                );
            }
        }
        $form = $error.$this->startStandardForm();
        if ($this->values['BARRE_OUTILS_AFFICHAGE_WEB']) {
            $this->values['AFFICHAGE'][] = 1;
        }
        if ($this->values['BARRE_OUTILS_AFFICHAGE_MOBILE']) {
            $this->values['AFFICHAGE'][] = 2;
        }
        $form .= $this->oForm->createInput("BARRE_OUTILS_LABEL", t('LIBELLE_INTERNE'), 255, "", true, $this->values['BARRE_OUTILS_LABEL'], $this->readO, 75);
        $form .= $this->oForm->createInput("BARRE_OUTILS_TITRE", t('TITRE'), 255, "", true, $this->values['BARRE_OUTILS_TITRE'], $this->readO, 75);
        $form .= $this->oForm->createRadioFromList("BARRE_OUTILS_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank", '3' => t('DEPLIE')), $this->values['BARRE_OUTILS_MODE_OUVERTURE'], true, $this->readO, "h", false, "onchange=\"showInput()\"");
        $form .= $this->oForm->createInput("BARRE_OUTILS_URL_WEB", t('URL_WEB'), 255, "internallink", false, $this->values['BARRE_OUTILS_URL_WEB'], $this->readO, 75);
        $form .= $this->oForm->createInput("BARRE_OUTILS_URL_MOBILE", t('URL_MOBILE'), 255, "internallink", false, $this->values['BARRE_OUTILS_URL_MOBILE'], $this->readO, 75);
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':ZONE_ID'] = Pelican::$config['ZONE']['FORMULAIRE'];
        $oConnection = Pelican_Db::getInstance();
        /*$sSQL = "
                SELECT
                    FORM_ID,
                    FORM_LABEL
                FROM
                    #pref#_form
                WHERE
                    SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID
        ";*/
        $sSQL = "
            SELECT
                pmz.PAGE_ID,
                pmz.LANGUE_ID,
                pmz.AREA_ID,
                pmz.ZONE_ORDER,
                pmz.ZONE_TITRE2
            FROM
                #pref#_page p
                INNER JOIN #pref#_page_version pv
                    ON (
                        p.PAGE_ID = pv.PAGE_ID
                        AND p.LANGUE_ID = pv.LANGUE_ID
                        AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION)
                INNER JOIN #pref#_page_multi_zone pmz
                    ON (
                        p.PAGE_ID = pmz.PAGE_ID
                        AND p.LANGUE_ID = pmz.LANGUE_ID
                        AND p.PAGE_DRAFT_VERSION = pmz.PAGE_VERSION)
            WHERE
                ZONE_ID = :ZONE_ID
                AND p.LANGUE_ID = :LANGUE_ID
                AND p.SITE_ID = :SITE_ID

        ";
        $results = $oConnection->queryTab($sSQL, $bind);
        $trancheForm =  array();
        if (is_array($results) && count($results)>0) {
            foreach ($results as $result) {
                //$trancheForm[$result['FORM_ID']] = $result['FORM_LABEL'];
                $trancheForm[$result['PAGE_ID'].'_'.$result['LANGUE_ID'].'_'.$result['AREA_ID'].'_'.$result['ZONE_ORDER']] = $result['ZONE_TITRE2'];
            }
            $trancheForm = array_replace($trancheForm, Pelican::$config['TRANCHE_FORMULAIRE_CONTACT']);
        } else {
            $trancheForm = Pelican::$config['TRANCHE_FORMULAIRE_CONTACT'];
        }
        $form .= $this->oForm->createComboFromList("BARRE_OUTILS_FORMULAIRE", t('TYPE_RESEAU'), $trancheForm, $this->values['BARRE_OUTILS_FORMULAIRE'], false, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("AFFICHAGE", t('AFFICHAGE'), array(1 => t('WEB'), 2 => t('MOBILE')), $this->values['AFFICHAGE'], true, $this->readO);

        $form .= $this->oForm->createMedia("MEDIA_GENERIQUE_ON", t('MEDIA_GENERIQUE_ON'), false, "image", "", $this->values["MEDIA_GENERIQUE_ON"], $this->readO, true, false);
        $form .= $this->oForm->createMedia("MEDIA_GENERIQUE_OFF", t('MEDIA_GENERIQUE_OFF'), false, "image", "", $this->values["MEDIA_GENERIQUE_OFF"], $this->readO, true, false);
        $form .= $this->oForm->createMedia("MEDIA_DS_ON", t('MEDIA_DS_ON'), false, "image", "", $this->values["MEDIA_DS_ON"], $this->readO, true, false);
        $form .= $this->oForm->createMedia("MEDIA_DS_OFF", t('MEDIA_DS_OFF'), false, "image", "", $this->values["MEDIA_DS_OFF"], $this->readO, true, false);

        $form .= $this->stopStandardForm();
        if ($aPagesConflictuelles) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $form = formToString($this->oForm, $form);

        if (!$this->readO) {
            $sJS = "<script type=\"text/javascript\">
                function showInput() {
                    if ($('input:radio[name=\'BARRE_OUTILS_MODE_OUVERTURE\']:checked').val() == 3) {
                        $('input[name=\'BARRE_OUTILS_URL_WEB\']').parent().parent().hide();
                        $('input[name=\'BARRE_OUTILS_URL_MOBILE\']').parent().parent().hide();
                        $('select[name=\'BARRE_OUTILS_FORMULAIRE\']').parent().parent().show();
                    }
                    else {
                        $('input[name=\'BARRE_OUTILS_URL_WEB\']').parent().parent().show();
                        $('input[name=\'BARRE_OUTILS_URL_MOBILE\']').parent().parent().show();
                        $('select[name=\'BARRE_OUTILS_FORMULAIRE\']').parent().parent().hide();
                    }
                }
                $(document).ready(function() {
                    showInput();
                });
                </script>
            ";
        }
        $this->setResponse($form.$sJS);
    }

    public function saveAction()
    {
        if (Pelican_Db::$values['BARRE_OUTILS_MODE_OUVERTURE'] == '3') {
            unset(Pelican_Db::$values['BARRE_OUTILS_URL_WEB']);
            unset(Pelican_Db::$values['BARRE_OUTILS_URL_MOBILE']);
        } else {
            unset(Pelican_Db::$values['BARRE_OUTILS_FORMULAIRE']);
        }
        if (in_array(1, Pelican_Db::$values['AFFICHAGE'])) {
            Pelican_Db::$values['BARRE_OUTILS_AFFICHAGE_WEB'] = 1;
        }
        if (in_array(2, Pelican_Db::$values['AFFICHAGE'])) {
            Pelican_Db::$values['BARRE_OUTILS_AFFICHAGE_MOBILE'] = 1;
        }
        Pelican_Db::$values['TYPE_CONFIGURATEUR'] = 1;

        parent::saveAction();

        Pelican_Cache::clean('Frontend/Citroen/ZoneMulti');
    }
}
