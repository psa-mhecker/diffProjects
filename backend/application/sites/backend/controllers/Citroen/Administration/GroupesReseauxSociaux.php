<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';
class Citroen_Administration_GroupesReseauxSociaux_Controller extends Citroen_Controller
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "groupe_reseaux_sociaux";
    protected $field_id = "GROUPE_RESEAUX_SOCIAUX_ID";
    protected $defaultOrder = "GROUPE_RESEAUX_SOCIAUX_ID";

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlList = "SELECT
						*
					FROM
						#pref#_groupe_reseaux_sociaux
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID ";
        if ($_GET['filter_search_keyword'] != '') {
            $sqlList .= " AND (
            GROUPE_RESEAUX_SOCIAUX_LABEL like '%".$_GET['filter_search_keyword']."%'
            )
            ";
        }

        $sqlList .= "ORDER BY ".$this->listOrder;
        $this->listModel = $oConnection->queryTab($sqlList, $aBind);
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int) $this->id;
        $this->editModel = "SELECT
								*
							FROM
								#pref#_groupe_reseaux_sociaux
							WHERE
								SITE_ID = :SITE_ID
							AND LANGUE_ID = :LANGUE_ID
							AND ".$this->field_id." = :".$this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "GROUPE_RESEAUX_SOCIAUX_ID");
        $table->addColumn(t('ID'), "GROUPE_RESEAUX_SOCIAUX_ID", "10", "left", "", "tblheader", "GROUPE_RESEAUX_SOCIAUX_ID");
        $table->addColumn(t('LIBELLE'), "GROUPE_RESEAUX_SOCIAUX_LABEL", "90", "left", "", "tblheader", "GROUPE_RESEAUX_SOCIAUX_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "GROUPE_RESEAUX_SOCIAUX_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "GROUPE_RESEAUX_SOCIAUX_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $oConnection = Pelican_Db::getInstance();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID',  $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createInput("GROUPE_RESEAUX_SOCIAUX_LABEL", t('TITRE'), 255, "", true, $this->values['GROUPE_RESEAUX_SOCIAUX_LABEL'], $this->readO, 75);
        $form .= $this->oForm->createLabel("", t('WARNING_SOCIAL_NETWORK_ON_SHARE'));
        $aBind[':SITE_ID'] =  $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] =  $_SESSION[APP]['LANGUE_ID'];
        $aBind[':GROUPE_RESEAUX_SOCIAUX_ID'] =  $this->id;
        $sSQLSelected = "
            SELECT
				RESEAU_SOCIAL_ID
            FROM
				#pref#_groupe_reseaux_sociaux_rs
            WHERE
				GROUPE_RESEAUX_SOCIAUX_ID = :GROUPE_RESEAUX_SOCIAUX_ID
            AND SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID";
        $aResult = $oConnection->queryTab($sSQLSelected, $aBind);
        if ($aResult) {
            foreach ($aResult as $r) {
                $aSelectedValues[] = $r['RESEAU_SOCIAL_ID'];
            }
        }
        $sSQLListe = "
            SELECT
                RESEAU_SOCIAL_ID as id,
                RESEAU_SOCIAL_LABEL as lib
            FROM
				#pref#_reseau_social
            WHERE
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID
            ORDER BY RESEAU_SOCIAL_ORDER asc";
        $form .= $this->oForm->createComboFromSql($oConnection, "RESEAUX_SOCIAUX", t('RESEAUX_SOCIAUX'), $sSQLListe, $aSelectedValues, true, $this->readO, 5, true, "", false, false, "", "", $aBind);
        $form .= $this->oForm->createCheckBoxFromList("GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA", t('REGROUPEMENT_DEFAUT_GALERIES_MEDIA'), array('1' => ""), $this->values['GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA'], false, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC", t('REGROUPEMENT_DEFAUT_PUBLIC'), array('1' => ""), $this->values['GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC'], false, $this->readO);
        $form .= $this->stopStandardForm();
        $form = formToString($this->oForm, $form);
        $this->setResponse($form);
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':GROUPE_RESEAUX_SOCIAUX_ID'] =  Pelican_Db::$values['GROUPE_RESEAUX_SOCIAUX_ID'];
        $aBind[':SITE_ID'] =  $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] =  $_SESSION[APP]['LANGUE_ID'];
        if (Pelican_Db::$values['form_action'] != "INS") {
            $sSQL = "DELETE FROM
						#pref#_groupe_reseaux_sociaux_rs
					WHERE
						GROUPE_RESEAUX_SOCIAUX_ID = :GROUPE_RESEAUX_SOCIAUX_ID
					AND SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID";
            $oConnection->query($sSQL, $aBind);
        }
        if (Pelican_Db::$values['GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA'] == '1') {
            $sSQL = "UPDATE
						#pref#_groupe_reseaux_sociaux
					SET
						GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA=0
					WHERE
						LANGUE_ID = :LANGUE_ID
					AND SITE_ID = :SITE_ID";
            $oConnection->query($sSQL, $aBind);
        }
        if (Pelican_Db::$values['GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC'] == '1') {
            $sSQL = "UPDATE
						#pref#_groupe_reseaux_sociaux
					SET
						GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC=0
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID";
            $oConnection->query($sSQL, $aBind);
        }
        parent::saveAction();
        if (Pelican_Db::$values['RESEAUX_SOCIAUX'] && $this->form_action != Pelican_Db::DATABASE_DELETE) {
            $DBVALUES_INIT = Pelican_Db::$values;
            foreach (Pelican_Db::$values['RESEAUX_SOCIAUX'] as $i => $rs) {
                Pelican_Db::$values['RESEAU_SOCIAL_ID'] = $rs;
                Pelican_Db::$values['RESEAU_SOCIAL_ORDER'] = $i+1;
                $oConnection->insertQuery("#pref#_groupe_reseaux_sociaux_rs");
            }
            Pelican_Db::$values = $DBVALUES_INIT;
        }
        Pelican_Cache::clean("Frontend/Citroen/GroupeReseauxSociaux");
        Pelican_Cache::clean("Citroen/GroupeReseauxSociaux");
        Pelican_Cache::clean("Frontend/Citroen/FindGroupeReseauxSociaux");
    }
}
