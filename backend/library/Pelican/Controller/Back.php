<?php
/**
 * .
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
pelican_import('Controller');
include_once pelican_path('Form');
pelican_import('List');
define('NO_TITLE', 'no_title');

/**
 * .
 *
 * @author Raphael Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Controller_Back extends Pelican_Controller
{
    /**
     * .
     *
     * @access public
     *
     * @var Pelican_Form
     */
    public $oForm;

    /**
     * .
     *
     * @access public
     *
     * @var string
     */
    public $multi;

    /**
     * .
     *
     * @access protected
     */
    protected $administration = false;

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $form_name = "";

    /**
     * .
     *
     * @access public
     *
     * @var string
     */
    public $form_action = '';

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $field_id = "";

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $defaultOrder = "";

    /**
     * .
     *
     * @static 
     * @access protected
     *
     * @var array
     */
    protected $processus = '';

    /**
     * .
     *
     * @static 
     * @access protected
     *
     * @var mixed
     */
    protected $decacheBack = '';
    
        /**
     * .
     *
     * @static 
     * @access protected
     *
     * @var array
     */
    protected $decacheBackOrchestra = [];

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $listDecache = array();
    
        /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $listDecacheOrchestra = array();

    /**
     * .
     *
     * @access protected
     */
    protected $aButton = array();

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $aBind = array();

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $aBindLob = array();

    /**
     * .
     *
     * @access public
     *
     * @var array
     */
    public $values = array();

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $form_database = '';

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $form_retour = '';

    /**
     * .
     *
     * @access public
     *
     * @var string
     */
    public $id = '';

    /**
     * .
     *
     * @access public
     *
     * @var bool
     */
    public $readO = false;

    /**
     * .
     *
     * @access protected
     *
     * @var bool
     */
    protected $show = true;

    /**
     * .
     *
     * @access public
     *
     * @var bool
     */
    public static $securityChecked = false;

    /**
     * .
     *
     * @access public
     *
     * @var int
     */
    public $indexBind = 1;

    /**
     * .
     *
     * @access public
     *
     * @var array
     */
    public $sauve;

    /**
     * **************.
     */

    /**
     * .
     *
     * @access protected
     *
     * @var bool
     */
    protected $bNoDelete = false;

    /**
     * .
     *
     * @access protected
     *
     * @var bool
     */
    protected $bPopup = false;

    /**
     * .
     *
     * @access protected
     *
     * @var bool
     */
    protected $print = false;

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $lang;

    /**
     * .
     *
     * @access protected
     *
     * @var int
     */
    protected $iTemplateId;

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $listOrder;

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $sAddUrl;

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $aHierarchie;

    /**
     * .
     *
     * @access protected
     *
     * @var int
     */
    protected $tid;

    /**
     * .
     *
     * @access protected
     *
     * @var int
     */
    protected $rid;

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $listModel;

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $editModel;

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $last;

    /**
     * .
     *
     * @access protected
     *
     * @var int
     */
    protected $iContentTypeId;

    /**
     * .
     *
     * @access protected
     *
     * @var int
     */
    protected $iContentTypeIdSearch;

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $aFormSauve = array();

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $title_left;

    /**
     * .
     *
     * @access protected
     *
     * @var string
     */
    protected $title = '';

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $aLastValues;

    /**
     * .
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_researchType;

    /**
     * .
     *
     * @access protected
     *
     * @var array
     */
    protected $defaultValues = [];

    /**
     * .
     *
     * @access public
     *
     * @param Pelican_Request $request   Pelican_Request
     */
    public function __construct(Pelican_Request $request)
    {
        parent::__construct($request);
        $this->initParams();

        /*
         * contrôle de l'authentification, 1 fois pour l'ensemble des appels
         * HMVC
         */
        if (strtolower($this->_action) != 'login' && ! self::$securityChecked) {
            if (empty($_SESSION[APP]["user"]["id"]) || empty($_SESSION[APP]['SITE_ID'])) {
                $_SESSION[APP]["user"]["id"] = null;
            }
            Pelican_Security::checkSessionValue($_SESSION[APP]["user"]["id"], "/_/Index/login");
            self::checkRights("/_/Index/login");
            self::$securityChecked = true;
        }
    }

    public function checkRights($redirect) {
        if (!empty($_SESSION[APP]["navigation"])) {
            $navigation = $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"] . "_" . $_SESSION[APP]['SITE_ID']]['onglet'][$_GET['view']]['navigation'];
            if (!empty($navigation)) {
				if (!empty($_GET['tid'])) {
                    if( $_GET['view'] == 'O_27' ) {
                        return true;
                    } else {
					foreach ($navigation as $nav) {
						if ($nav['TEMPLATE_ID'] == $_GET['tid']) {
							return true;
						}
					}
                    }
				} elseif( !empty($_GET['view']) ) {
					return true;
				}
                // no rights
                if (basename($_SERVER['REQUEST_URI']) != basename($redirect)) {
                    header("Location: " . $redirect);
                    exit();
                }
            }
        }
    }

    /**
     * Retourne La liste des Tags à décacher
     * @return array $listDecacheOrchestra
     */
    public function getListDecacheOrchestra()
    {
        return $this->listDecacheOrchestra;
    }
    
    /**
     * Set la liste de tag a décacher
     * @param array $listDecacheOrchestra
     * @return \Pelican_Controller_Back
     */
    public function setListDecacheOrchestra(array $listDecacheOrchestra)
    {
        $this->listDecacheOrchestra = $listDecacheOrchestra;
        
        return $this;
    }

     /**
     * .
     *
     * @access protected
     *
     * @return $this
     */
    protected function setConfig()
    {
        $this->show = Pelican::$config["SHOW_DEBUG"];
        $this->config['form']['list_title'] = t('Liste');
        $this->config['database']['insert_id'] = Pelican::$config["DATABASE_INSERT_ID"];
        $this->config['database']['insert'] = Pelican_Db::DATABASE_INSERT;
        $this->config['database']['update'] = Pelican_Db::DATABASE_UPDATE;
        $this->config['database']['delete'] = Pelican_Db::DATABASE_DELETE;
        if (isset(Pelican::$config["STATE_DEFAUT"])) {
            $this->config['default']['state'] = Pelican::$config["STATE_DEFAUT"];
        }
        if (isset(Pelican::$config["STATUT_DEFAUT"])) {
            $this->config['default']['statut'] = Pelican::$config["STATUT_DEFAUT"];
        }
        $this->config['path']['lib'] = Pelican::$config["LIB_PATH"];

        return $this;
    }

    /**
     * Initialisation des variables liées aux paramètres GET de l'url.
     *
     * @access protected
     *
     */
    protected function initParams()
    {
        $this->setConfig();
        if (! empty($this->form_name)) {

            /*
             * init GET params
             */
            $paramList = array(
                'id' => 'id',
                'readO' => 'readO',
                'form_action' => 'form_action',
                'form_retour' => 'form_retour',
                'form_database' => 'form_database',
                'print' => 'print',
                'bPopup' => 'popup_content',
                'iTemplateId' => 'tid',
                'tid' => 'tid',
                'listOrder' => 'order',
                'rid' => 'rid',
            );
            $this->_assignVars($paramList);

            /*
             * tri des listes
             */
            if (isset($this->defaultOrder) && empty($this->listOrder)) {
                $this->listOrder = $this->defaultOrder;
            }
            if (valueExists($_SESSION[APP], 'SITE_ID')) {
                $_SESSION[APP]["SITE_MEDIA"] = $_SESSION[APP]['SITE_ID'];
                $HTTP_SESSION_VARS[APP]["SITE_MEDIA"] = $_SESSION[APP]['SITE_ID'];
            }
            $this->lang = $_SESSION[APP]['LANGUE_ID'];
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @param array $paramList
     *                            mixed 
     *
     */
    protected function _assignVars($paramList)
    {
        foreach ($paramList as $key => $get) {
            $this->$key = $this->getRequest()->getQuery($get);
            if (empty($this->$key)) {

                /*
                 * Affectation des variables de base passées en POST aux
                 * variables GET
                 */
                $this->$key = $this->getRequest()->getPost($get);
                if (! empty($this->$key)) {
                    $this->getRequest()->setQuery($get, $this->$key);
                }
            }
        }
    }

    /**
     * .
     *
     * @access public
     *
     * @see Pelican_Controller#before()
     *
     */
    public function before()
    {

        /*
         * actions insert/update/delete
         */
        if (! empty($this->form_name)) {

            /*
             * initalisation des valeurs
             */
            Pelican_Db::$values = $this->getValues();
        }
    }

    /**
     * .
     *
     * @access public
     *
     * @see Pelican_Controller#after()
     *
     */
    public function after()
    {
        if (! empty($this->form_name)) {
            $action = $this->getRequest()->getQuery('form_action');
            if ($this->getRequest()->isPost() || ! empty($action)) {
                $this->updateResearch();
                Pelican::$config["GROUP_DECACHE"] = false;
                $this->execDecache();
                $this->redirectRequest();
            }
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function getListModel()
    {
        if (empty($this->listModel) && method_exists($this, 'setListModel')) {
            $this->setListModel();
        }

        return $this->listModel;
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function getEditModel()
    {
        if (empty($this->editModel) && method_exists($this, 'setEditModel')) {
            $this->setEditModel();
        }

        return $this->editModel;
    }

    /**
     * .
     *
     * @access public
     *
     */
    public function indexAction()
    {
        if (! empty($this->form_name)) {
            $action = $this->getRequest()->getQuery('form_action');
            if ($this->getRequest()->isPost() || ! empty($action)) {

                if (Pelican_Security::validateCsrfToken()) {
                    /**
                     * initialisation des variables dans la methode before()
                     */
                    $this->sauve = Pelican_Db::$values;
                    $this->actionCode['INS'] = 'insert';
                    $this->actionCode['UPD'] = 'update';
                    $this->actionCode['DEL'] = 'delete';
                    $this->actionCode['ON'] = 'workflow';
                    if (!isset($this->actionCode[$this->form_action])) {
                        $this->actionCode[$this->form_action] = $this->form_action;
                    }
                    if ($this->form_action) {
                        $this->beforeSave();
                        $this->_forward('save');
                        $this->afterSave();
                    }
                }

            /*
             * decache et redirection dans la methode after()
             */
            } elseif ($this->getRequest()->isGet()) {
                if (! $this->id) {
                    $this->_forward('list');
                } else {
                    $this->_forward('edit');
                }
            }
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function beforeSave()
    {
        if ($this->form_action == Pelican_Db::DATABASE_INSERT) {
            $this->beforeInsert();
        } elseif ($this->form_action == Pelican_Db::DATABASE_UPDATE) {
            $this->beforeUpdate();
        } elseif ($this->form_action == Pelican_Db::DATABASE_DELETE) {
            $this->beforeDelete();
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function beforeInsert()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function beforeUpdate()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function beforeDelete()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function afterSave()
    {
        if ($this->form_action == Pelican_Db::DATABASE_INSERT) {
            $this->afterInsert();
        } elseif ($this->form_action == Pelican_Db::DATABASE_UPDATE) {
            $this->afterUpdate();
        } elseif ($this->form_action == Pelican_Db::DATABASE_DELETE) {
            $this->afterDelete();
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function afterInsert()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function afterUpdate()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function afterDelete()
    {
    }

    /**
     * .
     *
     * @access public
     */
    public function saveAction()
    {
        if ($this->form_action) {
            if (method_exists($this, $this->actionCode[$this->form_action]."Action")) {
                call_user_func(array(
                    $this,
                    $this->actionCode[$this->form_action]."Action",
                ));
            }
        }
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function listAction()
    {
        $this->_initBack();
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function editAction()
    {
        $this->_initBack();
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function startStandardForm()
    {
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();

        return $form;
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function stopStandardForm()
    {
        $form = $this->oForm->endFormTable();
        /*
         * Mise à jour pour éviter les interférences du form_retour dans la variable de SESSION
         */
        $sRetour = '';
        $form .= $this->endForm($this->oForm, array(), $sRetour);
        $form .= $this->oForm->close();

        return $form;
    }

    /**
     * .
     *
     * @access public
     *
     * @param mixed $action
     *                         String 
     */
    public function genericAction($action)
    {
        $oConnection = Pelican_Db::getInstance();

        /*
         * si une entrée existe dans le tableau $PROCESSUS de db_sequences, on
         * l'exécute
         */
        if (! empty($this->processus)) {
            $oConnection->updateForm($action, $this->processus);
        } elseif (! empty($this->form_name)) {

            /*
             * sinon en tente la mise à jour de la table qui porte le nom du
             * formulaire
             */
            $oConnection->updateTable($action, '#pref#_'.$this->form_name);
        }
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function insertAction()
    {
        $this->genericAction($this->config['database']['insert']);
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function updateAction()
    {
        $this->genericAction($this->config['database']['update']);
    }

    /**
     * .
     *
     * @access public
     *
     * @return mixed
     */
    public function deleteAction()
    {
        $this->genericAction($this->config['database']['delete']);
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function noTitle()
    {
        $this->title = NO_TITLE;
    }

    /**
     * .
     *
     * @access protected
     *
     * @todo refactoring
     *
     * @return mixed
     */
    protected function _initBack()
    {

        /*
         * gestion de la session
         */
        $_SESSION["HTTP_REFERER"] = $_SERVER["REQUEST_URI"];
        if (! isset($this->sAddUrl)) {
            $this->sAddUrl = $_SERVER["REQUEST_URI"]."&id=".$this->config['database']['insert_id'];
        }
        $this->getLanguageView();
        if (! isset($this->id)) {
            $_SESSION[APP]["session_start_page".$this->bPopup] = str_replace("&langue=".$this->lang, "", $_SERVER["REQUEST_URI"]);
            $this->aButton["add"] = $this->sAddUrl;
            if ($this->title != NO_TITLE) {
                $this->title = $this->getTemplateTitle($this->getView()
                    ->getHead()->sTitle, $this->config['form']['list_title']);
            }
            Backoffice_Button_Helper::init($this->aButton);
        } else {
            $this->setFormValues();
            $this->setFormAction();
            if ($this->title != NO_TITLE) {
                $this->title = $this->getTemplateTitle($this->getView()
                    ->getHead()->sTitle, $this->form_action);
            }
        }
        if (! empty($this->title)) {
            $this->assign('title', $this->title, false);
        }
        Pelican::$config["DB_PATH"] = '/'.$this->getRequest()->uri;
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function getLanguageView()
    {
    }

    /**
     * .
     *
     * @access protected
     *
     * @param Pelican_Form $oForm
     *
     *
     * @return string
     */
    protected function beginForm(Pelican_Form $oForm)
    {
        $this->setDefaultValue('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $this->setDefaultValue('LANGUE_ID', $_SESSION[APP]['LANGUE_ID']);

        return '';
    }

    /**
     * Définition des valeurs par défaut des champs.
     *
     * @access public
     *
     * @return string
     */
    public function getFormRetour()
    {
        return $this->form_retour;
    }

    /**
     * .
     *
     * @access public
     *
     * @param string $form_retour
     *                              mixed
     *
     * @return string
     */
    public function setFormRetour($form_retour)
    {
        $this->form_retour = $form_retour;
    }

    /**
     * .
     *
     *
     * @param mixed $param
     * @param mixed $value
     *                        
     * @return $this
     */
    protected function setDefaultValue($param, $value)
    {
        $this->defaultValues[$param] = $value;

        return $this;
    }

    protected function initValues($array, $keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                if (! isset($array[$key])) {
                    $array[$key] = '';
                }
            }
        }

        return $array;
    }

    /**
     * Fin standard d'un formulaire => affichage des boutons et des inputs
     * cachés.
     *
     * @access protected
     *
     * @param mixed $oForm
     *                           Pelican_Form
     * @param mixed $type
     *                           (option) Mixed Type(s) d'affichage(s) des boutons
     *                           (noback, content, product etc...)
     * @param string $retour
     *                           (option) String
     * @param bool $noSave
     *                           (option) Bool
     * @param bool $noBack
     *                           (option) Bool
     * @param bool $noDelete
     *                           (option) Bool
     * @return mixed
     */
    protected function endForm(&$oForm, $type = array(), $retour = "", $noSave = false, $noBack = false, $noDelete = false)
    {
        $form = $this->getDefaultField($oForm, $retour);

        /*
         * Gestion des boutons
         */
        $this->getFormButtons($oForm, $type);
        if ($noSave) {
            $this->aButton["save"] = "";
        }
        if ($noBack) {
            $this->aButton["back"] = "";
        }
        if ($noDelete) {
            $this->aButton["delete"] = "";
        }
        Backoffice_Button_Helper::init($this->aButton);

        return $form;
    }

    /**
     * Initialisation des objets de données liés au formulaire.
     *
     * @access protected
     *
     * @return $this
     */
    protected function setFormValues()
    {
        if ($this->id) {
            if ($this->getEditModel()) {
                $connection = Pelican_Db::getInstance();
                $res = $connection->queryForm($this->editModel, $this->aBind, $this->aBindLob);
                if(is_array($res)) {
                    $res = array_merge($this->defaultValues, $res);
                }
                $this->values = $res;

            }
        }

        return $this;
    }

    /**
     * Initialisation du mode de transaction lié à l'affichage en cours.
     *
     * Ajout, modifiation ou suppression par analyse des paramètres $_GET["id"]
     * et
     * $_GET["readO"]
     *
     * @access protected
     *
     * @return mixed
     */
    protected function setFormAction()
    {
        if ($this->id == $this->config['database']['insert_id']) {
            $return = $this->config['database']['insert'];
        } else {
            if ($this->readO) {
                $return = $this->config['database']['delete'];
            } else {
                $return = $this->config['database']['update'];
            }
        }
        $this->form_action = $return;
    }

    /**
     * Création des champs (cachés ou non) par défauts nécessaire au
     * fonctionnement.
     *
     * De base du Pelican_Index_Backoffice
     *
     * @access protected
     *
     * @param mixed $oForm
     *                         Pelican_Form 
     * @param string   $retour
     *                         (option) String 
     *
     * @return mixed
     */
    protected function getDefaultField(&$oForm, $retour = "")
    {
        $form = '';

        /*
         * Template
         */
        if (! valueExists($oForm->_inputName, "tid")) {
            $form .= $oForm->createHidden("tid", $this->iTemplateId);
        }

        /*
         * Champs système
         */
        if (! valueExists($oForm->_inputName, $this->field_id)) {
            $form .= $oForm->createHidden($this->field_id, $this->id);
        }
        if (! valueExists($oForm->_inputName, 'SITE_ID')) {
            $form .= $oForm->createHidden('SITE_ID', (! empty($this->values['SITE_ID']) ? $this->values['SITE_ID'] : $_SESSION[APP]['SITE_ID']));
        }
        if (! $this->form_retour) {
            $this->form_retour = $_SESSION[APP]["session_start_page".$this->bPopup];
            if ($retour) {
                $this->form_retour = $retour;
            }
        }
        $form .= $oForm->createHidden("form_name", $this->form_name);
        $form .= $oForm->createHidden("form_action", $this->form_action);
        $form .= $oForm->createHidden("form_database", $this->form_database);
        $form .= $oForm->createHidden("form_retour", $this->form_retour);
        $form .= $oForm->createHidden("form_preview", "");
        $form .= $oForm->createHidden("oldAction", "");
        $form .= $oForm->createHidden("form_button", "");
        $form .= $oForm->createHidden("form_user", $_SESSION[APP]["user"]["id"]);
        $form .= $oForm->createHidden("form_start", $_SESSION[APP]["session_start_page".$this->bPopup]);

        /*
         * Langue
         */
        if (! valueExists($oForm->_inputName, 'LANGUE_ID')) {
            /*
             * if (! $_SESSION[APP]['LANGUE_ID']) { $_SESSION[APP]['LANGUE_ID'] = Pelican::$config['LANGUE_ID']; }
             */
            $form .= $oForm->createHidden('LANGUE_ID', (! empty($this->values['LANGUE_ID']) ? $this->values['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']));
            // Changement de langue
            $form .= $oForm->createHidden("NEW_LANGUE_ID", "");
        }
        if ($oForm->bDirectOutput) {
            echo($form);

            return true;
        } else {
            return $form;
        }
    }

    /**
     * Gestion de l'affichage des boutons d'action suivant le contexte (liste ou
     * formulaire).
     *
     * @access protected
     *
     * @param mixed $oForm
     *                        Pelican_Form 
     * @param mixed $type
     *                        (option) String Type de bouton spécifique à utiliser
     *
     * @return mixed
     */
    protected function getFormButtons(&$oForm, $type = array())
    {
        switch ($type) {
            case "aucun":
                {
                    $type = "noback";
                    $this->aButton["save"] = "";
                    break;
                }
            case "directory":
                {
                    $this->aButton["save"] = $oForm->sFormName;
                    break;
                }
            case "nosave":
                {
                    break;
                }
            default:
                {
                    if (! $this->bPopup && $this->readO && ! $this->bNoDelete) {
                        $this->aButton["delete"] = $oForm->sFormName;
                    } elseif (! $this->bNoDelete && ! $this->readO) {
                        $this->aButton["save"] = $oForm->sFormName;
                    }
                }
        }
        if ($type != "noback") {
            $this->aButton["back"] = $this->form_retour;
        }
        // Ajout pour supression bouton back
        if ($type == "nodelete") {
            unset($this->aButton["delete"]);
        }
    }
    // formulaire

    /**
     * Exécute une série de requêtes pour vérifier si le cotenu/layout/media
     * est associé à d'autres données en base => pour masquer le bouton.
     *
     * Supprimer
     * et garantir l'intégrité des données
     *
     * Paramétrage dans pelican.ini.php : Pelican::$config["USAGE"] =>
     * Pelican::$config["USAGE"]["CONTENT_ID"]/Pelican::$config["USAGE"]["PAGE_ID"]/Pelican::$config["USAGE"]["TAG_ID"]
     *
     * @access protected
     *
     * @param mixed $id
     *                       Int Identifiant du contenu/layout/media
     * @param mixed $type
     *                       String Type de données testée : CONTENT/PAGE/MEDIA
     *
     * @return bool
     */
    protected function checkUsage($id, $type)
    {
        if (Pelican::$config["USAGE"][$type."_ID"] && $id != $this->config['database']['insert_id']) {
            $oConnection = Pelican_Db::getInstance();
            foreach (Pelican::$config["USAGE"][$type."_ID"] as $table => $field) {
                $count = $oConnection->queryItem("SELECT count(1) FROM ".$table." WHERE ".$field."=:ID", array(
                    ":ID" => $id,
                ));
                if ($count) {
                    $usage[$table] = $count;
                }
            }

            return $usage;
        } else {
            return false;
        }
    }
    // affichage

    /**
     * Affichage du titre d'une page.
     *
     * @access protected
     *
     * @param mixed $title
     *                           String Titre principal
     * @param string   $subtitle
     *                           (option) String Sous-titre
     * @param mixed $class
     *                           (option) String Classe CSS du titre
     *
     * @return Code
     */
    protected function getTemplateTitle($title, $subtitle = "", $class = "form_title")
    {
        switch ($subtitle) {
            case $this->config['database']['insert']:
                {
                    $etat = t('Ajout');
                    break;
                }
            case $this->config['database']['update']:
                {
                    $etat = t('Edition');
                    break;
                }
            case $this->config['database']['delete']:
                {
                    $etat = t('Suppression');
                    break;
                }
            default:
                {
                    if ($subtitle) {
                        $etat = $subtitle;
                    } else {
                        $etat = "&nbsp;";
                    }
                    break;
                }
        }
        if (! $this->bPopup && ! $this->print) {
            $titleTranslate = t($title);
            if(strpos($titleTranslate, "[cle1:") !== false) {
                $titleTranslate = $title;
            }
            if (! empty($_GET["is_edit_front"])) {
                $return = Pelican_Html::script(array(
                    type => "text/javascript",
                ), "if(parent.setRightTitle) parent.setRightTitle('".(addslashes(Pelican_Text::unhtmlentities($titleTranslate)))."')");
            } else {
                $return = Pelican_Html::script(array(
                    type => "text/javascript",
                ), "if(top.setRightTitle) top.setRightTitle('".(addslashes(Pelican_Text::unhtmlentities($titleTranslate)))."')");
            }
            $return .= Pelican_Html::div(array(
                "class" => $class,
            ), $etat).Pelican_Html::br();
        }

        return $return;
    }
    // navigation

    /**
     * Retourne un objet hiérarchique contenant la navigation disponible pour
     * l'utilisateur en fonction de son profil et du site consulté.
     *
     * @access protected
     *
     * @return mixed
     */
    protected function getHierarchieMenu()
    {
        $this->aHierarchie = $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"]."_".$_SESSION[APP]['SITE_ID']];

        return $this->aHierarchie;
    }

    /**
     * ********************* Process **********************.
     */

    /**
     * Initialisation de la variable contenant les valeurs du formulaire
     * => associée à la variable globale Pelican_Db::$values.
     *
     * => par référence pour une compatibilité descendante)
     *
     * @access protected
     *
     * @return Tableau
     */
    protected function getValues()
    {

        /*
         * si le formulaire était en GET, on récupère les valeurs dans le
         * tableau POST
         */
        if (empty($_POST["form_name"]) && ! empty($_GET["form_name"])) {
            $_POST = $_GET;
        }
        Pelican_Db::$values = $_POST;
        if (empty(Pelican_Db::$values["form_name"]) && ! empty($_REQUEST["form_name"])) {
            Pelican_Db::$values["form_name"] = $_REQUEST["form_name"];
        }
        if (empty(Pelican_Db::$values["form_action"]) && ! empty($_REQUEST["form_action"])) {
            Pelican_Db::$values["form_action"] = $_REQUEST["form_action"];
        }
        // $aFormSauve = Pelican_Db::$values;
        return Pelican_Db::$values;
    }

    /**
     * .
     *
     * @access protected
     *
     * @param mixed $oSearch
     *                          
     *
     * @return mixed
     */
    protected function hookReseach($oSearch)
    {
        return $oSearch;
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function updateResearch()
    {
        pelican_import('Search');
        $oConnection = Pelican_Db::getInstance();
        $this->_researchType = '';
        if (empty($this->_researchType) && isset($this->workflowField)) {
            $this->_researchType = $this->workflowField;
        }

        if ($this->_researchType == "MEDIA") {
            $MEDIA = Pelican_Search::getMediaType();
            Pelican_Db::$values["MEDIA_TYPE_ID"] = $MEDIA[Pelican_Db::$values["MEDIA_TYPE_ID"]];
        }
        if ($this->_researchType != "CONTENT") {
            Pelican_Db::$values["PAGE_TYPE_ID"] = 1;
        }

        if ($this->_researchType) {
            $DBVALUES_ORI = Pelican_Db::$values;
            $oSearch = Pelican_Factory::getInstance('Search', "Db");
            $oSearch->indexationInit(Pelican_Db::$values[$this->_researchType."_ID"], Pelican_Db::$values['SITE_ID'], (Pelican_Db::$values['LANGUE_ID'] ? Pelican_Db::$values['LANGUE_ID'] : 1), $this->_researchType, Pelican_Db::$values[$this->_researchType."_TYPE_ID"], Pelican_Db::$values[$this->_researchType."_DATE"]);
            if (Pelican_Db::$values["PUBLICATION"] || $_POST["form_action"] == Pelican::$config["DATABASE_DELETE"]) {
                $oSearch->deleteSearchRecord();
            }
            if ($_POST["form_action"] != Pelican::$config["DATABASE_DELETE"] && ! empty(Pelican_Db::$values["PUBLICATION"])) {

            /*
             * * Configuration de la recherche pour le type de contenu en cours
             */
            $oSearch->getParams();
                $oSearch->addValue("RESEARCH_URL", Pelican_Db::$values[$this->_researchType."_CLEAR_URL"]);
                $oSearch->addValue("RESEARCH_URL_TITLE", Pelican_Db::$values[$this->_researchType."_TITLE_URL"]);
                $oSearch->addValue("RESEARCH_URL_PICTO", Pelican_Db::$values[$this->_researchType."_PICTO_URL"]);
                $oSearch->addValue("RESEARCH_STATUS", Pelican_Db::$values[$this->_researchType."_STATUS"]);
                $oSearch->addValue("RESEARCH_DATE", (Pelican_Db::$values[$this->_researchType."_DATE"] ? Pelican_Db::$values[$this->_researchType."_DATE"] : Pelican_Db::$values[$this->_researchType."_PUBLICATION_DATE"]));
                $oSearch->addValue("RESEARCH_PUBLICATION_DATE", Pelican_Db::$values[$this->_researchType."_PUBLICATION_DATE"]);
                if ($this->_researchType == "CONTENT") {
                    if (Pelican_Db::$values["PAGE_ID"]) {
                        $aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
                        $aBind[":SITE_ID"] = Pelican_Db::$values['SITE_ID'];
                        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                        $sql = "select PAGE_DISPLAY_SEARCH from #pref#_page p
					INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
					WHERE p.PAGE_ID = :PAGE_ID
					AND p.LANGUE_ID = :LANGUE_ID
					AND p.SITE_ID = :SITE_ID";
                        $display = $oConnection->queryItem($sql, $aBind);

                    /*
                     * si la page n'est pas dans la recherche, par principe le
                     * contenu ne l'est pas
                     */
                    $display = (! $display ? 0 : Pelican_Db::$values[$this->_researchType."_DISPLAY_SEARCH"]);
                    }
                    $oSearch->addValue("RESEARCH_DISPLAY", ($display ? '1' : '0'));
                } elseif ($this->_researchType == "PAGE") {
                    $oSearch->addValue("RESEARCH_DISPLAY", Pelican_Db::$values[$this->_researchType."_DISPLAY_SEARCH"]);
                } else {
                    $oSearch->addValue("RESEARCH_DISPLAY", 1);
                    $oSearch->addValue("RESEARCH_STATUS", $oSearch->values["RESEARCH_DISPLAY"]);
                }
                $oSearch->addValue("RESEARCH_DESCRIPTION", str_replace("  ", " ", Pelican_Text::unhtmlentities(strip_tags(str_replace("</", " </", nl2br(Pelican_Db::$values[$oSearch->researchParam["RESEARCH_DESCRIPTION"]]))))));

            /*
             * Clés étrangères
             */
            $oSearch->addValue("PAGE_ID", Pelican_Db::$values["PAGE_ID"]);
                $oSearch->addValue("MEDIA_ID", Pelican_Db::$values["DOC_ID"]);

            /*
             * TITRE
             * Recherche du titre :
             * soit le titre est dans le champs RESEARCH_TITLE : la virgule
             * permet de concaténer les valeurs de champs
             * soit on prend par défaut la valeur du champ
             * CONTENT_TITLE/PAGE_TITLE/CATEGORY_TITLE
             */
            if ($oSearch->researchParam["RESEARCH_TITLE"]) {
                $researchTitleInit = explode(",", $oSearch->researchParam["RESEARCH_TITLE"]);
                if ($researchTitleInit) {
                    foreach ($researchTitleInit as $field) {
                        $oSearch->addMultiValue("RESEARCH_TITLE", Pelican_Db::$values[$field]);
                    }
                }
            } else {
                $oSearch->addValue("RESEARCH_TITLE", Pelican_Db::$values[$this->_researchType."_TITLE"]);
            }

            /*
             * DATES
             * dates par défaut : "01/01/1900" et "01/01/2900"
             */
            $this->values = Pelican_Db::$values;
                $oSearch->addDateValue("RESEARCH_DATE_BEGIN", $this->values, "01/01/1900");
                $oSearch->addDateValue("RESEARCH_DATE_END", $this->values, "01/01/2900");

            /*
             * CONTENU
             */
            if ($this->_researchType == "MEDIA") {
                $oSearch->addValue("RESEARCH_CONTENT", $oSearch->getContentFromFile(Pelican::$config["MEDIA_ROOT"].Pelican_Db::$values["MEDIA_PATH"]));
                $oSearch->addValue("RESEARCH_URL", Pelican::$config["MEDIA_HTTP"].Pelican_Db::$values["MEDIA_PATH"]);
            } elseif ($oSearch->researchParamField) {
                foreach ($oSearch->researchParamField as $key => $search) {
                    if ($key) {

                        /*
                         * champs multiples
                         */
                        for ($i = 0; $i <= Pelican_Db::$values["count_".$key]; $i ++) {
                            $prefixe = (Pelican_Db::$values["prefixe_".$key] ? Pelican_Db::$values["prefixe_".$key] : "multi");
                            foreach ($search as $field) {
                                $oSearch->addMultiValue("RESEARCH_CONTENT", Pelican_Db::$values[$prefixe.$i."_".$field]);
                                if (! Pelican_Db::$values[$oSearch->researchParam["RESEARCH_DESCRIPTION"]] && $oSearch->researchParam["RESEARCH_DESCRIPTION"] == $key.".".$field) {
                                    Pelican_Db::$values[$oSearch->researchParam["RESEARCH_DESCRIPTION"]] = Pelican_Db::$values[$prefixe.$i."_".$field];
                                }
                            }
                        }
                    } else {

                        /*
                         * champs simples
                         */
                        foreach ($search as $field) {
                            $oSearch->addMultiValue("RESEARCH_CONTENT", Pelican_Db::$values[$field]);
                        }
                    }
                }
            }

            /*
             * COMPLEMENTS
             */
            if ($oSearch->researchParam["RESEARCH_ADDON"]) {
                $aBind[":".$this->_researchType."_ID"] = Pelican_Db::$values[$this->_researchType."_ID"];
                $aBind[":".$this->_researchType."_VERSION"] = Pelican_Db::$values[$this->_researchType."_VERSION"];
                $oConnection->query(str_replace("\n", " ", str_replace("\r", " ", $oSearch->researchParam["RESEARCH_ADDON"])), $aBind);
                if ($oConnection->data) {
                    Pelican_Db::$values["ADDON"] = implode(" - ", $oConnection->data["TITLE"]);
                    $oSearch->addMultiValue("RESEARCH_CONTENT", Pelican_Db::$values["ADDON"]);
                }
            }
                if ($oSearch->values["RESEARCH_CONTENT"]) {
                    $oSearch->values["RESEARCH_CONTENT"] = Pelican_Search::cleanSearch($oSearch->values["RESEARCH_CONTENT"]);
                    $oSearch->values["RESEARCH_KEYWORD"] = Pelican_Search::reduceSearch($oSearch->values["RESEARCH_CONTENT"]);
                }
                $oSearch = $this->hookReseach($oSearch);
                if ($oSearch->values["RESEARCH_CONTENT"]) {

                /*
                 * insertion dans la table de recherche
                 */
                $oSearch->values["RESEARCH_MAJ"] = ":DATE_COURANTE";
                    Pelican_Db::$values = $oSearch->values;
                    $oConnection->insertQuery("#pref#_research");
                    $aBind[":RESEARCH_ID"] = $oSearch->values["RESEARCH_ID"];
                    $aBind[":SITE_ID"] = $oSearch->values['SITE_ID'];
                    $aBind[":LANGUE_ID"] = $oSearch->values['LANGUE_ID'];
                    $aBind[":RESEARCH_TYPE"] = $oConnection->strToBind($oSearch->values["RESEARCH_TYPE"]);
                    $aBind[":RESEARCH_TYPE_ID"] = $oSearch->values["RESEARCH_TYPE_ID"];

                /*
                 * fin du traitement des dates
                 */
                if ($oSearch->dateOperation["RESEARCH_DATE_BEGIN"]) {
                    $oConnection->query("update #pref#_research set RESEARCH_DATE_BEGIN=RESEARCH_DATE_BEGIN".$oSearch->dateOperation["RESEARCH_DATE_BEGIN"]." WHERE SITE_ID=:SITE_ID AND RESEARCH_TYPE=:RESEARCH_TYPE AND RESEARCH_TYPE_ID=:RESEARCH_TYPE_ID AND RESEARCH_ID=:RESEARCH_ID", $aBind);
                }
                    if ($oSearch->dateOperation["RESEARCH_DATE_END"]) {
                        $oConnection->query("update #pref#_research set RESEARCH_DATE_END=RESEARCH_DATE_END".$oSearch->dateOperation["RESEARCH_DATE_END"]." WHERE SITE_ID=:SITE_ID AND RESEARCH_TYPE=:RESEARCH_TYPE AND RESEARCH_TYPE_ID=:RESEARCH_TYPE_ID AND RESEARCH_ID=:RESEARCH_ID", $aBind);
                    }
                }
                Pelican_Db::$values = $DBVALUES_ORI;

            /*
             * Propagation de l'affichage dans la recherche pour une page
             */
            if (Pelican_Db::$values["PAGE_PARENT_ID"] && ! substr_count(Pelican_Db::$values["PAGE_PATH"], "%")) {
                if (Pelican_Db::$values["PAGE_DISPLAY_SEARCH"] != Pelican_Db::$values["PAGE_DISPLAY_SEARCH_SAUVE"] || ! Pelican_Db::$values["PAGE_DISPLAY_SEARCH_SAUVE"] || Pelican_Db::$values["PUBLICATION"]) {
                    $aBind[":RESEARCH_DISPLAY"] = (Pelican_Db::$values["PAGE_DISPLAY_SEARCH"] ? 1 : 0);
                    $aBind[":PATH"] = $oConnection->strToBind(Pelican_Db::$values["PAGE_PATH"]."#%");
                    $aBind[":LIBPATH"] = $oConnection->strToBind(Pelican_Db::$values["PAGE_LIBPATH"]."%");
                    $oConnection->query("update #pref#_page_version set PAGE_DISPLAY_SEARCH=:RESEARCH_DISPLAY WHERE PAGE_ID in (select PAGE_ID from #pref#_page where PAGE_PATH like :PATH)", $aBind);
                    $oConnection->query("update #pref#_research set RESEARCH_DISPLAY=:RESEARCH_DISPLAY WHERE MORE_PATH like :LIBPATH", $aBind);
                    $aBind = array();
                }
            }
            }
            Pelican_Db::$values = $DBVALUES_ORI;
        }
    }

    /**
     * .
     *
     * @access protected
     *
     */
    protected function execDecache()
    {
        if (! $this->getRequest()->isAjax()) {
            $aFormSauve = Pelican_Db::$values;
            if ($this->decacheBack) {
                $this->listDecache = array_merge($this->listDecache, $this->decacheBack);
            }
            if ($this->listDecache) {
                $this->arrayDecache($this->listDecache);
            }
            if (!empty($this->decacheBackOrchestra)) {
                $this->listDecacheOrchestra = array_merge($this->listDecacheOrchestra, $this->decacheBackOrchestra);
            }
            if ($this->listDecacheOrchestra) {
                $this->decacheOrchestra();
            }            
            Pelican_Db::$values = $aFormSauve;

            /*
             * dans le cas d'un décache en groupe
             */
            if (Pelican::$config["GROUP_DECACHE"] && $this->decacheDefer) {
                // A TESTER//Pelican::runCommand(implode("\n",
                // $this->decacheDefer));
            }
        }
    }

    /**
     * .
     *
     * @access protected
     */
    protected function redirectRequest()
    {
        if (! Pelican_Db::$values["form_retour"] && $_REQUEST['retour']) {
            Pelican_Db::$values["form_retour"] = $_REQUEST['retour'];
        }
        if ($this->form_action || $_REQUEST['form_action']) {
            /*
             * Redirection en fin de traitement
             */
            if (! Pelican_Db::$values['form_retour']) {
                switch ($this->form_action) {
                    case $this->config['database']['insert']:
                        {

                            /*
                             * INSERT
                             */
                            $this->getRequest()->redirect(Pelican_Db::$values["form_start"], 200);
                            break;
                        }
                    case $this->config['database']['update']:
                        {

                            /*
                             * UPDATE
                             */
                            $this->getRequest()->redirect(Pelican_Db::$values["form_start"], 200);
                            // $this->getRequest()->redirect($_SESSION["HTTP_REFERER"],
                            // 200);
                            break;
                        }
                    case $this->config['database']['delete']:
                        {

                            /*
                             * DELETE
                             */
                            $this->getRequest()->redirect(Pelican_Db::$values["form_start"], 200);
                            break;
                        }
                    case "MOVE":
                        {

                            /*
                             * MOVE
                             */
                            $this->getRequest()->redirect("/?view=".$_REQUEST["view"], 200);
                            break;
                        }
                    default:
                        {
                            $this->getRequest()->redirect($_SESSION["HTTP_REFERER"], 200);
                            break;
                        }
                }
            } else {
                $this->getRequest()->redirect(Pelican_Db::$values['form_retour'], 200);
            }
        }
    }
    
    protected function decacheOrchestra(){
        if(!is_array($this->getListDecacheOrchestra())){
            return false;
        }
        $listeDecacheOrchestra = $this->getListDecacheOrchestra();
        if(Pelican_Db::$values['PAGE_GENERAL'] != 1){
            unset($listeDecacheOrchestra['strategy']['general']);
        }
        foreach($listeDecacheOrchestra as $cacheTypeTag => $decacheTags){
            if (is_array($decacheTags)) {
                foreach ($decacheTags as $decacheTag) {
                    $cache = new Ndp_Cache($cacheTypeTag, $decacheTag);
                    $cache->hydrate(Pelican_Db::$values)
                        ->decacheOrchestra();
                }
            }
        }
    }
    /**
     * .
     *
     * @access protected
     *
     * @param mixed $values
     *                                mixed 
     * @param string   $intercepValue
     *                                (option) String 
     *
     * @return mixed
     */
    protected function arrayDecache($values, $intercepValue = "")
    {
        if ($values) {
            foreach ($values as $decache) {
                $todo = '';
                $decacheParams = array();
                $params = array();
                if (is_array($decache)) {

                    /*
                     * un paramètre doit exister
                     */
                    if (count($decache) > 1) {
                        $file = $decache[0];
                        if (! $intercepValue) {

                            /*
                             * si le paramètre est rempli sinon on ne lance pas
                             * le décache
                             */
                            if ($decache[1]) {
                                $params = $decache[1];
                            }
                        } else {
                            $params = $intercepValue;
                        }
                        if ($params) {
                            if (! is_array($params)) {
                                $params = array(
                                    $params,
                                );
                            }
                            foreach ($params as $field) {
                                if (isset($_REQUEST[$field])) {
                                    $decacheParams[] = $_REQUEST[$field];
                                } else {
                                    $decacheParams[] = $field;
                                }
                            }
                            Pelican_Cache::clean($file, $decacheParams, "", Pelican::$config["GROUP_DECACHE"]);
                        }
                    } else {
                        Pelican_Cache::clean($decache[0], "", "", Pelican::$config["GROUP_DECACHE"]);
                    }
                } else {
                    Pelican_Cache::clean($decache, "", "", Pelican::$config["GROUP_DECACHE"]);
                }
            }
        }
    }

    /**
     * .
     *
     * @access protected
     *
     * @return mixed
     */
    protected function _isAdminSite()
    {
        return $_SESSION[APP]["admin"];
    }

    /**
     * Création d'un onglet.
     *
     * @access protected
     *
     * @param mixed $label
     *                           String Titre de l'onglet
     * @param string   $id
     *                           (option) String 
     * @param bool     $activate
     *                           (option) Bool 
     * @param string   $link
     *                           (option) String 
     * @param string   $onclick
     *                           (option) String 
     * @param mixed $title
     *                           (option) String Titre de la partie de gauche (peut être
     *                           défini dans le formulaire de gestion de l'onglet (ou menu))
     * @param string   $size
     *                           (option) String Identifiant de taille (vide par défaut,
     *                           sinon "big", "small") pour choisir l'image de fond
     * @param string   $width
     *                           (option) String 
     * @param string   $limit
     *                           (option) String 
     */
    protected function zz_buildTab($label, $id = "", $activate = false, $link = "", $onclick = "", $title = "Rubriques", $size = "", $width = "", $limit = "")
    {
        global $intOnglet;
        ++ $this->countOnglet;
        $int = "";
        if ($intOnglet) {
            $int = "_int";
        }
        if ($size) {
            $size .= "_";
        }
        if ($width) {
            $width = " style=\"width:".$width."\"";
        }
        if ($link) {
            $link = " href=\"".$link."\"";
        }
        if ($onclick) {
            $onclick = " onclick=\"".$onclick."\"";
        }
        if ($id) {
            $id1 = " id=\"".$id."_1\"";
            $id2 = " id=\"".$id."_2\"";
            $id3 = " id=\"".$id."_3\"";
        }
        $etat = "off";
        $font = "";
        if ($activate) {
            $etat = "on";
            $font = "font-weight: bold;";
        }
        $head = $this->getView()->getHead();
        $imageLeft = $head->skinPath."/images/".$size."onglet_".$etat."_gauche".($this->countOnglet != 1 ? $int : "").".gif";
        $imageRight = $head->skinPath."/images/".$size."onglet_".$etat."_droite".($this->countOnglet != $this->maxOnglet ? $int : "").".gif";
        $return = "<div class=\"".$size."onglet\">";
        $return .= "<div class=\"".$size."onglet ".$size."onglet_side\"><img".$id1." border=\"0\" alt=\"\" src=\"".$imageLeft."\" /></div>";
        $return .= "<div".$id2." class=\"".$size."onglet ".$size."onglet_centre\" style=\"background-image: url(".$head->skinPath."/images/".$size."onglet_".$etat."_centre.gif);".$font."\"".$width.">";
        if ($etat == "off" || $onclick) {
            $return .= "<a ".$link.$onclick.">";
            $return .= $label;
            $return .= "</a>";
        } else {
            $return .= $label;
        }
        $return .= "</div>";
        $return .= "<div class=\"".$size."onglet ".$size."onglet_side\"><img".$id3." border=\"0\" alt=\"\" src=\"".$imageRight."\" /></div>";
        $return .= "</div>";
        if ($activate) {
            $this->title_left = $title; // @todo
        }

        return $return;
    }

    public static function screenSize()
    {
        $return = '';
        if(isset($_GET['screen_width'])) {
            $return .= '&screen_width='.$_GET['screen_width'];
        }
        if(isset($_GET['screen_height'])) {
            $return .= '&screen_height='.$_GET['screen_height'];
        }
        return $return;
    }

    /**
     * .
     *
     * @access public
     *
     * @param string $head
     *                     (option) 
     *
     * @return mixed
     */
    public function _setSkin($head = '')
    {
        if (! $head) {
            $head = $this->getView()->getHead();
        }

        $id = Pelican::$config["SKIN"];
        $relPath = "/library/Pelican/Index/Backoffice/public/skins";
        $rootPath = Pelican::$config["LIB_ROOT"];
        $css = str_replace("library/library", "library", $rootPath.$relPath."/".$id."/css/style.css.php");
        $urlinfo = parse_url($_SERVER['REQUEST_URI']);
        $page = substr($urlinfo['path'], 1, strlen($urlinfo['path']));
        if (file_exists($css)) {

            $head->setCss($relPath."/".$id."/css/style.css.php?page=".$page.self::screenSize());
            $css2 = $relPath."/".$id."/css/pelican.css";
            $input = str_replace("library/library", "library", $rootPath.$relPath."/".$id."/css/pelican.less");
            $output = Pelican::$config['MEDIA_ROOT'].'/'.$id."_pelican.css";
            /*
             * si un fichier less existe
             */
            if (file_exists($input)) {
                if (! file_exists($output)) {
                    require_once Pelican::$config['LIB_ROOT'].'/External/lessphp/lessc.inc.php';
                    $less = new lessc();
                    $cache = $less->cachedCompile($input);
                    file_put_contents($output, $cache["compiled"]);
                }
                $css2 = Pelican::$config['MEDIA_HTTP'].'/'.$id."_pelican.css";
            }
            $head->setCss($css2);
            $head->skinPath = $relPath."/".$id;
            pelican_import('Form.Tab');
            Pelican_Form_Tab::$imgPath = $head->skinPath;
            Backoffice_Div_Helper::setSkin($head->skinPath);
        }
    }

    public function addFlashMessage($msg, $type = 'error')
    {
        if(!is_array($_SESSION[APP]['flash_messages'])) {
            $_SESSION[APP]['flash_messages'] = [];
        }

        $_SESSION[APP]['flash_messages'][] = ['type'=> $type , 'message'=>$msg];
    }

    public function getFlashMessages()
    {
        $messages = [];
        if(!empty($_SESSION[APP]['flash_messages'])) {
            $messages = $_SESSION[APP]['flash_messages'];
        }
        if(!empty($GLOBALS['flash_messages'])) {
           $messages = array_merge($GLOBALS['flash_messages'], $messages);
        }

        unset($_SESSION[APP]['flash_messages']);

        return $messages;
    }


    /**
     * return the front base url for page depending of current site and current url
     * @return string
     */
    public function getBaseUrl() {
        $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
        $url = $site["SITE_URL"];
        $protocole = 'http://';
        $urlBO = ($_SERVER['HTTP_CLIENT_HOST'] != '') ? $_SERVER['HTTP_CLIENT_HOST'] : $_SERVER['SERVER_NAME'];
        if ($site['DNS'][$urlBO]) {
            if ($site['DNS'][$urlBO]['SITE_DNS_BO']) {
                $url = $site['DNS'][$urlBO]['SITE_DNS_BO'];
            }
            if ($site['DNS'][$urlBO]['SITE_DNS_HTTP']) {
                $protocole = ($site['DNS'][$urlBO]['SITE_DNS_HTTP'] == 'https') ? 'https://' : 'http://';
            }
        }

        return $protocole.$url;
    }


}
