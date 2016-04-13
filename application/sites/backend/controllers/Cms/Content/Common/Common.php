<?php

class Cms_Content_Common_Common extends Cms_Content_Module
{

    public static function render (Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $return = '';
        if ($controller->values["PAGE_ID"]) {
            $return .= $controller->oForm
                ->createHidden("OLD_PAGE_ID", $controller->values["PAGE_ID"]);

            $sSQL = "select PAGE_PARENT_ID from #pref#_page where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
            $aBind[":PAGE_ID"] = $controller->values["PAGE_ID"];
            $aBind[":LANGUE_ID"] = $controller->values['LANGUE_ID'];
            $item = $oConnection->queryItem($sSQL, $aBind);
            $return .= $controller->oForm
                ->createHidden("OLD_PAGE_PARENT_ID", ($item == 1 ? 0 : $item));

        }

        if ($controller->id != - 2) {
            $return .= $controller->oForm
                ->createLabel("ID (cid)", $controller->values["CONTENT_ID"]);
            $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
            $url = $site["SITE_URL"];
            /*$return .= $controller->oForm
                ->createLabel(t('LINK'), Pelican_Html::a(array(
                href => "http://" . $url . $controller->values["CONTENT_CLEAR_URL"] ,
                target => "_blank"
            ), $controller->values["CONTENT_CLEAR_URL"]));*/
        }

        if (in_array($_GET['uid'], array(Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE']))) {
            $return .= $controller->oForm->createHidden("CONTENT_TITLE_BO", $controller->values["CONTENT_TITLE"]);
            $return .= $controller->oForm->createInput("CONTENT_TITLE", t('TITRE_COURT'), 255, "", true, $controller->values["CONTENT_TITLE"], $controller->readO, 100, false, "", false);
            $return .= $controller->oForm->createTextArea("CONTENT_SHORTTEXT", t('TITRE_LONG'), true, $controller->values["CONTENT_SHORTTEXT"], 1000, $controller->readO, 2, 100, false, "", false);
        } else {
            $return .= $controller->oForm
                ->createInput("CONTENT_TITLE_BO", t("GESTION_TITRE"), 1000, "", false, $controller->values["CONTENT_TITLE_BO"], $controller->readO, 100, false, "", false);
            $return .= $controller->oForm
                ->createTextArea("CONTENT_TITLE", t("PUBLIC_TITLE"), true, $controller->values["CONTENT_TITLE"], 255, $controller->readO, 2, 100, false, "", false);
        }
        //$return .= $controller->oForm->createInput("CONTENT_SUBTITLE", "Sous-Titre", 255, "", false, $controller->values["CONTENT_SUBTITLE"], $controller->readO, 100, false, "", false);



        $aPage = getComboValuesFromCache("Backend/Page", array(
            $_SESSION[APP]['SITE_ID'] ,
            $_SESSION[APP]['LANGUE_ID']
        ));
        if (!in_array($_GET['uid'], array(Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE']))) {
            $return .= $controller->oForm->createTextArea("CONTENT_SHORTTEXT", t('CHAPO'), false, $controller->values["CONTENT_SHORTTEXT"], 255, $controller->readO, 5, 100, false, "", true);
        }
        if ($controller->values["PAGE_ID"]) {
            $page = Pelican_Cache::fetch("Backend/Page", array(
                $controller->values['SITE_ID'] ,
                $controller->values['LANGUE_ID'] ,
                $controller->values["PAGE_ID"]
            ));
        }

        // Affichage de la rubrique parente avec sélection possible d'une nouvelle rubrique
        Pelican::$config['BO_USE_EXTJS_TREE'] = false;
        if (! Pelican::$config['BO_USE_EXTJS_TREE']) {
            include (Pelican::$config['APPLICATION_CONTROLLERS'] . '/Cms/Navigation.php');
            $return .= $controller->oForm
                ->createDiv("PAGE_ID", t('Linked page'), true, $controller->values["PAGE_ID"], $page["lib"], Cms_Navigation_Controller::getPageHierarchy("PAGE_ID2", "selectInputDiv", '', $controller->getView()
                ->getHead()->skinPath), $controller->readO, $controller->readO);
        } elseif (Pelican::$config['BO_USE_EXTJS_TREE']) {
            $objOTree = getPageHierarchy("PAGE_ID2", "selectInputDiv", "", true);

            $pageExtJS = Pelican_Cache::fetch("Frontend/Page/Path", array(
                $controller->values["PAGE_ID"] ,
                $controller->values['LANGUE_ID']
            ));
            $pagePath = implode('/', array_reverse($pageExtJS[2]));
            $js_selected_page = "
	Ext.onReady(function (){
		Ext.getCmp('dtreePAGE_ID" . $_SESSION[APP]['SITE_ID'] . $_SESSION[APP]['SITE_ID'] . "').doDefault('" . $pagePath . "');
	});
	";

            $return .= $controller->oForm
                ->createDiv("PAGE_ID", "Rubrique principale", true, $controller->values["PAGE_ID"], $page["lib"], $objOTree, $controller->readO, $controller->readO);
        }

        //



        $val0 = ($controller->values["CONTENT_DIRECT_PAGE"] == null && $controller->values["CONTENT_DIRECT_HOME"] == null ? 1 : $controller->values["CONTENT_DIRECT_PAGE"]);
        $aValues["1"] = t('In page') . $controller->getPageOrder($controller->values["PAGE_ID"], /*""*/$controller->values["CONTENT_TYPE_ID"], $controller->values["CONTENT_ID"]);
        $val1 = ($controller->values["CONTENT_DIRECT_PAGE"] == null && $controller->values["CONTENT_DIRECT_HOME"] == null ? 0 : ($controller->values["CONTENT_DIRECT_HOME"] ? 10 : 0));
        $aValues["10"] = t('In home') . $controller->getPageOrder(1, "", $controller->values["CONTENT_ID"]);
        $arrayCheckBoxes = array(
            $val0 ,
            $val1
        );
        $return .= $controller->oForm
            ->createCheckBoxFromList("CONTENT_DIRECT", t('Auto raise'), $aValues, $arrayCheckBoxes, false, $controller->readO, "v");

        if (isset($js_selected_page)) {
            $return .= Pelican_Html::script(array(), $js_selected_page);
        }

		//Old title pour les redirections 301
		$return .= $controller->oForm->createHidden("CONTENT_OLD_TITLE", '');

		if (in_array($_GET['uid'], array(Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE']))) {
			$return .= $controller->oForm->createJS("
				if(obj.CONTENT_SHORTTEXT.value.length > 70){
					alert('".t('ALERT_CNT_TITLE_LONG_MAX', 'js')."');
				}
			");
		}else{
			$return .= $controller->oForm->createJS("
				if(obj.CONTENT_TITLE_BO.value.length > 70){
					alert('".t('ALERT_CNT_TITLE_LONG_MAX', 'js')."');
				}
			");
		}
        return $return;

    }
}