<?php
/**
 * Fichier de Citroen_Typeformulaire: 
 *
 * Classe Back-Office de contribution des types de formulaires. Des formulaires
 * Front-Office seront listés et typés les valeurs de ce formulaire
 * 
 * @package Citroen
 * @subpackage Administration
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 28/08/2013
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Typeformulaire_Controller extends Citroen_Controller
{
    //protected $administration = true;
    /* Table utilisée */
    protected $form_name    = 'form_type';
    /* Champ Identifiant de la table */
    protected $field_id     = 'FORM_TYPE_ID';
    /* Champ pour ordonner la liste */
    protected $defaultOrder = 'FORM_TYPE_ID';
    /* Activation de la barre de langue */
    protected $multiLangue  = false;
    /* Décache */
//    protected $decacheBack  = array(
//        array('Frontend/Citroen/Faq/Rubrique', 
//            array('SITE_ID', 'LANGUE_ID')
//            )
//    );
    /**
     * Méthode protégées d'instanciation de la propriété listModel.
     * La méthode instancie listModel avec un tableau de données qui sera utilisé
     * pour afficher la liste de véhicule
     */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSqlListModel = "
                SELECT 
                    FORM_TYPE_ID,
                    FORM_TYPE_LABEL
                FROM 
                    #pref#_{$this->form_name} ";

        if ($_GET['filter_search_keyword'] != '') {
            $sSqlListModel.= " WHERE (
            FORM_TYPE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
        }
        $sSqlListModel.= " ORDER BY {$this->listOrder}";
          
          $this->listModel = $oConnection->queryTab($sSqlListModel);
    }
    
    /**
     * Méthode protégées d'instanciation de la propriété editModel.
     * La méthode instancie editModel avec un tableau de données qui sera utilisé
     * l'instanciation de la propriété 'value'
     */
    protected function setEditModel()
    {
        /* Valeurs Bindées pour la requête */
        $this->aBind[':' . $this->field_id ] = (int)$this->id;
        /* Requête remontant les données du véhicule sélectionnée pour un pays
         * et une langue donnée.
         */
        $sSqlForm = <<<SQL
                SELECT 
                    *
                FROM 
                    #pref#_{$this->form_name}
                WHERE 
                    {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
SQL;
        $this->editModel = $sSqlForm;
    }
    
    /**
     * Méthode de création de la liste des éléments du formulaire
     */
    public function listAction()
    {
        
        parent::listAction();
        
        /* Initialisation de l'objet List*/ 
        $oTable = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');

        $oTable->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $oTable->getFilter(1);
        /* Mise en place des valeurs à utiliser pour le tableau de liste */
        $oTable->setValues($this->getListModel(), $this->field_id);
        /* Création du tableau en utilisant les données du setValues */
        $oTable->addColumn(t('ID'), $this->field_id, '10', 'left', '', 'tblheader', $this->field_id);
        $oTable->addColumn(t('FORM_TYPE_LABEL'), 'FORM_TYPE_LABEL', '90', 'left', '', 'tblheader', 'FORM_TYPE_LABEL');
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
        
        /* Ajout du champ caché indiquant l'identifiant du type de formulaire*/
        $sForm .= $this->oForm->createLabel(t('ID'), $this->id);
        /* Nom de la rubrique */
        $sForm .= $this->oForm->createInput('FORM_TYPE_LABEL', t('FORM_TYPE_LABEL2'), 255, '', true, $this->values['FORM_TYPE_LABEL'], $this->readO, 44);
        /* identifiant formulaire GTM */
        $sForm .= $this->oForm->createInput('FORM_TYPE_GTM_ID', t('FORM_TYPE_GTM_ID'), 64, '', false, $this->values['FORM_TYPE_GTM_ID'], $this->readO, 44);
        
        /* Affichage du formulaire */
        $sForm .= $this->stopStandardForm();
        $sFinalForm = formToString ($this->oForm, $sForm);
        $this->setResponse($sFinalForm);
    }
}
?>