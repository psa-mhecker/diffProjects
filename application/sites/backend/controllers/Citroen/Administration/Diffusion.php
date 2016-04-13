<?php
require_once (Pelican::$config["APPLICATION_CONTROLLERS"] . "/Administration/Directory.php");
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
require_once(Pelican::$config['LIB_ROOT'].'/Pelican/Mail.php');

class Citroen_Administration_Diffusion_Controller extends Citroen_Controller
{

    protected $administration = true;

    protected $form_name = "diffusion";

    public function editAction()
    {
        parent::editAction();
        $oConnection = Pelican_Db::getInstance();
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;
        $form = $oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($oForm);
        $form .= $oForm->beginFormTable();

        if( isset($_REQUEST['msg']) && !empty($_REQUEST['msg'])){
            $form .= '<p>' . $_REQUEST['msg'] . '</p><br/><br/>';
        }
        
        //Choix de la langue
        $sSqlLanguesSource = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY id";
		$aTemp = $oConnection->queryTab($sSqlLanguesSource);
		$aLanguesSource = array();
		if ($aTemp) {
			foreach ($aTemp as $tmp) {
				$aLanguesSource[$tmp['id']] = $tmp['lib'];
			}
		}

        // Fin choix de la langue
        
        if (count($aLanguesSource) > 1){
            $form .= $oForm->createRadioFromList("LANGUE_SOURCE", t('LANGUE_SOURCE'), $aLanguesSource, null, true, $readO, "h", false, "onchange=\"callAjax('/Citroen_Administration_Diffusion/ajaxShowArborescence',this.value);\"");
            $form .= $oForm->createFreeHtml("<div id='arborescence'></div>");
        }elseif(count($aLanguesSource) == 1){
            $form .= $oForm->createSubFormHmvc(
                "ARBO_SOURCE",
                t('ARBORESCENCE_SOURCE')." *",
                array('class' => 'Administration_Directory_Controller', 'method' => 'pageCopieColle'),
                array('SITE_ID' => $_SESSION[APP]['SITE_ID'], 'LANGUE_ID' => key($aLanguesSource)),
                $this->readO
            );
        }
        $form .= $oForm->createMultiHmvc("SITEADD", t('PAYS_CIBLES'), array(
            "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Citroen/Administration/Diffusion.php",
            "class" => "Citroen_Administration_Diffusion_Controller",
            "method" => "siteAddForm"
         ), $multiValues, "SITEADD", $this->readO, "", true, true, "SITEADD");
        $form .= $oForm->showSeparator();
        $form .= $oForm->createTextArea("TEXTE_NOTIFICATION", t('TEXTE_NOTIFICATION'), true, "", "", $this->readO, 10, 80);
        $form .= $oForm->endFormTable();
        $oForm->createJS('
            if($("#count_SITEADD").val() == -1) {
                alert(\'' . t('PAYS_CIBLES_OBLIGATOIRE', 'js2') . '\');
                return false;
            } else {
                for(i=0; i<=$("#count_SITEADD").val(); i++) {

                    if ($("#SITEADD"+i+"_multi_table select[name=\'SITE_ID\']  option:selected").val() == "") {
                        alert(\'' . t('PAYS_CIBLES_OBLIGATOIRE', 'js2') . '\');
                        return false;
                    } else {
                        for(j=0; j<=$("#SITEADD"+i+"_inputLang tr").length; j++) {
                            if ($("input[name=\'SITEADD"+i+"_LANGUE_CIBLE_"+j+"\']:checked").val() && $("select[name=\'SITEADD"+i+"_LANGUE_SOURCE_"+j+"\']  option:selected").val() == "") {
                                alert(\'' . t('LANGUE_SOURCE_OBLIGATOIRE', 'js2') . '\');
                                return false;
                            }

                        }
                    }
                }
            }
        ');
        $form .= $this->endForm($oForm);
        $form .= $oForm->close();
        $this->setResponse($form);
    }

    public function listAction()
    {
    }

    public function saveAction()
    {
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE){
            // Récupération des informations de page pour chaque site pays
            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "SITEADD");

            // Création d'un composite de page
            $pageComposite = Pelican_Factory::getInstance ('PageComposite');
            $aPagesSource   =   Pelican_Db::$values['PAGE_ID'];
            
            $pageIdAccueil  =   $this->getPageIdAccueilBySiteId($_SESSION[APP]['SITE_ID']);
            if( $pageIdAccueil == $aPagesSource[0]){
                unset( $aPagesSource[0] );
            }
            
            //On boucle sur toutes les pages à diffuser
            foreach( $aPagesSource as $pageIdSource){
                //Pour chaque site on récupère les infos sources et cibles necessaire pour la diffusion
                foreach( $aSiteAddMulti as $aSiteInfos){
                    //On ajoute une page si le site source a bien été pris en compte par le multi
                    if($aSiteInfos['multi_display'] == 1){
                        $surchargeTitleBo = ' (diff ' . date('d/m/y') . ')';
                        $etat = Pelican:: $config["PAGE_ETAT"]['BROUILLON'];

                        //La page diffusée provient du site master
                        $siteIdSource = $_SESSION[APP]['SITE_ID'];
                        // On récupère pour un site source toutes les langues cibles et sources associées
                        for ($i = 0; $i <= count($aSiteInfos); $i++) {
                            if (isset($aSiteInfos['LANGUE_CIBLE_' . $i]) && !empty($aSiteInfos['LANGUE_SOURCE_' . $i])){
                                $langIdSource = $aSiteInfos['LANGUE_SOURCE_' . $i];
                                if ($langIdSource != '-1') {
                                    $langIdCible = $aSiteInfos['LANGUE_CIBLE_' . $i];
                                    $siteIdCible = $aSiteInfos['SITE_ID'];
                                    $bDiffusion = true;
                                    $bXml = false;
                                    // On ajoute une page dans le composite
                                    $pageComposite->addPage($pageIdSource, $langIdSource, $siteIdSource,$langIdCible, $siteIdCible, $bXml, $etat, $surchargeTitleBo, $bDiffusion);
                                }
                            }
                        }
                    }
                }
            }


            //On enregistre toutes les pages dans le composite
            $aPageSave = $pageComposite->save();

            if(false == $aPageSave['ERROR']) {
                if(is_array($aPageSave['PAGE'])) {
                    $aPageSendMail = array();
                    foreach ($aPageSave['PAGE'] as $siteId => $aPages) {
                        foreach ($aPages as $pageId => $pageInfo) {
                            if( isset($pageId) && !empty($pageId)) {
                                $aPageSendMail[$siteId][$pageId] = $pageInfo['#pref#_page_version'][0]['PAGE_TITLE_BO'];
                            }
                        }
                    }
                }
                $msg = t('LA_DIFFUSION_EST_UN_SUCCES');
            }else{
                $msg = t('ERREUR_PENDANT_LA_DIFFUSION');
            }
            //On envoi un mail aux webmasteurs des sites
            if(is_array($aPageSendMail) && !empty($aPageSendMail)){
                $message = $_POST['TEXTE_NOTIFICATION'];
                foreach ($aPageSendMail as $siteId => $aTitlePages){
                    if( isset($aTitlePages) && !empty($aTitlePages)){
                        $this->sendMailDiffusion($siteId, $aTitlePages, $message);
                    }
                }
            }
        }
        Pelican_Db::$values["form_retour"] = '/_/Index/child?tid=' . Pelican::$config['TPL_DIFFUSION'] . '&id=1&msg=' . $msg;
    }


    public function sendMailDiffusion($siteId, $aTitlePages, $message){
        $siteLabel = $this->getLabelSiteBySiteId($siteId);
        $objet = 'CPP ' . $siteLabel['SITE_LABEL'] . ' information: broadcasting of content(s): ' . implode(',', $aTitlePages) . '.';

        $body = 'Contents: ' . implode(',', $aTitlePages) . ' has been broadcasted to you content menu in the home folder"';
        $body .= '<br/>';
        $body .= '<br/>';
        $body .= 'The Central Webmaster wrote you a message:';
        $body .= '<br/>';
        $body .= '<pre>' . utf8_decode($message) . '</pre>';
        $body .= '<br/>';
        $body .= '-----------------------------------';
        $body .= '<br/>';
        $body .= '<br/>';
        $body .= '"Le(s) contenu(s): ' . implode(',', $aTitlePages) . ' vous ont été diffusé dans le dossier accueil de votre arborescence."';
        $body .= '<br/>';
        $body .= '<br/>';
        $body .= 'Le Webmasteur Central vous a laissé un message:';
        $body .= '<br/>';
        $body .= '<pre>' . utf8_decode($message) . '</pre>';

        $oMail = new Pelican_Mail();
        $oMail->setSubject(utf8_decode($objet));
        $oMail->setBodyHtml(utf8_decode($body));
        $oMail->setFrom(Pelican::$config ['EMAIL']['WEBMASTEUR_CENTRAL']);
        foreach ($this->getMailWebmasteurBysiteId($siteId) as $to) {
            $oMail->addTo($to);
        }
        $oMail->send();
    }

    public function getMailWebmasteurBysiteId($siteId)
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $siteId;
        $sql = 'SELECT SITE_MAIL_WEBMASTER
                FROM #pref#_site
                WHERE `SITE_ID`  = :SITE_ID';
        $aMailSite = $oConnection->queryRow($sql, $this->aBind);
        if (is_array($aMailSite) && !empty($aMailSite)) {
            return $aMailSite;
        }
        return false;
    }

    public function getLabelSiteBySiteId($siteId)
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $siteId;
        $sql = 'SELECT SITE_LABEL
                FROM #pref#_site
                WHERE `SITE_ID`  = :SITE_ID';
        $labelSite = $oConnection->queryRow($sql, $this->aBind);
        if (is_array($labelSite) && !empty($labelSite)) {
            return $labelSite;
        }
        return false;
    }

    public function siteAddForm($oForm, $values, $readO, $multi)
    {
        $sSqlListe = "
            select
                site_id as id,
                site_label as lib
            from #pref#_site
            where site_id not in (" . Pelican:: $config['SITE_BO'] . ", " . Pelican:: $config['SITE_MASTER'] . "," . $_SESSION[APP]['SITE_ID'] . ")
            ORDER BY lib";
        $return .= $oForm->createComboFromSql($oConnection, $multi .'SITE_ID', t('PAYS_CIBLE'), $sSqlListe, $sSqlListe, true, $readO, "1", false, "", true, false, "onchange=\"callAjax('/Citroen_Administration_Diffusion/ajaxShowMappingLang', this.value, '" . $multi . "');\"");
        $return .= $oForm->createFreeHtml("<tr><td class=\"formlib\">".t('LANGUE_CIBLE')." *</td><td class=\"formval\" id =\"" . $multi . "inputLang\"></td></tr>");
        return $return;
    }

    public function ajaxShowMappingLangAction($idSiteCible, $multi = "")
    {
        $this->getRequest()->addResponseCommand('assign', array(
                'id' => $multi . 'inputLang',
                'attr' => 'innerHTML',
                'value' => ($idSiteCible)?$this->getHtmlMappingLang( $idSiteCible, $multi ):''
            )
        );
    }

    public function getHtmlMappingLang( $idSiteCible, $multi )
    {
        $oConnection = Pelican_Db::getInstance();
        $oFormAjax = Pelican_Factory::getInstance('Form', false);
        $oFormAjax->sFormName = "fForm";
        $sSqlLanguesSource = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY lib";
        $aDataValues = array(-1=>t('AUCUNE'));
        $aTemp = $oConnection->queryTab($sSqlLanguesSource);
        if ($aTemp) {
            foreach ($aTemp as $temp) {
                $aDataValues[$temp['id']] = $temp['lib'];
            }
        }
        $sSqlLanguesCible = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $idSiteCible . "
            ORDER BY lib";
        $aLanguesCible = $oConnection->queryTab($sSqlLanguesCible);
        $return = $oFormAjax->createFreeHtml("<table>");
        foreach($aLanguesCible as $idx => $langue) {
            $return .= $oFormAjax->createFreeHtml("<tr><td>");
            $return .= $oFormAjax->createCheckBoxFromList($multi . "LANGUE_CIBLE_" . $idx, "", array($langue['id'] => $langue['lib']), $langue['id'], false, false, "h", true);
            $return .= $oFormAjax->createFreeHtml("</td><td>".t('LANGUE_SOURCE')."</td><td>");
            $return .= $oFormAjax->createComboFromList($multi . "LANGUE_SOURCE_" . $idx, "", $aDataValues, null, false, $readO, "1", false, "", true, true);
            //$return .= $oFormAjax->createComboFromSql($oConnection, $multi . "LANGUE_SOURCE_" . $idx, "", $sSqlLanguesSource, null, false, $readO, "1", false, "",true, true);
            $return .= $oFormAjax->createFreeHtml("</td></tr>");
        }
        $return .= $oFormAjax->createFreeHtml("</table>");
        $sJS .= "$('#td_SITEADD input[type=checkbox]').attr('readonly', 'readonly').click(function(){ return false; });";
        $oConnection->close();
        $return .= Pelican_Html::script($sJS);
        return $return;
    }

    /* Fonction de lecture des multis */
    public static function myReadMulti($table, $prefixe){
        $aMulti = array();
        if(is_array($table) && !empty($table)){
            foreach ($table as $key => $value){
                $iPrefixeLenght = strlen($prefixe);
                if(substr($key, 0, $iPrefixeLenght) === $prefixe){
                    $aTemp=explode('_',$key);
                    $index = substr($aTemp[0],$iPrefixeLenght);
                    $rest = substr(strstr($key, '_'), 1);
                    if($rest){
                        $aMulti[$index][$rest] = $value;
                    }
                }
            }
        }
        return $aMulti;
    }
    
    /**
	 *
	 */
	public function getHtmlArboAndMappingLang($idLangSource, $idSiteSource, $idSiteCible)
	{
		$oConnection = Pelican_Db::getInstance();
		$oFormAjax = Pelican_Factory::getInstance('Form', false);
		$oFormAjax->sFormName = "fForm";

		//Gestion de l'arborescence source
		$return .= $oFormAjax->createFreeHtml("<table class='form'>");
		$return .= $oFormAjax->createFreeHtml("<tr><td>");
        
		$return .= $oFormAjax->createSubFormHmvc(
			"ARBO_SOURCE", t('ARBORESCENCE_SOURCE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageArboSource'), array('SITE_ID' => $idSiteSource,
			'LANGUE_ID' => $idLangSource), $this->readO
		);
		$return .= $oFormAjax->createFreeHtml("</td></tr>");
		$sSqlListe = "
            select
            site_id as id,
            site_label as lib
            from #pref#_site
            where site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY id";
		$return .= $oFormAjax->createComboFromSql($oConnection, $multi . 'SITE_ID', t('PAYS_CIBLE'), $sSqlListe, $_SESSION[APP]['SITE_ID'], true, true, "1", false, "", true, false);
		// Gestion du mapping de langue
		$sSqlLanguesSource = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY id";
            print_r($sSqlLanguesSource);
		$sSqlLanguesCible = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $idSiteCible . "
            ORDER BY id";
		$aLanguesCible = $oConnection->queryTab($sSqlLanguesCible);
		$return .= $oFormAjax->createFreeHtml("<tr><td class=\"formlib\">" . t('LANGUE_CIBLE') . "</td></tr>");
		foreach ($aLanguesCible as $idx => $langue) {
			$return .= $oFormAjax->createFreeHtml("<tr><td class='langues'>");
			$return .= $oFormAjax->createCheckBoxFromList("LANGUE_CIBLE_" . $idx, "", array($langue['id'] => $langue['lib']), $langue['id'], false, false, "h", true);
			$return .= $oFormAjax->createFreeHtml("</td><td>" . t('LANGUE_SOURCE') . " : ");
			$return .= $oFormAjax->createComboFromSql($oConnection, "LANGUE_SOURCE_" . $idx, "", $sSqlLanguesSource, null, false, $readO, "1", false, "", true, true);
			$return .= $oFormAjax->createFreeHtml("</td></tr>");
		}
		$return .= $oFormAjax->createFreeHtml("</table>");

		$return .= $oFormAjax->showSeparator();
		//Gestion de l'arborescence source
		$return .= $oFormAjax->createFreeHtml("<table class='form'>");
		$return .= $oFormAjax->createFreeHtml("<tr><td>");
		$return .= $oFormAjax->createSubFormHmvc(
			"ARBO_CIBLE", t('ARBORESCENCE_CIBLE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageArboCible'), array('SITE_ID' => $idSiteCible), $this->readO
		);
		$return .= $oFormAjax->createFreeHtml("</td></tr>");
		$return .= $oFormAjax->createFreeHtml("</table>");
		$sJS .= "$('#NB_LANGUES').val(" . sizeof($aLanguesCible) . ");";
		$oConnection->close();
		$return .= Pelican_Html::script($sJS);
		return $return;
	}
    
    /**
	 *
	 */
	public function ajaxShowArborescenceAction($idLangSource)
	{
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'arborescence',
			'attr' => 'innerHTML',
			'value' => $this->getHtmlArboSource($idLangSource)
			)
		);
	}
    
	/**
	 *
	 */
	public function getHtmlArboSource($idLangSource)
	{
		$oConnection = Pelican_Db::getInstance();
		$oFormAjax = Pelican_Factory::getInstance('Form', false);
		//Gestion de l'arborescence source
		$return .= $oFormAjax->createFreeHtml("<table class='form'>");
		$return .= $oFormAjax->createFreeHtml("<tr><td>");
		$return .= $oFormAjax->createSubFormHmvc(
			"ARBO_SOURCE", t('ARBORESCENCE_SOURCE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageCopieColle'), array('SITE_ID' => $_SESSION[APP]['SITE_ID'],
			'LANGUE_ID' => $idLangSource), $this->readO
		);
		$return .= $oFormAjax->createFreeHtml("</td></tr>");
		$return .= $oFormAjax->createFreeHtml("</table>");
		$oConnection->close();
		return $return;
	}
    
	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getPageIdAccueilBySiteId($siteId)
	{
		$this->aBind[':SITE_ID'] = $siteId;
		$this->aBind[':STATUT_ID'] = 1;
		$sql = 'SELECT PAGE_ID  FROM #pref#_page
                   WHERE SITE_ID      = :SITE_ID
                   AND PAGE_STATUS    = :STATUT_ID
                   AND PAGE_PARENT_ID is null
                   AND PAGE_PATH is not null
                   AND PAGE_GENERAL <> 1';
        $oConnection = Pelican_Db::getInstance();
		$aPageId = $oConnection->queryRow($sql, $this->aBind);        
		if (is_array($aPageId) && !empty($aPageId)) {
			return $aPageId['PAGE_ID'];
		}
		return false;
	}
}