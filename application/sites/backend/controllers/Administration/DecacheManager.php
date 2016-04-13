<?php
/**
 * Formulaire de gestion des cache pays disponibles pour la fonction nettoyage (Decache.php)
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 * @since 21/11/2014
 */

class Administration_DecacheManager_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    // Table utilisée pour stocker la liste des caches
    protected $form_name = "decache_manager";

    // Clé primaire de la table
    protected $field_id = "id";

    // Colonne utilisée pour le tri des données dans la liste
    protected $defaultOrder = "cache_object";

    // Sélection des données de la liste de cache
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $stmt = "SELECT * FROM #pref#_decache_manager ORDER BY ".$this->listOrder.";";
        $this->listModel = $oConnection->queryTab($stmt);
    }

    protected function setEditModel()
    {
        $this->aBind[':'.$this->field_id] = $this->id;
        $this->editModel = "SELECT dm.* FROM #pref#_decache_manager dm WHERE dm.".$this->field_id." = :".$this->field_id.";";
    }

    public function listAction()
    {
        parent::listAction();
        
        // Initialisation de la liste
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("cache_object", "<b>" . t('RECHERCHER') . " :</b>", "");
        
        // Chargement des données dans la liste
        $table->setValues($this->getListModel(), "id");
        
        // Paramétrage de la liste
        $table->addColumn(t('ID'), "id", "10", "left", "", "tblheader", "id");
        $table->addColumn(t('CACHE'), "cache_object", "90", "left", "", "tblheader", "cache_object");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "id"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "id", "" => "readO=true"), "center");
        
        // Affichage de la liste
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden("id", $this->values ["id"]);
        $form .= $this->oForm->createInput("cache_object", t('CACHE'), 255, "", true, $this->values['cache_object'], $this->readO, 75);
        $enumCacheTypes = array('global' => 'global', 'par site' => 'par site');
        $form .= $this->oForm->createComboFromList("cache_type", t("TYPE"), $enumCacheTypes, $this->values['cache_type'], true, $this->readO, null, false, null, false);
        $form .= $this->oForm->createInput("siteid_order", t('ORDER'), 2, "", false, $this->values['siteid_order'], $this->readO, 5);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }
}
