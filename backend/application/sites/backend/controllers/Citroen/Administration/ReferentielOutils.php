<?php
/**
 * Gestion de la typologie des outils.
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 *
 * @since 06/01/2015
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_ReferentielOutils_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "referentiel_outils"; // Table utilisée pour stocker la liste des caches
    protected $field_id = "ID";                  // Clé primaire de la table
    protected $defaultOrder = "ID";              // Colonne utilisée pour le tri des données dans la liste

    protected $decacheBack = array(
        array('Frontend/Citroen/ReferentielOutils'),
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/ReferentielOutils'),
    );

    // Sélection des données de la liste (listAction)
    protected function setListModel()
    {
        $stmt = "SELECT * FROM #pref#_".$this->form_name." ro WHERE ro.SITE_ID = :SITE_ID AND ro.LANGUE_ID = :LANGUE_ID ORDER BY ".$this->listOrder.";";
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $oConnection = Pelican_Db::getInstance();
        $result = $oConnection->queryTab($stmt, $bind);

        // Traduction label dans le tableau
        foreach ($result as $key => $val) {
            $result[$key]['_i18n_TYPE'] = isset(Pelican::$config['REFOUTIL_TYPES'][$val['TYPE']]) ? t(Pelican::$config['REFOUTIL_TYPES'][$val['TYPE']]) : '#'.$val['TYPE'];
        }

        $this->listModel = $result;
    }

    // Sélection des données de l'élément a éditer (formulaire editAction)
    protected function setEditModel()
    {
        $this->aBind[':'.$this->field_id] = $this->id;
        $this->editModel = "SELECT ro.* FROM #pref#_".$this->form_name." ro WHERE ro.".$this->field_id." = :".$this->field_id.";";
    }

    public function listAction()
    {
        $this->showFlashMessage();
        $this->multiLangue = true; // Affiche les onglets de langue

        parent::listAction();

        // Initialisation de la liste
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("cache_object", "<b>".t('RECHERCHER')." :</b>", "");

        // Chargement des données dans la liste
        $table->setValues($this->getListModel(), "id");

        // Paramétrage de la liste
        $table->addColumn(t('ID'), "ID", "10", "left", "", "tblheader", "ID");
        $table->addColumn(t('TYPO_TYPE'), "_i18n_TYPE", "90", "left", "", "tblheader", "TYPE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");

        // Affichage de la liste
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $this->showFlashMessage();

        // Liste des typologies d'outil
        $typologies = array();
        foreach (Pelican::$config['REFOUTIL_TYPES'] as $key => $val) {
            $typologies[$key] = t($val);
        }

        // Récupération de la liste des outils
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $stmt = "SELECT BARRE_OUTILS_ID, BARRE_OUTILS_LABEL FROM #pref#_barre_outils WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID;";
        $oConnection = Pelican_Db::getInstance();
        $outils = $oConnection->queryTab($stmt, $bind);

        $outilsAssoc = array();
        foreach ($outils as $key => $val) {
            $outilsAssoc[$val['BARRE_OUTILS_ID']] = $val['BARRE_OUTILS_LABEL'];
        }

        // Récupération association outils pour la typologie courante
        $bind = array(':TYPO_ID' => $this->values["ID"]);
        $stmt = "SELECT OUTIL_ID, DEVICE FROM #pref#_referentiel_outils_assoc WHERE TYPO_ID = :TYPO_ID";
        $result = $oConnection->queryTab($stmt, $bind);
        $selectedWeb = array();
        $selectedMobile = array();
        foreach ($result as $val) {
            switch ($val['DEVICE']) {
                case 'web':
                    $selectedWeb[] = $val['OUTIL_ID'];
                    break;
                case 'mobile':
                    $selectedMobile[] = $val['OUTIL_ID'];
                    break;
            }
        }

        // Formulaire d'édition
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden("id", $this->values["ID"]);
        $form .= $this->oForm->createComboFromList("TYPE", t('TYPO_TYPE'), $typologies, $this->values['TYPE'], false, $this->readO);

        // $selected = array(39);
        $selected_first = array_shift(array_keys($outilsAssoc));
        $selected = array($selected_first);
        $form .= $this->oForm->createAssocFromList(null, "RATTACHEMENT_OUTIL_WEB", t('REFOUTIL_RATTACHEMENT_OUTIL_WEB'), $outilsAssoc, $selectedWeb, true, true, $this->readO, 8, 350);
        $form .= $this->oForm->createAssocFromList(null, "RATTACHEMENT_OUTIL_MOBILE", t('REFOUTIL_RATTACHEMENT_OUTIL_MOBILE'), $outilsAssoc, $selectedMobile, true, true, $this->readO, 8, 350);

        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        // Vérification duplicate (site, langue, type)
        $bind[':ID'] = Pelican_Db::$values['ID'];
        $bind[':TYPE'] = $oConnection->strToBind(Pelican_Db::$values['TYPE']);
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $stmt = "SELECT ID FROM #pref#_".$this->form_name." WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID AND TYPE = :TYPE AND ID != :ID;";
        $result = $oConnection->queryTab($stmt, $bind);

        // Si duplicate : mémorisation du message d'erreur à afficher
        if (!empty($result)) {
            $_SESSION[APP]['tmp_flash_message'] = array('message' => "Doublon : cette typologie d'outil (#".Pelican_Db::$values['TYPE'].") existe déjà pour ce couple site/langue. Enregistrement annulé.", 'type' => 'danger');

            return;
        }

        // Enregisrement des champs simples (table form_name)
        parent::saveAction();

        // Mise à jour association outils (suppression & enregistrement)
        $bind = array(':TYPO_ID' => Pelican_Db::$values['ID']);
        $stmt = "DELETE FROM #pref#_referentiel_outils_assoc WHERE TYPO_ID = :TYPO_ID";
        $oConnection->query($stmt, $bind);

        $insertAssocStmt = "INSERT INTO #pref#_referentiel_outils_assoc (TYPO_ID, OUTIL_ID, DEVICE) VALUES (:TYPO_ID, :OUTIL_ID, :DEVICE)";
        $bind = array(
            ':TYPO_ID'  => Pelican_Db::$values['ID'],
            ':OUTIL_ID' => null,
            ':DEVICE'   => null,
        );
        if (is_array(Pelican_Db::$values['RATTACHEMENT_OUTIL_WEB'])) {
            $bind[':DEVICE'] = $oConnection->strToBind('web');
            foreach (Pelican_Db::$values['RATTACHEMENT_OUTIL_WEB'] as $val) {
                $bind[':OUTIL_ID'] = intval($val);
                $oConnection->query($insertAssocStmt, $bind);
            }
        }
        if (is_array(Pelican_Db::$values['RATTACHEMENT_OUTIL_MOBILE'])) {
            $bind[':DEVICE'] = $oConnection->strToBind('mobile');
            foreach (Pelican_Db::$values['RATTACHEMENT_OUTIL_MOBILE'] as $val) {
                $bind[':OUTIL_ID'] = intval($val);
                $oConnection->query($insertAssocStmt, $bind);
            }
        }
    }

    // Affichage message d'information
    private function showFlashMessage($display = true)
    {
        if (empty($_SESSION[APP]['tmp_flash_message'])) {
            return;
        }

        $messageClass = isset($_SESSION[APP]['tmp_flash_message']['type']) ? 'alert-'.$_SESSION[APP]['tmp_flash_message']['type'] : '';
        $message = '<div class="alert '.$messageClass.'">'.htmlspecialchars($_SESSION[APP]['tmp_flash_message']['message']).'</div>';
        unset($_SESSION[APP]['tmp_flash_message']);

        if ($display) {
            echo $message;
        } else {
            return $message;
        }
    }
}
