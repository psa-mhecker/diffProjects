<?php
/**
 * Fichier de Citroen_Formulaire: 
 *
 * Classe Back-Office de contribution des formulaires qui seront présentés sur
 * le site. Ces "formulaires" vont en fait gérer l'appel à des WebServices qui
 * gèreront l'affichage des formulaires HTML
 * 
 * @package Citroen
 * @subpackage Administration
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 28/08/2013
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Formulaire_Controller extends Citroen_Controller
{

    //protected $administration = true;
    /* Table utilisée */
    protected $form_name    = 'form';
    /* Champ Identifiant de la table */
    protected $field_id     = 'FORM_ID';
    /* Champ pour ordonner la liste */
    protected $defaultOrder = 'FORM_ID';
    /* Activation de la barre de langue */
    protected $multiLangue  = false;
    /* Décache */
//    protected $decacheBack  = array(
//        array('Frontend/Citroen/Faq/Rubrique', 
//            array('SITE_ID', 'LANGUE_ID')
//            )
//    );
    protected $decacheBack = array(
        array('Frontend/Citroen/Formulaire')
    );
    protected $decachePublication = array(
        array('Frontend/Citroen/Formulaire')
    );
    /**
     * Méthode protégées d'instanciation de la propriété listModel.
     * La méthode instancie listModel avec un tableau de données qui sera utilisé
     * pour afficher la liste de véhicule
     */
    protected function setListModel()
    {
       
       
        /* Connexion à la bdd */
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        $sFilterPrefix = 'filter_';
        $sFieldFormTypeId = 'FORM_TYPE_ID';
        $sFieldFormEqptCode = 'FORM_EQUIPEMENT_CODE';
        $sFieldFormUserTypeCode = 'FORM_USER_TYPE_CODE';
        $sFieldLangueId = 'LANGUE_ID';
        $sFieldSiteId = 'SITE_ID';
        $aBind = array();
        $sAddAnd = '';
        
         /** Gestion des filtres */
        /* Ajout du filtre sur la langue */
        if ( isset($_GET[$sFilterPrefix . $sFieldLangueId]) 
                && !empty($_GET[$sFilterPrefix . $sFieldLangueId]) ) {
            $aBind[':' . $sFieldLangueId] = $_GET[$sFilterPrefix . $sFieldLangueId];
        }
        if ( isset($_GET[$sFilterPrefix . $sFieldSiteId])
            && !empty($_GET[$sFilterPrefix . $sFieldSiteId]) ) {
            $aBind[':' . $sFieldSiteId] = $_GET[$sFilterPrefix . $sFieldSiteId];
        }
        
        
        /* Ajout du filtre sur les type de formulaire */
        if ( isset($_GET[$sFilterPrefix . $sFieldFormTypeId]) 
                && !empty($_GET[$sFilterPrefix . $sFieldFormTypeId]) ) {
            $aBind[':' . $sFieldFormTypeId] = (int)$_GET[$sFilterPrefix . $sFieldFormTypeId];
        }
        /* Ajout du filtre sur les type d'équipement */
        if ( isset($_GET[$sFilterPrefix . $sFieldFormEqptCode]) 
                && !empty($_GET[$sFilterPrefix . $sFieldFormEqptCode]) ) {
            $aBind[':' . $sFieldFormEqptCode] = $oConnection->strToBind((string)$_GET[$sFilterPrefix . $sFieldFormEqptCode]);
        }
        /* Ajout du filtre sur les type d'internautes */
        if ( isset($_GET[$sFilterPrefix . $sFieldFormUserTypeCode]) 
                && !empty($_GET[$sFilterPrefix . $sFieldFormUserTypeCode]) ) {
            $aBind[':' . $sFieldFormUserTypeCode] = $oConnection->strToBind((string)$_GET[$sFilterPrefix . $sFieldFormUserTypeCode]);
        }
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSqlListModel = <<<SQL
                SELECT 
                    f.{$this->field_id},
                    f.SITE_ID,
                    f.FORM_LABEL,
                    f.FORM_INCE_CODE,
                    f.FORM_EQUIPEMENT_CODE,
                    f.FORM_USER_TYPE_CODE,
                    f.FORM_CONTEXT_CODE,
                    ft.FORM_TYPE_LABEL,
                    l.LANGUE_LABEL
                FROM 
                    #pref#_{$this->form_name} f
                        INNER JOIN #pref#_form_type ft ON (f.FORM_TYPE_ID = ft.FORM_TYPE_ID)
                        INNER JOIN #pref#_language l ON (f.LANGUE_ID = l.LANGUE_ID)
SQL;
        
        
        $sSqlListModel .= <<<SQL
                ORDER BY {$this->listOrder}
SQL;
                
        /*$aListResult = $oConnection->queryTab($sSqlListModel,$aBind);
        
        /* Parcours du tableau pour modifier les valeurs présentes dans des tableaux
         * de configuration
         
        if ( is_array($aListResult) && !empty($aListResult) ){
            
            foreach ($aListResult as $key => $aOneElmt ){
                if ( $key == $sFieldFormEqptCode ){
                    $aOneElmt = Pelican::$config['FORM']['EQUIPMENT'][$aOneElmt['FORM_EQUIPEMENT_CODE']];
                }
                if ( $key == $sFieldFormUserTypeCode ){
                    $aOneElmt = Pelican::$config['FORM']['USER_TYPE'][$aOneElmt['FORM_USER_TYPE_CODE']];
                }
                $aTemp[$key] = $aOneElmt;
            }
            $aListResult = $aTemp;
        }else{
            $aListResult = array();
        }*/
        
                
       
        $this->listModel = $sSqlListModel;
      
        
    }
    
    /**
     * Méthode protégées d'instanciation de la propriété editModel.
     * La méthode instancie editModel avec un tableau de données qui sera utilisé
     * l'instanciation de la propriété 'value'
     */
    protected function setEditModel()
    {        
        /* Valeurs Bindées pour la requête */
        $this->aBind[':' . $this->field_id] = (int)$this->id;
        
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
        
        /* Création des filtres */
        
        //$oTable->setFilterField("site", "<b>" . t('SITE') . "&nbsp;:</b><br />", "#pref#_form.SITE_ID", "select #pref#_site.SITE_ID as id, SITE_LABEL as lib FROM #pref#_site ORDER BY SITE_LABEL");
        
       $oTable->setFilterField('FORM_EQUIPEMENT_CODE', t('FORM_EQUIPEMENT'), 'f.FORM_EQUIPEMENT_CODE', self::createArrayFilter(self::getComboFormat(Pelican::$config['FORM']['EQUIPMENT'])), self::getComboFormat(Pelican::$config['FORM']['EQUIPMENT']));
      
        $oTable->setFilterField('FORM_TYPE_ID', t('FORM_TYPE_LABEL2'), 'ft.FORM_TYPE_ID', self::createArrayFilter(self::getFormType()));
        
        $oTable->setFilterField('FORM_USER_TYPE_CODE', t('FORM_USER_TYPE'), 'f.FORM_USER_TYPE_CODE', self::createArrayFilter(self::getComboFormat(Pelican::$config['FORM']['USER_TYPE'])),self::getComboFormat(Pelican::$config['FORM']['USER_TYPE']));
        $oTable->setFilterField('LANGUE_ID', t('LABEL_TRAD_LANG_BO'), 'l.LANGUE_ID', self::createArrayFilter(self::getLanguages()),self::getLanguages());
        $oTable->setFilterField('SITE_ID', t('LABEL_TRAD_SITE_BO'), 'f.SITE_ID', self::createArrayFilter(self::getSite()),self::getSite());
        $oTable->getFilter(4);
        
        /* Mise en place des valeurs à utiliser pour le tableau de liste */
        $oTable->setValues($this->getListModel(), $this->field_id);
        /* Création du tableau en utilisant les données du setValues */
        $oTable->addColumn(t('ID'), $this->field_id, '45', 'left', '', 'tblheader', $this->field_id);          
        $oTable->addColumn(t('FORM_EQUIPEMENT'), 'FORM_EQUIPEMENT_CODE', '90', 'left', '', 'tblheader', 'FORM_EQUIPEMENT_CODE');
        $oTable->addColumn(t('FORM_TYPE_LABEL'), 'FORM_TYPE_LABEL', '90', 'left', '', 'tblheader', 'FORM_TYPE_LABEL');   
        $oTable->addColumn(t('FORM_USER_TYPE_CODE'), 'FORM_USER_TYPE_CODE', '90', 'left', '', 'tblheader', 'FORM_USER_TYPE_CODE');
        $oTable->addColumn(t('FORM_CONTEXT_CODE'), 'FORM_CONTEXT_CODE', '90', 'left', '', 'tblheader', 'FORM_CONTEXT_CODE');
        $oTable->addColumn(t('LABEL_TRAD_LANG_BO'), 'LANGUE_LABEL', '90', 'left', '', 'tblheader', 'LANGUE_LABEL');
        
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
        /* Equipement */
        $sForm .= $this->oForm->createComboFromList('FORM_EQUIPEMENT_CODE', t('FORM_EQUIPEMENT'), Pelican::$config['FORM']['EQUIPMENT'], $this->values['FORM_EQUIPEMENT_CODE'], true, $this->readO);
        /* Titre interne */
        $sForm .= $this->oForm->createInput('FORM_LABEL', t('FORM_INT_LABEL'), 255, '', true, $this->values['FORM_LABEL'], $this->readO, 44);
        /* Type de formulaire */
        $sForm .= $this->oForm->createComboFromList('FORM_TYPE_ID', t('FORM_TYPE_LABEL2'), self::getFormType(), $this->values['FORM_TYPE_ID'], true, $this->readO);
        /* Type internaute */
        $sForm .= $this->oForm->createComboFromList('FORM_USER_TYPE_CODE', t('FORM_USER_TYPE'), Pelican::$config['FORM']['USER_TYPE'], $this->values['FORM_USER_TYPE_CODE'], true, $this->readO);
        /* Langue */
        $sForm .= $this->oForm->createComboFromList('LANGUE_ID', t('LABEL_TRAD_LANG_BO'), self::getLanguages(), $this->values['LANGUE_ID'], true, $this->readO);        
        /* Site */
        $sForm .= $this->oForm->createComboFromList('SITE_ID', t('FORM_SITE'), self::getSite(), $this->values['SITE_ID'], true, $this->readO);                
        /* Contextualisation */
        $sForm .= $this->oForm->createComboFromList('FORM_CONTEXT_CODE', t('FORM_CONTEXT_CODE'), Pelican::$config['FORM']['CONTEXT'], $this->values['FORM_CONTEXT_CODE'], true, $this->readO);
        /* Code instance de formulaire INCE */
        $sForm .= $this->oForm->createInput('FORM_INCE_CODE', t('FORM_INCE_CODE'), 255, '', true, $this->values['FORM_INCE_CODE'], $this->readO, 44);
        /* Paramètre supplémentaire au formulaire */
        $sForm .= $this->oForm->createInput('FORM_PARAMS', t('FORM_PARAMS'), 255, '', false, $this->values['FORM_PARAMS'], $this->readO, 44);
        /* GDO marketing code */
        $sForm .= $this->oForm->createInput('FORM_GDO_MARKETING_CODE', t('FORM_GDO_MARKETING_CODE'), 255, '', false, $this->values['FORM_GDO_MARKETING_CODE'], $this->readO, 44);

/*
        $this->values['CONTENT_TITLE2'] = $this->values['FORM_ML_TYPE'];
        $this->values['CONTENT_TITLE3'] = $this->values['FORM_ML_TITRE'];
        $this->values['CONTENT_TEXT'] = $this->values['FORM_ML_TEXTE'];
        $this->values['MEDIA_ID2'] = $this->values['FORM_ML_MEDIA'];
        $this->values['CONTENT_TITLE4'] = $this->values['FORM_ML_LIEN_PAGE'];
        $aModeAffichage = Pelican::$config['TRANCHE_COL']["MODE_AFF"];
        $sForm .= $this->oForm->createComboFromList("FORM_MODE_AFF", t("MODE_AFFICHAGE"), $aModeAffichage, ($this->values['FORM_ID'] == -2)?'NEUTRE':$this->values['FORM_MODE_AFF'], true, $this->readO);
        $sForm .= $this->oForm->createInput ("FORM_TITRE", t ('TITRE'), 255, "", false, $this->values["FORM_TITRE"], $this->read0, 100);
        $sForm .= $this->oForm->createEditor("FORM_CHAPO", t('CHAPEAU'), false, $this->values["FORM_CHAPO"], $this->readO, true, "", 650, 150);
        $sForm .= $this->oForm->createInput ("FORM_TITRE_THANKS", t ('TITRE'), 255, "", false, $this->values["FORM_TITRE_THANKS"], $this->read0, 100);
        $sForm .= $this->oForm->createEditor("FORM_TEXTE_THANKS", t('MESSAGE_REMERCIEMENT'), false, $this->values["FORM_TEXTE_THANKS"], $this->readO, true, "", 650, 150);
        $sForm .= $this->oForm->createInput ("FORM_TITRE_SHARE", t ('TITRE_SHARER'), 255, "", false, $this->values["FORM_TITRE_SHARE"], $this->read0, 100);
        $sForm .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($this, 'FORM_SHARE', $defaut = 'PUBLIC', $obligatoire = true, true);
        $sForm .= Backoffice_Form_Helper::getMentionsLegales($this, true);
        $multiValues = array();
        if($this->id != '-2'){
            $oConnection = Pelican_Db::getInstance();
            $aBind[":FORM_ID"] = $this->id;

            $SQL = "
                SELECT
                  *
                FROM
                  #pref#_form_multi
                WHERE
                  FORM_ID = :FORM_ID
                ORDER BY FORM_MULTI_ORDER";

            $multiValues = $oConnection->queryTab($SQL, $aBind);
        }
        $sForm .= $this->oForm->createMultiHmvc("CTAFORM", t('ADD_FORM_CTA'), array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "ctaAddForm"
            ), $multiValues,  "CTAFORM", $this->readO, '', true, true, "CTAFORM");

*/


        /* Affichage du formulaire */
        $sForm .= $this->stopStandardForm();
        $sFinalForm = formToString ($this->oForm, $sForm);
        $this->setResponse($sFinalForm);
    }

    public static function ctaAddForm($oForm, $values, $readO, $multi)
    {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];

        $return = $oForm->createInput ($multi . "FORM_MULTI_LABEL", t ( 'LIBELLE' ), 40, "", true, $values["FORM_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "FORM_MULTI_URL_WEB", t ( 'URL_WEB' ), 255, "internallink", true, $values["FORM_MULTI_URL_WEB"], $readO, 100);
        $return .= $oForm->createInput ($multi . "FORM_MULTI_URL_MOBILE", t ( 'URL_MOB' ), 255, "internallink", false, $values["FORM_MULTI_URL_MOBILE"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "FORM_MULTI_MODE_OPEN", t("MODE_OUVERTURE"), $aDataValues, strtoupper($values["FORM_MULTI_MODE_OPEN"]), true, $readO);

        return $return;
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values['FORM_ML_TYPE'] = Pelican_Db::$values['CONTENT_TITLE2'];
        Pelican_Db::$values['FORM_ML_TITRE'] = Pelican_Db::$values['CONTENT_TITLE3'];
        Pelican_Db::$values['FORM_ML_TEXTE'] = Pelican_Db::$values['CONTENT_TEXT'];
        Pelican_Db::$values['FORM_ML_MEDIA'] = Pelican_Db::$values['MEDIA_ID2'];
        Pelican_Db::$values['FORM_ML_LIEN_PAGE'] = Pelican_Db::$values['CONTENT_TITLE4'];
        parent::saveAction();
        readMulti("CTAFORM", "CTAFORM");
        $aBind[':FORM_ID'] = Pelican_Db::$values['FORM_ID'];
        $sSQL = "delete from #pref#_form_multi where FORM_ID=:FORM_ID";
        $oConnection->query($sSQL, $aBind);
        $k = 0;
        if (Pelican_Db::$values['CTAFORM']) {
            foreach (Pelican_Db::$values['CTAFORM'] as $item) {
                if ($item['multi_display'] == 1) {
                    $DBVALUES_SAVE = Pelican_Db::$values;
                    $k++;
                    Pelican_Db::$values['FORM_MULTI_ID'] = -2;
                    Pelican_Db::$values['FORM_MULTI_LABEL'] = $item['FORM_MULTI_LABEL'];
                    Pelican_Db::$values['FORM_MULTI_URL_WEB'] = $item['FORM_MULTI_URL_WEB'];
                    Pelican_Db::$values['FORM_MULTI_URL_MOBILE'] = $item['FORM_MULTI_URL_MOBILE'];
                    Pelican_Db::$values['FORM_MULTI_MODE_OPEN'] = $item['FORM_MULTI_MODE_OPEN'];
                    Pelican_Db::$values['FORM_MULTI_ORDER'] = $k;
                    $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_form_multi");
                    Pelican_Db::$values = $DBVALUES_SAVE;
                }
            }
        }
		Pelican_Cache::clean("Frontend/Citroen/FormType");
		Pelican_Cache::clean("Frontend/Citroen/Formulaire"); 
    }

    
        /** 
     * Création du tableau pour remonter les S.S des filtes
     */
    public static function createArrayFilter($aValues)
    {
        $i = 0;
     
        foreach($aValues as  $key=>$type)
        {
            $j = 0;
            $essai[$j] = $key;
            $j++;
            $essai[$j] = $type;
            $aWM[$i] = $essai;
            $i++;
        }
        
        return $aWM;
    }
    
    
    /**
     * Reformattage du tableau passé en paramètre pour qu'il corresponde à la 
     * forme du tableau des filtresde configuration des équipements pour créer le combo 
     * @param string[]  $aValues  
     * @return array    $aResult
     */
    public static function getComboFormat($aValues)
    {
        /* Initialisation des variables */
        $aResult = array();
        $aLine = array();
        
        if ( is_array($aValues) && !empty($aValues) ){
            $i=0;
            foreach($aValues as $key => $value){
                $aLine[$key] = $value;
            }
            $aResult = $aLine;
        }
        
        return $aResult;
    }
    
    /**
     * Méthode statique remontant les types de formulaire
     */
    public static function getFormType()
    {
        /* Initialisation des variables */
        $aResult = array();
        
        $oConnection = Pelican_Db::getInstance();
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSqlFormTypes = <<<SQL
                SELECT 
                    FORM_TYPE_ID as id,
                    FORM_TYPE_LABEL as lib
                FROM 
                    #pref#_form_type
                ORDER BY FORM_TYPE_ID
SQL;
        $aFormTypes = $oConnection->queryTab($sSqlFormTypes);
        if ( is_array($aFormTypes) && !empty($aFormTypes) ){
            foreach($aFormTypes as $form){
                $aResult[$form['id']] = $form['lib'];
            }            
        }
          
        return $aResult;
    }
    
    /**
     * Méthode statique remontant les Sites
     */
    public static function getSite()
    {
        /* Initialisation des variables */
        $aResult = array();
        
        $oConnection = Pelican_Db::getInstance();
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSql = <<<SQL
                SELECT 
                    SITE_ID as id,
                    SITE_LABEL as lib
                FROM 
                    #pref#_site
                WHERE SITE_ID != 1
                ORDER BY id
SQL;
        $aTemp = $oConnection->queryTab($sSql);
        if ( is_array($aTemp) && !empty($aTemp) ){
            foreach($aTemp as $form){
                $aResult[$form['id']] = $form['lib'];
            } 
        }
          
        return $aResult;
    }
    
    /**
     * Méthode statique remontant les langues
     */
    public static function getLanguages()
    {
        /* Initialisation des variables */
        $aResult = array();
        
        $oConnection = Pelican_Db::getInstance();
        
        /* Requête remontant l'ensemble des véhicules pour un site 
         * et une langue donnée.
         */
        $sSql = <<<SQL
                SELECT 
                    LANGUE_ID id,
                    LANGUE_LABEL lib
                FROM 
                    #pref#_language
                WHERE 
                    LANGUE_CPP = 1
                ORDER BY lib
SQL;
        $aTemp = $oConnection->queryTab($sSql);
        if ( is_array($aTemp) && !empty($aTemp) ){
            foreach($aTemp as $form){
                $aResult[$form['id']] = $form['lib'];
            } 
        }
          
        return $aResult;
    }
}
?>