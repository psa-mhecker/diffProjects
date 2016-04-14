<?php
/**
 * Fichier de Citroen_Vehicules :.
 *
 * Classe Back-Office de contribution des véhicules de manières manuelle
 * Cette contribution sera utilisée en Front-Office pour surcharger les données
 * des véhicules renvoyées par le WebService PSA
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 17/07/2013
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_Vehicules_Controller extends Citroen_Controller
{
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
            array('SITE_ID', 'LANGUE_ID'),
        ) ,
        array('Frontend/Citroen/CarSelector/Resultats',
            array('SITE_ID', 'LANGUE_ID'),
        ) ,
        array('Frontend/Citroen/Finitions',
            array('VEHICULE_ID'),
        ) ,
        array('Frontend/Citroen/VehiculeById',
            array('VEHICULE_ID'),
        ) ,
        array('Frontend/Citroen/VehiculeDisponibleSur',
            array('SITE_ID', 'LANGUE_ID'),
        ) ,
        array('Frontend/Citroen/VehiculeShowroomById',
            array('SITE_ID', 'LANGUE_ID', 'VEHICULE_ID'),
        ) ,
        array('Frontend/Citroen/VehiculesParGamme',
            array('SITE_ID', 'LANGUE_ID'),
        ) ,
        array('Frontend/Citroen/Finitions'),
        array('Frontend/Citroen/Finitions/Caracteristiques'),
        array('Frontend/Citroen/Finitions/EngineList'),
        array('Frontend/Citroen/Finitions/Equipement'),
        array('Frontend/Citroen/Navigation'),
    );

    /**
     * Méthode protégées d'instanciation de la propriété listModel.
     * La méthode instancie listModel avec un tableau de données qui sera utilisé
     * pour afficher la liste de véhicule.
     */
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindVehiculesList[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBindVehiculesList[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];

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
            $sSqlVehiculesList .= " AND (
     VEHICULE_LABEL like '%".$_GET['filter_search_keyword']."%'
     OR VEHICULE_LCDV6_MANUAL like '%".$_GET['filter_search_keyword']."%'
     OR VEHICULE_LCDV6_CONFIG like '%".$_GET['filter_search_keyword']."%'
     )
     ";
        }
        $sSqlVehiculesList .=  "ORDER BY {$this->listOrder}";

        $this->listModel = $oConnection->queryTab($sSqlVehiculesList, $aBindVehiculesList);
    }

    /**
     * Méthode protégées d'instanciation de la propriété editModel.
     * La méthode instancie editModel avec un tableau de données qui sera utilisé
     * l'instanciation de la propriété 'value'.
     */
    protected function setEditModel()
    {
        /* Valeurs Bindées pour la requête */
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':VEHICULE_ID'] = (int) $this->id;

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
     * Méthode de création de la liste des éléments du formulaire.
     */
    public function listAction()
    {
        parent::listAction();

        /* Initialisation de l'objet List*/
        $oTable = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $oTable->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
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
     * Création du formulaire de contribution.
     */
    public function editAction()
    {
        parent::editAction();
        $oConnection = Pelican_Db::getInstance();
        $aBind[':VEHICULE_ID'] = $this->values['VEHICULE_ID'];
        $aBind[':SITE_ID'] = $this->values['SITE_ID'];
        $aBind[':LANGUE_ID'] = $this->values['LANGUE_ID'];

        if (empty($this->values['CODE_REGROUPEMENT_SILHOUETTE'])) {
            /*code de regroupement silhouette*/

        if (empty($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_LCDV6_MANUAL'])) {
            $sLcdv6 = $this->values['VEHICULE_LCDV6_MANUAL'];
        } elseif (!empty($this->values['VEHICULE_LCDV6_CONFIG'])) {
            $sLcdv6 = $this->values['VEHICULE_LCDV6_CONFIG'];
        }

            $aBind[':LCDV6'] = $oConnection->strToBind($sLcdv6);
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
        } else {
            $aCodeSilouhetteRaw = explode(':', $this->values['CODE_REGROUPEMENT_SILHOUETTE']);
            $aCodesRegroupementSilhouette[] = array(
                                                       'CRIT_BODY_CODE' => $aCodeSilouhetteRaw[0],
                                                       'CRIT_BODY_LABEL' => $aCodeSilouhetteRaw[1],
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

        $sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_LCDV6_CONFIG', t('VEHICULE_LABEL_LCDV6CONFIG'), $aComboLCDV, $this->values['VEHICULE_GAMME_CONFIG'].'_'.$this->values['VEHICULE_LCDV6_CONFIG'], false, $this->readO);
        $sForm .= $this->oForm->createLabel('', t('VEHICULE_LABEL_LCDV6CONFIGRELOAD'));

        /*Code de regroupement Silhouette*/

        if (is_array($aCodesRegroupementSilhouette)) {
            if (count($aCodesRegroupementSilhouette) == 1) {
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
                          $sForm .= '<tr colspan="2"><td><a href="#" id="clean_code_regroupement_silhouette" onclick="reset_crs();">'.t('RESET').'</a></td></tr>';
            } elseif (count($aCodesRegroupementSilhouette)>1) {
                foreach ($aCodesRegroupementSilhouette as $aOneCode) {
                    $aRegroupementValues[sprintf(
                                '%s:%s',
                                $aOneCode['CRIT_BODY_CODE'],
                                $aOneCode['CRIT_BODY_LABEL']
                                )] = sprintf(
                                '%s (%s)',
                                $aOneCode['CRIT_BODY_CODE'],
                                $aOneCode['CRIT_BODY_LABEL']
                                );
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
        foreach ($Values as $OneValue) {
            $aComboCateg[$OneValue['CATEG_VEHICULE_LABEL']] = $OneValue['CATEG_VEHICULE_LABEL'];
        }
        unset($Values);
        if (!empty($aComboCateg)) {
            $sForm .= $this->oForm->createComboFromList('VEHICULE_CATEG_LABEL', t('CATEG_VEHICULE_LABEL'), $aComboCateg, $this->values['VEHICULE_CATEG_LABEL'], false, $this->readO);
        }

        /* Code LCDV6 manuel du véhicule */
        $sForm .= $this->oForm->createInput('VEHICULE_LCDV6_MANUAL', t('VEHICULE_LABEL_LCDVMANUAL'), 6, '', false, $this->values['VEHICULE_LCDV6_MANUAL'], $this->readO, 10);
        /* Code Gamme manuel du véhicule */
        $this->values['RADIO_GAMME_MANUAL']['VP'] = t('VEHICULE_LABEL_GAMMEVP');
        $this->values['RADIO_GAMME_MANUAL']['VU'] = t('VEHICULE_LABEL_GAMMEVU');
        $sForm .= $this->oForm->createComboFromList('VEHICULE_GAMME_MANUAL', t('VEHICULE_LABEL_GAMMEVP').'/'.t('VEHICULE_LABEL_GAMMEVU').' ('.t('SI_PAS_DE_WEBSERVICE').')', $this->values['RADIO_GAMME_MANUAL'], $this->values['VEHICULE_GAMME_MANUAL'], false, $this->readO);
       /* Vignette du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_THUMBNAIL', t('VEHICULE_LABEL_THUMBNAIL'), true, 'image', '', $this->values['VEHICULE_MEDIA_ID_THUMBNAIL'], $this->readO, true, false, '16_9');
        /* Visuel de fond WEB 1 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB1', t('VEHICULE_LABEL_MEDIAWEB').' 1', true, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB1'], $this->readO, true, false, '16_9');
        /* Visuel de fond WEB 2 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB2', t('VEHICULE_LABEL_MEDIAWEB').' 2', false, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB2'], $this->readO, true, false, '16_9');
        /* Visuel de fond WEB 3 du véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_WEB3', t('VEHICULE_LABEL_MEDIAWEB').' 3', false, 'image', '', $this->values['VEHICULE_MEDIA_ID_WEB3'], $this->readO, true, false, '16_9');
        /* Visuel de fond Mobile des véhicule */
        $sForm .= $this->oForm->createMedia('VEHICULE_MEDIA_ID_MOB', t('VEHICULE_LABEL_MEDIAMOB'), true, 'image', '', $this->values['VEHICULE_MEDIA_ID_MOB'], $this->readO, true, false, '16_9');

        /* Génération du multi pour les teintes du véhicules */
        $sForm .= $this->oForm->createMultiHmvc($this->multi.'ADDCOLOUR', t('VEHICULE_COLOURS'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addFormColour',
         ), self::getMultiColoursValues(), $this->multi.'ADDCOLOUR', $this->readO, '', true, true, $this->multi."ADDCOLOUR");

        /* Affichage du prix. En base de données on n'enregistrera que la constante
         * de traduction tandis que l'on affichera se traduction
         */
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
        if (array_key_exists('VEHICULE_LCDV6_CONFIG', $this->values) && !empty($this->values['VEHICULE_LCDV6_CONFIG'])) {
            /* Récupération des finitions pour le véhicule sélectionné dans la
            * combo du code LCDV du configurateur
            */
            //$aShowroomVehicule = \Citroen\GammeFinition\VehiculeGamme::getShowRoomVehicule($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_ID']);
            $aVehiculeCashPrice =   \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeFirstCashPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_LCDV6_CONFIG']);
            $sForm .= $this->oForm->createLabel(t('VEHICULE_LABEL_CASHPRICE'), $aVehiculeCashPrice);
            $sForm .= $this->oForm->createHidden('VEHICULE_CASH_PRICE', '');
        } else {
            /* Prix comptant */
            $sForm .= $this->oForm->createInput('VEHICULE_CASH_PRICE', t('VEHICULE_LABEL_CASHPRICE'), 255, '', true, $this->values['VEHICULE_CASH_PRICE'], false, 44);
        }

        /* Type de prix comptant  Les valeurs disponibles sont : TTC/HT */
        $this->values['RADIO_CASH_PRICE_TYPE'] = Pelican::$config['CASH_PRICE_TAXE'];
        if (!isset($this->values) ||
                (is_array($this->values) && (!isset($this->values['VEHICULE_CASH_PRICE_TYPE']) || empty($this->values['VEHICULE_CASH_PRICE_TYPE'])))
          ) {
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
        if (array_key_exists('VEHICULE_LCDV6_CONFIG', $this->values) && !empty($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_USE_FINANCIAL_SIMULATOR'])) {
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
        } else {
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

        /* Filtre de type 1 */
        $aBindCritType[':SITE_ID']      = (int) $_SESSION[APP]['SITE_ID'];
        $aBindCritType[':LANGUE_ID']    = (int) $_SESSION[APP]['LANGUE_ID'];
        $aBindCritType[':VEHICULE_ID']  = (int) $this->values['VEHICULE_ID'];
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
        $sFinalForm = formToString($this->oForm, $sForm);

        if (is_array($productDependence) && count($productDependence)> 0 && $this->form_action == 'DEL') {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        $this->setResponse($sFinalForm);
    }

    /**
     * Méthode privée permettant de remonter l'ensemble des couleurs pour
     * un véhicule, un site et une langue.
     *
     * @return array
     */
    private function getMultiColoursValues()
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
        $aResult = $oConnection->queryTab($sSqlVehiculesCouleur, $aBindVehiculesCouleur);

        return $aResult;
    }

    /**
     * Méthode statique de création du formulaire multiple.
     *
     * @param object $oForm       Objet de la classe Form
     * @param array  $aValues     Tableau de données permettant de remplir les multi
     * @param mixed  $mReadO      Null ou false pour permettre la saisie dans le multi
     *                            true pas de saisie possible
     * @param string $sMultiLabel Préfixe des champs du multi
     *
     * @return string $sMultiForm     Formulaire généré
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
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1", t('VEHICULE_COULEUR_LABEL_CAR_WEB').' 1', true, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1'], $mReadO, true, false, '16_9');
        /* Visuel véhicule WEB 2  */
        $sMultiForm .= $oForm->createMedia("{$sMultiLabel}VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2", t('VEHICULE_COULEUR_LABEL_CAR_WEB').' 2', false, 'image', '', $aValues['VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2'], $mReadO, true, false, '16_9');
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
               var selectInputCouleur   = "#'.$sMultiLabel.'";
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
     * un véhicule, un site et une langue.
     *
     * @return array
     */
    private function getCreditPriceValues()
    {
        /*Initialisation des variables */
        $aFinancement = array();

        if (is_array($this->values) && isset($this->values['VEHICULE_LCDV6_CONFIG']) && !empty($this->values['VEHICULE_LCDV6_CONFIG'])) {
            /* Initialisation des variables */
           $sPrixHT = '';
            $sPrixTTC = '';
            $bTTCPrice = true;

           /* Recherche des informations du véhicule */
           $aWSVehiculeInfo = \Citroen\GammeFinition\VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_LCDV6_CONFIG'], 'row');

           /* Vérification si le prix est HT ou TTC */
           if (is_array($this->values)
                   && isset($this->values['VEHICULE_CASH_PRICE_TYPE'])
                   && $this->values['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC') {
               $bTTCPrice = false;
           }
           /* Si le prix au comptant a été renseigné dans le BO on l'utilise en priorité
            * sinon on utilise le prix de la version la moins chère
            */
           if (!empty($this->values['VEHICULE_CASH_PRICE_TYPE']) && !empty($this->values['VEHICULE_CASH_PRICE'])) {
               $sPrice = $this->values['VEHICULE_CASH_PRICE'];
           } else {
               $aShowroomVehicule = \Citroen\GammeFinition\VehiculeGamme::getShowRoomVehicule($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_ID']);
               $sPrice = $aShowroomVehicule[0]['VEHICULE']['CASH_PRICE'];
           }
            if ($bTTCPrice === true) {
                $sPrixTTC = $sPrice;
            } else {
                $sPrixHT = $sPrice;
            }
           /* Récupération des informations sur le prix à crédit */
           $aFinancement = \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeCreditPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->values['VEHICULE_LCDV6_CONFIG'], $aWSVehiculeInfo['MODEL_LABEL'], $aWSVehiculeInfo['GAMME'], $sPrixHT, $sPrixTTC);
        }

        return $aFinancement;
    }

    /**
     * Surcharge de la méthode de sauvegarde pour y inclure les enregistrements
     * des multis et tableaux associatif.
     *
     * @param Pelican_Controller $controller
     */
    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Sauvegarde des données du formulaire */
        $aSaveValues = Pelican_Db::$values;

        if (isset(Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE']) && !empty(Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE'])) {
            $aCodeSilouhetteRaw = explode(' ', Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE']);
            Pelican_Db::$values['CODE_REGROUPEMENT_SILHOUETTE'] = sprintf('%s:%s', trim($aCodeSilouhetteRaw[0]), str_replace(
                                                                                       array( '(', ')' ),
                                                                                       '',
                                                                                       trim($aCodeSilouhetteRaw[1])
                                                                                       ));
        }

        /* Gestion des cases à cocher Affichage des prix comptants et crédis */
        if (array_key_exists('VEHICULE_DISPLAY_PRICE', Pelican_Db::$values) &&
                is_array(Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'])
        ) {
            if (Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][0] == 1) {
                Pelican_Db::$values['VEHICULE_DISPLAY_CASH_PRICE'] = 1;
            } else {
                Pelican_Db::$values['VEHICULE_DISPLAY_CASH_PRICE'] = 0;
            }

            if (Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][0] == 2 || Pelican_Db::$values['VEHICULE_DISPLAY_PRICE'][1] == 2) {
                Pelican_Db::$values['VEHICULE_DISPLAY_CREDIT_PRICE'] = 1;
            } else {
                Pelican_Db::$values['VEHICULE_DISPLAY_CREDIT_PRICE'] = 0;
            }
        }

         /* Enregistrement de la Gamme + lcdv6 sélectionné dans la combo configurateur */
        if (array_key_exists('VEHICULE_GAMME_LCDV6_CONFIG', Pelican_Db::$values) &&
                strpos(Pelican_Db::$values['VEHICULE_GAMME_LCDV6_CONFIG'], '_') === 2
        ) {
            $aConfigInfo = explode('_', Pelican_Db::$values['VEHICULE_GAMME_LCDV6_CONFIG']);
            Pelican_Db::$values['VEHICULE_GAMME_CONFIG'] = $aConfigInfo[0];
            Pelican_Db::$values['VEHICULE_LCDV6_CONFIG'] = $aConfigInfo[1];
        }

        $aBind[':SITE_ID'] = $aSaveValues['SITE_ID'];
        $aBind[':LANGUE_ID'] = $aSaveValues['LANGUE_ID'];
        $aBind[':VEHICULE_ID'] = Pelican_Db::$values['VEHICULE_ID'];
        /* Suppression des critères pour le véhicule en cours */
        $oConnection->query('DELETE FROM #pref#_vehicule_criteres WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);
        $oConnection->query('DELETE FROM #pref#_vehicule_couleur WHERE VEHICULE_ID =:VEHICULE_ID and SITE_ID = :SITE_ID and  LANGUE_ID = :LANGUE_ID', $aBind);

        /* Appel de la méthode parente */
        parent::saveAction();

        /* Enregistrement des données de base d'un véhicule */
        //$oConnection->updateTable(Pelican_Db::$values['form_action'], '#pref#_vehicule');

        /* Gestion de l'enregistrement des informations de teintes d'un véhicule
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
        if ($aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE) {
            /* Génération du tableau de multi */
            $aMultiColours = Backoffice_Form_Helper::myReadMulti($aSaveValues, 'ADDCOLOUR');
            /* Rajout des champs nécessaires à l'enregistrement de chaque couleur */
            if (is_array($aMultiColours) && !empty($aMultiColours)) {
                $i = 1;
                foreach ($aMultiColours as $aOneColour) {
                    /* le multi_display défini si l'élément est présent et à prendre
                     * en compte pour l'enregistrement
                     */
                    if ($aOneColour['multi_display'] == 1) {
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

        /* Gestion de l'enregistrement des critères d'un véhicule  */
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
        if (array_key_exists('ASSOC_CRIT_TYPE1', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE1']) && !empty($aSaveValues['ASSOC_CRIT_TYPE1'])
        ) {
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE1']);
        }

        if (array_key_exists('ASSOC_CRIT_TYPE2', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE2']) && !empty($aSaveValues['ASSOC_CRIT_TYPE2'])
        ) {
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE2']);
        }

        if (array_key_exists('ASSOC_CRIT_TYPE3', $aSaveValues) &&
                is_array($aSaveValues['ASSOC_CRIT_TYPE3']) && !empty($aSaveValues['ASSOC_CRIT_TYPE3'])
        ) {
            $aAllCrit = array_merge($aAllCrit, $aSaveValues['ASSOC_CRIT_TYPE3']);
        }
        /* Si on est pas dans le cadre de la suppression d'un véhicule, on insère
         * les données dans la table vehicule_couleur
         */
        if ($aSaveValues['form_action'] != Pelican_Db::DATABASE_DELETE) {
            /* Rajout des champs nécessaires à l'enregistrement de chaque critère */
            if (is_array($aAllCrit) && !empty($aAllCrit)) {
                foreach ($aAllCrit as $iCtritId) {
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

        /* Remise en place des valeurs du formulaire */
        Pelican_Db::$values = $aSaveValues;
        Pelican_Cache::clean("Frontend/Citroen/CarSelector/Resultats");
    }
}
