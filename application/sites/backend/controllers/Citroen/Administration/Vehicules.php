<?php
/**
 * Fichier de Citroen_Vehicules :
 *
 * Classe Back-Office de contribution des véhicules de manières manuelle
 * Cette contribution sera utilisée en Front-Office pour surcharger les données
 * des véhicules renvoyées par le WebService PSA
 *
 * @package Citroen
 * @subpackage Administration
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 17/07/2013
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');

class Citroen_Administration_Vehicules_Controller extends Citroen_Controller
{

    const VISUEL_INTERIEUR = 'VISUEL_INTERIEUR';
	//protected $administration = true;
    /* Table utilisée */
    protected $form_name = "vehicule";
    /* Champ Identifiant de la table */
    protected $field_id = "VEHICULE_ID";
    /* Champ pour ordonner la liste */
    protected $defaultOrder = "VEHICULE_ID";
    /* Activation de la barre de langue */
    protected $multiLangue = true;
    /* Décache */
    protected $decacheBack = array(
        array('Citroen/GammeVehiculeGamme',
            array('SITE_ID', 'LANGUE_ID')
        ) ,
        array('Frontend/Citroen/CarSelector/Resultats',
            array('SITE_ID', 'LANGUE_ID')
        ) ,
        array('Frontend/Citroen/Finitions',
            array('VEHICULE_ID')
        ) ,
        array('Frontend/Citroen/VehiculeById',
            array('VEHICULE_ID')
        ) ,
        array('Frontend/Citroen/VehiculeDisponibleSur',
            array('SITE_ID', 'LANGUE_ID')
        ) ,
        array('Frontend/Citroen/VehiculeShowroomById',
            array('SITE_ID', 'LANGUE_ID', 'VEHICULE_ID')
        ) ,
        array('Frontend/Citroen/VehiculesParGamme',
            array('SITE_ID', 'LANGUE_ID')
        ) ,
        array('Frontend/Citroen/Finitions'),
        array('Frontend/Citroen/Finitions/Caracteristiques'),
        array('Frontend/Citroen/VehiculeByLCDVGamme'),
        array('Frontend/Citroen/Finitions/EngineList'),
        array('Frontend/Citroen/Finitions/Equipement'),
        array('Frontend/Citroen/Perso/ProduitPrefereGamme'),
        array('Frontend/Citroen/Perso/ProduitPrefereLigne'),
        array('Frontend/Citroen/Perso/Lcdv6ByProduct'),
        array('Frontend/Citroen/VehiculeExpandCTA'),
        array('Frontend/Citroen/ExpandGamme'),
        array('Frontend/Citroen/Navigation'),
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
        $aBindVehiculesList[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBindVehiculesList[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];

        /* Requête remontant l'ensemble des véhicules pour un site
         * et une langue donnée.
         */
        $sSqlVehiculesList = "
                SELECT
                    VEHICULE_ID,
                    SITE_ID,
                    LANGUE_ID,
                    VEHICULE_LCDV6_MANUAL,
                    VEHICULE_LCDV6_CONFIG,
                    VEHICULE_GAMME_MANUAL,
                    VEHICULE_GAMME_CONFIG,
                    VEHICULE_LABEL
                FROM
                    #pref#_{$this->form_name}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID ";
      if ($_GET['filter_search_keyword'] != '') {
     $sSqlVehiculesList.= " AND (
     VEHICULE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
     OR VEHICULE_LCDV6_MANUAL like '%" . $_GET['filter_search_keyword'] . "%' 
     OR VEHICULE_LCDV6_CONFIG like '%" . $_GET['filter_search_keyword'] . "%' 
     )
     ";
     }
               $sSqlVehiculesList .=  "ORDER BY {$this->listOrder}";

          $this->listModel = $oConnection->queryTab($sSqlVehiculesList,$aBindVehiculesList);
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
        $this->aBind[':VEHICULE_ID'] = (int)$this->id;

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
                    AND VEHICULE_ID = :VEHICULE_ID
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
        $oTable = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $oTable->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $oTable->getFilter(1);
        /* Mise en place des valeurs à utiliser pour le tableau de liste */
        $oTable->setValues($this->getListModel(), $this->field_id);
        /* Création du tableau en utilisant les données du setValues */
        $oTable->addColumn(t('ID'), $this->field_id, '10', 'left', '', 'tblheader', $this->field_id);
        $oTable->addColumn(t('VEHICULE_LIST_GAMMECONFIG'), 'VEHICULE_GAMME_CONFIG', '10', 'left', '', 'tblheader', 'VEHICULE_GAMME_CONFIG');
        $oTable->addColumn(t('VEHICULE_LIST_LCDV6CONFIG'), 'VEHICULE_LCDV6_CONFIG', '10', 'left', '', 'tblheader', 'VEHICULE_LCDV6_CONFIG');
        $oTable->addColumn(t('VEHICULE_LIST_GAMMEMANUAL'), 'VEHICULE_GAMME_MANUAL', '10', 'left', '', 'tblheader', 'VEHICULE_GAMME_MANUAL');
        $oTable->addColumn(t('VEHICULE_LIST_LCDV6MANUAL'), 'VEHICULE_LCDV6_MANUAL', '10', 'left', '', 'tblheader', 'VEHICULE_LCDV6_MANUAL');
        $oTable->addColumn(t('VEHICULE_NAME'), 'VEHICULE_LABEL', '50', 'left', '', 'tblheader', 'VEHICULE_LABEL');
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
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[':VEHICULE_ID'] = $this->values['VEHICULE_ID'];
        $aBind[':SITE_ID'] = $this->values['SITE_ID'];
        $aBind[':LANGUE_ID'] = $this->values['LANGUE_ID'];
        $sSQL = '
                SELECT
                   PRODUCT_ID,
                   PRODUCT_LABEL
                FROM
                    #pref#_perso_product pp
                INNER JOIN #pref#_vehicule v ON (v.VEHICULE_ID = pp.VEHICULE_ID and v.SITE_ID = :SITE_ID and v.LANGUE_ID = :LANGUE_ID)
                WHERE
                    pp.VEHICULE_ID = :VEHICULE_ID
                and pp.SITE_ID = :SITE_ID
        ';
        
        $productDependence = $oConnection->queryTab($sSQL, $aBind);
        if (is_array($productDependence) && count($productDependence)>0) {
            $error = '';
            foreach($productDependence as $p) {
                $error .= $p['PRODUCT_LABEL'].' (id : '.$p['PRODUCT_ID'].')'.Pelican_Html::br();
            }
            $error = Pelican_Html::div(
                array("class" => t('ERROR')),
                Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
            );
        }
        
        if(empty($this->values['CODE_REGROUPEMENT_SILHOUETTE'])){
        /*code de regroupement silhouette*/
        
        if(empty($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_LCDV6_MANUAL'])){
	        $sLcdv6 = $this->values['VEHICULE_LCDV6_MANUAL'];
        }elseif(!empty($this->values['VEHICULE_LCDV6_CONFIG'])){
			$sLcdv6 = $this->values['VEHICULE_LCDV6_CONFIG'];
        }
        
        
	        $aBind[':LCDV6']=$oConnection->strToBind($sLcdv6);
	        $sSQL = 'SELECT 
	        			DISTINCT CRIT_BODY_CODE,
	        			CRIT_BODY_LABEL
	        			
	        			FROM
	        			
	        			#pref#_ws_critere_selection wcs
	        			
	        			WHERE 
	        			
	        			wcs.LCDV6=:LCDV6
	        			 AND wcs.SITE_ID=:SITE_ID
	        			 AND wcs.LANGUE_ID=:LANGUE_ID
	        			 ';
	        $aCodesRegroupementSilhouette = $oConnection->queryTab($sSQL, $aBind);	
			//$aCodesRegroupementSilhouette[] =array('CRIT_BODY_CODE'=>'00000065','CRIT_BODY_LABEL'=>'Fourgon Tôlé');
        }else{

	       $aCodeSilouhetteRaw = explode(':' , $this->values['CODE_REGROUPEMENT_SILHOUETTE']); 
	       $aCodesRegroupementSilhouette[] = array(
	       											'CRIT_BODY_CODE'=>$aCodeSilouhetteRaw[0],
	       											'CRIT_BODY_LABEL'=>$aCodeSilouhetteRaw[1]
	       											); 

	       
        }		
        /* Initialisation du formulaire */
       

        $sForm = $warning.$error.$this->startStandardForm();
        $this->oForm->bDirectOutput = false;

        /* Ajout du champ caché indiquant l'identifiant du véhicule*/
        $sForm .= $this->oForm->createLabel(t('ID'), $this->id);
        /* Combo des Gammes. En base de données on n'enregistrera que la constante
         * de traduction tandis que l'on affichera se traduction
         */
        
        $this->values['COMBO_GAMME'] = Pelican::$config['VEHICULE_GAMME'];
        $sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_LABEL', t('VEHICULE_LABEL_GAMME'), $this->values['COMBO_GAMME'], $this->values['VEHICULE_GAMME_LABEL'], true, $this->readO);

        /* Combo des code LCDV des véhicules. Les données remontent de la table
         * reliées au webService psa_ws_vehicule_gamme. Les données sont en cache
         */
        $aComboLCDV = \Citroen\GammeFinition\VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], null, 'combo');
		  $aComboLcdvMtcfg= \Citroen\GammeFinition\VehiculeGamme::getVehiculesGammeMtcfg($this->getCodePays());
        
      
        $sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_LCDV6_CONFIG', t('VEHICULE_LABEL_LCDV6CONFIG'), $aComboLCDV, $this->values['VEHICULE_GAMME_CONFIG'] . '_' . $this->values['VEHICULE_LCDV6_CONFIG'], false, $this->readO);
		$sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_LCDV6_MTCFG', t('VEHICULE_GAMME_LCDV6_MTCFG'), $aComboLcdvMtcfg['COMBO'], $this->values['VEHICULE_GAMME_MTCFG'] . '_' . $this->values['VEHICULE_LCDV6_MTCFG'], false, $this->readO);
        $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_LCDV6CONFIGRELOAD'));
        
        
        /*Code de regroupement Silhouette*/
        
        if(is_array($aCodesRegroupementSilhouette)){
	        if(count($aCodesRegroupementSilhouette)==1){
	    // public function createInput($strName, $strLib, $iMaxLength = "255", $strControl = "", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $strType = "text", $aSuggest = array(), $multiple = false)
	        	        $sForm .= $this->oForm->createInput(
	        	        	'CODE_REGROUPEMENT_SILHOUETTE',
							t('CODE_REGROUPEMENT_SILHOUETTE'),
							255,
							'',
	        	            false,
	        	            sprintf(
	        	            	'%s (%s)',
	        	            	$aCodesRegroupementSilhouette[0]['CRIT_BODY_CODE'],
	        	            	$aCodesRegroupementSilhouette[0]['CRIT_BODY_LABEL']
	        	            	),
	        	            true,
	        	            44
	        	          );
	        	           $this->getView()->getHead()->setScript('function reset_crs(){
	        	           $("#CODE_REGROUPEMENT_SILHOUETTE").val("");
	        	           var  readonly_fields = $("table#tableClassForm tbody > tr:nth-child(5) td:nth-child(2)");
	        	           readonly_fields[0].innerHtml = "";
	        	           };');
	        	          //$sForm .= $this->oForm->createJs('function reset_crs(){alert("hh");}');
	        	          $sForm .= '<tr colspan="2"><td><a href="#" id="clean_code_regroupement_silhouette" onclick="reset_crs();">'.t('RESET').'</a></td></tr>' ;
	        	          
          
		        
	        }elseif(count($aCodesRegroupementSilhouette)>1){

	        
	        foreach($aCodesRegroupementSilhouette as $aOneCode){
		        $aRegroupementValues[sprintf(
	        	            	'%s:%s',
	        	            	$aOneCode['CRIT_BODY_CODE'],
	        	            	$aOneCode['CRIT_BODY_LABEL']
	        	            	)] = sprintf(
	        	            	'%s (%s)',
	        	            	$aOneCode['CRIT_BODY_CODE'],
	        	            	$aOneCode['CRIT_BODY_LABEL']
	        	            	) ;
	        }
	        
		         $sForm .= $this->oForm->createComboFromList('CODE_REGROUPEMENT_SILHOUETTE', t('CODE_REGROUPEMENT_SILHOUETTE'), $aRegroupementValues, $this->values['CODE_REGROUPEMENT_SILHOUETTE'], false, $this->readO);
	        }
        }
        
        /* Nom du véhicule */
        $sForm .= $this->oForm->createInput('VEHICULE_LABEL', t('VEHICULE_LABEL_NAME'), 255, '', true, $this->values['VEHICULE_LABEL'], $this->readO, 44);

        /* Categorie du Véhicule */
        $SQL = "
            SELECT
                CATEG_VEHICULE_ID,
                CATEG_VEHICULE_LABEL
            FROM
                #pref#_categ_vehicule
            WHERE
                SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID";

        $Values = $oConnection->queryTab($SQL, $aBind);      
        $aComboCateg = array();
        foreach($Values as $OneValue){
            $aComboCateg[$OneValue['CATEG_VEHICULE_LABEL']] = $OneValue['CATEG_VEHICULE_LABEL'];
        }
        unset($Values);
        if(!empty($aComboCateg))
            $sForm .= $this->oForm->createComboFromList('VEHICULE_CATEG_LABEL', t('CATEG_VEHICULE_LABEL'), $aComboCateg, $this->values['VEHICULE_CATEG_LABEL'], false, $this->readO);

        /* Code LCDV6 manuel du véhicule */
        $sForm .= $this->oForm->createInput('VEHICULE_LCDV6_MANUAL', t('VEHICULE_LABEL_LCDVMANUAL'), 6, '', false, $this->values['VEHICULE_LCDV6_MANUAL'], $this->readO, 10);
        /* Code Gamme manuel du véhicule */
        $this->values['RADIO_GAMME_MANUAL']['VP'] = t('VEHICULE_LABEL_GAMMEVP');
        $this->values['RADIO_GAMME_MANUAL']['VU'] = t('VEHICULE_LABEL_GAMMEVU');
        $sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_MANUAL', t('VEHICULE_LABEL_GAMMEVP') . '/' . t('VEHICULE_LABEL_GAMMEVU').' ('.t('SI_PAS_DE_WEBSERVICE').')', $this->values['RADIO_GAMME_MANUAL'], $this->values['VEHICULE_GAMME_MANUAL'], false, $this->readO);
       /* Vignette du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_THUMBNAIL', t('VEHICULE_LABEL_THUMBNAIL'), true, 'image', '', $this->values['VEHICULE_MEDIA_ID_THUMBNAIL'],$this->readO, true, false, '16_9');
        /* Visuel de fond WEB 1 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB1', t('VEHICULE_LABEL_MEDIAWEB').' 1', true, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB1'],$this->readO, true, false, '16_9');
        /* Visuel de fond WEB 2 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB2', t('VEHICULE_LABEL_MEDIAWEB').' 2', false, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB2'],$this->readO, true, false, '16_9');
        /* Visuel de fond WEB 3 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB3', t('VEHICULE_LABEL_MEDIAWEB').' 3', false, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB3'],$this->readO, true, false, '16_9');
        /* Visuel de fond Mobile des véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_MOB', t('VEHICULE_LABEL_MEDIAMOB'), true, 'image', '', $this->values['VEHICULE_MEDIA_ID_MOB'],$this->readO, true, false, '16_9');

        /* Génération du multi pour les teintes du véhicules */
        $sForm .= $this->oForm->createMultiHmvc($this->multi .'ADDCOLOUR', t('VEHICULE_COLOURS'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addFormColour'
         ), self::getMultiColoursValues(), $this->multi . 'ADDCOLOUR', $this->readO, '', true, true, $this->multi . "ADDCOLOUR");
        
        /* Multi CTA Visible sur le showroom color picker en 4eme image */
        $sForm .= $this->oForm->createMultiHmvc($this->multi .'ADDCTASHOWROOM', t('VEHICULE_CTA_SHOWROOM'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addFormCtaShowroom'
         ), self::getMultiCtaShowroomValues(), $this->multi . 'ADDCTASHOWROOM', $this->readO, '2', true, true, $this->multi . "ADDCTASHOWROOM");
        
        $CtaExpandValues = self::getMultiCtaExpandValues();
        
        $sForm .= $this->oForm->createTitle(t('AJOUTER_CTA_SUR_EXPAND_GAMME'));       
         $sForm .= $this->oForm->createCheckBoxFromList($this->multi . "DISPLAY_CTA_DISCOVER_CHECKBOX", t('DISPLAY_CTA_DISCOVER'), array(1 => ""), (count($CtaExpandValues)?"1":$this->values['DISPLAY_CTA_DISCOVER']),
                     false, $this->readO, "h",  false,  (count($CtaExpandValues)?"disabled='disabled'":""));
        $sForm .= $this->oForm->createHidden($this->multi . "DISPLAY_CTA_DISCOVER",  (count($CtaExpandValues)?"1":$this->values['DISPLAY_CTA_DISCOVER']),true);
        $sForm .= $this->oForm->createRadioFromList($this->multi . "MODE_OUVERTURE_SHOWROOM", t('MODE_OUVERTURE_SHOWROOM'), array('1' => "_self", '2' => "_blank"), $this->values['MODE_OUVERTURE_SHOWROOM'], true, $this->readO);
        
        /* Multi CTA Visible sur le showroom color picker en 4eme image */
        $sForm .= $this->oForm->createMultiHmvc($this->multi.'ADDCTAEXPAND', t('VEHICULE_CTA_EXPAND'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addFormCtaExpand'
            ), $CtaExpandValues, $this->multi.'ADDCTAEXPAND', $this->readO, '2', true, true, $this->multi."ADDCTAEXPAND");
        $sForm.= "<script type=\"text/javascript\">\n" .
            '$(".buttonmulti[name=\''.$this->multi.'ADDCTAEXPAND\']").click(function(){'
            .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER\']").val(1);'
            .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("checked",true).attr("disabled",true);'
            . '});'
            .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").change(function(){'
            .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER\']").val($(this).val());});'
            .' $(document).on("click",".'.$this->multi.'ADDCTAEXPAND_subForm.multi input.btn_delete_clone",function(){'
            .'  if( ($(".'.$this->multi.'ADDCTAEXPAND_subForm.multi").length-1) == 0){'
            .'      $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("disabled",false); '
            .'  }else{'
            .'      $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("checked",true).attr("disabled",true);'
            . ' }'
            . '});'
            .'</script>';
			
		
			
			
        /* Affichage du prix. En base de données on n'enregistrera que la constante
         * de traduction tandis que l'on affichera se traduction
         */
        
       /* Gestion des visuels 360° */
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':VEHICULE_ID'] = $this->id;
		$aBind[':TYPE_VISUEL'] = $oConnection->strtobind(self::VISUEL_INTERIEUR);
		
        $sqlVisuelInterieur360 = <<<SQL
                SELECT
                   *
                FROM
                    #pref#_vehicule_media
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID
					AND TYPE_VISUEL = :TYPE_VISUEL                
SQL;
        $values = $oConnection->queryRow($sqlVisuelInterieur360, $aBind);	   
        $sForm .= $this->oForm->createLabel('', t('VUE-INT'));
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID', t('VISUEL_INT_TOP'), false, 'image', '', $values['MEDIA_ID'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID2', t('VISUEL_INT_RIGHT'), false, 'image', '', $values['MEDIA_ID2'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID3', t('VISUEL_INT_BACK'), false, 'image', '', $values['MEDIA_ID3'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID4', t('VISUEL_INT_BOTTOM') , false, 'image', '', $values['MEDIA_ID4'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID5', t('VISUEL_INT_LEFT'), false, 'image', '', $values['MEDIA_ID5'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createMedia('INTERIEUR_MEDIA_ID6', t('VISUEL_INT_FRONT'), false, 'image', '', $values['MEDIA_ID6'],$this->readO, true, false, '960x960');
        $sForm .= $this->oForm->createCheckBoxFromList("AFFICHAGE_VISUEL_360_WEB", t('ACTIVER-VUE-INT-MOBILE'), array(1 => t('WEB')), $this->values['AFFICHAGE_VISUEL_360_WEB'], false, $this->readO);
        $sForm .= $this->oForm->createCheckBoxFromList("AFFICHAGE_VISUEL_360_MOBILE", t('ACTIVER-VUE-INT-DESKTOP'), array(1 => t('MOBILE')), $this->values['AFFICHAGE_VISUEL_360_MOBILE'], false, $this->readO);
        $sForm .= $this->oForm->showSeparator();
                
        if (!empty($this->values['VEHICULE_DISPLAY_CASH_PRICE'])) {
            $this->values['CHECKBOX_DISPLAY_PRICE'][] = 1;
        }
        if (!empty($this->values['VEHICULE_DISPLAY_CREDIT_PRICE'])) {
            $this->values['CHECKBOX_DISPLAY_PRICE'][] = 2;
        }
        $sForm .= $this->oForm->createCheckBoxFromList('VEHICULE_DISPLAY_PRICE', t('VEHICULE_LABEL_DISPLAYPRICE'), array(1 => t('VEHICULE_LABEL_DISPLAYCASHPRICE'), 2 => t('VEHICULE_LABEL_DISPLAYCREDITPRICE')), $this->values['CHECKBOX_DISPLAY_PRICE'], false, $this->readO);

        /* Prix comptant. Si le code LCDV provient du Configurateur,
         * le champ est pré-rempli avec le prix en provenance du Configurateur. Il n’est pas modifiable.
         * Si le code LCDV est saisi manuellement, le champ est modifiable.
         */
        if (array_key_exists('VEHICULE_LCDV6_CONFIG', $this->values) && !empty($this->values['VEHICULE_LCDV6_CONFIG'])){
           /* Récupération des finitions pour le véhicule sélectionné dans la
            * combo du code LCDV du configurateur
            */
			// CPW-3404
            $aShowroomVehicule = \Citroen\GammeFinition\VehiculeGamme::getShowRoomVehicule($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_ID']);
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CASHPRICE'), $aShowroomVehicule[0]['VEHICULE']['CASH_PRICE']);
			//FIN CPW-3404
			//$aVehiculeCashPrice =   \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeFirstCashPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_LCDV6_CONFIG'] );
			//$sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CASHPRICE'), $aVehiculeCashPrice);
            $sForm .= $this->oForm->createHidden('VEHICULE_CASH_PRICE', '');
        }else{
            /* Prix comptant */
            $sForm .= $this->oForm->createInput('VEHICULE_CASH_PRICE', t('VEHICULE_LABEL_CASHPRICE'), 255, '', true, $this->values['VEHICULE_CASH_PRICE'], false, 44);
        }
		

        /* Type de prix comptant  Les valeurs disponibles sont : TTC/HT */
        $this->values['RADIO_CASH_PRICE_TYPE'] = Pelican::$config['CASH_PRICE_TAXE'];
        if( !isset($this->values) ||
                ( is_array($this->values) && ( !isset($this->values['VEHICULE_CASH_PRICE_TYPE']) || empty($this->values['VEHICULE_CASH_PRICE_TYPE'])))
          ){
            $this->values['VEHICULE_CASH_PRICE_TYPE'] = Pelican::$config['TAXE_TYPE']['TTC'];
        }
        $sForm .= $this->oForm->createRadioFromList('VEHICULE_CASH_PRICE_TYPE', t('VEHICULE_LABEL_CASHPRICETYPE'), $this->values['RADIO_CASH_PRICE_TYPE'], $this->values['VEHICULE_CASH_PRICE_TYPE'], true, $this->readO);

        /* Mention légale prix comptant	*/
		//$sForm .= $this->oForm->createEditor ( "VEHICULE_CASH_PRICE_LEGAL_MENTION", t('VEHICULE_LABEL_CASHPRICE_LM'), "", $this->values ["VEHICULE_CASH_PRICE_LEGAL_MENTION"], $this->readO, true, "", 500, 100 );
        $sForm .= $this->oForm->createTextArea('VEHICULE_CASH_PRICE_LEGAL_MENTION', t('VEHICULE_LABEL_CASHPRICE_LM'), false, $this->values['VEHICULE_CASH_PRICE_LEGAL_MENTION'], 255, $this->readO, 5, 50);
        /* Un exemple sera renseigné à la suite du champ : « Tarif CITROËN TTC conseillé en vigueur ». */
        $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_CASHPRICE_LM_EX'));

        /* Utiliser le Simulateur Financier Groupe */
        if (!empty($this->values['VEHICULE_USE_FINANCIAL_SIMULATOR'])) {
            $this->values['CHECKBOX_DISPLAY_PRICE'][] = 1;
        }
        $sForm .= $this->oForm->createCheckBoxFromList('VEHICULE_USE_FINANCIAL_SIMULATOR', t('VEHICULE_LABEL_USEFINANCIALSIMULATOR'), array(1 => t('VEHICULE_LABEL_USEFINANCIALSIMULATOR')), $this->values['VEHICULE_USE_FINANCIAL_SIMULATOR'], false, $this->readO);
        $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_USEFINANCIALSIMULATOR_RELOAD'));

        /*
         * Si la case a cocher « Utiliser le Simulateur Financier Groupe » est activée
         * et que le prix au comptant provient du configurateur, alors les champs suivants proviennent
         * du WS financier
         * Prix à crédit : Loyer suivant
         * Mention légale loyer suivant
         * Prix à crédit : premier loyer
         * Mention légale : premier loyer
         */
        if (array_key_exists('VEHICULE_LCDV6_CONFIG', $this->values) && !empty($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_USE_FINANCIAL_SIMULATOR'])){
            $aFinancement = $this->getCreditPriceValues();
            /* Récupération du Prix à crédit : Loyer suivant/à partir de */
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CREDITPRICE_NR'), $aFinancement['VEHICULE_CREDIT_PRICE_NEXT_RENT']);
            $sForm .= $this->oForm->createHidden('VEHICULE_CREDIT_PRICE_NEXT_RENT', '');

            /* Récupération de la Mention légale loyer suivant */
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CREDITPRICE_NR_LM'), $aFinancement['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION']);
            $sForm .= $this->oForm->createHidden('VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION', '');

            /* Récupération du Prix à crédit : premier loyer */
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CREDITPRICE_FR'), $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
            $sForm .= $this->oForm->createHidden('VEHICULE_CREDIT_PRICE_FIRST_RENT', '');

            /* Récupération du Mention légale : premier loyer */
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CREDITPRICE_FR_LM'), $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION']);
            $sForm .= $this->oForm->createHidden('VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION', '');

        }else{
            /* Prix à crédit : Loyer suivant/à partir de */
            $sForm .= $this->oForm->createInput('VEHICULE_CREDIT_PRICE_NEXT_RENT', t('VEHICULE_LABEL_CREDITPRICE_NR'), '', '', false, $this->values['VEHICULE_CREDIT_PRICE_NEXT_RENT'], $this->readO, 44);
            /* un exemple sera renseigné à la suite du champ : « 500€/mois ». */
            $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_CREDITPRICE_NR_EX'));

             /* Mention légale loyer suivant */
			//$sForm .= $this->oForm->createEditor ( "VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION", t('VEHICULE_LABEL_CREDITPRICE_NR_LM'), "", $this->values ["VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION"], $this->readO, true, "", 500, 100 );
            $sForm .= $this->oForm->createTextArea('VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION', t('VEHICULE_LABEL_CREDITPRICE_NR_LM'), false, $this->values['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'], '', $this->readO, 5, 50);
            /* Un exemple sera renseigné à la suite du champ : « Tarif CITROËN TTC conseillé en vigueur ». */
            //$sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_CREDITPRICE_NR_LM_EX'));

            /* Prix à crédit : premier loyer */
            $sForm .= $this->oForm->createInput('VEHICULE_CREDIT_PRICE_FIRST_RENT', t('VEHICULE_LABEL_CREDITPRICE_FR'), '', '', false, $this->values['VEHICULE_CREDIT_PRICE_FIRST_RENT'], $this->readO, 44);
            /* un exemple sera renseigné à la suite du champ : « 500€/mois ». */
            $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_CREDITPRICE_FR_EX'));

            /* Mention légale : premier loyer */
			//$sForm .= $this->oForm->createEditor ( "VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION", t('VEHICULE_LABEL_CREDITPRICE_FR_LM'), "", $this->values ["VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION"], $this->readO, true, "", 500, 100 );
            $sForm .= $this->oForm->createTextArea('VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION', t('VEHICULE_LABEL_CREDITPRICE_FR_LM'), false, $this->values['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'], '', $this->readO, 5, 50);
            /* Un exemple sera renseigné à la suite du champ : « Tarif CITROËN TTC conseillé en vigueur ». */
            $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_CREDITPRICE_FR_LM_EX'));

        }

        /*$this->oForm->createJS("
            var cnt = $('#count_ADDCOLOUR').val();
            var noBackTeinte2 = 0;
            var BackNoTeinte2 = 0;
            var noBackTeinte3 = 0;
            var BackNoTeinte3 = 0;

            for (i=0;i<=cnt;i++) {
                var AddColourI2 = $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2').val();
                var AddColourI3 = $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3').val();

                if ($('#VEHICULE_MEDIA_ID_WEB2').val()!='' && ( $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2').val()=='' || typeof AddColourI2 == 'undefined') ) {
                    BackNoTeinte2 = 1;
                } else if ($('#VEHICULE_MEDIA_ID_WEB2').val()=='' && ( $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2').val()!='' && typeof AddColourI2 != 'undefined') ) {
                    noBackTeinte2 = 1;
                }

                if ($('#VEHICULE_MEDIA_ID_WEB3').val()!='' && ( $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3').val()=='' || typeof AddColourI3 == 'undefined')) {
                    BackNoTeinte3 = 1;
                } else if ($('#VEHICULE_MEDIA_ID_WEB3').val()=='' && ( $('#ADDCOLOUR'+i+'_VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3').val()!='' && typeof AddColourI3 != 'undefined')) {
                    noBackTeinte3 = 1;
                }
            }

            if (BackNoTeinte2) {
                alert('".t('BACK_BUT_NO_COLOR_2', 'js')."');
            } else if (noBackTeinte2) {
                alert('".t('NO_BACK_BUT_COLOR_2', 'js')."');
            }

            if (BackNoTeinte3) {
                alert('".t('BACK_BUT_NO_COLOR_3', 'js')."');
            } else if (noBackTeinte3) {
                alert('".t('NO_BACK_BUT_COLOR_3', 'js')."');
            }

            if (BackNoTeinte2 || BackNoTeinte3 || noBackTeinte2 || noBackTeinte3) return false;

");*/

        /* Filtre de type 1 */
        $aBindCritType[':SITE_ID']      = (int)$_SESSION[APP]['SITE_ID'];
        $aBindCritType[':LANGUE_ID']    = (int)$_SESSION[APP]['LANGUE_ID'];
        $aBindCritType[':VEHICULE_ID']  = (int)$this->values['VEHICULE_ID'];
        $aBindCritType[':CRITERE_TYPE'] = 1;
        $sSqlAllCritType = <<<SQL
                SELECT
                    CRITERE_ID ID,
                    CRITERE_LABEL_INTERNE LIB
                FROM
                    #pref#_critere
                WHERE
                    SITE_ID = :SITE_ID
                    AND CRITERE_TYPE = :CRITERE_TYPE
                    AND LANGUE_ID = :LANGUE_ID
                ORDER BY CRITERE_ORDER
SQL;

        $sSqlSelectedCritType = <<<SQL
                SELECT
                    vc.CRITERE_ID ID,
                    c.CRITERE_LABEL_INTERNE LIB
                FROM
                    #pref#_vehicule_criteres vc
                        INNER JOIN #pref#_critere c ON (c.CRITERE_ID = vc.CRITERE_ID AND c.LANGUE_ID = :LANGUE_ID and c.SITE_ID = :SITE_ID )
                WHERE
                    vc.SITE_ID = :SITE_ID
                    AND vc.LANGUE_ID = :LANGUE_ID
                    AND vc.VEHICULE_ID = :VEHICULE_ID
                    AND c.CRITERE_TYPE = :CRITERE_TYPE
                ORDER BY CRITERE_ORDER
SQL;
        /* Filtre de type 1 */
        $sForm .= Backoffice_Form_Helper::createSimpleAssocFromSQL($this, 'ASSOC_CRIT_TYPE1', t('VEHICULE_LABEL_ASSOCCRITTYPE').' 1', $sSqlAllCritType, $aBindCritType, $sSqlSelectedCritType, $aBindCritType,  false, true, $this->readO);
        /* Filtre de type 2 */
        $aBindCritType[':CRITERE_TYPE'] = 2;
        $sForm .= Backoffice_Form_Helper::createSimpleAssocFromSQL($this, 'ASSOC_CRIT_TYPE2', t('VEHICULE_LABEL_ASSOCCRITTYPE').' 2', $sSqlAllCritType, $aBindCritType, $sSqlSelectedCritType, $aBindCritType,  false, true, $this->readO);
        /* Filtre de type 3 */
        $aBindCritType[':CRITERE_TYPE'] = 3;
        $sForm .= Backoffice_Form_Helper::createSimpleAssocFromSQL($this, 'ASSOC_CRIT_TYPE3', t('VEHICULE_LABEL_ASSOCCRITTYPE').' 3', $sSqlAllCritType, $aBindCritType, $sSqlSelectedCritType, $aBindCritType,  false, true, $this->readO);

        $this->oForm->createJs('alert("'.addslashes(t(WARNING_CHECK_VEHICULE)).'");');

        $sForm .= $this->stopStandardForm();
        $sFinalForm = formToString ($this->oForm, $sForm);

        if(is_array($productDependence) && count($productDependence)> 0 && $this->form_action == 'DEL'){
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $this->setResponse($sFinalForm);
    }

    /**
     * Méthode privée permettant de remonter l'ensemble des couleurs pour
     * un véhicule, un site et une langue
     * @return array
     */
    private function getMultiColoursValues ()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindVehiculesCouleur[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBindVehiculesCouleur[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBindVehiculesCouleur[':VEHICULE_ID'] = $this->id;

        /* Requête remontant l'ensemble des teintes pour un véhicule pour un site
         * et une langue donnée.
         */
        $sSqlVehiculesCouleur = <<<SQL
                SELECT
                   *
                FROM
                    #pref#_{$this->form_name}_couleur
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID
                ORDER BY VEHICULE_COULEUR_ORDER
SQL;
        $aResult = $oConnection->queryTab($sSqlVehiculesCouleur,$aBindVehiculesCouleur);
        return $aResult;
    }
    
    private function getMultiCtaShowroomValues()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindVehiculesCtaShowroom[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBindVehiculesCtaShowroom[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBindVehiculesCtaShowroom[':VEHICULE_ID'] = $this->id;
        
        /* Requête remontant l'ensemble des cta showroom pour un véhicule pour un site
         * et une langue donnée.
         */
        $sSqlVehiculesCtaShowroom = <<<SQL
                SELECT
                   *
                FROM
                    #pref#_{$this->form_name}_cta_showroom
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID
                ORDER BY PAGE_ZONE_MULTI_ORDER
SQL;
        $aResult = $oConnection->queryTab($sSqlVehiculesCtaShowroom, $aBindVehiculesCtaShowroom);
        return $aResult;
    }
    
     private function getMultiCtaExpandValues()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindVehiculesCtaExpand[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBindVehiculesCtaExpand[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBindVehiculesCtaExpand[':VEHICULE_ID'] = $this->id;

        /* Requête remontant l'ensemble des cta expand gamme pour un véhicule pour un site
         * et une langue donnée.
         */
        $sSqlVehiculesCtaExpand = <<<SQL
                SELECT
                   *
                FROM
                    #pref#_{$this->form_name}_cta_expand
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND VEHICULE_ID = :VEHICULE_ID
                ORDER BY PAGE_ZONE_MULTI_ORDER
SQL;
        $aResult = $oConnection->queryTab($sSqlVehiculesCtaExpand, $aBindVehiculesCtaExpand);
		
		if(is_array($aResult) && sizeof($aResult)>0){
			foreach($aResult as $iKey=>$aResultCtaExpand){
				if (!empty($aResultCtaExpand['VEHICULE_CTA_EXPAND'])) {
					$aResult[$iKey]['VEHICULE_CTA_EXPAND_VALUES'][] = 1;
				}
				if (!empty($aResultCtaExpand['VEHICULE_CTA_EXPAND_HOME'])) {
					$aResult[$iKey]['VEHICULE_CTA_EXPAND_VALUES'][] = 2;
				}
				if (!empty($aResultCtaExpand['VEHICULE_CTA_EXPAND_MASTER'])) {
					$aResult[$iKey]['VEHICULE_CTA_EXPAND_VALUES'][] = 3;
				}
			}
		}
        return $aResult;
    }
    /**
     * Méthode statique de création du formulaire multiple
     * @param object    $oForm          Objet de la classe Form
     * @param array     $aValues        Tableau de données permettant de remplir les multi
     * @param mixed     $mReadO         Null ou false pour permettre la saisie dans le multi
     *                                  true pas de saisie possible
     * @param string    $sMultiLabel    Préfixe des champs du multi
     * @return string   $sMultiForm     Formulaire généré
     */
    public static function addFormCtaShowroom($oForm, $aValues, $mReadO, $sMultiLabel)
    {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = 'SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM 
                    #pref#_barre_outils
                WHERE
                    BARRE_OUTILS_MODE_OUVERTURE IN (1,2)
                GROUP BY SITE_ID, LANGUE_ID, ID
                HAVING 
                    SITE_ID = :SITE_ID 
                AND LANGUE_ID = :LANGUE_ID';
        $val = $oConnection->queryTab($sql, $aBind);

        foreach ($val as $outil) {
            $aDataOutilWeb[$outil['ID']] = $outil['LIB'];
        }

        $sMultiForm .= $oForm->createComboFromList("{$sMultiLabel}VEHICULE_CTA_SHOWROOM_OUTIL", t("CTA_OUTIL"), $aDataOutilWeb, $aValues["VEHICULE_CTA_SHOWROOM_OUTIL"], false, $readO);
        $sMultiForm .= $oForm->showSeparator("formSep");
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_CTA_SHOWROOM_LABEL", t('LIBELLE'), 100, '', false, $aValues['VEHICULE_CTA_SHOWROOM_LABEL'], $mReadO, 75);
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_CTA_SHOWROOM_URL", t('URL_WEB'), 100, 'internallink', false, $aValues['VEHICULE_CTA_SHOWROOM_URL'], $mReadO, 75);
        $sMultiForm .= $oForm->createComboFromList("{$sMultiLabel}VEHICULE_CTA_SHOWROOM_VALUE", t("MODE_OUVERTURE"), $aDataValues, strtoupper($aValues["VEHICULE_CTA_SHOWROOM_VALUE"]), false, $readO);

        return $sMultiForm;
    }

    /**
     * Méthode statique de création du formulaire multiple
     * @param object    $oForm          Objet de la classe Form
     * @param array     $aValues        Tableau de données permettant de remplir les multi
     * @param mixed     $mReadO         Null ou false pour permettre la saisie dans le multi
     *                                  true pas de saisie possible
     * @param string    $sMultiLabel    Préfixe des champs du multi
     * @return string   $sMultiForm     Formulaire généré
     */
    public static function addFormCtaExpand($oForm, $aValues, $mReadO, $sMultiLabel)
    {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
		$aDataValuesAfficherSur = Pelican::$config['VEHICULES']["AFFICHER_SUR"] ;
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = 'SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM 
                    #pref#_barre_outils
                WHERE
                    BARRE_OUTILS_MODE_OUVERTURE IN (1,2)
                GROUP BY SITE_ID, LANGUE_ID, ID
                HAVING 
                    SITE_ID = :SITE_ID 
                AND LANGUE_ID = :LANGUE_ID';
        $val = $oConnection->queryTab($sql, $aBind);

        foreach ($val as $outil) {
            $aDataOutilWeb[$outil['ID']] = $outil['LIB'];
        }
	
        

        $sMultiForm .= $oForm->createComboFromList("{$sMultiLabel}VEHICULE_CTA_EXPAND_OUTIL", t("CTA_OUTIL"), $aDataOutilWeb, $aValues["VEHICULE_CTA_EXPAND_OUTIL"], false, $readO);
        $sMultiForm .= $oForm->showSeparator("formSep");
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_CTA_EXPAND_LABEL", t('LIBELLE'), 100, '', false, $aValues['VEHICULE_CTA_EXPAND_LABEL'], $mReadO, 75);
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_CTA_EXPAND_URL", t('URL_WEB'), 100, 'internallink', false, $aValues['VEHICULE_CTA_EXPAND_URL'], $mReadO, 75);
        $sMultiForm .= $oForm->createComboFromList("{$sMultiLabel}VEHICULE_CTA_EXPAND_VALUE", t("MODE_OUVERTURE"), $aDataValues, strtoupper($aValues["VEHICULE_CTA_EXPAND_VALUE"]), false, $readO);
		$sMultiForm .= $oForm->createCheckBoxFromList("{$sMultiLabel}VEHICULE_CTA_EXPAND_VALUES", t('AFFICHER_SUR').'*',$aDataValuesAfficherSur, $aValues['VEHICULE_CTA_EXPAND_VALUES'], false, $readO);
		// $sMultiForm.= "<script type=\"text/javascript\">\n" .
            // '$(".buttonmulti[name=\''.$this->multi.'ADDCTAEXPAND\']").click(function(){'
            // .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER\']").val(1);'
            // .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("checked",true).attr("disabled",true);'
            // . '});'
            // .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").change(function(){'
            // .' $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER\']").val($(this).val());});'
            // .' $(document).on("click",".'.$this->multi.'ADDCTAEXPAND_subForm.multi input.btn_delete_clone",function(){'
            // .'  if( ($(".'.$this->multi.'ADDCTAEXPAND_subForm.multi").length-1) == 0){'
            // .'      $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("disabled",false); '
            // .'  }else{'
            // .'      $("input[name=\''.$this->multi.'DISPLAY_CTA_DISCOVER_CHECKBOX\']").attr("checked",true).attr("disabled",true);'
            // . ' }'
            // . '});'
            // .'</script>';
			
			
			
			// $sMultiForm .= $oForm->createJS('
			
			// console.log($("input[name=\"'.$sMultiLabel.'VEHICULE_CTA_EXPAND_VALUES\"]").is(":checked"));
			// console.log($("input[name=\"'.$sMultiLabel.'VEHICULE_CTA_EXPAND_VALUES[]\"]").attr("name"));
			// console.log($("input[name=\"'.$sMultiLabel.'VEHICULE_CTA_EXPAND_VALUES[]\"]").attr("name").is(":checked"));
			
			// $("input:checkbox[name=\"ADDCTAEXPAND0_VEHICULE_CTA_EXPAND_VALUES\"]").click(function() {
			  // alert("ddddd");
			// });
			// var cheked = $("input[name=\"'.$sMultiLabel.'VEHICULE_CTA_EXPAND_VALUES[]\"]").is(":checked");
			
				// $.each($("input[name=\'ADDCTAEXPAND0_VEHICULE_CTA_EXPAND_VALUES[]\']"), function(){    
				// $(this).bind(
                    // "click",
                    // function() {
                        // favorite.push($(this).val());
                        // alert("hoooooo");
                    // });

            // });

			// if(cheked ==false){
				// $("#FIELD_BLANKS").val(1);;
				// return false;
			// }
			// ');
	   return $sMultiForm;
    }

    /**
     * Méthode statique de création du formulaire multiple
     * @param object    $oForm          Objet de la classe Form
     * @param array     $aValues        Tableau de données permettant de remplir les multi
     * @param mixed     $mReadO         Null ou false pour permettre la saisie dans le multi
     *                                  true pas de saisie possible
     * @param string    $sMultiLabel    Préfixe des champs du multi
     * @return string   $sMultiForm     Formulaire généré
     */
    public static function addFormColour($oForm, $aValues, $mReadO, $sMultiLabel)
    {
        /* Libellé de la couleur */
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_COULEUR_LABEL", t('VEHICULE_COULEUR_LABEL_NAME'), 100, '', true, $aValues['VEHICULE_COULEUR_LABEL'], $mReadO, 75);
        /* Picto de couleur */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_PICTO", t('VEHICULE_COULEUR_LABEL_PICTO'), true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_PICTO'], $mReadO, true, false, 'carre');
        /* Code de la couleur */
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_COULEUR_CODE", t('VEHICULE_COULEUR_LABEL_CODE'), 100, '', false, $aValues['VEHICULE_COULEUR_CODE'], $mReadO, 75);
        /* Code LCDV de la version */
        $sMultiForm .= $oForm->createInput("{$sMultiLabel}VEHICULE_COULEUR_LCDV6", t('VEHICULE_COULEUR_LABEL_CODELCDV'), 16, '', false, $aValues['VEHICULE_COULEUR_LCDV6'], $mReadO, 10);
        /* Visuel de fond WEB 1 */
        //$sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB1", t('VEHICULE_COULEUR_LABEL_BCKGRD_WEB').' 1', true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB1'], $mReadO);
        /* Visuel de fond WEB 2 */
        //$sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB2", t('VEHICULE_COULEUR_LABEL_BCKGRD_WEB').' 2', true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB2'],$mReadO);
        /* Visuel de fond WEB 3 */
        //$sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB3", t('VEHICULE_COULEUR_LABEL_BCKGRD_WEB').' 3', true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB3'],$mReadO);
        /* Visuel véhicule WEB 1  */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1", t('VEHICULE_COULEUR_LABEL_CAR_WEB').' 1', true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1'],$mReadO, true, false, '16_9');
        /* Visuel véhicule WEB 2  */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2", t('VEHICULE_COULEUR_LABEL_CAR_WEB').' 2', false, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2'],$mReadO, true, false, '16_9');
        /* Visuel véhicule WEB 3  */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3", t('VEHICULE_COULEUR_LABEL_CAR_WEB').' 3', false, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3'], $mReadO, true, false, '16_9');
        /* Visuel véhicule mobile  */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_MOB1", t('VEHICULE_COULEUR_LABEL_CAR_MOB'), true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_MOB1'], $mReadO, true, false, '16_9');
        
        $warning1 = t("LA_COULEUR", 'js2');
        $warning2 = t("MEME_ORDRE_QUE_LE_LIB_DE_LA_COULEUR", 'js2');
        $sMultiForm .= $oForm->createJS('
            var countCouleur = $("#count_ADDCOLOUR").val();
            for (i = 0; i < countCouleur; i++)
            {
               var allCouleur           = $("#ADDCOLOUR"+i+"_PAGE_ZONE_MULTI_ORDER").val();
               var selectCouleur        = $("#'.$sMultiLabel.'PAGE_ZONE_MULTI_ORDER").val();
               var selectInputAll       = "#ADDCOLOUR"+i+"_";
               var selectInputCouleur   = "#'.$sMultiLabel . '";
               if(allCouleur == selectCouleur && selectInputAll !=  selectInputCouleur){
                    var libSelectCOuleur    =   $("#'.$sMultiLabel.'VEHICULE_COULEUR_LABEL").val();
                    var allCouleur          =   $("#ADDCOLOUR"+i+"_VEHICULE_COULEUR_LABEL").val();
                    alert("La couleur " + libSelectCOuleur + " à le meme ordre que le libelle de la couleur " + allCouleur);
                    return false;
               }
            } 
            
        ');
        return $sMultiForm;
    }

    /**
     * Méthode privée permettant de remonter l'ensemble des couleurs pour
     * un véhicule, un site et une langue
     * @return array
     */
    private function getCreditPriceValues ()
    {
        /*Initialisation des variables */
        $aFinancement = array();

        if ( is_array($this->values) && isset($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_LCDV6_CONFIG']) ){
           /* Initialisation des variables */
           $sPrixHT = '';
           $sPrixTTC = '';
           $bTTCPrice = true;

           /* Recherche des informations du véhicule */
           $aWSVehiculeInfo = \Citroen\GammeFinition\VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_LCDV6_CONFIG'], 'row');

           /* Vérification si le prix est HT ou TTC */
           if( is_array($this->values)
                   && isset($this->values['VEHICULE_CASH_PRICE_TYPE'])
                   && $this->values['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC'){
               $bTTCPrice = false;
           }
           /* Si le prix au comptant a été renseigné dans le BO on l'utilise en priorité
            * sinon on utilise le prix de la version la moins chère
            */
           if( !empty($this->values['VEHICULE_CASH_PRICE_TYPE']) && !empty($this->values['VEHICULE_CASH_PRICE'])){
               $sPrice = $this->values['VEHICULE_CASH_PRICE'];
           }else{
               $aShowroomVehicule = \Citroen\GammeFinition\VehiculeGamme::getShowRoomVehicule($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_ID']);
               $sPrice = $aShowroomVehicule[0]['VEHICULE']['CASH_PRICE'];
           }
           if( $bTTCPrice === true ){
               $sPrixTTC = $sPrice;
           }else{
               $sPrixHT = $sPrice;
           }
           /* Récupération des informations sur le prix à crédit */
           $aFinancement = \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeCreditPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'],$this->values['VEHICULE_LCDV6_CONFIG'],$aWSVehiculeInfo['MODEL_LABEL'], $aWSVehiculeInfo['GAMME'],$sPrixHT,$sPrixTTC);

        }
        return $aFinancement;
    }

    /**
     * Surcharge de la méthode de sauvegarde pour y inclure les enregistrements
     * des multis et tableaux associatif
     *
     * @param Pelican_Controller $controller
     */
    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Sauvegarde des données du formulaire */
        $aSaveValues = Pelican_Db::$values;
        

        
		if(isset(Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE'])&&!empty(Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE']))		{
			$aCodeSilouhetteRaw = explode(' ' , Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE']); 
			Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE'] = sprintf('%s:%s',trim($aCodeSilouhetteRaw[0]),str_replace(
	       																			array( '(', ')' ),
	       																			'',
	       																			trim($aCodeSilouhetteRaw[1])
	       																			));

		}        
        
        /* Gestion des cases à cocher Affichage des prix comptants et crédis */
        if ( array_key_exists('VEHICULE_DISPLAY_PRICE', Pelican_Db::$values) &&
                is_array(Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'])
        ){
            if (Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][0] == 1){
                Pelican_Db::$values['VEHICULE_DISPLAY_CASH_PRICE'] = 1;
            }else{
                Pelican_Db::$values['VEHICULE_DISPLAY_CASH_PRICE'] = 0;
            }

            if (Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][0] == 2 || Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][1] == 2){
                Pelican_Db::$values['VEHICULE_DISPLAY_CREDIT_PRICE'] = 1;
            }else{
                Pelican_Db::$values['VEHICULE_DISPLAY_CREDIT_PRICE'] = 0;
            }
        }


         /* Enregistrement de la Gamme + lcdv6 sélectionné dans la combo configurateur */
        if ( array_key_exists('VEHICULE_GAMME_LCDV6_CONFIG', Pelican_Db::$values) &&
                strpos(Pelican_Db::$values['VEHICULE_GAMME_LCDV6_CONFIG'], '_') === 2
        ){
            $aConfigInfo = explode('_', Pelican_Db::$values['VEHICULE_GAMME_LCDV6_CONFIG']);
            Pelican_Db::$values['VEHICULE_GAMME_CONFIG'] = $aConfigInfo[0];
            Pelican_Db::$values['VEHICULE_LCDV6_CONFIG'] = $aConfigInfo[1];

        }
		
		if ( array_key_exists('VEHICULE_GAMME_LCDV6_MTCFG', Pelican_Db::$values) &&
                strpos(Pelican_Db::$values['VEHICULE_GAMME_LCDV6_MTCFG'], '_') === 2
        ){
            $aConfigInfo = explode('_', Pelican_Db::$values['VEHICULE_GAMME_LCDV6_MTCFG']);
            Pelican_Db::$values['VEHICULE_GAMME_MTCFG'] = $aConfigInfo[0];
            Pelican_Db::$values['VEHICULE_LCDV6_MTCFG'] = $aConfigInfo[1];

        }

		$aBind[':SITE_ID'] = $aSaveValues['SITE_ID'];
		$aBind[':LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
		$aBind[':VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
        /* Suppression des critères pour le véhicule en cours */
        $oConnection->query('DELETE FROM #pref#_vehicule_criteres WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
        $oConnection->query('DELETE FROM #pref#_vehicule_couleur WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
        $oConnection->query('DELETE FROM #pref#_vehicule_cta_showroom WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
        $oConnection->query('DELETE FROM #pref#_vehicule_cta_expand WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
        
        /* Appel de la méthode parente */
        if($aSaveValues['form_action'] == Pelican_Db::DATABASE_DELETE){
            $oConnection->query('set foreign_key_checks=0');
            $oConnection->query('DELETE FROM #pref#_vehicule WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
            $oConnection->query('set foreign_key_checks=1');
        }else{
            parent::saveAction();
        }

        /** Enregistrement des données de base d'un véhicule */
        //$oConnection->updateTable(Pelican_Db::$values['form_action'], '#pref#_vehicule');

        /** Gestion de l'enregistrement des informations de teintes d'un véhicule
         *  (multi)
         */
        /* Suppression des enregistrements des couleurs pour le véhicule actuel */
        $sVehiculeColourTableName = '#pref#_vehicule_couleur';
        /* Création du tableau avec les champs nécessaires pour la suppression */
        $aDeleteMultiColours['SITE_ID'] = $aSaveValues['SITE_ID'];
        $aDeleteMultiColours['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
        $aDeleteMultiColours['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
        Pelican_Db::$values = $aDeleteMultiColours;
        $aColoursUsedColumns[] = 'SITE_ID';
        $aColoursUsedColumns[] = 'LANGUE_ID';
        $aColoursUsedColumns[] = 'VEHICULE_ID';
        /* Suppression des éléments de la couleur pour le véhicule en cours */
        $oConnection->deleteQuery($sVehiculeColourTableName, '', $aColoursUsedColumns);

        /* Si on est pas dans le cadre de la suppression d'un véhicule, on insère
         * les données dans la table vehicule_couleur
         */
        if ( $aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE ){
            /* Génération du tableau de multi */
            $aMultiColours = Backoffice_Form_Helper::myReadMulti($aSaveValues, 'ADDCOLOUR');
            /* Rajout des champs nécessaires à l'enregistrement de chaque couleur */
            if ( is_array($aMultiColours) && !empty($aMultiColours) ){
                $i = 1;
                foreach ( $aMultiColours as $aOneColour ){
                    /* le multi_display défini si l'élément est présent et à prendre
                     * en compte pour l'enregistrement
                     */
                    if( $aOneColour['multi_display'] == 1 ){
                        /* Insertion des données nécessaires à l'enregistrement */
                        $aOneColour['VEHICULE_COULEUR_ORDER'] = $i;
                        $aOneColour['SITE_ID'] = $aSaveValues['SITE_ID'];
                        $aOneColour['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
                        $aOneColour['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
                        Pelican_Db::$values = $aOneColour;
                        /* Insertion des données de critères de véhicules */
                        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sVehiculeColourTableName);
                        $i++;
                    }
                }
            }
        }

        /* Enregistremen CTA Expand */
        if ($aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE) {
            /* Génération du tableau de multi */
            $aMultiCtaExpand = Backoffice_Form_Helper::myReadMulti($aSaveValues, 'ADDCTAEXPAND');
            /* Rajout des champs nécessaires à l'enregistrement de chaque cta */
            if (is_array($aMultiCtaExpand) && !empty($aMultiCtaExpand)) {
                $i = 1;
                foreach ($aMultiCtaExpand as $aOneCtaExpand) {
                    /* le multi_display défini si l'élément est présent et à prendre
                     * en compte pour l'enregistrement
                     */
                    if ($aOneCtaExpand['multi_display'] == 1) {
						
						if(is_array($aOneCtaExpand['VEHICULE_CTA_EXPAND_VALUES']) && sizeof($aOneCtaExpand['VEHICULE_CTA_EXPAND_VALUES'])>0){
							foreach($aOneCtaExpand['VEHICULE_CTA_EXPAND_VALUES'] as $aValuesExpand){
								switch ($aValuesExpand) {
									case 1:
										$aOneCtaExpand['VEHICULE_CTA_EXPAND'] = 1;
										break;
									case 2:
										$aOneCtaExpand['VEHICULE_CTA_EXPAND_HOME'] = 1;
										break;
									case 3:
										$aOneCtaExpand['VEHICULE_CTA_EXPAND_MASTER'] = 1;
										break;
								}
							}
						}
                        /* Insertion des données nécessaires à l'enregistrement */
                        $aOneCtaExpand['SITE_ID'] = $aSaveValues['SITE_ID'];
                        $aOneCtaExpand['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
                        $aOneCtaExpand['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
                        Pelican_Db::$values = $aOneCtaExpand;

                        /* Insertion des cta expand de véhicules */
                        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_vehicule_cta_expand');
                        $i++;
                    }
                }
            }
        }
        
        /* Enregistremen CTA Showroom */
        if ( $aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE ){
            /* Génération du tableau de multi */
            $aMultiCtaShowroom = Backoffice_Form_Helper::myReadMulti($aSaveValues, 'ADDCTASHOWROOM');
            /* Rajout des champs nécessaires à l'enregistrement de chaque couleur */
            if ( is_array($aMultiCtaShowroom) && !empty($aMultiCtaShowroom) ){
                $i = 1;
                foreach ( $aMultiCtaShowroom as $aOneCtaShowroom ){
                    /* le multi_display défini si l'élément est présent et à prendre
                     * en compte pour l'enregistrement
                     */
                    if( $aOneCtaShowroom['multi_display'] == 1 ){
                        /* Insertion des données nécessaires à l'enregistrement */
                        $aOneCtaShowroom['SITE_ID'] = $aSaveValues['SITE_ID'];
                        $aOneCtaShowroom['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
                        $aOneCtaShowroom['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
                        Pelican_Db::$values = $aOneCtaShowroom;
                        
                        /* Insertion des données de critères de véhicules */
                        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_vehicule_cta_showroom');
                        $i++;
                    }
                }
            }
        }
        
        /** Gestion de l'enregistrement des critères d'un véhicule  */
        /* Création du tableau avec les champs nécessaires pour la suppression */
        $aDeleteAllCrit['SITE_ID'] = $aSaveValues['SITE_ID'];
        $aDeleteAllCrit['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
        $aDeleteAllCrit['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
        Pelican_Db::$values = $aDeleteAllCrit;
        /* Suppression des critères pour le véhicule en cours */
        //$oConnection->deleteQuery('#pref#_vehicule_criteres', array( 0 => 'CRITERE_ID'));

        $aAllCrit = array();
        /* Création du tableau de tous les critères sélectionnés peut importe
         * leur type
         */
        if( array_key_exists('ASSOC_CRIT_TYPE1', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE1']) && !empty($aSaveValues['ASSOC_CRIT_TYPE1'])
        ){
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE1']);
        }

        if( array_key_exists('ASSOC_CRIT_TYPE2', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE2']) && !empty($aSaveValues['ASSOC_CRIT_TYPE2'])
        ){
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE2']);
        }

        if( array_key_exists('ASSOC_CRIT_TYPE3', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE3']) && !empty($aSaveValues['ASSOC_CRIT_TYPE3'])
        ){
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE3']);
        }
        /* Si on est pas dans le cadre de la suppression d'un véhicule, on insère
         * les données dans la table vehicule_couleur
         */
        if ( $aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE ){
            /* Rajout des champs nécessaires à l'enregistrement de chaque critère */
            if ( is_array($aAllCrit) && !empty($aAllCrit) ){
                foreach ( $aAllCrit as $iCtritId ){
                        /* Insertion des données nécessaires à l'enregistrement */
                        $aOneCrit['SITE_ID'] = $aSaveValues['SITE_ID'];
                        $aOneCrit['LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
                        $aOneCrit['VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
                        $aOneCrit['CRITERE_ID'] = $iCtritId;
                        Pelican_Db::$values = $aOneCrit;
                        /* Insertion des données de critères de véhicules */
                        $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_vehicule_criteres');

                }
            }
        }
		$connection = Pelican_db::getInstance();
        /* Remise en place des valeurs du formulaire */
        Pelican_Db::$values = $aSaveValues;
		
		
		/* Suppression des visuels pour le véhicule en cours */
		$aBind[':SITE_ID'] = Pelican_Db::$values['SITE_ID'];
		$aBind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
		$aBind[':VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
		$aBind[':TYPE_VISUEL'] = $oConnection->strtobind(self::VISUEL_INTERIEUR);
		
		$sqlDeleteVisuel = 'DELETE FROM #pref#_vehicule_media 
							WHERE SITE_ID = :SITE_ID 
							AND LANGUE_ID = :LANGUE_ID 
							AND VEHICULE_ID = :VEHICULE_ID 
							AND TYPE_VISUEL = :TYPE_VISUEL';
							
		$oConnection->query($sqlDeleteVisuel, $aBind);		

        $saved = Pelican_Db::$values;
        foreach (self::getVisuelsInterieur() as $champs => $visuelInterieur){
            if (!empty(Pelican_Db::$values[$visuelInterieur])){
                Pelican_Db::$values[$champs] = Pelican_Db::$values[$visuelInterieur];                   
            }
        }
		Pelican_Db::$values['TYPE_VISUEL'] = self::VISUEL_INTERIEUR;
		$connection->insertQuery('#pref#_vehicule_media');
        Pelican_Db::$values = $saved;
		Pelican_Cache::clean("Frontend/Citroen/CarSelector/Resultats");
		Pelican_Cache::clean("Frontend/Citroen/VehiculesVisuelInterieur", array(Pelican_Db::$values['SITE_ID'],Pelican_Db::$values['LANGUE_ID'],Pelican_Db::$values['VEHICULE_ID']));
    }
	
    public static function getVisuelsInterieur()
    {

        return array(
            'MEDIA_ID' => 'INTERIEUR_MEDIA_ID',
            'MEDIA_ID2' => 'INTERIEUR_MEDIA_ID2',
            'MEDIA_ID3' => 'INTERIEUR_MEDIA_ID3',
            'MEDIA_ID4' => 'INTERIEUR_MEDIA_ID4',
            'MEDIA_ID5' => 'INTERIEUR_MEDIA_ID5',
            'MEDIA_ID6' => 'INTERIEUR_MEDIA_ID6'			
        );
    }

	 public function getCodePays(){
        $oConnection = Pelican_Db::getInstance();
        $sqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        if(empty($sqlCodePays)){
            
            return false;
        }
        
        return $sqlCodePays; 
    }	
}
?>
