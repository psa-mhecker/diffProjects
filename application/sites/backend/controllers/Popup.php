<?php
pelican_import('Profiler');
include_once (pelican_path('Form'));
require_once ('Cms.php');
include_once (Pelican::$config ['APPLICATION_VIEW_HELPERS'] . '/Div.php');

class Popup_Controller extends Pelican_Controller_Back
{

    public function sortAction ()
    {

        $head = $this->getView()->getHead();
        $head->setTitle(t('Content order'));
        $this->_setSkin();
        $head->setJs("/js/navigation.js");
        $head->setJs("/library/External/scriptaculous-js/prototype.js");
        $head->setJs("/library/External/scriptaculous-js/scriptaculous.js");
        $head->setCss("/css/style.css");

        if ($this->getRequest()->isPost()) {
            if ($_POST["PAGE_ORDER"]) {
                Cms_Controller::setBatchPageOrder($_POST["PAGE_ID"], explode(",", $_POST["PAGE_ORDER"]), $_POST["uid"], $_SESSION[APP]['LANGUE_ID']);
            }
            $this->assign('header', Pelican_Html::head(Pelican_Html::script(array(), "window.close();")), false);
        } else {

            if ($_GET["pid"]) {
                $result = Pelican_Cache::fetch("Frontend/Page/ChildContent", array(
                    $_GET["pid"] ,
                    $_SESSION[APP]['SITE_ID'] ,
                    $_SESSION[APP]['LANGUE_ID'] ,
                    "DRAFT" ,
                    (($_GET["uid"] > 0 && $_GET["pid"] == 1) ? $_GET["uid"] : "") ,
                    20 ,
                    "" ,
                    "" ,
                    $_GET["id"]
                ));
                if ($result) {
                    foreach ($result as $col) {
                        foreach ($col as $link) {
                            switch ($link["TYPE"]) {
                                case "PAGE":
                                    {
                                        $img = Pelican::$config["SKIN_PATH"] . "/images/tree_table.gif";
                                        break;
                                    }
                                case "CONTENT":
                                    {
                                        $publication = Pelican_Cache::fetch("Backend/State/Publication", $link["STATE"]);
                                        if (($link["VERSION"] == 1 && $publication) || $link["VERSION"] > 1) {
                                            $img = Pelican::$config["SKIN_PATH"] . "/images/tree_file.gif";
                                            $alt = "publié";
                                        } else {
                                            $img = Pelican::$config["SKIN_PATH"] . "/images/tree_file_red.gif";
                                            $alt = "non publié";
                                        }
                                        break;
                                    }
                            }
                            if ($img) {
                                $img = Pelican_Html::img(array(
                                    src => $img ,
                                    border => 0 ,
                                    hspace => 5 ,
                                    align => "center" ,
                                    alt => $alt
                                ));
                            }
                            if ($link["CURRENT"]) {
                                $link["SHORT_TITLE"] = Pelican_Html::span(array(
                                    style => "font-weight:bold;"
                                ), $link["SHORT_TITLE"]);
                            }
                            $li[] = Pelican_Html::li(array(
                                id => "item_" . $link["ID"]
                            ), $img . "(" . $link["ID"] . ") " . $link["SHORT_TITLE"] . $link["PICTO"]);
                        }
                    }
                }
            }
            $this->assign('header', $head->getHeader(false), false);

        }

        $this->assign('li', (isset($li) ? implode("", $li) : ''));
        $this->assign('uid', ($_GET["uid"] > 0 ? $_GET["uid"] : 0));
        $this->assign('pid', $_GET["pid"]);
        $this->assign('redirect', '');

        $this->assign('doctype', $head->getDocType());
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }

    public function navigationAction ()
    {

        $head = $this->getView()
            ->getHead();

        $head->setTitle("Navigation");
        $this->_setSkin();
        $head->setJs("http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js");
        $head->setJs("/library/Pelican/Form/public/js/xt_mozilla_fonctions.js");
        
        $head->setJs("/js/navigation.js");

        $oForm = Pelican_Factory::getInstance('Form', false);
        $form = $oForm->open("");
        if (! $_GET["multiple"]) {
            $form .= "<fieldset><legend><b>" . t('Menu properties') . "</b></legend>";
            if ($_GET["img"]) {
                $pathinfo = pathinfo($_GET["img"]);
                $tmp = str_replace("." . $pathinfo["extension"], "", $pathinfo["basename"]);
                $tmp = preg_replace('/.*\./', "", $tmp);
                if ($tmp) {
                    $values["img"] = $tmp;
                }
            }
            $form .= $oForm->beginFormTable();
            $form .= $oForm->createHidden("NAVIGATION_LEVEL", "");
            $form .= $oForm->createHidden("NAVIGATION_TITLE2", "");
            $form .= $oForm->createHidden("NAVIGATION_ZONE_TEMPLATE_ID", "");
            $inputLength = 100;
            if ($_GET["type"]=="shoppingtools1") {
                $inputLength = 40;
            }
            $form .= $oForm->createInput("NAVIGATION_TITLE", t('FORM_LABEL'), $inputLength, "", true, "", false, 50);
            $form .= $oForm->createHidden("NAVIGATION_BOLD", "");
             //
            /*$form .= $oForm->createCheckBoxFromList("NAVIGATION_BOLD", t('EDITOR_BOLD'), array(
                "1" => ""
            ), "", false);*/
            if ($_GET["type"] == "navigation") {
                $form .= $oForm->createHidden("NAVIGATION_IMG", "");
                $form .= $oForm->createLabel("", "<i>" . t('Info external links') . "</i>");
                $form .= $oForm->createInput("NAVIGATION_URL", t('POPUP_MEDIA_LABEL_HTTP'), 255, "", false, "", false, 50);
                $form .= $oForm->createHidden("NAVIGATION_PARAMETERS", "");
            } elseif ($_GET["type"] == "media") {
                $form .= $oForm->createMedia("NAVIGATION_IMG", t('Image'), false, "image", "", $values["img"]);
                $form .= $oForm->createLabel("", "<i>" . t('Info external links') . "</i>");
                $form .= $oForm->createInput("NAVIGATION_URL", t('POPUP_MEDIA_LABEL_HTTP'), 255, "", false, "", false, 50);
                $form .= $oForm->createHidden("NAVIGATION_PARAMETERS", "");
            } elseif ($_GET["type"] == "accessibility") {
                $form .= $oForm->createInput("NAVIGATION_URL", t('POPUP_MEDIA_LABEL_HTTP'), 255, "", false, "", false, 50);
                $form .= $oForm->createInput("NAVIGATION_PARAMETERS", t('Accesibility key'), 1, "", false, "", false, 1);
                $form .= $oForm->createHidden("NAVIGATION_IMG", "");
            } elseif ($_GET["type"] == "lien") {
                $form .= $oForm->createHidden("NAVIGATION_IMG", "");
                $form .= $oForm->createInput("NAVIGATION_URL", t('POPUP_MEDIA_LABEL_HTTP'), 255, "", true, "", false, 50);
                $form .= $oForm->createRadioFromList("NAVIGATION_PARAMETERS", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), "", true, false);
            } elseif ($_GET["type"] == "shoppingtools1") {
                $form .= $oForm->createMedia("NAVIGATION_IMG", t('Image'), true, "image", "", $values["img"]);
                $form .= $oForm->createHidden("NAVIGATION_URL", "");
                $form .= $oForm->createHidden("NAVIGATION_PARAMETERS", "");
            } else {
                $form .= $oForm->createMedia("NAVIGATION_IMG", t('Image'), false, "image", "", $values["img"]);
                // $form .= $oForm->createInput("NAVIGATION_IMG", t('Image'), 50, "", false, "", !$admin, 50);
                $form .= $oForm->createLabel("", "<i>" . t('Info external links') . "</i>");
                $form .= $oForm->createInput("NAVIGATION_URL", t('POPUP_MEDIA_LABEL_HTTP'), 800, "", false, "", false, 50);
                $form .= $oForm->createHidden("NAVIGATION_PARAMETERS", "");
            }
            $form .= $oForm->createHidden("NAVIGATION_IMG2", "");
            $form .= $oForm->endFormTable();
            $form .= "</fieldset>";
        }
        if ($_GET["type"] != "shoppingtools1") {
            $form .= "<fieldset><legend><b>" . t('MSG_AIDE_SELECT') . "</b></legend>";
            $from_navigation = 1;
            $form .= self::internalLink($oForm, $values);
            if (! $_GET["multiple"]) {
                $form .= '<p class="bottom">
    <button onclick="return updateURLAjax(' . (Pelican::$config["CLEAR_URL"] ? "true" : "false") . ');">' . t('Url update') . '</button>
    </p></fieldset>';
            }
        }
        $form .= $oForm->close();
        /** body */
        $this->assign('multiple', $_GET["multiple"]);
        $img = "if (document.getElementById('imgdivNAVIGATION_IMG'))";
        $img .= "document.fForm.NAVIGATION_IMG.value = document.getElementById('imgdivNAVIGATION_IMG').href.replace('" . Pelican::$config["MEDIA_HTTP"] . "','');\r\n";
        $this->assign('img', $img);
        $this->assign('plan_site', $_GET["type"] == 'plan_site');
        $this->assign('media', $_GET["type"] == 'media');
        $this->assign('form', $form, false);
        $this->assign('langue_id', $_SESSION[APP]['LANGUE_ID'], false);

        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }

    public function internalLinkAction ()
    {
        $params = $this->getParams();
        $tiny = ($this->getParam('tiny')?true:false);

        $head = $this->getView()
            ->getHead();

        $head->setTitle(t('EDITOR_INTERNAL'));
		$this->_setSkin();
        $head->setJs("/js/navigation.js");
        $head->setJs("http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js");
        $head->setJs("/library/Pelican/Form/public/js/xt_text_controls.js");
        if ($tiny) {
            $head->endJs(Pelican::$config["LIB_PATH"] . '/External/tiny_mce/tiny_mce_popup.js');
            $head->endJs(Pelican::$config["LIB_PATH"] . '/External/tiny_mce/plugins/betd_internallink/js/betd_internallink.js');
        }

        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;
        $form = $oForm->open("");
        $form .= Pelican_Html::fieldset(Pelican_Html::legend(Pelican_Html::b(t('MSG_AIDE_SELECT'))) . self::internalLink($oForm));
        $form .= $oForm->close();

        $langueId = ($params['LANGUE_ID'] != '' ) ? $params['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID'];
        $this->assign('url', (Pelican::$config["CLEAR_URL"] ? "true" : "false"));
        $this->assign('form', $form, false);
        $this->assign('langueId', $langueId);
        $this->assign('tiny', $tiny);

        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }

    public function contentAction ()
    {

        if ($_GET["s"]) {
            $sess = session_id(base64_decode($_GET["s"]));
        }

        if ($_GET["s"]) {
            $this->setResponse(Pelican_Html::script("document.location.href=document.location.href.replace('&s=" . $_GET["s"] . "','');"));
        } else {
            $this->setResponse(Pelican_Request::call('_/Index/popup'));
        }
    }

    public static function internalLink ($oForm, $values = '')
    {

        $form .= '<script type="text/javascript">

var xmlDoc2 = null;
var xmlDoc  = null;

function in_array(element, arr){
	for(i=0; i<arr.length; i++ ){
		if(arr[i]==element) return true;
	}
}
function toHidElement(elementID){
	var text = top.document.getElementById(elementID);
	if(text){
		if(text.style.display==\'inline\'){
			text.style.display=\'none\';
		}
	}
}
function toDisplayElement(elementID){
	var text =

top . document . getElementById(elementID);
if (text) {
    if (text . style . display == \'none\') {
			text.style.display=\'inline\';
    }
}
}

function resetField(){
	document.fForm.NAVIGATION_PARAMETERS.value=\'\';
	document.fForm.NAVIGATION_PARAMETERS_TITLE.value=\'\';
}

function displayComboDirectory() {
	var text = top.document.getElementById("combo2");
	if (xmlDoc2.readyState == 4) {
		// only if OK
		if (xmlDoc2.status == 200) {
			text.innerHTML = xmlDoc2.responseText;
		} else {
			alert("There was a problem retrieving the data:\n" + xmlDoc2.statusText);
		}
	}
}

function changeFilter(tpl) {
	if (typeof window.ActiveXObject != \'undefined\' ) {
		xmlDoc = new ActiveXObject("Microsoft.XMLHTTP");
		xmlDoc.onreadystatechange = updateFunctionParameter ;
	} else {
		xmlDoc = new XMLHttpRequest();
		xmlDoc.onload = updateFunctionParameter ;
	}
	xmlDoc.open( "GET", \'/xt_template_content_type.php?tpl=\'+tpl, true );
	xmlDoc.send(null);
}

function updateFunctionParameter(){
	var element = fForm.bSearchCONTENT_NAVIGATION_ID;
	if (xmlDoc.readyState == 4) {
		// only if OK
		if (xmlDoc.status == 200) {
			eval(xmlDoc.responseText);
			element.onclick = changeOnClick;
		} else {
			alert("There was a problem retrieving the data:\n" + xmlDoc.statusText);
		}
	}
}
	</script>
<span style="color: red; font-weight: bold;">' . t('80 letters alert') . '</span>
';
        $form .= $oForm->beginFormTable();
        $aPage = getComboValuesFromCache("Backend/Page", array(
            $_SESSION[APP]['SITE_ID'] ,
            $_SESSION[APP]['LANGUE_ID']
        ));
        $form .= $oForm->createComboFromList("PAGE_NAVIGATION_ID", t('PAGE') . " :", $aPage, "", false, false, "1", false, "", " ", false);
        $form .= $oForm->createHidden("TPL_ID", "");
        $form .= $oForm->createHidden("POPUP_TPL_ID", "");
        $form .= $oForm->createHidden("CONTENT_NAVIGATION_ID", "");
        /*$form .= $oForm->createContentFromList("CONTENT_NAVIGATION_ID", t('Content') . "
:", "", false, false, ($_GET["multiple"] ? 13 : 1), 200, false, true);*/
        $form .= $oForm->createHidden("ZONE_TITRE", "");
        //$form .= $oForm->createMedia("ZONE_TITRE", "fichier :", false, 'file');

        if (! $oForm->_inputName["NAVIGATION_PARAMETERS"]) {
            $form .= $oForm->createHidden("NAVIGATION_PARAMETERS", "");
        }
        if (! $oForm->_inputName["NAVIGATION_URL"]) {
            $form .= $oForm->createHidden("NAVIGATION_URL", "");
        }
        $form .= $oForm->endFormTable();
        return $form;
    }


    public function sortContentFaqAction ()
    {

        $head = $this->getView()->getHead();
        $head->setTitle(t('Content order'));
        $this->_setSkin();
        $head->setJs('/js/navigation.js');
        $head->setJs('/library/External/scriptaculous-js/prototype.js');
        $head->setJs('/library/External/scriptaculous-js/scriptaculous.js');
        $head->setCss('/css/style.css');

        /* Traitement lors de la validation de l'ordre des contenus au sein de
         * la rubrique
         */
        if ($this->getRequest()->isPost()) {
            /* Initialisation des variables */
            $aContents      = explode(',', $_POST['FAQ_RUBRIQUE_CONTENT_ORDER']);
            $iFaqRubriqueId = (int)$_POST['FAQ_RUBRIQUE_ID'];
            $iLangueId      = (int)$_POST['LANGUE_ID'];
            $iSiteId        = (int)$_POST['SITE_ID'];

            if ( is_array($aContents) 
                    && !empty($iFaqRubriqueId) 
                    && !empty($iLangueId)
                    && !empty($iSiteId)
                    ) {

                //setBatchPageOrder($page, $aId, $type, $langue = 1) {
                $oConnection = Pelican_Db::getInstance();
                $sOrderTableName = '#pref#_faq_rubrique_content';

                /* Création du tableau avec les champs nécessaires pour la suppression */
                $aDeleteOrder['FAQ_RUBRIQUE_ID']    = $iFaqRubriqueId;
                $aDeleteOrder['LANGUE_ID']          = $iLangueId;
                Pelican_Db::$values = $aDeleteOrder;
                /* Suppression de l'ordre pour la rubrique de FAQ sélectionnée */
                $oConnection->deleteQuery($sOrderTableName, '', array_keys($aDeleteOrder));

                /* Insertion des contenus dans la table de gestion de l'ordre des
                 * contenus pour une rubrique de FAQ
                 */
                if ( is_array($aContents) && !empty($aContents) ) {
                    Pelican_Db::$values = array();
                    Pelican_Db::$values['FAQ_RUBRIQUE_ID']  = $iFaqRubriqueId;
                    Pelican_Db::$values['LANGUE_ID']        = $iLangueId;
                    $i = 1;
                    foreach($aContents as $iContentId) {
                        $iContentId = (int)$iContentId;
                        if (!empty($iContentId)) {
                            Pelican_Db::$values['CONTENT_ID'] = $iContentId;
                            Pelican_Db::$values['FAQ_RUBRIQUE_CONTENT_ORDER'] = $i;
                            $oConnection->insertQuery($sOrderTableName);
                            $i++;
                        }
                    }
                    Pelican_Cache::clean('Frontend/Citroen/Faq/RubriqueContent', array($iSiteId, $iLangueId, $iFaqRubriqueId), '', Pelican::$config["GROUP_DECACHE"]);
                }
            }
            /* Fermeture de la popup */
            $this->assign('header', Pelican_Html::head(Pelican_Html::script(array(), "window.close();")), false);
            
        /* Traitement de l'affichage des contenus de la rubrique dans la popup */
        } else {
            /* Initialisation des variables */
            $iSiteId        = (int)$_GET['sid'];
            $iLangueId      = (int)$_GET['lid'];
            $iFaqRubriqueId = (int)$_GET['rid'];
            $iContentTypeId = (int)$_GET['uid'];
            $iContentId     = (int)$_GET['cid'];
            $sHtmlLi        = '';

            if ( !empty($iFaqRubriqueId) && !empty($iContentTypeId)
                    && !empty($iContentId)
                    && !empty($iSiteId)
                    && !empty($iLangueId)
                ) {
                /* Récupération des contenus possédant un ordre ou pas */
                $aRubriqueContents = Pelican_Cache::fetch(
                        'Frontend/Citroen/Faq/RubriqueContent',
                        array(
                            $iSiteId,
                            $iLangueId,
                            $iFaqRubriqueId,
                            $iContentTypeId,
                            'DRAFT'
                            )
                        );
                
                /* Création de la liste des contenus */
                if ( is_array($aRubriqueContents) && !empty($aRubriqueContents) ){
                    foreach ($aRubriqueContents as $aOneContent){
                        /* Vérification que le statut en cours est un statut de publication*/
                        $bStatePublication = (bool)Pelican_Cache::fetch(
                                'Backend/State/Publication',
                                $aOneContent['STATE_ID']
                                );

                        /* On vérifie si le contenu a déjà été publié une fois
                         * pour afficher une image rouge si non publié, bleu sinon
                         * Si le contenu à plus d'une version (c'est qu'il a déjà été publié une fois)
                         * Ou si la version en cours est la première, on vérifie que son statut est
                         * un statut de publication
                         */
                        if ( $aOneContent['CONTENT_VERSION'] > 1 ||
                                ($aOneContent['CONTENT_VERSION'] == 1 && $bStatePublication === true)) {
                            $sImgHtml = Pelican_Html::img(array(
                                    src => Pelican::$config['SKIN_PATH'] . '/images/tree_file.gif' ,
                                    border => 0 ,
                                    hspace => 5 ,
                                    align => 'center' ,
                                    alt => t('FORM_PUBLISHED')
                                    ));
                        }else{
                            $sImgHtml = Pelican_Html::img(array(
                                    src => Pelican::$config['SKIN_PATH'] . '/images/tree_file_red.gif' ,
                                    border => 0 ,
                                    hspace => 5 ,
                                    align => 'center' ,
                                    alt => t('FORM_NOT_PUBLISHED')
                                    ));
                        }

                        /* Création de la liste des contenus */
                        $sTitle = $aOneContent['CONTENT_TITLE_BO'];

                        /* Le titre du contenu en cours de consultation est mis en gras */
                        if ( (int)$aOneContent['CONTENT_ID'] == $iContentId ){
                            $sTitle = Pelican_Html::span(
                                        array( style => 'font-weight:bold;' ),
                                        $aOneContent['CONTENT_TITLE_BO']);
                        }

                        $sHtmlLi .= Pelican_Html::li(
                                array( id => "item_{$aOneContent['CONTENT_ID']}"),
                                "{$sImgHtml}({$aOneContent['CONTENT_ID']}) {$sTitle}{$aOneContent['CONTENT_PICTO_URL']}"
                                );
                    }
                }
            }
            /* Assignation des variables SMARTY */
            $this->assign('header', $head->getHeader(false), false);
            $this->assign('sLi', $sHtmlLi);
            $this->assign('redirect', '');
            $this->assign('iLangueId', $iLangueId);
            $this->assign('iFaqRubriqueId', $iFaqRubriqueId);
            $this->assign('iContentTypeId', $iContentTypeId);
            $this->assign('iSiteId', $iSiteId);
            $this->assign('doctype', $head->getDocType());
            $this->assign('footer', $head->getFooter(), false);
            $this->fetch();
        }

        $this->assign('li', (isset($li) ? implode("", $li) : ''));
        $this->assign('uid', ($_GET["uid"] > 0 ? $_GET["uid"] : 0));
        $this->assign('pid', $_GET["pid"]);
        $this->assign('redirect', '');

        $this->assign('doctype', $head->getDocType());
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }
}