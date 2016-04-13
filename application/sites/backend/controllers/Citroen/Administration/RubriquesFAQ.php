<?php
/**
 * Fichier de Citroen_RubriquesFAQ: 
 *
 * Classe Back-Office de contribution des rubriques FAQ. Les Questions/reponses
 * de la FAQ sont listées à l'intérieur d'une rubrique. Ces rubriques permettent
 * de catégoriser les questions/réponses.
 * 
 * @package Citroen
 * @subpackage Administration
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 20/08/2013
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_RubriquesFAQ_Controller extends Citroen_Controller
{
    //protected $administration = true;
    /* Table utilisée */
    protected $form_name    = 'faq_rubrique';
    /* Champ Identifiant de la table */
    protected $field_id     = 'FAQ_RUBRIQUE_ID';
    /* Champ pour ordonner la liste */
    protected $defaultOrder = 'FAQ_RUBRIQUE_ID';
    /* Activation de la barre de langue */
    protected $multiLangue  = true;
    /* Décache */
    protected $decacheBack  = array(
        array('Frontend/Citroen/Faq/Rubrique', 
            array('SITE_ID', 'LANGUE_ID')
            )
    );
    /**
     * Méthode protégées d'instanciation de la propriété listModel.
     * La méthode instancie listModel avec un tableau de données qui sera utilisé
     * pour afficher la liste de véhicule
     */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindListModel[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBindListModel[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSqlListModel = "
                SELECT 
                    FAQ_RUBRIQUE_ID,
                    SITE_ID,
                    LANGUE_ID,
                    FAQ_RUBRIQUE_LABEL
                FROM 
                    #pref#_{$this->form_name}
                WHERE 
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID ";

        if ($_GET['filter_search_keyword'] != '') {
            $sSqlListModel.= " AND (
             FAQ_RUBRIQUE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
             )
            ";
        }           
        $sSqlListModel .= " ORDER BY {$this->listOrder} ";
          
          $this->listModel = $oConnection->queryTab($sSqlListModel,$aBindListModel);
    }
    
    /**
     * Méthode protégées d'instanciation de la propriété editModel.
     * La méthode instancie editModel avec un tableau de données qui sera utilisé
     * l'instanciation de la propriété 'value'
     */
    protected function setEditModel()
    {
        /* Valeurs Bindées pour la requête */
        $this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':FAQ_RUBRIQUE_ID'] = (int)$this->id;
        
        /* Requête remontant les données du véhicule sélectionnée pour un pays
         * et une langue donnée.
         */
        $sSqlVehiculesForm = <<<SQL
                SELECT 
                    *
                FROM 
                    #pref#_{$this->form_name}
                WHERE 
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND FAQ_RUBRIQUE_ID = :FAQ_RUBRIQUE_ID
                ORDER BY {$this->listOrder}
SQL;
          
        $this->editModel = $sSqlVehiculesForm;
    }
    
    /**
     * Méthode de création de la liste des éléments du formulaire
     */
    public function listAction()
    {
        
        parent::listAction();
        
        /* Initialisation de l'objet List*/
        $oTable = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');+
        $oTable->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $oTable->getFilter(1);

        /* Mise en place des valeurs à utiliser pour le tableau de liste */
        $oTable->setValues($this->getListModel(), $this->field_id);
        /* Création du tableau en utilisant les données du setValues */
        $oTable->addColumn(t('ID'), $this->field_id, '10', 'left', '', 'tblheader', $this->field_id);
        $oTable->addColumn(t('FAQ_RUBRIQUE_LABEL'), 'FAQ_RUBRIQUE_LABEL', '90', 'left', '', 'tblheader', 'FAQ_RUBRIQUE_LABEL');
        $oTable->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => $this->field_id), 'center');
        $oTable->addInput(t('POPUP_LABEL_DEL'), 'button', array('id' => $this->field_id, '' => 'readO=true'), 'center');
        
        /* Affichage du tableau */
        $this->setResponse($oTable->getTable());
    }
    
    /** 
     * Création du formulaire de contribution
     */
    public function editAction()
    {
        
        parent::editAction();
        /* Initialisation du formulaire */
        $sForm = $this->startStandardForm();
        $this->oForm->bDirectOutput = false;
        
        /* Ajout du champ caché indiquant l'identifiant du véhicule*/
        $sForm .= $this->oForm->createLabel(t('ID'), $this->id);
        /* Picto de la rubrique */
        $sForm .= $this->oForm->createMedia('FAQ_RUBRIQUE_PICTO', t('FAQ_RUBRIQUE_PICTO'), true, 'image', '', $this->values['FAQ_RUBRIQUE_PICTO'],$this->readO, true, false, 'carre');
        /* Nom de la rubrique */
        $sForm .= $this->oForm->createInput('FAQ_RUBRIQUE_LABEL', t('FAQ_RUBRIQUE_LABEL'), 255, '', true, $this->values['FAQ_RUBRIQUE_LABEL'], $this->readO, 44);
        
        /* Affichage du formulaire */
        $sForm .= $this->stopStandardForm();
        $sFinalForm = formToString ($this->oForm, $sForm);
        $this->setResponse($sFinalForm);
    }
    
    public function saveAction()
    {
        parent::saveAction();
        Pelican_Cache::clean("Backend/Themes");
    }
}
?>