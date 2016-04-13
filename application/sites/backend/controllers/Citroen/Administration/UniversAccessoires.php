<?php
/**
 * Définition du visuel pour les accessoires
 * Le formulaire permet de définir un visuel pour chaque univers accessoire,
 * remplaçant ainsi le visuel par défaut fourni par le webservice accessoires.
 * 
 * @package Citroen
 * @subpackage Administration
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 * @since 12/02/2015
 */

use Citroen\Accessoires;

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_UniversAccessoires_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "univers_accessoires"; // Table utilisée pour stocker la liste des caches
    protected $field_id = "code";                 // Identifiant d'un élément de la liste
    protected $defaultOrder = "label";            // Champ par défaut pour le tri des données dans la liste
    protected $decacheBack = array(
        array('Frontend/Citroen/Accessoires/VisuelUnivers')
    );
    
    /** Sélection des données de la liste (listAction) */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        
        // Récupération de la liste des accessoires
        $codePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(":SITE_ID" => $_SESSION[APP]['SITE_ID']));
        $languageCode = strtolower($_SESSION[APP]['LANGUE_CODE']) . '_' . $codePays;
        $univers = Accessoires::getCriteriaValues("Universes", $languageCode);
        
        // Récupération des visuel associés aux accessoires (indexé par le code accessoire)
        $stmt = "SELECT ua.ID, ua.MEDIA_ID, ua.CODE, m.MEDIA_PATH
        FROM #pref#_".$this->form_name." ua
        LEFT JOIN #pref#_media m ON m.MEDIA_ID = ua.MEDIA_ID
        WHERE ua.SITE_ID = :SITE_ID AND ua.LANGUE_ID = :LANGUE_ID;";
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $result = $oConnection->queryTab($stmt, $bind);
        $visuels = array();
        foreach ($result as $key => $val) {
            $visuels[$val['CODE']] = $val;
        }
        
        // Assemblage du tableau de liste
        $listModel = array();
        if (is_array($univers['universes'])) {
            foreach ($univers['universes'] as $universkey => $univers) {
                foreach ($univers as $key => $val) {
                    unset($val['subUniverses']);
                    $imgUrl = !empty($visuels[$val['code']]['MEDIA_PATH']) ? Pelican::$config['MEDIA_HTTP'].$visuels[$val['code']]['MEDIA_PATH'] : Pelican::$config['DOCUMENT_HTTP'].'/images/default_image_univers_accessoire.jpg';
                    $val['order']      = (string) $val['order'];
                    $val['MEDIA_ID']   = isset($visuels[$val['code']]) ? $visuels[$val['code']]['MEDIA_ID'] : null;
                    $val['MEDIA_PATH'] = isset($visuels[$val['code']]) ? $visuels[$val['code']]['MEDIA_PATH'] : null;
                    $val['_img_tag']   = '<img width="100" style="padding:10px;" src="'.$imgUrl.'" />';
                    $val['_ua_id']     = isset($visuels[$val['code']]) ? $visuels[$val['code']]['ID'] : null;
                    $listModel[] = $val;
                }
            }
        }
        
        $this->listModel = $listModel;
    }
    
    /** Sélection des données de l'élément a éditer (formulaire editAction) */
    protected function setEditModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = $oConnection->strToBind($this->id);
        $this->editModel = "SELECT ua.* FROM #pref#_".$this->form_name." ua WHERE ua.SITE_ID = :SITE_ID AND ua.LANGUE_ID = :LANGUE_ID AND ua.".$this->field_id." = :".$this->field_id.";";
    }

    /** Tableau de liste des éléments à éditer */
    public function listAction()
    {
        $this->showFlashMessage();
        $this->multiLangue = true;
        parent::listAction();
        
        // Masquage bouton ajouter (il n'a aucun sens ici puisque les univers viennent d'un webservice, en lecture seule)
        $this->aButton["add"] = null;
        Backoffice_Button_Helper::init($this->aButton);
        
        // Initialisation de la liste
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("cache_object", "<b>" . t('RECHERCHER') . " :</b>", "");
        
        // Tri des données de la liste
        $listModel = $this->getListModel();
        preg_match('/^(.*?)(?:\s(DESC|ASC)?|$)/i', $this->listOrder, $matches);
        $sortKey = !empty($matches[1]) ? $matches[1] : $this->defaultOrder;
        $sortOrder = !empty($matches[2]) ? $matches[2] : 'ASC';
        usort($listModel, function ($a, $b) use ($sortKey, $sortOrder) {
            if (!isset($a[$sortKey]) || !isset($b[$sortKey])) {
                return 0;
            }
            if ($a[$sortKey] == $b[$sortKey]) {
                return 0;
            }
            $result = $a[$sortKey] < $b[$sortKey] ? -1 : 1;
            if ($sortOrder == 'DESC') {
                $result *= -1;
            }
            return $result;
        });
        
        // Chargement des données dans la liste
        $table->setValues($listModel, "id");
        
        // Paramétrage de la liste
        $table->addColumn(t('UNIVERS_ACCESSOIRE_IMAGE'), "_img_tag", "10", "left", "", "tblheader", "_img_tag");
        $table->addColumn(t('UNIVERS_ACCESSOIRE_LABEL'), "label", "90", "left", "", "tblheader", "label");
        $table->addColumn(t('UNIVERS_ACCESSOIRE_CODE'), "code", "30", "left", "", "tblheader", "code");
        $table->addColumn(t('UNIVERS_ACCESSOIRE_ORDER'), "order", "10", "left", "", "tblheader", "order");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "code"), "center");
        
        // Affichage de la liste
        $this->setResponse($table->getTable());
    }

    /** Formulaire d'édition */
    public function editAction()
    {
        $this->showFlashMessage();
        parent::editAction();
        
        // Récupération données du webservice (en ne gardant que l'élément édité)
        $listModel = $this->getListModel();
        $data = array();
        foreach ($listModel as $key => $val) {
            if ($val['code'] == $this->id) {
                $data = $val;
                break;
            }
        }
        
        // Formulaire d'édition
        $form = $this->startStandardForm();
        $form .= $this->oForm->createHidden("_ua_id", $data["_ua_id"]);
        $form .= $this->oForm->createLabel(t('UNIVERS_ACCESSOIRE_LABEL'), $data['label']);
        $form .= $this->oForm->createLabel(t('UNIVERS_ACCESSOIRE_CODE'), $data['code']);
        $form .= $this->oForm->createLabel(t('UNIVERS_ACCESSOIRE_ORDER'), $data['order']);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('VISUEL'), false, "image", "", $this->values['MEDIA_ID'], $readO, true, false);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    /** Enregistrement du formulaire d'édition */
    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $action = empty(Pelican_Db::$values['MEDIA_ID']) ? 'remove' : 'set';
        if ($action == 'set') {
            $stmt = "INSERT INTO #pref#_univers_accessoires (SITE_ID, LANGUE_ID, CODE, MEDIA_ID)
            VALUES (:SITE_ID, :LANGUE_ID, :CODE, :MEDIA_ID)
            ON DUPLICATE KEY UPDATE MEDIA_ID = :MEDIA_ID;";
            $bind = array(
                ':SITE_ID'   => $_SESSION[APP]['SITE_ID'],
                ':SITE_ID'   => $_SESSION[APP]['SITE_ID'],
                ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
                ':CODE'      => $oConnection->strToBind(Pelican_Db::$values['code']),
                ':MEDIA_ID'  => Pelican_Db::$values['MEDIA_ID'],
            );
            $oConnection->query($stmt, $bind);
            $_SESSION[APP]['tmp_flash_message'] = array('message' => t("UNIVERS_ACCESSOIRE_SAVE_SUCCESS"), 'type' => 'success');
        } elseif ($action == 'remove') {
            if (empty(Pelican_Db::$values['_ua_id'])) {
                return;
            }
            $stmt = "DELETE FROM #pref#_univers_accessoires WHERE ID = :ID;";
            $bind = array(':ID' => Pelican_Db::$values['_ua_id']);
            $oConnection->query($stmt, $bind);
            $_SESSION[APP]['tmp_flash_message'] = array('message' => t("UNIVERS_ACCESSOIRE_DELETE_SUCCESS"), 'type' => 'success');
        }
    }
}
