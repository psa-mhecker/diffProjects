<?php

/**
 * Formulaire de gestion des templates de saisie du Back Office
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 02/07/2004
 */

class Administration_Template_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "template";

    protected $field_id = "TEMPLATE_ID";

    protected $defaultOrder = "TEMPLATE_LABEL";

    protected $decacheBack = array(
        "Template" , 
        "Backend/Template" , 
        "Backend/Layout" , 
        array(
            "Backend/Generic" , 
            "template_group"
        )
    );

    protected function setListModel ()
    {
        
        if ($_GET["tc"] == 3) {
            $field_count = "CONTENT_TYPE_ID";
            $join_count = " left join #pref#_content_type ct on (t.TEMPLATE_ID=ct.TEMPLATE_ID)";
        } else {
            $field_count = "DIRECTORY_ID";
            $join_count = " left join #pref#_directory d on (t.TEMPLATE_ID=d.TEMPLATE_ID)";
        }
        
        $this->listModel = "SELECT t.TEMPLATE_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_GROUP_LABEL, t.TEMPLATE_GROUP_ID, count(" . $field_count . ") as NB
		from #pref#_template t
		left join #pref#_template_group tg on (t.TEMPLATE_GROUP_ID=tg.TEMPLATE_GROUP_ID)
		" . $join_count . "
			where TEMPLATE_TYPE_ID='" . $_GET['tc'] . "'
			and (PLUGIN_ID is null or PLUGIN_ID='')
		group by t.TEMPLATE_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_GROUP_LABEL, t.TEMPLATE_GROUP_ID
			order by TEMPLATE_GROUP_LABEL, " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_template WHERE TEMPLATE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        
        $aTemplateGroups = Pelican_Cache::fetch("Backend/Generic", "template_group");
        $table->setFilterField("template_group", "<b>" . t('Groupe') . "</b> :", "t.TEMPLATE_GROUP_ID", $aTemplateGroups);
        $table->setFilterField("nom", "<b>" . t('Name or path') . "</b> :", array(
            "TEMPLATE_LABEL" , 
            "TEMPLATE_PATH"
        ), 2);
        $table->getFilter(2);
        
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "t.TEMPLATE_ID", "TEMPLATE_GROUP_LABEL");
        $table->addColumn(t('ID'), "TEMPLATE_ID", "10", "left", "", "tblheader", "TEMPLATE_ID");
        $table->addColumn(t('FIRST_NAME'), "TEMPLATE_LABEL", "90", "left", "", "tblheader", "TEMPLATE_LABEL");
        $table->addColumn(t('Back'), "TEMPLATE_PATH", "10", "left", "", "tblheader", "TEMPLATE_PATH");
        $table->addColumn(t('Front'), "TEMPLATE_PATH_FO", "10", "left", "", "tblheader", "TEMPLATE_PATH_FO");
        
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "TEMPLATE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "TEMPLATE_ID" , 
            "" => "readO=true"
        ), "center", array(
            "NB=0"
        ));
        $this->setResponse($table->getTable() . "<br /><br />");
    
    }

    public function editAction ()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        
        $form .= $this->oForm->createHidden("TEMPLATE_TYPE_ID", $_GET["tc"]);
        $form .= $this->oForm->createHidden("PLUGIN_ID", $this->values["PLUGIN_ID"]);
        $form .= $this->oForm->createInput("TEMPLATE_LABEL", t('FIRST_NAME'), 100, "", true, $this->values["TEMPLATE_LABEL"], $this->readO, 100);
        $form .= $this->oForm->createInput("TEMPLATE_PATH", t('BACK_PATH'), 255, "", false, $this->values["TEMPLATE_PATH"], $this->readO, 50);
        $highlight = '';
        if ($this->values["TEMPLATE_PATH"]) {
            $this->getView()->getHead()->setCss("/library/External/SyntaxHighlighter/css/SyntaxHighlighter.css");
            $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shCore.js");
            $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shBrushPhp.js");
            $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shBrushXml.js");
            $skeleton = $this->getSkeleton($_GET["tc"], $this->values["TEMPLATE_PATH"]);
            $form .= $this->oForm->createLabel('Code', Pelican_Html::pre(array(
                name => 'bocode' , 
                "class" => "php"
            ), htmlentities($skeleton)));
            $highlight .= "dp.SyntaxHighlighter.ClipboardSwf = '/library/External/SyntaxHighlighter/js/clipboard.swf';";
            
            $highlight .= "dp.SyntaxHighlighter.HighlightAll('bocode');";
        }
        if ($highlight) {
            $form .= $this->oForm->createFreeHtml(Pelican_Html::script($highlight));
        }
        if ($_GET["tc"] > 2) {
            $form .= $this->oForm->createInput("TEMPLATE_PATH_FO", t('FRONT_PATH'), 255, "", false, $this->values["TEMPLATE_PATH_FO"], $this->readO, 50);
            $highlight = '';
            if ($this->values["TEMPLATE_PATH_FO"]) {
                $skeleton = $this->getSkeleton(4, $this->values["TEMPLATE_PATH_FO"]);
                $form .= $this->oForm->createLabel('Code', Pelican_Html::pre(array(
                    name => 'focode' , 
                    "class" => "php"
                ), htmlentities($skeleton)));
                
                $highlight .= "dp.SyntaxHighlighter.HighlightAll('focode');";
            }
            if ($highlight) {
                $form .= Pelican_Html::script($highlight);
            }
        }
        $form .= $this->oForm->createCombo(Pelican_Db::getInstance(), "TEMPLATE_GROUP_ID", t('Template_type'), "template_group", "", "", $this->values["TEMPLATE_GROUP_ID"], true, $this->readO, "1", false, "", true, true);
        $form .= $this->oForm->createInput("TEMPLATE_COMPLEMENT", t('Complement'), 100, "", false, $this->values["TEMPLATE_COMPLEMENT"], $this->readO, 100);
        
        $form .= $this->stopStandardForm();
        
        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop
        

        $this->setResponse($form);
    }

    public function saveAction ()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        Pelican_Db::$values["TEMPLATE_PATH"] = trim(Pelican_Db::$values["TEMPLATE_PATH"]);
        Pelican_Db::$values["TEMPLATE_PATH_FO"] = trim(Pelican_Db::$values["TEMPLATE_PATH_FO"]);
        Pelican_Db::$values["TEMPLATE_COMPLEMENT"] = trim(Pelican_Db::$values["TEMPLATE_COMPLEMENT"]);
        Pelican_Db::$values["PLUGIN_ID"] = trim(Pelican_Db::$values["PLUGIN_ID"]);
        if (Pelican_Db::$values['SITE_ID'] == $_SESSION[APP]['SITE_ID']) {
            Pelican_Db::$values['SITE_ID'] = "";
        }
        
        parent::saveAction();
    
    }
    
    protected function afterSave()
    {
        /** décache de fichiers */
		Pelican_Cache::clean("Frontend/Page/Zone");
    }

    public static function getSkeleton ($type, $controller, $field = "")
    {
        
        $skeleton[1] = '<?php
class _XXX__Controller extends Pelican_Controller_Back
{
    protected $administration = false; //true

    protected $form_name = "yyy";

    protected $field_id = "YYY_ID";

    protected $defaultOrder = "YYY_LABEL";

    protected function setListModel ()
    {
        $this->listModel = "SELECT * from #pref#_yyy
        order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->aBind[\':ID\'] = $this->id;
    	$this->editModel = "SELECT * from #pref#_yyy WHERE YYY_ID=:ID";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance(\'List\', \'\', \'\', 0, 0, 0, \'liste\');

        // {LIST}  

        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        parent::editAction();
//------------ Begin startStandardForm ----------
        $this->oForm = Pelican_Factory::getInstance(\'Form\', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config[\'DB_PATH\']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
//------------ End startStandardForm ----------

        // {FORM} 

//------------ Begin stopStandardForm ----------
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
//------------ End stopStandardForm ----------
        $this->setResponse($form);        
    }
}';
        
        $skeleton[3] = '<?php
class _XXX_ extends Cms_Content_Module
{
    public static function render(Pelican_Controller $controller)
    {
        //exemple $return = $controller->oForm->createEditor("CONTENT_TEXT", t(\'Main text\'), false, $controller->values["CONTENT_TEXT"], $controller->readO, true, "", 650, 300);
        // {FORM}
        
        return $return;  
    }
}';
        
        $skeleton[4] = '<?php
include_once(Pelican::$config[\'APPLICATION_CONTROLLERS\'] . \'/Content.php\');

class _XXX__Controller extends Content_Controller
{

    public function indexAction()
    {
        parent::indexAction();
    }

}';
        
        $skeleton[2] = '<?php
class _XXX__Controller extends Pelican_Controller_Back {
    public function indexAction () {

        // {CODE}  

        $this->fetch();
    }
}';
        
        $skeleton[10] = '<?php
class Cms_Page__XXX_ extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        //exemple $return = $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t(\'FORM_LABEL\'), 150, "", false, $controller->values["ZONE_TITRE"], $controller->readO, 50);
        //exemple $return .= $controller->oForm->createInput($controller->multi . "ZONE_PARAMETERS", t(\'LINK\'), 255, "internallink", false, $controller->values["ZONE_PARAMETERS"], $controller->readO, 50, false);
        // {FORM}  

        return $return;
    }
}';
        
        $skeleton[20] = "<?php
class _XXX__Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        \$this->setParam('ZONE_TITRE', 'titre');
        \$this->assign('data', \$this->getParams());
        \$this->fetch();
    }
}";
        
        $skeleton[30] = "<?php

class _XXX_ extends Cms_Page_Module
{

    public static function render(Pelican_Controller \$controller)
    {
        //exemple \$return = \$controller->oForm->createEditor(\$controller->multi . \"ZONE_TEXTE\", t('Content'), false, \$controller->zoneValues[\"ZONE_TEXTE\"], \$controller->readO, true, \"\", 500, 150);
        // {FORM}  

        return \$return; 
    }
    
       public static function save(Pelican_Controller \$controller)
    {
        \$oConnection = Pelican_Db::getInstance();
        
         // {CODE}  
        
         parent::save();
    }
}";
        
        $skeleton[100] = '<?xml version="1.0" encoding="utf-8"?>
<plugin>
	<title>_XXX_</title>
	<description>_DESC_</description>
	<version>1.0</version>
	<date>_DATE_</date>
	<category>Widget</category>
</plugin>';
        
        $skeleton[110] = '<?php

/**
 * - load
 * - install
 * - uninstall
 * Appel au Pelican_Cache avec id du plugin
 *
 */
class Module__XXX_ extends Pelican_Plugin
{

    /**
     * Défintion de constantes ou traitements d\'initialisation du plugin au chargement
     *
     */
    function load ()
    {}

    /**
     * à lancer lors de l\'installation du plugin :
     * - insertion de données
     * - création d\'une table
     * - création de répertoires etc...
     */
    function install ()
    {}

    /**
     * à lancer lors de la désinstatllation
     * - suppression de tables
     * - suppression de données
     *
     */
    function uninstall ()
    {}

}';
        
        $aTmp = explode('_', $controller);
        $name = array_pop($aTmp);
        if (empty($field)) {
            $field = $name;
        }
        
        $return = str_replace(array(
            '_XXX_' , 
            'xxx' , 
            'XXX' , 
            'Xxx' , 
            'yyy' , 
            'YYY'
        ), array(
            $controller , 
            strtolower($name) , 
            strtoUpper($name) , 
            ucfirst($name) , 
            strtolower($field) , 
            strtoUpper($field)
        ), $skeleton[$type]);
        return $return;
    }
}