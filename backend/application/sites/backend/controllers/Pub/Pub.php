<?php

/**
 * Formulaire de gestion des tags publicitaire.
 *
 * @author Fairouz Bihler <fbihler@businessdecision.com>
 *
 * @since 21/03/2005
 */
/*?>
<script type="text/javascript"
    src="<?=Pelican::$config["LIB_PATH"]?>/Pelican/Form/public/js/xt_toggle.js"></script>
<?php*/
class Pub_Pub_Controller extends Pelican_Controller_Back
{
    public $form_name = "pub";

    public $field_id = "PUB_ID";

    public $defaultOrder = "PUB_CREATION_DATE DESC";

    public function before()
    {
        $this->aBind[":PUB_ID"] = $this->id;
        $this->aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        parent::before();
    }

    protected function setEditModel()
    {
        $this->editModel = " SELECT * FROM #pref#_pub
		WHERE
		PUB_ID = :PUB_ID";
    }

    protected function setListModel()
    {
        $this->listModel = " SELECT PUB_ID, PUB_BANNER, PUB_SKYSCRAPER, SITE_ID, ".$oConnection->dateSqlToString("PUB_CREATION_DATE")." PUB_CREATION_DATE_TXT,
		PUB_CREATION_DATE PUB_CREATION_DATE_ORDER, PUB_LABEL FROM
		#pref#_pub
		where SITE_ID=:SITE_ID
		ORDER BY ".$this->listOrder;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste", "", true, true);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        $table->setValues($this->getListModel(), "PUB_ID", "", $this->aBind);
        $table->addColumn(t('ID'), "PUB_ID", "10", "center", "", "tblheader", "PUB_ID");
        $table->addColumn("Nom Tag", "PUB_LABEL", "70", "left", "", "tblheader", "PUB_LABEL");
        $table->addColumn(t('POPUP_MEDIA_LABEL_LAST_ACCES'), "PUB_CREATION_DATE_TXT", "10", "center", "", "tblheader", "PUB_CREATION_DATE_ORDER");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "PUB_ID",
            "uid" => "CONTENT_TYPE_ID",
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "PUB_ID",
            "uid" => "CONTENT_TYPE_ID",
            "" => "readO=true",
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createInput("PUB_LABEL", "Nom Tag", 255, "", false, $this->values["PUB_LABEL"], $this->readO, 75, false);
        $form .= $this->oForm->createTextArea("PUB_BANNER", "Tag bannière", false, $this->values["PUB_BANNER"], 2000, $this->readO, 10, 75);
        $form .= $this->oForm->createTextArea("PUB_SKYSCRAPER", "Tag Skycraper", false, $this->values["PUB_SKYSCRAPER"], 2000, $this->readO, 10, 75);
        $form .= $this->oForm->createHidden("MOD_DB", 1);
        $form .= $this->oForm->createHidden("PUB_CREATION_DATE", $this->values["PUB_CREATION_DATE"]);

        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }
}
