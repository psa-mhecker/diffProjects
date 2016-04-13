<?php
/**
 * Scoring des referrer
 * Permet de saisir les score à affecter en fonction du referrer (from-banner)
 *
 * @package DSPP
 * @subpackage Administration
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 * @since 16/11/2015
 */

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_Perso_Referrer_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_referrer_score"; // Table utilisée pour stocker les données
    protected $field_id = "ID";     // Clé primaire de la table
    protected $defaultOrder = "ID"; // Colonne utilisée pour le tri des données dans la liste
    
    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/ReferrerScore')
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/Perso/ReferrerScore')
    );
    
    // Données listing
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $sqlCond = array(
            "ent.SITE_ID = :SITE_ID",
            "(ent.IS_DEFAULT_SCORE IS NULL OR ent.IS_DEFAULT_SCORE != 1)",
        );
        if (!empty($_GET['filter_search_keyword'])) {
            $sqlCond[] = "ent.KEYWORD LIKE ".$oConnection->strToBind('%'.$_GET['filter_search_keyword'].'%');
        }
        $stmt = "
        SELECT *
        FROM #pref#_".$this->form_name." ent
        WHERE ".implode(' AND ', $sqlCond)."
        ORDER BY ".$this->listOrder.";";
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $result = $oConnection->queryTab($stmt, $bind);
        $result = is_array($result) ? $result : array();
        foreach ($result as $key => $val) {
            $result[$key]['date_maj'] = !empty($val['UPDATED']) ? date('d/m/Y H:i:s', $val['UPDATED']) : null;
        }
        $this->listModel = $result;
    }

    // Listing
    public function listAction()
    {
        $this->showFlashMessage();
        parent::listAction();
        
        // Initialisation de la liste
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);

        // Chargement des données dans la liste
        $table->setValues($this->getListModel(), "id");

        // Paramétrage de la liste
        $table->addColumn(t('ID'), "ID", "10", "left", "", "tblheader", "ID");
        $table->addColumn(t('REFERRER'), "KEYWORD", "90", "left", "", "tblheader", "KEYWORD");
        $table->addColumn(t('SCORE'), "SCORE", "90", "left", "", "tblheader", "SCORE");
        $table->addColumn(t('DATE_MAJ'), "date_maj", "90", "left", "", "tblheader", "UPDATED");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");

        // Affichage de la liste
        $defaultBtnMarkup = <<<'EOT'
<form action="" method="get">
    <input type="hidden" name="tid" value="%s" />
    <button name="id" value="default" type="submit" class="button">%s</button>
</form>
<br/>
EOT;
        $defaultBtn = sprintf($defaultBtnMarkup, $_GET['tid'], t('EDIT_DEFAULT_SCORE'));
        $this->setResponse($defaultBtn.$table->getTable());
    }

    // Données d'édition
    protected function setEditModel()
    {
        if ($this->id == 'default') {
            $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $this->editModel = "
            SELECT ent.*
            FROM #pref#_".$this->form_name." ent
            WHERE ent.SITE_ID = :SITE_ID
              AND ent.IS_DEFAULT_SCORE = 1
            ORDER BY UPDATED DESC LIMIT 0,1";
        } else {
            $this->aBind[':'.$this->field_id] = $this->id;
            $this->editModel = "
            SELECT ent.*
            FROM #pref#_".$this->form_name." ent
            WHERE ent.".$this->field_id." = :".$this->field_id.";";
        }
    }

    // Formulaire d'édition
    public function editAction()
    {
        parent::editAction();
        $this->showFlashMessage();
        $oConnection = Pelican_Db::getInstance();
        
        // Édition du score par défaut (bouton "Modifier le score par défaut")
        if ($this->id == 'default') {
            return $this->editDefaultScore();
        }
        
        // Contrôle édition score par défaut
        if ($this->values['IS_DEFAULT_SCORE']) {
            $_SESSION[APP]['tmp_flash_message'] = array('message' => "Merci d'utiliser le bouton \"Modifier le score par défaut\" pour éditer le score par défaut", 'type' => 'danger');
            $this->showFlashMessage();
            return;
        }
        
        // Récupération du score par défaut
        $stmt = "
        SELECT *
        FROM #pref#_".$this->form_name." ent
        WHERE ent.SITE_ID = :SITE_ID
          AND ent.IS_DEFAULT_SCORE = 1
        ORDER BY ".$this->listOrder.";";
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $oConnection = Pelican_Db::getInstance();
        $result = $oConnection->queryTab($stmt, $bind);
        $result = is_array($result) ? $result : array();
        $first = array_shift($result);
        $defaultScore = $first['SCORE'];

        // Formulaire d'édition
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden("ID", $this->values["ID"]);
        $form .= $this->oForm->createHidden("SITE_ID", $_SESSION[APP]["SITE_ID"]);
        $form .= $this->oForm->createInput("KEYWORD", t("Referrer"), 250, "", true, $this->values["KEYWORD"], $this->readO, 50);
        $form .= $this->oForm->createInput("SCORE", t('SCORE'), 4, "", false, $this->values['SCORE'], $this->readO, 4);
        $form .= $this->oForm->createCheckBoxFromList("USE_DEFAULT_SCORE", t('USE_DEFAULT_SCORE').' ('.$defaultScore.')', array(1 => ""), $this->values['USE_DEFAULT_SCORE'], false, $this->readO);
        $form .= $this->stopStandardForm();

        $this->setResponse($form);
    }
    
    // Formulaire d'édition du score par défaut
    public function editDefaultScore()
    {
        if (empty($this->values["ID"])) {
            $this->values["ID"] = -2;
        }
        
        // Formulaire d'édition
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden("ID", $this->values["ID"]);
        $form .= $this->oForm->createHidden("SITE_ID", $_SESSION[APP]["SITE_ID"]);
        $form .= $this->oForm->createHidden("IS_DEFAULT_SCORE", 1);
        $form .= $this->oForm->createInput("SCORE", t('SCORE_PAR_DEFAUT'), 4, "", false, $this->values['SCORE'], $this->readO, 4);
        $form .= '<tr><td colspan="2">'.t('SCORE_PAR_DEFAUT_AIDE').'</td></tr>';
        $form .= $this->stopStandardForm();

        $this->setResponse($form);
    }
    
    // Enregistrement du formulaire
    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        
        // Contrôle format score (format attendu : float)
        if (!empty(Pelican_Db::$values['SCORE']) && !is_numeric(Pelican_Db::$values['SCORE'])) {
            $_SESSION[APP]['tmp_flash_message'] = array('message' => sprintf("Le score saisi (%s) n'est pas valide, enregistrement annulé", Pelican_Db::$values['SCORE']), 'type' => 'danger');
            return;
        }
        
        // Mode édition du score par défaut
        if (Pelican_Db::$values['IS_DEFAULT_SCORE'] == 1) {
            if (Pelican_Db::$values['ID'] == -2) {
                $stmt = "INSERT INTO #pref#_".$this->form_name." (`SITE_ID`, `UPDATED`, `IS_DEFAULT_SCORE`, `SCORE`) VALUES (:SITE_ID, :UPDATED, :IS_DEFAULT_SCORE, :SCORE)";
                $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                $bind[':UPDATED'] = time();
                $bind[':IS_DEFAULT_SCORE'] = 1;
                $bind[':SCORE'] = Pelican_Db::$values['SCORE'];
                $result = $oConnection->query($stmt, $bind);
            } else {
                $stmt = "UPDATE #pref#_".$this->form_name." SET SCORE = :SCORE, UPDATED = :UPDATED WHERE ID = :ID";
                $bind[':ID'] = Pelican_Db::$values['ID'];
                $bind[':SCORE'] = Pelican_Db::$values['SCORE'];
                $bind[':UPDATED'] = time();
                $result = $oConnection->query($stmt, $bind);
            }
            return;
        }
        
        Pelican_Db::$values['UPDATED'] = time();
        parent::saveAction();
    }
}
