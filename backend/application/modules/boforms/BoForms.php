<?php

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialResponse.php');

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

/**
 * - load
 * - install
 * - uninstall
 * Appel au Pelican_Cache avec id du plugin
 *
 */
class Module_BoForms extends Pelican_Plugin
{

	/**
	 * DÃ©fintion de constantes ou traitements d'initialisation du plugin au chargement
	 */
	public function load ()
	{}

	/**
	 * Ã  lancer lors de l'installation du plugin :
	 * - insertion de donnÃ©es
	 * - crÃ©ation d'une table
	 * - crÃ©ation de rÃ©pertoires etc...
	 */
	public function install ()
	{
		/*
		 #pref#_boforms_trace
		 #pref#_boforms_state_history
		 #pref#_boforms_brand
		 #pref#_boforms_composant_draft
		 #pref#_boforms_composant_ligne
		 #pref#_boforms_composant_ligne_traduction
		 #pref#_boforms_composant_type
		 #pref#_boforms_context
		 #pref#_boforms_culture
		 #pref#_boforms_device
		 #pref#_boforms_formulaire
		 #pref#_boforms_formulaire_composant
		 #pref#_boforms_formulaire_site
		 #pref#_boforms_formulaire_version
		 #pref#_boforms_groupe
		 #pref#_boforms_opportunite
		 #pref#_boforms_target
		 #pref#_boforms_conf
		 #pref#_boforms_list_conf
		 #pref#_boforms_groupe_formulaire
		 */
		 
		 
		$oConnection = Pelican_Db::getInstance();

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_state_history;";
		$sql[] = "CREATE TABLE IF NOT EXISTS `#pref#_boforms_state_history` (
		`HISTORY_ID` int(11) NOT NULL auto_increment,
		`PAGE_ID` int(11) NOT NULL,
		`PAGE_VERSION` int(11) NOT NULL,
		`LANGUE_ID` int(11) NOT NULL,
		`HISTORY_DATE` datetime NOT NULL,
		`STATE_ID` int(11) NOT NULL,
		`SITE_ID` int(11) NOT NULL,
		`ZONE_ID` int(11) NOT NULL,
		`HISTORY_TARGET` varchar(255) collate utf8_swedish_ci default NULL,
		`HISTORY_DEVICE` varchar(255) collate utf8_swedish_ci default NULL,
		`HISTORY_TYPE` varchar(255) collate utf8_swedish_ci default NULL,
		PRIMARY KEY  (`HISTORY_ID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";
		
		
		$pass=Pelican::$config['PROXY']['PWD'];
		if(!empty($pass))
		{
			$pass=FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],$pass);
		}
		
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_conf;";
		$sql[] = "CREATE TABLE IF NOT EXISTS `#pref#_boforms_conf` (
					  `CONF_VALUE_ID` int(11) NOT NULL auto_increment,
					  `CONF_VALUE_KEY` varchar(255) NOT NULL,
					  `CONF_VALUE` varchar(255) NOT NULL,
					  `CONF_ID` int(11) NOT NULL COMMENT 'foreign key sur la table boforms_conf',
					  PRIMARY KEY  (`CONF_VALUE_ID`),
					  KEY `CONF_VALUE_KEY` (`CONF_VALUE_KEY`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";
		$sql[] = "REPLACE INTO `#pref#_boforms_conf` (`CONF_VALUE_ID`, `CONF_VALUE_KEY`, `CONF_VALUE`, `CONF_ID`) VALUES
					(1, 'JIRA_USERNAME', 'E464305', 1),
					(2, 'JIRA_PASSWORD', '1tOapKyvbp4=', 1),
					(3, 'AC_SERVICE_BOFORMS0PARAMETERS0location', '". \Itkg::$config['AC_SERVICE_BOFORMS']['PARAMETERS']['location'] ."', 2),
					(4, 'AC_SERVICE_BOFORMS0PARAMETERS0wsdl', '". \Itkg::$config['AC_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] ."', 2),
					(5, 'CITROEN_SERVICE_I18N0PARAMETERS0location', '". \Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['location'] ."', 3),
					(6, 'CITROEN_SERVICE_I18N0PARAMETERS0wsdl', '". \Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['wsdl'] ."', 3),
					(7, 'CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0location', '". \Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['PARAMETERS']['location'] ."', 4),
					(8, 'CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0wsdl', '". \Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['PARAMETERS']['wsdl'] ."', 4),
					(9, 'BOFORMS_JIRA0PROJECT_KEY', '".Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY']."', 1),
					(10, 'BOFORMS_JIRA0ISSUE_URL', '".Pelican::$config['BOFORMS_JIRA']['ISSUE_URL']."', 1),
					(11, 'BOFORMS_JIRA0ASSIGNEE_NAME', '".Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME']."', 1),
					(12, 'BOFORMS_JIRA0OTHER_ASSIGNEE', '". implode(",", Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']) ."', 1),
					(13, 'BOFORMS_URL_CLEARCACHE', '".Pelican::$config['BOFORMS_URL_CLEARCACHE']."', 5),
					(14, 'BOFORMS_URL_CLEARCACHE_KEY', '".Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY']."', 5),
					(15, 'BOFORMS_URL_LP', '".Pelican::$config['BOFORMS_URL_LP']."', 5),
					(16, 'BOFORMS_URL_RENDERER', '".Pelican::$config['BOFORMS_URL_RENDERER']."', 5),
					(17, 'BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', '". implode(",", Pelican::$config['BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL']) ."', 5),
					(18, 'BOFORMS_USER_SUPER_ADMIN', '". implode(",", Pelican::$config['BOFORMS_USER_SUPER_ADMIN'] )."', 6),
					(19, 'BOFORMS_BRAND_ID', '".Pelican::$config['BOFORMS_BRAND_ID']."', 5),
					(20, 'BOFORMS_CONSUMER', '".Pelican::$config['BOFORMS_CONSUMER']."', 5),
					(21, 'AC_PROXY0URL', '" . Pelican::$config['AC_PROXY']['URL'] . "', 7),
					(22, 'AC_PROXY0LOGIN', '" . Pelican::$config['AC_PROXY']['LOGIN'] . "', 7),
					(23, 'AC_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['AC_PROXY']['PWD']) . "', 7),
					(24, 'AC_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['AC_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(25, 'BOFORMS_FORM_XSD', '" . Pelican::$config['BOFORMS_FORM_XSD'] . "', 5),	
					(26, 'DS_SERVICE_BOFORMS0PARAMETERS0location', '" . \Itkg::$config['DS_SERVICE_BOFORMS']['PARAMETERS']['location'] . "', 2),
					(27, 'DS_SERVICE_BOFORMS0PARAMETERS0wsdl', '" . \Itkg::$config['DS_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] . "', 2),
					(28, 'AP_SERVICE_BOFORMS0PARAMETERS0location', '" . \Itkg::$config['AP_SERVICE_BOFORMS']['PARAMETERS']['location'] . "', 2),
					(29, 'AP_SERVICE_BOFORMS0PARAMETERS0wsdl', '" . \Itkg::$config['AP_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] . "', 2),
					(30, 'DS_PROXY0URL', '" . Pelican::$config['DS_PROXY']['URL'] . "', 7),
					(31, 'DS_PROXY0LOGIN', '" . Pelican::$config['DS_PROXY']['LOGIN'] . "', 7),
					(32, 'DS_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['DS_PROXY']['PWD']) . "', 7),
					(33, 'DS_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['DS_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(34, 'AP_PROXY0URL', '" . Pelican::$config['AP_PROXY']['URL'] . "', 7),
					(35, 'AP_PROXY0LOGIN', '" . Pelican::$config['AP_PROXY']['LOGIN'] . "', 7),
					(36, 'AP_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['AP_PROXY']['PWD']) . "', 7),
					(37, 'AP_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['AP_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(38, 'BOFORMS_LOG_PATH', '" . Pelican::$config['BOFORMS_LOG_PATH'] . "', 5),
					(200, 'URL_BOLP_FR', '', 10),
					(201, 'URL_BOLP_BE', '', 10)
					 ;";
							
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_list_conf;";
		$sql[] = "CREATE TABLE IF NOT EXISTS `#pref#_boforms_list_conf` (
					  `CONF_ID` int(11) NOT NULL auto_increment,
					  `CONF_KEY` varchar(255) NOT NULL,
					  `CONF_ORDER` smallint(6) NOT NULL,
					  PRIMARY KEY  (`CONF_ID`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci ;";
		
		$sql[] = "REPLACE INTO `#pref#_boforms_list_conf` (`CONF_ID`, `CONF_KEY`, `CONF_ORDER`) VALUES
					(1, 'JIRA', 1);";
		
		$sql[] = "REPLACE INTO `#pref#_boforms_list_conf` (`CONF_ID`, `CONF_KEY`, `CONF_ORDER`) VALUES
					(2, 'WEBSERVICE_BOFORMS', 2),
					(3, 'WEBSERVICE_I18N', 3),
					(4, 'WEBSERVICE_DEALERSERVICE', 4),
					(5, 'CONFIGURATION_GENERALE', 5),
					(6, 'ADMINISTRATION_SUPER_ADMIN', 6),
					(7, 'PROXY', 7), 
					(8, 'CUSCO', 8), 
					(9, 'GDO', 9), 
					(10, 'ADMINISTRATION_URL_BOLP', 10);";
		
		
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_trace;";

		$sql[] ="
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_trace` (
        `FORM_INCE` varchar(255) collate utf8_swedish_ci NOT NULL,
        `FORM_VERSION` int(11) NOT NULL,
        `TRACE_DATE` timestamp NOT NULL default CURRENT_TIMESTAMP,
        `USER_LOGIN` varchar(50) collate utf8_swedish_ci NOT NULL,
        `TRACE_CONTENT` text collate utf8_swedish_ci,
        PRIMARY KEY  (`FORM_INCE`,`FORM_VERSION`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
        ";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_brand;";

		$sql[] ="
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_brand` (
        `BRAND_ID` char(2) collate utf8_swedish_ci NOT NULL,
        `BRAND_KEY` varchar(255) collate utf8_swedish_ci default NULL,
        PRIMARY KEY  (`BRAND_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
        ";

		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
        ('BOFORMS_REFERENTIAL_BRAND_AC', NULL,1),
        ('BOFORMS_REFERENTIAL_BRAND_AP', NULL,1),
        ('BOFORMS_REFERENTIAL_BRAND_DS', NULL,1);";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
        ('BOFORMS_REFERENTIAL_BRAND_AC', 1, 'Automobile Citroën', 1),
        ('BOFORMS_REFERENTIAL_BRAND_AC', 2, 'Automobile Citroën', 1),
        ('BOFORMS_REFERENTIAL_BRAND_AP', 1, 'Automobile Peugeot', 1),
        ('BOFORMS_REFERENTIAL_BRAND_AP', 2, 'Automobile Peugeot', 1),
        ('BOFORMS_REFERENTIAL_BRAND_DS', 1, 'Automobile DS', 1),
        ('BOFORMS_REFERENTIAL_BRAND_DS', 2, 'Automobile DS', 1);";

		$sql[] ="
        INSERT INTO `#pref#_boforms_brand` (`BRAND_ID`, `BRAND_KEY`) VALUES
        ('AC', 'AC'),
        ('AP', 'AP'),
        ('DS', 'DS');
        ";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_draft;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_composant_draft` (
        `DRAFT_COMPOSANT_BRAND` varchar(100) character set utf8 collate utf8_swedish_ci NOT NULL,
        `DRAFT_COMPOSANT_COUNTRY` varchar(100) character set utf8 collate utf8_swedish_ci NOT NULL,
        `DRAFT_COMPOSANT_CULTURE` varchar(50) character set utf8 collate utf8_swedish_ci NOT NULL,
        `DRAFT_COMPOSANT_COMPONENT` varchar(50) character set utf8 collate utf8_swedish_ci NOT NULL,
        `DRAFT_COMPOSANT_JSON` text character set utf8 collate utf8_swedish_ci NOT NULL,
        PRIMARY KEY  (`DRAFT_COMPOSANT_BRAND`,`DRAFT_COMPOSANT_COUNTRY`,`DRAFT_COMPOSANT_CULTURE`,`DRAFT_COMPOSANT_COMPONENT`)
        )  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";


		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_ligne;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_composant_ligne` (
        `COMPOSANT_GROUPE` varchar(255) collate utf8_swedish_ci NOT NULL,
        `COMPOSANT_CODE` varchar(255) collate utf8_swedish_ci NOT NULL,
        `COMPOSANT_TYPE_ID` int(11) default NULL,
        PRIMARY KEY  (`COMPOSANT_GROUPE`,`COMPOSANT_CODE`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";


		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_ligne_traduction;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_composant_ligne_traduction` (
        `COMPOSANT_GROUPE` varchar(255) collate utf8_swedish_ci NOT NULL,
        `COMPOSANT_CODE` varchar(255) collate utf8_swedish_ci NOT NULL,
        `COMPOSANT_TEXTE` varchar(255) collate utf8_swedish_ci default NULL,
        `COMPOSANT_LANGUE` varchar(255) collate utf8_swedish_ci default NULL,
        PRIMARY KEY  (`COMPOSANT_GROUPE`,`COMPOSANT_CODE`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";


		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_type;";

		$sql[] = "
		CREATE TABLE IF NOT EXISTS `#pref#_boforms_composant_type` (
		  `COMPOSANT_TYPE_ID` int(11) NOT NULL auto_increment,
		  `COMPOSANT_TYPE_LABEL` varchar(255) collate utf8_swedish_ci default NULL,
		  `COMPOSANT_HTML_LABEL` varchar(255) collate utf8_swedish_ci NOT NULL,
		  `COMPOSANT_MOBILE_LABEL` varchar(255) collate utf8_swedish_ci NOT NULL,
		  `COMPOSANT_TYPE_SITE` varchar(255) collate utf8_swedish_ci NOT NULL,
		  PRIMARY KEY  (`COMPOSANT_TYPE_ID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=13 ;";

		if(Pelican::$config['BOFORMS_BRAND_ID'] == 'AC')
		{
			$sql[] = "
			REPLACE INTO `#pref#_boforms_composant_type` (`COMPOSANT_TYPE_ID`, `COMPOSANT_TYPE_LABEL`, `COMPOSANT_HTML_LABEL`, `COMPOSANT_MOBILE_LABEL`, `COMPOSANT_TYPE_SITE`) VALUES
			(1, 'Brochure picker', 'HTML_brochurePicker', 'MOBILE_brochurePicker', 'LP'),
			(2, 'Car Picker', 'HTML_carPicker', 'MOBILE_carPicker', 'LP'),
			(3, 'Dealer Locator', 'HTML_dealerLocator', 'MOBILE_dealerLocator', 'LP'),
			(4, 'DealerLocatorLight', 'HTML_dealerLocatorLight', 'MOBILE_dealerLocatorLight', 'LP'),
			(5, 'LoginPopin', 'HTML_loginPopin', 'MOBILE_loginPopin', 'LP'),
			(6, 'LoginPopup', 'HTML_loginPopup', 'MOBILE_loginPopup', 'LP'),
			(7, 'Brochure picker', 'cpw_HTML_brochurePicker', 'cpw_MOBILE_brochurePicker', ''),
			(8, 'Car Picker', 'cpw_HTML_carPicker', 'cpw_MOBILE_carPicker', ''),
			(9, 'Dealer Locator', 'cpw_HTML_dealerLocator', 'cpw_MOBILE_dealerLocator', ''),
			(10, 'DealerLocatorLight', 'cpw_HTML_dealerLocatorLight', 'cpw_MOBILE_dealerLocatorLight', ''),
			(11, 'LoginPopin', 'cpw_HTML_loginPopin', 'cpw_MOBILE_loginPopin', ''),
			(12, 'LoginPopup', 'cpw_HTML_loginPopup', 'cpw_MOBILE_loginPopup', '');";
				
		}else{
			$sql[] = "
				REPLACE INTO `#pref#_boforms_composant_type` (`COMPOSANT_TYPE_ID`, `COMPOSANT_TYPE_LABEL`, `COMPOSANT_HTML_LABEL`, `COMPOSANT_MOBILE_LABEL`) VALUES
				(1, 'Car Picker', 'cpw_HTML_carPicker', 'cpw_MOBILE_carPicker'),
				(2, 'Select Car Picker', 'cpw_HTML_selectCarPicker', 'cpw_MOBILE_selectCarPicker'),
				(3, 'Brochure picker', 'cpw_HTML_brochurePicker', 'cpw_MOBILE_brochurePicker'),
				(4, 'Select Brochure picker', 'cpw_HTML_selectBrochurePicker', 'cpw_MOBILE_selectBrochurePicker'),
				(5, 'DealerLocatorLight', 'cpw_HTML_dealerLocatorLight', 'cpw_MOBILE_dealerLocatorLight'),
				(6, 'DealerLocatorMedium', 'cpw_HTML_dealerLocatorMedium', 'cpw_MOBILE_dealerLocatorMedium'),
				(7, 'DealerLocator', 'cpw_HTML_dealerLocator', 'cpw_MOBILE_dealerLocator'),
				(8, 'Login Popin', 'cpw_HTML_loginPopin', 'cpw_MOBILE_loginPopin'),
				(9, 'Login Popup', 'cpw_HTML_loginPopup', 'cpw_MOBILE_loginPopup');";
		}
		

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_context;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_context` (
        `CONTEXT_ID` int(11) NOT NULL,
        `CONTEXT_KEY` varchar(255) NOT NULL,
        PRIMARY KEY  (`CONTEXT_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";

		$sql[] = "
        REPLACE INTO `#pref#_boforms_context` (`CONTEXT_ID`, `CONTEXT_KEY`) VALUES
        (0, 'STANDARD'),
        (1, 'CONTEXTUALIZED_SALE'),
        (2, 'CONTEXTUALIZED_VEHICLE');
        ";

		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICLE', NULL,1);";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD', 1, 'Standard',1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD', 2, 'Standard',1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE', 1, 'Contextualisé Point de vente',1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE', 2, 'Contextualized point of sale',1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICLE', 1, 'Contextualisé Véhicule',1),
        ('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICLE', 2, 'Contextualized vehicle',1);";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_culture;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_culture` (
        `CULTURE_ID` int(11) NOT NULL,
        `LANGUE_ID` int(11) default NULL,
        `CULTURE_LABEL` varchar(255) collate utf8_swedish_ci default NULL,
        `CULTURE_KEY` char(2) collate utf8_swedish_ci NOT NULL,
        PRIMARY KEY  (`CULTURE_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";

		$sql[] = "
        REPLACE INTO `#pref#_boforms_culture` (`CULTURE_ID`, `LANGUE_ID`, `CULTURE_LABEL`, `CULTURE_KEY`) VALUES
        (1, 5, 'Afrikaans', 'af'),
        (2, 6, 'Bulgare', 'bg'),
        (3, 8, 'Tchèque', 'cs'),
        (4, 9, 'Danois', 'da'),
        (5, 10, 'Allemand', 'de'),
        (6, 11, 'Grec', 'el'),
        (7, 2, 'Anglais', 'en'),
        (8, 4, 'Espagnol', 'es'),
        (9, NULL, 'Finlandais', 'fi'),
        (10, 1, 'Français', 'fr'),
        (11, 16, 'Croate', 'hr'),
        (12, 17, 'Hongrois', 'hu'),
        (13, 3, 'Italien', 'it'),
        (14, 28, 'Norvégien', 'nn'),
        (15, 27, 'Hollandais', 'nl'),
        (16, 28, 'Norvégien', 'nn'),
        (17, 29, 'Polonais', 'pl'),
        (18, 30, 'Potugais', 'pt'),
        (19, 31, 'Roumain', 'ro'),
        (20, 32, 'Russe', 'ru'),
        (21, 33, 'Slovaque', 'sk'),
        (22, 34, 'Slovénien', 'si'),
        (23, 37, 'Suèdois', 'sv'),
        (24, 39, 'Turc', 'tr'),
        (25, NULL, 'Ukrainien', 'uk');";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_device;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_device` (
        `DEVICE_ID` int(11) NOT NULL,
        `DEVICE_KEY` varchar(255) collate utf8_swedish_ci NOT NULL,
        PRIMARY KEY  (`DEVICE_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";

		$sql[] = "
        REPLACE INTO `#pref#_boforms_device` (`DEVICE_ID`, `DEVICE_KEY`) VALUES
        (0, 'WEB'),
        (1, 'MOBILE');";

		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
        ('BOFORMS_REFERENTIAL_DEVICE_WEB', NULL,1),
        ('BOFORMS_REFERENTIAL_DEVICE_MOBILE', NULL,1);";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
        ('BOFORMS_REFERENTIAL_DEVICE_WEB', 1, 'Web/Tablette',1),
        ('BOFORMS_REFERENTIAL_DEVICE_WEB', 2, 'Web/Tablet',1),
        ('BOFORMS_REFERENTIAL_DEVICE_MOBILE', 1, 'Mobile',1),
        ('BOFORMS_REFERENTIAL_DEVICE_MOBILE', 2, 'Mobile',1);";


		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire;";

		$sql[] = "
			CREATE TABLE IF NOT EXISTS `#pref#_boforms_formulaire` (
			  `FORM_INCE` varchar(255) collate utf8_swedish_ci NOT NULL,
			  `FORM_NAME` varchar(255) collate utf8_swedish_ci default NULL,
			  `FORM_CONTEXT` int(11) default NULL,
			  `FORM_CURRENT_VERSION` int(11) default NULL,
			  `FORM_DRAFT_VERSION` int(11) default NULL,
			  `FORM_PARENT_INCE` varchar(255) collate utf8_swedish_ci default NULL,
			  `DEVICE_ID` int(11) default NULL,
			  `TARGET_ID` int(11) default NULL,
			  `CULTURE_ID` int(11) default NULL,
			  `FORMSITE_ID` int(11) default NULL,
			  `PAYS_CODE` char(2) collate utf8_swedish_ci default NULL,
			  `FORM_BRAND` char(2) collate utf8_swedish_ci default NULL,
			  `FORM_AB_TESTING` int(11) default NULL,
			  `OPPORTUNITE_ID` int(11) default NULL,
			  `FORM_GENERIC` int(11) default NULL,
			  `FORM_EDITABLE` int(11) default NULL,
			  `FORM_COMMENTARY` text collate utf8_swedish_ci,
			  `FORM_ACTIVATED` int(11) NOT NULL,
        	  `FORM_INSTANCE_NAME` varchar(255) collate utf8_swedish_ci default NULL,
			  `FORM_ID` varchar(255) collate utf8_swedish_ci default NULL,
			  `FORM_TYPE` varchar(255) collate utf8_swedish_ci NOT NULL,
			  PRIMARY KEY  (`FORM_INCE`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
        ";

		 
		$sql[] = "CREATE TABLE IF NOT EXISTS `#pref#_boforms_traductions_referentiel` (
				  `trad_ref_id` int(11) NOT NULL AUTO_INCREMENT,
				  `trad_ref_key` varchar(20) NOT NULL,
				  PRIMARY KEY (`trad_ref_id`),
				  UNIQUE KEY `trad_ref_key` (`trad_ref_key`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `#pref#_boforms_traductions_referentiel_datas` (
					  `TRAD_REF_ID` int(11) NOT NULL,
					  `TRAD_REF_LOCALE` int(11) NOT NULL,
					  `SITE_CODE_PAYS` varchar(2) NOT NULL,
					  `TRAD_REF_VALUE` varchar(255) NOT NULL,
					  PRIMARY KEY (`TRAD_REF_ID`,`TRAD_REF_LOCALE`,`SITE_CODE_PAYS`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
		
		
		
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire_site;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_formulaire_site` (
        `FORMSITE_ID` int(11) NOT NULL,
        `FORMSITE_LABEL` varchar(255) collate utf8_swedish_ci default NULL,
        `FORMSITE_KEY` varchar(255) collate utf8_swedish_ci default NULL,
        PRIMARY KEY  (`FORMSITE_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
        ";

		$sql[] = "
        REPLACE INTO `#pref#_boforms_formulaire_site` (`FORMSITE_ID`, `FORMSITE_LABEL`,`FORMSITE_KEY`) VALUES
        (1, 'Site de la marque','BRAND_SITE'),
        (2, 'Espace Perso','PERSONAL_SPACE'),
        (3, 'Landing Page','LANDING_PAGE'),
        (4, 'Configurateur','CONFIGURATOR'),
        (5, 'eDealer','EDEALER'),
        (6, 'Store','STORE'),
        (7, 'Produits dérivés','DERIVED_PRODUCT');
        ";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire_version;";

		$sql[] = "
		CREATE TABLE IF NOT EXISTS `#pref#_boforms_formulaire_version` (
		  `FORM_INCE` varchar(255) collate utf8_swedish_ci NOT NULL,
		  `FORM_VERSION` int(11) NOT NULL,
		  `FORM_XML_CONTENT` longtext collate utf8_swedish_ci,
		  `FORM_DATE` timestamp NULL default NULL,
		  `FORM_LOG` varchar(255) collate utf8_swedish_ci default NULL,
		  `USER_LOGIN` varchar(50) collate utf8_swedish_ci default NULL,
		  `STATE_ID` int(11) default NULL,
		  PRIMARY KEY  (`FORM_INCE`,`FORM_VERSION`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_groupe;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_groupe` (
        `GROUPE_ID` int(11) NOT NULL auto_increment,
        `GROUPE_LABEL` varchar(255) collate utf8_swedish_ci default NULL,
        `FORMSITE_ID_MASTER` int(11) default NULL,
        `GROUPE_TEXT` text collate utf8_swedish_ci,
        `SITE_ID` int(11) NOT NULL,
        PRIMARY KEY  (`GROUPE_ID`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci ;";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_groupe_formulaire;";
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `#pref#_boforms_groupe_formulaire` (
		`GROUPE_ID` int(11) NOT NULL default '0',
		`FORMSITE_ID` int(11) NOT NULL,
		PRIMARY KEY  (`GROUPE_ID`,`FORMSITE_ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";
		
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_opportunite;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_opportunite` (
        `OPPORTUNITE_ID` int(11) NOT NULL,
        `OPPORTUNITE_KEY` varchar(255) collate utf8_swedish_ci default NULL,
        PRIMARY KEY  (`OPPORTUNITE_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";


		$sql[] = "
        REPLACE INTO `#pref#_boforms_opportunite` (`OPPORTUNITE_ID`, `OPPORTUNITE_KEY`) VALUES
        (1, 'BOOK_A_TEST_DRIVE'),
        (2, 'REQUEST_A_BROCHURE'),
        (3, 'REQUEST_AN_OFFER'),
        (4, 'SUBSCRIBE_NEWSLETTER'),
        (5, 'UNSUBSCRIBE_NEWSLETTER'),
        (6, 'REQUEST_A_CONTACT_BUSINESS'),
        (7, 'CLAIMS'),
        (8, 'REQUEST_AN_INFORMATIONS'),
        (9, 'CLAIMS_ABOUT_YOUR_CAR'),
        (10, 'CLAIMS_ABOUT_YOUR_DEALER'),
        (11, 'CLAIMS_ABOUT_DOCUMENTATION'),
        (12, 'CLAIMS_OTHER'),
        (13, 'LANDING_PAGE'),
        (14, 'LANDING_PAGE_1'),
        (15, 'LANDING_PAGE_2'),
        (16, 'UNSUBSCRIBE_NEWSLETTER_AMEX'),
        (17, 'UNSUBSCRIBE_NEWSLETTER_EMAILING'),
        (18, 'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'),
        (19, 'UNSUBSCRIBE_NEWSLETTER_B2B'),
        (20, 'UNSUBSCRIBE_NEWSLETTER_CNIL');";

		/* (9, 'CLAIMS_ABOUT_YOUR_CAR'),
        (10, 'CLAIMS_ABOUT_YOUR_DEALER'),
        (11, 'CLAIMS_ABOUT_DOCUMENTATION'),
		(12, 'CLAIMS_OTHER')*/


		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
		('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_BOOK_A_TEST_DRIVE', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BROCHURE', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_OFFER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_SUBSCRIBE_NEWSLETTER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_CONTACT_BUSINESS', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_INFORMATIONS', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_CAR', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_DEALER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_DOCUMENTATION', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_OTHER', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_1', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_2', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_AMEX', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_EMAILING', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CREDIPAR', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_B2B', NULL,1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CNIL', NULL,1)
       
         ;";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
		('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', 1, 'Autre', 1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', 2, 'Other', 1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_BOOK_A_TEST_DRIVE', 1, 'Demande d''essai', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_BOOK_A_TEST_DRIVE', 2, 'Test drive request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BROCHURE', 1, 'Demande de brochure', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BROCHURE', 2, 'Brochure request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_OFFER', 1, 'Demande d''offre commerciale', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_OFFER', 2, 'Offer request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_SUBSCRIBE_NEWSLETTER', 1, 'Inscription Newsletter', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_SUBSCRIBE_NEWSLETTER', 2, 'Newsletter subscription', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER', 1, 'Désinscription Newsletter', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER', 2, 'Newsletter Unsubscription', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_CONTACT_BUSINESS', 1, 'Contact Business', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_CONTACT_BUSINESS', 2, 'Contact business request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS', 1, 'Demande de réclamation', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS', 2, 'Reclamation request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_INFORMATIONS', 1, 'Demande d''information', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_INFORMATIONS', 2, 'Personal contact request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_CAR', 1, 'Contact véhicule', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_CAR', 2, 'Contact vehicle ', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_DEALER', 1, 'Contact Point de Vente', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_YOUR_DEALER', 2, 'Add a Field', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_DOCUMENTATION', 1, 'Documentation', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_ABOUT_DOCUMENTATION', 2, 'Documentation', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_OTHER', 1, 'Autre demande', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_CLAIMS_OTHER', 2, 'Other request', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE', 1, 'Multi-opportunités', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE', 2, 'Multi-opportunities', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_1', 1, 'Multi-opportunités page 1', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_1', 2, 'Multi-opportunities page 1', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_2', 1, 'Multi-opportunités page 2', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_LANDING_PAGE_2', 2, 'Multi-opportunities page 2', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_AMEX', 1, 'Désinscription newsletter AMEX', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_AMEX', 2, 'Newsletter unsubscription AMEX', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_EMAILING', 1, 'Désinscription newsletter emailing', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_EMAILING', 2, 'Newsletter unsubscription emailing', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CREDIPAR', 1, 'Désinscription newsletter Credipar', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CREDIPAR', 2, 'Newsletter unsubscription Credipar', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_B2B', 1, 'Désinscription newsletter B2B', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_B2B', 2, 'Newsletter unsubscription B2B', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CNIL', 1, 'Désinscription newsletter CNIL', 1),
        ('BOFORMS_REFERENTIAL_FORM_TYPE_UNSUBSCRIBE_NEWSLETTER_CNIL', 2, 'Newsletter unsubscription CNIL', 1)		
        		;";

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_target;";

		$sql[] = "
        CREATE TABLE IF NOT EXISTS `#pref#_boforms_target` (
        `TARGET_ID` int(11) NOT NULL,
        `TARGET_KEY` varchar(255) collate utf8_swedish_ci default NULL,
        PRIMARY KEY  (`TARGET_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;";
		 
		$sql[] = "
        REPLACE INTO `#pref#_boforms_target` (`TARGET_ID`, `TARGET_KEY`) VALUES
        (1, 'PARTICULAR'),
        (2, 'PROFESSIONAL'),
        (3, 'INTERSTITIAL');
        ";

		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR', NULL,1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL', NULL,1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_INTERSTITIAL', NULL,1);";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR', 1, 'Particulier', 1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR', 2, 'Particular', 1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL', 1, 'Professionnel', 1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL', 2, 'Professional', 1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_INTERSTITIAL', 1, 'Interstitiel', 1),
        ('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_INTERSTITIAL', 2, 'Interstitial', 1);";
		 
		
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_country;";

		$sql[] = "
		CREATE TABLE IF NOT EXISTS `#pref#_boforms_country` (
		`id` smallint(5) unsigned NOT NULL auto_increment,
		`code` int(3) NOT NULL,
		`alpha2` varchar(2) NOT NULL,
		`alpha3` varchar(3) NOT NULL,
		`nom_en_gb` varchar(45) NOT NULL,
		`nom_fr_fr` varchar(45) NOT NULL,
		PRIMARY KEY  (`id`),
		UNIQUE KEY `alpha2` (`alpha2`),
		UNIQUE KEY `alpha3` (`alpha3`),
		UNIQUE KEY `code_unique` (`code`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=242 ;";
		
		$sql[] = "
		REPLACE INTO `#pref#_boforms_country` (`id`, `code`, `alpha2`, `alpha3`, `nom_en_gb`, `nom_fr_fr`) VALUES
		(1, 4, 'AF', 'AFG', 'Afghanistan', 'Afghanistan'),
		(2, 8, 'AL', 'ALB', 'Albania', 'Albanie'),
		(3, 10, 'AQ', 'ATA', 'Antarctica', 'Antarctique'),
		(4, 12, 'DZ', 'DZA', 'Algeria', 'Algérie'),
		(5, 16, 'AS', 'ASM', 'American Samoa', 'Samoa Américaines'),
		(6, 20, 'AD', 'AND', 'Andorra', 'Andorre'),
		(7, 24, 'AO', 'AGO', 'Angola', 'Angola'),
		(8, 28, 'AG', 'ATG', 'Antigua and Barbuda', 'Antigua-et-Barbuda'),
		(9, 31, 'AZ', 'AZE', 'Azerbaijan', 'Azerbaïdjan'),
		(10, 32, 'AR', 'ARG', 'Argentina', 'Argentine'),
		(11, 36, 'AU', 'AUS', 'Australia', 'Australie'),
		(12, 40, 'AT', 'AUT', 'Austria', 'Autriche'),
		(13, 44, 'BS', 'BHS', 'Bahamas', 'Bahamas'),
		(14, 48, 'BH', 'BHR', 'Bahrain', 'Bahreïn'),
		(15, 50, 'BD', 'BGD', 'Bangladesh', 'Bangladesh'),
		(16, 51, 'AM', 'ARM', 'Armenia', 'Arménie'),
		(17, 52, 'BB', 'BRB', 'Barbados', 'Barbade'),
		(18, 56, 'BE', 'BEL', 'Belgium', 'Belgique'),
		(19, 60, 'BM', 'BMU', 'Bermuda', 'Bermudes'),
		(20, 64, 'BT', 'BTN', 'Bhutan', 'Bhoutan'),
		(21, 68, 'BO', 'BOL', 'Bolivia', 'Bolivie'),
		(22, 70, 'BA', 'BIH', 'Bosnia and Herzegovina', 'Bosnie-Herzégovine'),
		(23, 72, 'BW', 'BWA', 'Botswana', 'Botswana'),
		(24, 74, 'BV', 'BVT', 'Bouvet Island', 'Île Bouvet'),
		(25, 76, 'BR', 'BRA', 'Brazil', 'Brésil'),
		(26, 84, 'BZ', 'BLZ', 'Belize', 'Belize'),
		(27, 86, 'IO', 'IOT', 'British Indian Ocean Territory', 'Territoire Britannique de l''Océan Indien'),
		(28, 90, 'SB', 'SLB', 'Solomon Islands', 'Îles Salomon'),
		(29, 92, 'VG', 'VGB', 'British Virgin Islands', 'Îles Vierges Britanniques'),
		(30, 96, 'BN', 'BRN', 'Brunei Darussalam', 'Brunéi Darussalam'),
		(31, 100, 'BG', 'BGR', 'Bulgaria', 'Bulgarie'),
		(32, 104, 'MM', 'MMR', 'Myanmar', 'Myanmar'),
		(33, 108, 'BI', 'BDI', 'Burundi', 'Burundi'),
		(34, 112, 'BY', 'BLR', 'Belarus', 'Bélarus'),
		(35, 116, 'KH', 'KHM', 'Cambodia', 'Cambodge'),
		(36, 120, 'CM', 'CMR', 'Cameroon', 'Cameroun'),
		(37, 124, 'CA', 'CAN', 'Canada', 'Canada'),
		(38, 132, 'CV', 'CPV', 'Cape Verde', 'Cap-vert'),
		(39, 136, 'KY', 'CYM', 'Cayman Islands', 'Îles Caïmanes'),
		(40, 140, 'CF', 'CAF', 'Central African', 'République Centrafricaine'),
		(41, 144, 'LK', 'LKA', 'Sri Lanka', 'Sri Lanka'),
		(42, 148, 'TD', 'TCD', 'Chad', 'Tchad'),
		(43, 152, 'CL', 'CHL', 'Chile', 'Chili'),
		(44, 156, 'CN', 'CHN', 'China', 'Chine'),
		(45, 158, 'TW', 'TWN', 'Taiwan', 'Taïwan'),
		(46, 162, 'CX', 'CXR', 'Christmas Island', 'Île Christmas'),
		(47, 166, 'CC', 'CCK', 'Cocos (Keeling) Islands', 'Îles Cocos (Keeling)'),
		(48, 170, 'CO', 'COL', 'Colombia', 'Colombie'),
		(49, 174, 'KM', 'COM', 'Comoros', 'Comores'),
		(50, 175, 'YT', 'MYT', 'Mayotte', 'Mayotte'),
		(51, 178, 'CG', 'COG', 'Republic of the Congo', 'République du Congo'),
		(52, 180, 'CD', 'COD', 'The Democratic Republic Of The Congo', 'République Démocratique du Congo'),
		(53, 184, 'CK', 'COK', 'Cook Islands', 'Îles Cook'),
		(54, 188, 'CR', 'CRI', 'Costa Rica', 'Costa Rica'),
		(55, 191, 'HR', 'HRV', 'Croatia', 'Croatie'),
		(56, 192, 'CU', 'CUB', 'Cuba', 'Cuba'),
		(57, 196, 'CY', 'CYP', 'Cyprus', 'Chypre'),
		(58, 203, 'CZ', 'CZE', 'Czech Republic', 'République Tchèque'),
		(59, 204, 'BJ', 'BEN', 'Benin', 'Bénin'),
		(60, 208, 'DK', 'DNK', 'Denmark', 'Danemark'),
		(61, 212, 'DM', 'DMA', 'Dominica', 'Dominique'),
		(62, 214, 'DO', 'DOM', 'Dominican Republic', 'République Dominicaine'),
		(63, 218, 'EC', 'ECU', 'Ecuador', 'Équateur'),
		(64, 222, 'SV', 'SLV', 'El Salvador', 'El Salvador'),
		(65, 226, 'GQ', 'GNQ', 'Equatorial Guinea', 'Guinée Équatoriale'),
		(66, 231, 'ET', 'ETH', 'Ethiopia', 'Éthiopie'),
		(67, 232, 'ER', 'ERI', 'Eritrea', 'Érythrée'),
		(68, 233, 'EE', 'EST', 'Estonia', 'Estonie'),
		(69, 234, 'FO', 'FRO', 'Faroe Islands', 'Îles Féroé'),
		(70, 238, 'FK', 'FLK', 'Falkland Islands', 'Îles (malvinas) Falkland'),
		(71, 239, 'GS', 'SGS', 'South Georgia and the South Sandwich Islands', 'Géorgie du Sud et les Îles Sandwich du Sud'),
		(72, 242, 'FJ', 'FJI', 'Fiji', 'Fidji'),
		(73, 246, 'FI', 'FIN', 'Finland', 'Finlande'),
		(74, 248, 'AX', 'ALA', 'Åland Islands', 'Îles Åland'),
		(75, 250, 'FR', 'FRA', 'France', 'France'),
		(76, 254, 'GF', 'GUF', 'French Guiana', 'Guyane Française'),
		(77, 258, 'PF', 'PYF', 'French Polynesia', 'Polynésie Française'),
		(78, 260, 'TF', 'ATF', 'French Southern Territories', 'Terres Australes Françaises'),
		(79, 262, 'DJ', 'DJI', 'Djibouti', 'Djibouti'),
		(80, 266, 'GA', 'GAB', 'Gabon', 'Gabon'),
		(81, 268, 'GE', 'GEO', 'Georgia', 'Géorgie'),
		(82, 270, 'GM', 'GMB', 'Gambia', 'Gambie'),
		(83, 275, 'PS', 'PSE', 'Occupied Palestinian Territory', 'Territoire Palestinien Occupé'),
		(84, 276, 'DE', 'DEU', 'Germany', 'Allemagne'),
		(85, 288, 'GH', 'GHA', 'Ghana', 'Ghana'),
		(86, 292, 'GI', 'GIB', 'Gibraltar', 'Gibraltar'),
		(87, 296, 'KI', 'KIR', 'Kiribati', 'Kiribati'),
		(88, 300, 'GR', 'GRC', 'Greece', 'Grèce'),
		(89, 304, 'GL', 'GRL', 'Greenland', 'Groenland'),
		(90, 308, 'GD', 'GRD', 'Grenada', 'Grenade'),
		(91, 312, 'GP', 'GLP', 'Guadeloupe', 'Guadeloupe'),
		(92, 316, 'GU', 'GUM', 'Guam', 'Guam'),
		(93, 320, 'GT', 'GTM', 'Guatemala', 'Guatemala'),
		(94, 324, 'GN', 'GIN', 'Guinea', 'Guinée'),
		(95, 328, 'GY', 'GUY', 'Guyana', 'Guyana'),
		(96, 332, 'HT', 'HTI', 'Haiti', 'Haïti'),
		(97, 334, 'HM', 'HMD', 'Heard Island and McDonald Islands', 'Îles Heard et Mcdonald'),
		(98, 336, 'VA', 'VAT', 'Vatican City State', 'Saint-Siège (état de la Cité du Vatican)'),
		(99, 340, 'HN', 'HND', 'Honduras', 'Honduras'),
		(100, 344, 'HK', 'HKG', 'Hong Kong', 'Hong-Kong'),
		(101, 348, 'HU', 'HUN', 'Hungary', 'Hongrie'),
		(102, 352, 'IS', 'ISL', 'Iceland', 'Islande'),
		(103, 356, 'IN', 'IND', 'India', 'Inde'),
		(104, 360, 'ID', 'IDN', 'Indonesia', 'Indonésie'),
		(105, 364, 'IR', 'IRN', 'Islamic Republic of Iran', 'République Islamique d''Iran'),
		(106, 368, 'IQ', 'IRQ', 'Iraq', 'Iraq'),
		(107, 372, 'IE', 'IRL', 'Ireland', 'Irlande'),
		(108, 376, 'IL', 'ISR', 'Israel', 'Israël'),
		(109, 380, 'IT', 'ITA', 'Italy', 'Italie'),
		(110, 384, 'CI', 'CIV', 'Côte d''Ivoire', 'Côte d''Ivoire'),
		(111, 388, 'JM', 'JAM', 'Jamaica', 'Jamaïque'),
		(112, 392, 'JP', 'JPN', 'Japan', 'Japon'),
		(113, 398, 'KZ', 'KAZ', 'Kazakhstan', 'Kazakhstan'),
		(114, 400, 'JO', 'JOR', 'Jordan', 'Jordanie'),
		(115, 404, 'KE', 'KEN', 'Kenya', 'Kenya'),
		(116, 408, 'KP', 'PRK', 'Democratic People''s Republic of Korea', 'République Populaire Démocratique de Corée'),
		(117, 410, 'KR', 'KOR', 'Republic of Korea', 'République de Corée'),
		(118, 414, 'KW', 'KWT', 'Kuwait', 'Koweït'),
		(119, 417, 'KG', 'KGZ', 'Kyrgyzstan', 'Kirghizistan'),
		(120, 418, 'LA', 'LAO', 'Lao People''s Democratic Republic', 'République Démocratique Populaire Lao'),
		(121, 422, 'LB', 'LBN', 'Lebanon', 'Liban'),
		(122, 426, 'LS', 'LSO', 'Lesotho', 'Lesotho'),
		(123, 428, 'LV', 'LVA', 'Latvia', 'Lettonie'),
		(124, 430, 'LR', 'LBR', 'Liberia', 'Libéria'),
		(125, 434, 'LY', 'LBY', 'Libyan Arab Jamahiriya', 'Jamahiriya Arabe Libyenne'),
		(126, 438, 'LI', 'LIE', 'Liechtenstein', 'Liechtenstein'),
		(127, 440, 'LT', 'LTU', 'Lithuania', 'Lituanie'),
		(128, 442, 'LU', 'LUX', 'Luxembourg', 'Luxembourg'),
		(129, 446, 'MO', 'MAC', 'Macao', 'Macao'),
		(130, 450, 'MG', 'MDG', 'Madagascar', 'Madagascar'),
		(131, 454, 'MW', 'MWI', 'Malawi', 'Malawi'),
		(132, 458, 'MY', 'MYS', 'Malaysia', 'Malaisie'),
		(133, 462, 'MV', 'MDV', 'Maldives', 'Maldives'),
		(134, 466, 'ML', 'MLI', 'Mali', 'Mali'),
		(135, 470, 'MT', 'MLT', 'Malta', 'Malte'),
		(136, 474, 'MQ', 'MTQ', 'Martinique', 'Martinique'),
		(137, 478, 'MR', 'MRT', 'Mauritania', 'Mauritanie'),
		(138, 480, 'MU', 'MUS', 'Mauritius', 'Maurice'),
		(139, 484, 'MX', 'MEX', 'Mexico', 'Mexique'),
		(140, 492, 'MC', 'MCO', 'Monaco', 'Monaco'),
		(141, 496, 'MN', 'MNG', 'Mongolia', 'Mongolie'),
		(142, 498, 'MD', 'MDA', 'Republic of Moldova', 'République de Moldova'),
		(143, 500, 'MS', 'MSR', 'Montserrat', 'Montserrat'),
		(144, 504, 'MA', 'MAR', 'Morocco', 'Maroc'),
		(145, 508, 'MZ', 'MOZ', 'Mozambique', 'Mozambique'),
		(146, 512, 'OM', 'OMN', 'Oman', 'Oman'),
		(147, 516, 'NA', 'NAM', 'Namibia', 'Namibie'),
		(148, 520, 'NR', 'NRU', 'Nauru', 'Nauru'),
		(149, 524, 'NP', 'NPL', 'Nepal', 'Népal'),
		(150, 528, 'NL', 'NLD', 'Netherlands', 'Pays-Bas'),
		(151, 530, 'AN', 'ANT', 'Netherlands Antilles', 'Antilles Néerlandaises'),
		(152, 533, 'AW', 'ABW', 'Aruba', 'Aruba'),
		(153, 540, 'NC', 'NCL', 'New Caledonia', 'Nouvelle-Calédonie'),
		(154, 548, 'VU', 'VUT', 'Vanuatu', 'Vanuatu'),
		(155, 554, 'NZ', 'NZL', 'New Zealand', 'Nouvelle-Zélande'),
		(156, 558, 'NI', 'NIC', 'Nicaragua', 'Nicaragua'),
		(157, 562, 'NE', 'NER', 'Niger', 'Niger'),
		(158, 566, 'NG', 'NGA', 'Nigeria', 'Nigéria'),
		(159, 570, 'NU', 'NIU', 'Niue', 'Niué'),
		(160, 574, 'NF', 'NFK', 'Norfolk Island', 'Île Norfolk'),
		(161, 578, 'NO', 'NOR', 'Norway', 'Norvège'),
		(162, 580, 'MP', 'MNP', 'Northern Mariana Islands', 'Îles Mariannes du Nord'),
		(163, 581, 'UM', 'UMI', 'United States Minor Outlying Islands', 'Îles Mineures Éloignées des États-Unis'),
		(164, 583, 'FM', 'FSM', 'Federated States of Micronesia', 'États Fédérés de Micronésie'),
		(165, 584, 'MH', 'MHL', 'Marshall Islands', 'Îles Marshall'),
		(166, 585, 'PW', 'PLW', 'Palau', 'Palaos'),
		(167, 586, 'PK', 'PAK', 'Pakistan', 'Pakistan'),
		(168, 591, 'PA', 'PAN', 'Panama', 'Panama'),
		(169, 598, 'PG', 'PNG', 'Papua New Guinea', 'Papouasie-Nouvelle-Guinée'),
		(170, 600, 'PY', 'PRY', 'Paraguay', 'Paraguay'),
		(171, 604, 'PE', 'PER', 'Peru', 'Pérou'),
		(172, 608, 'PH', 'PHL', 'Philippines', 'Philippines'),
		(173, 612, 'PN', 'PCN', 'Pitcairn', 'Pitcairn'),
		(174, 616, 'PL', 'POL', 'Poland', 'Pologne'),
		(175, 620, 'PT', 'PRT', 'Portugal', 'Portugal'),
		(176, 624, 'GW', 'GNB', 'Guinea-Bissau', 'Guinée-Bissau'),
		(177, 626, 'TL', 'TLS', 'Timor-Leste', 'Timor-Leste'),
		(178, 630, 'PR', 'PRI', 'Puerto Rico', 'Porto Rico'),
		(179, 634, 'QA', 'QAT', 'Qatar', 'Qatar'),
		(180, 638, 'RE', 'REU', 'Réunion', 'Réunion'),
		(181, 642, 'RO', 'ROU', 'Romania', 'Roumanie'),
		(182, 643, 'RU', 'RUS', 'Russian Federation', 'Fédération de Russie'),
		(183, 646, 'RW', 'RWA', 'Rwanda', 'Rwanda'),
		(184, 654, 'SH', 'SHN', 'Saint Helena', 'Sainte-Hélène'),
		(185, 659, 'KN', 'KNA', 'Saint Kitts and Nevis', 'Saint-Kitts-et-Nevis'),
		(186, 660, 'AI', 'AIA', 'Anguilla', 'Anguilla'),
		(187, 662, 'LC', 'LCA', 'Saint Lucia', 'Sainte-Lucie'),
		(188, 666, 'PM', 'SPM', 'Saint-Pierre and Miquelon', 'Saint-Pierre-et-Miquelon'),
		(189, 670, 'VC', 'VCT', 'Saint Vincent and the Grenadines', 'Saint-Vincent-et-les Grenadines'),
		(190, 674, 'SM', 'SMR', 'San Marino', 'Saint-Marin'),
		(191, 678, 'ST', 'STP', 'Sao Tome and Principe', 'Sao Tomé-et-Principe'),
		(192, 682, 'SA', 'SAU', 'Saudi Arabia', 'Arabie Saoudite'),
		(193, 686, 'SN', 'SEN', 'Senegal', 'Sénégal'),
		(194, 690, 'SC', 'SYC', 'Seychelles', 'Seychelles'),
		(195, 694, 'SL', 'SLE', 'Sierra Leone', 'Sierra Leone'),
		(196, 702, 'SG', 'SGP', 'Singapore', 'Singapour'),
		(197, 703, 'SK', 'SVK', 'Slovakia', 'Slovaquie'),
		(198, 704, 'VN', 'VNM', 'Vietnam', 'Viet Nam'),
		(199, 705, 'SI', 'SVN', 'Slovenia', 'Slovénie'),
		(200, 706, 'SO', 'SOM', 'Somalia', 'Somalie'),
		(201, 710, 'ZA', 'ZAF', 'South Africa', 'Afrique du Sud'),
		(202, 716, 'ZW', 'ZWE', 'Zimbabwe', 'Zimbabwe'),
		(203, 724, 'ES', 'ESP', 'Spain', 'Espagne'),
		(204, 732, 'EH', 'ESH', 'Western Sahara', 'Sahara Occidental'),
		(205, 736, 'SD', 'SDN', 'Sudan', 'Soudan'),
		(206, 740, 'SR', 'SUR', 'Suriname', 'Suriname'),
		(207, 744, 'SJ', 'SJM', 'Svalbard and Jan Mayen', 'Svalbard etÎle Jan Mayen'),
		(208, 748, 'SZ', 'SWZ', 'Swaziland', 'Swaziland'),
		(209, 752, 'SE', 'SWE', 'Sweden', 'Suède'),
		(210, 756, 'CH', 'CHE', 'Switzerland', 'Suisse'),
		(211, 760, 'SY', 'SYR', 'Syrian Arab Republic', 'République Arabe Syrienne'),
		(212, 762, 'TJ', 'TJK', 'Tajikistan', 'Tadjikistan'),
		(213, 764, 'TH', 'THA', 'Thailand', 'Thaïlande'),
		(214, 768, 'TG', 'TGO', 'Togo', 'Togo'),
		(215, 772, 'TK', 'TKL', 'Tokelau', 'Tokelau'),
		(216, 776, 'TO', 'TON', 'Tonga', 'Tonga'),
		(217, 780, 'TT', 'TTO', 'Trinidad and Tobago', 'Trinité-et-Tobago'),
		(218, 784, 'AE', 'ARE', 'United Arab Emirates', 'Émirats Arabes Unis'),
		(219, 788, 'TN', 'TUN', 'Tunisia', 'Tunisie'),
		(220, 792, 'TR', 'TUR', 'Turkey', 'Turquie'),
		(221, 795, 'TM', 'TKM', 'Turkmenistan', 'Turkménistan'),
		(222, 796, 'TC', 'TCA', 'Turks and Caicos Islands', 'Îles Turks et Caïques'),
		(223, 798, 'TV', 'TUV', 'Tuvalu', 'Tuvalu'),
		(224, 800, 'UG', 'UGA', 'Uganda', 'Ouganda'),
		(225, 804, 'UA', 'UKR', 'Ukraine', 'Ukraine'),
		(226, 807, 'MK', 'MKD', 'The Former Yugoslav Republic of Macedonia', 'L''ex-République Yougoslave de Macédoine'),
		(227, 818, 'EG', 'EGY', 'Egypt', 'Égypte'),
		(228, 826, 'GB', 'GBR', 'United Kingdom', 'Royaume-Uni'),
		(229, 833, 'IM', 'IMN', 'Isle of Man', 'Île de Man'),
		(230, 834, 'TZ', 'TZA', 'United Republic Of Tanzania', 'République-Unie de Tanzanie'),
		(231, 840, 'US', 'USA', 'United States', 'États-Unis'),
		(232, 850, 'VI', 'VIR', 'U.S. Virgin Islands', 'Îles Vierges des États-Unis'),
		(233, 854, 'BF', 'BFA', 'Burkina Faso', 'Burkina Faso'),
		(234, 858, 'UY', 'URY', 'Uruguay', 'Uruguay'),
		(235, 860, 'UZ', 'UZB', 'Uzbekistan', 'Ouzbékistan'),
		(236, 862, 'VE', 'VEN', 'Venezuela', 'Venezuela'),
		(237, 876, 'WF', 'WLF', 'Wallis and Futuna', 'Wallis et Futuna'),
		(238, 882, 'WS', 'WSM', 'Samoa', 'Samoa'),
		(239, 887, 'YE', 'YEM', 'Yemen', 'Yémen'),
		(240, 891, 'CS', 'SCG', 'Serbia and Montenegro', 'Serbie-et-Monténégro'),
		(241, 894, 'ZM', 'ZMB', 'Zambia', 'Zambie');";
		

		/* CONSTANTE de traduction */

		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
('BOFORMS_LABEL_INPUTMASK', NULL,1),
('BOFORMS_LABEL_SBS_NWL_NEWS', NULL,1),
('BOFORMS_LABEL_connector_brandid', NULL,1),
('BOFORMS_LABEL_connector_brandid_AC', NULL,1),
('BOFORMS_LABEL_connector_brandid_AP', NULL,1),
('BOFORMS_LABEL_connector_brandid_DS', NULL,1),
('BOFORMS_LABEL_connector_facebook', NULL,1),
('BOFORMS_LABEL_USR_PHONE_TYPE', NULL,1),
('BOFORMS_LABEL_USR_PHONE_HOME', NULL,1),
('BOFORMS_LABEL_USR_PHONE_MOBILE', NULL,1),
('BOFORMS_LABEL_USR_PHONE_MOBILE_HOME', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', NULL,1),
('BOFORMS_RELOAD_INSTANCES_CONFIRM', NULL,1),
('BOFORMS_RELOAD_REFERENTIAL_CONFIRM', NULL,1),
('BOFORMS_BTN_RELOAD_INSTANCES', NULL,1),
('BOFORMS_BTN_RELOAD_REFERENTIAL', NULL,1),
('BOFORMS_RELOAD_INSTANCES_DONE', NULL,1),
('BOFORMS_RELOAD_REFERENTIAL_DONE', NULL,1),
('BOFORMS_LABEL_DEFAULT_VALUE_MSG', NULL,1),
('BOFORMS_ADD_FIELD', NULL,1),
('BOFORMS_CANCEL', NULL,1),
('BOFORMS_EDIT', NULL,1),
('BOFORMS_LABEL_CODE', NULL,1),
('BOFORMS_LABEL_REGEXP', NULL,1),
('BOFORMS_LABEL_REGEXP_MSG', NULL,1),
('BOFORMS_LABEL_REQUIRED_MSG', NULL,1),
('BOFORMS_LABEL_REGEXP_SAMPLE', NULL,1),
('BOFORMS_LABEL_REQUIRED?', NULL,1),
('BOFORMS_LABEL_SUCCESS', NULL,1),
('BOFORMS_LABEL_TITLE', NULL,1),
('BOFORMS_TITLE_PHONE', NULL,1),
('BOFORMS_TYPE_DATE', NULL,1),
('BOFORMS_TITLE_POPIN_COUNTRY', NULL,1),
('BOFORMS_BTN_CLEAR_CACHE', NULL,1),
('BOFORMS_TRACE_EDIT_COMPONENT', NULL,1),
('BOFORMS_TRACE_ADD_COMPONENT', NULL,1),
('BOFORMS_TRACE_REMOVE_COMPONENT', NULL,1),
('BOFORMS_TRACE_CHANGE_STEP_COMPONENT', NULL,1),
('BOFORMS_TRACE_MOVE_COMPONENT', NULL,1),
('BOFORMS_TRACE_EDIT_STEP_COMPONENT', NULL,1),
('BOFORMS_TRACE_MOVE_STEP', NULL,1),
('BOFORMS_REPORTINGSTATUSFORMSBYCOUNTRY', NULL,1),
('BOFORMS_NODE_label', NULL,1),
('BOFORMS_NODE_help', NULL,1),
('BOFORMS_NODE_align', NULL,1),
('BOFORMS_NODE_regexp', NULL,1),
('BOFORMS_NODE_is_required', NULL,1),
('BOFORMS_LABEL_PHONE', NULL,1),
('BOFORMS_AUCUN_RESULTAT', NULL,1),
('BOFORMS_LABEL_GET_MYDS', NULL,1)
				";

		$sql[] = "REPLACE INTO `#pref#_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES 
('BOFORMS_LABEL_INPUTMASK', '1', 'Masque de saisie', 1),
('BOFORMS_LABEL_INPUTMASK', '2', 'Input mask', 1),
('BOFORMS_LABEL_SBS_NWL_NEWS', '1', 'Opt-in newsletter', 1),
('BOFORMS_LABEL_SBS_NWL_NEWS', '2', 'Newsletter Opt-in', 1),
('BOFORMS_LABEL_GET_MYDS', '1', 'Opt-in_MYDS', 1),
('BOFORMS_LABEL_GET_MYDS', '2', 'Opt-in_MYDS', 1),
('BOFORMS_LABEL_connector_brandid_AC', 1, 'Citroën ID', 1),
('BOFORMS_LABEL_connector_brandid_AC', 2, 'Citroën ID', 1),
('BOFORMS_LABEL_connector_brandid_AP', 1, 'Peugeot ID', 1),
('BOFORMS_LABEL_connector_brandid_AP', 2, 'Peugeot ID', 1),
('BOFORMS_LABEL_connector_brandid_DS', 1, 'DS ID', 1),
('BOFORMS_LABEL_connector_brandid_DS', 2, 'DS ID', 1),
('BOFORMS_LABEL_connector_brandid', 1, 'Citroën ID', 1),
('BOFORMS_LABEL_connector_brandid', 2, 'Citroën ID', 1),
('BOFORMS_LABEL_connector_facebook', 1, 'Facebook ID', 1),
('BOFORMS_LABEL_connector_facebook', 2, 'Facebook ID', 1),		
('BOFORMS_NODE_is_required', 1, 'obligatoire', 1),
('BOFORMS_NODE_is_required', 2, 'require', 1),
('BOFORMS_LABEL_PHONE', 1, 'Téléphone', 1),
('BOFORMS_LABEL_PHONE', 2, 'Phone', 1),
('BOFORMS_NODE_label', 1, 'libellé', 1),
('BOFORMS_NODE_label', 2, 'label', 1),
('BOFORMS_NODE_help', 1, 'bulle d''aide', 1),
('BOFORMS_NODE_help', 2, 'tooltip', 1),
('BOFORMS_NODE_align', 1, 'alignement du libellé', 1),
('BOFORMS_NODE_align', 2, 'alignment title', 1),
('BOFORMS_NODE_regexp', 1, 'expression régulière', 1),
('BOFORMS_NODE_regexp', 2, 'regular expression', 1),
('BOFORMS_LABEL_USR_PHONE_TYPE', 1, 'Type de téléphone', 1),
('BOFORMS_LABEL_USR_PHONE_TYPE', 2, 'Phone type', 1),
('BOFORMS_LABEL_USR_PHONE_HOME', 1, 'Téléphone fixe', 1),
('BOFORMS_LABEL_USR_PHONE_HOME', 2, 'Fixed number', 1),
('BOFORMS_LABEL_USR_PHONE_MOBILE', 1, 'Téléphone mobile', 1),
('BOFORMS_LABEL_USR_PHONE_MOBILE', 2, 'Mobile phone', 1),
('BOFORMS_LABEL_USR_PHONE_MOBILE_HOME', 1, 'Téléphone mobile', 1),
('BOFORMS_LABEL_USR_PHONE_MOBILE_HOME', 2, 'Mobile phone', 1),
('BOFORMS_TRACE_EDIT_COMPONENT', 1, 'Modification du champ', 1),
('BOFORMS_TRACE_EDIT_COMPONENT', 2, 'Edit field', 1),
('BOFORMS_TRACE_ADD_COMPONENT', 1, 'Ajout du champ', 1),
('BOFORMS_TRACE_ADD_COMPONENT', 2, 'Add field', 1),
('BOFORMS_TRACE_REMOVE_COMPONENT', 1, 'Suppression du champ', 1),
('BOFORMS_TRACE_REMOVE_COMPONENT', 2, 'Remove field', 1),
('BOFORMS_TRACE_CHANGE_STEP_COMPONENT', 1, 'Changement d''étape du champ', 1),
('BOFORMS_TRACE_CHANGE_STEP_COMPONENT', 2, 'Change step field', 1),
('BOFORMS_TRACE_MOVE_COMPONENT', 1, 'Déplacement', 1),
('BOFORMS_TRACE_MOVE_COMPONENT', 2, 'Move', 1),
('BOFORMS_TRACE_EDIT_STEP_COMPONENT', 1, 'Modification du titre de l''étape', 1),
('BOFORMS_TRACE_EDIT_STEP_COMPONENT', 2, 'Edit step title', 1),
('BOFORMS_TRACE_MOVE_STEP', 1, 'Autre Déplacement d''étape',1),
('BOFORMS_TRACE_MOVE_STEP',2, 'Other Move step',1),
('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', 2, 'Other', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', 1, 'Autre', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER', 2, 'Other', 1),
('BOFORMS_RELOAD_INSTANCES_CONFIRM', '1', 'Voulez-vous recharger les instances?', 1),
('BOFORMS_RELOAD_INSTANCES_CONFIRM', '2', 'Do you want to reload the instances?', 1),
('BOFORMS_RELOAD_REFERENTIAL_CONFIRM', '1', 'Voulez-vous recharger les données de référence?', 1),
('BOFORMS_RELOAD_REFERENTIAL_CONFIRM', '2', 'Do you want to reload the reference data?', 1),
('BOFORMS_RELOAD_INSTANCES_DONE', '1', 'Mise à jour effectuée', 1),
('BOFORMS_RELOAD_INSTANCES_DONE', '2', 'Instances loaded successfully', 1),
('BOFORMS_RELOAD_REFERENTIAL_DONE', '1', 'Mise à jour effectuée', 1),
('BOFORMS_RELOAD_REFERENTIAL_DONE', '2', 'Reference data loaded successfully', 1),
('BOFORMS_BTN_RELOAD_INSTANCES', '1', 'Recharger les instances', 1),
('BOFORMS_BTN_RELOAD_INSTANCES', '2', 'Reload instances', 1),
('BOFORMS_BTN_RELOAD_REFERENTIAL', '1', 'Recharger les référentiels', 1),
('BOFORMS_BTN_RELOAD_REFERENTIAL', '2', 'Reload referentials', 1),		
('BOFORMS_LABEL_DEFAULT_VALUE_MSG', 1, 'Valeur par défaut', 1),
('BOFORMS_LABEL_DEFAULT_VALUE_MSG', 2, 'Default value', 1),
('BOFORMS_ADD_FIELD', 1, 'Ajouter un champ', 1),
('BOFORMS_ADD_FIELD', 2, 'Add a Field', 1),
('BOFORMS_CANCEL', 1, 'Fermer', 1),
('BOFORMS_CANCEL', 2, 'Close', 1),
('BOFORMS_EDIT', 1, 'Modifier', 1),
('BOFORMS_EDIT', 2, 'Edit', 1),
('BOFORMS_LABEL_CODE', 1, 'Code', 1),
('BOFORMS_LABEL_CODE', 2, 'Code', 1),
('BOFORMS_LABEL_INSTRUCTIONS', 1, 'Texte de la bulle d''aide', 1),
('BOFORMS_LABEL_INSTRUCTIONS', 2, 'Text of the tooltip', 1),
('BOFORMS_LABEL_INSTRUCTIONS_LP', 1, 'Message de réassurance', 1),
('BOFORMS_LABEL_INSTRUCTIONS_LP', 2, 'Message reinsurance', 1),
('BOFORMS_LABEL_REGEXP', 1, 'Contrôle de saisie : Expression régulière', 1),
('BOFORMS_LABEL_REGEXP', 2, 'Input control : Regexp', 1),
('BOFORMS_LABEL_REGEXP_MSG', 1, 'Message d''erreur - expression régulière non respectée', 1),
('BOFORMS_LABEL_REGEXP_MSG', 2, 'Error message : regular expression', 1),
('BOFORMS_LABEL_REQUIRED_MSG', 1, 'Message d''erreur - champ obligatoire', 1),
('BOFORMS_LABEL_REQUIRED_MSG', 2, 'Error message - required field', 1),
('BOFORMS_LABEL_REGEXP_SAMPLE', 1, 'Exemples', 1),
('BOFORMS_LABEL_REGEXP_SAMPLE', 2, 'Samples', 1),
('BOFORMS_LABEL_REQUIRED?', 1, 'Obligatoire ?', 1),
('BOFORMS_LABEL_REQUIRED?', 2, 'Required?', 1),
('BOFORMS_LABEL_SUCCESS', 1, 'Message de confirmation', 1),
('BOFORMS_LABEL_SUCCESS', 2, 'Confirmation message', 1),
('BOFORMS_LABEL_SUCCESS', 3, 'Messaggio di confirmatione', 1),
('BOFORMS_LABEL_SUCCESS', 4, 'Mensaje Confirmación', 1),
('BOFORMS_LABEL_TITLE', 1, 'Titre', 1),
('BOFORMS_LABEL_TITLE', 2, 'Title', 1),
('BOFORMS_TITLE_PHONE', 1, 'Téléphone', 1),
('BOFORMS_TITLE_PHONE', 2, 'Phone', 1),
('BOFORMS_TYPE_DATE', 1, 'Date', 1),
('BOFORMS_TYPE_DATE', 2, 'Date', 1),
('BOFORMS_TITLE_POPIN_COUNTRY', 1, 'Nombre d\'activation/désactivation par mois', 1),
('BOFORMS_TITLE_POPIN_COUNTRY', 2, 'Number activation/deactivation by month', 1),
('BOFORMS_BTN_CLEAR_CACHE', 1, 'Vider le cache des instances', 1),
('BOFORMS_BTN_CLEAR_CACHE', 2, 'Clear instances cache', 1),
('BOFORMS_REPORTINGSTATUSFORMSBYCOUNTRY', 1, 'Etat des formulaires par pays', 1),
('BOFORMS_REPORTINGSTATUSFORMSBYCOUNTRY', 2, 'Form activation status by country', 1),
('BOFORMS_CONFMODULE', 1, 'BOFORMS Configuration', 1),
('BOFORMS_CONFMODULE', 2, 'BOFORMS Configuration', 1),
('BOFORMS_AUCUN_RESULTAT', 1, 'Aucun résultat', 1),
('BOFORMS_AUCUN_RESULTAT', 2, 'No result found', 1)
";



		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
('BOFORMS_CONFMODULE', NULL,1),
('BOFORMS_VALIDATION_ERROR_GROUP_LABEL', NULL,1),
('BOFORMS_FIELD_EXAMPLE', NULL,1), 
('BOFORMS_CLOSE_POPUP', NULL,1),		
('BOFORMS_TEXT_MENTION_LEGALE', NULL,1), 
('BOFORMS_TYPE_FORM_OBJECTIVE', NULL,1),		
('BOFORMS_TYPE_FORMULAIRE', NULL,1),
('BOFORMS_CONTEXT', NULL,1),
('BOFORMS_TARGET',NULL,1),
('BOFORMS_DEVICE',NULL,1),
('BOFORMS_SITE',NULL,1),
('BOFORMS_SITE_S',NULL,1),
('BOFORMS_GROUPE_REFERENT',NULL,1),
('BOFORMS_LABEL',NULL,1),
('BOFORMS_COMMENTAIRE',NULL,1),
('BOFORMS_I18N_EDIT_WEB',NULL,1),
('BOFORMS_I18N_EDIT_MOBILE',NULL,1),
('BOFORMS_LOAD_SELECT',NULL,1),
('BOFORMS_LOAD_DRAFT_VERSION',NULL,1),
('BOFORMS_LOAD_XML_VERSION',NULL,1),
('BOFORMS_LISTESITEMODULE',NULL,1),
('BOFORMS_COMPOSANTMODULE',NULL,1),
('BOFORMS_MODULE',NULL,1),
('BOFORMS_LISTENER_LISTENED',NULL,1),
('BOFORMS_LISTENER_LISTENING',NULL,1),
('BOFORMS_SITES_ASSOCIES',NULL,1),

('BOFORMS_TAB_ORDER',NULL,1),
('BOFORMS_TAB_VERSIONS',NULL,1),
('BOFORMS_FORM_REFERENCE',NULL,1),
('BOFORMS_NO_VERSIONS',NULL,1),
('BOFORMS_PREVIEW',NULL,1),
('BOFORMS_OVERWRITE_DRAFT',NULL,1),
('BOFORMS_PREVIEW_CONTEXT',NULL,1),
('BOFORMS_VERSION_N-1',NULL,1),
('BOFORMS_VAR_ABTESTING',NULL,1),
('BOFORMS_NEW_VERSION',NULL,1),
('BOFORMS_DRAFT_VERSION',NULL,1),
('BOFORMS_PUBLISHED_VERSION',NULL,1),
('BOFORMS_ORDER',NULL,1),
('BOFORMS_STEP_PARAM',NULL,1),
('BOFORMS_MODIFY_FIELD',NULL,1),
('BOFORMS_ADD_FIELD',NULL,1),
('BOFORMS_FIELD_PARAM',NULL,1),
('BOFORMS_SELECTED_ITEMS',NULL,1),
('BOFORMS_BUTTON_PUBLISH',NULL,1),
('BOFORMS_BUTTON_SAVE',NULL,1),
('BOFORMS_BUTTON_RESET',NULL,1),
('BOFORMS_CONFIRM_CLOSE',NULL,1),
('BOFORMS_SUPPORT_CONTACT',NULL,1),
('BOFORMS_TITLE_SUPPORT_CONTACT',NULL,1),
('BOFORMS_AVAILABLE_ITEMS',NULL,1),
('BOFORMS_LABEL_CODE',NULL,1),
('BOFORMS_LABEL_ALIGNEMENT',NULL,1),
('BOFORMS_LABEL_ALIGNEMENT_LEFT',NULL,1),
('BOFORMS_LABEL_ALIGNEMENT_TOP',NULL,1),
('BOFORMS_LABEL_INSTRUCTIONS',NULL,1),
('BOFORMS_LABEL_INSTRUCTIONS_LP',NULL,1),
('BOFORMS_LABEL_CHANGE_STEP',NULL,1),
('BOFORMS_DATEPICKER_START_DATE',NULL,1),
('BOFORMS_DATEPICKER_END_DATE',NULL,1),
('BOFORMS_DATEPICKER_OPENING_START_DATE',NULL,1),
('BOFORMS_DATEPICKER_OPENING_END_DATE',NULL,1),
('BOFORMS_DATEPICKER_LIBELET_ENUM',NULL,1),
('BOFORMS_DATEPICKER_DAYS',NULL,1),
('BOFORMS_DATEPICKER_MONTH',NULL,1),
('BOFORMS_DATEPICKER_YEARS',NULL,1),
('BOFORMS_DATEPICKER_FORBIDDEN_DAYS',NULL,1),
('BOFORMS_DATEPICKER_WEEKDAY',NULL,1),
('BOFORMS_DATEPICKER_PERIOD',NULL,1),
('BOFORMS_DATEPICKER_RECURSIV_DAY',NULL,1),
('BOFORMS_DATEPICKER_HOUR_LABEL',NULL,1),
('BOFORMS_SELECT_FIELD',NULL,1),
('BOFORMS_CONFIRM_DRAFT_SAVED',NULL,1),
('BOFORMS_CONFIRM_PUBLISH_SAVED',NULL,1),
('BOFORMS_CONFIRM_DELETE_ABTESTING',NULL,1),
('BOFORMS_CONFIRM_OVERWRITE_DRAFT',NULL,1),
('BOFORMS_CONFIRM_RESET',NULL,1),
('BOFORMS_CONFIRM_RESET_DONE',NULL,1),
('BOFORMS_COMMENTARY_DEFAULT',NULL,1),
('BOFORMS_INFO',NULL,1),
('BOFORMS_INFO_ACTIVATED',NULL,1),
('BOFORMS_INFO_DISABLED',NULL,1),
('BOFORMS_BUTTON_PERSO_LP',NULL,1),
('BOFORMS_SAVE_FAILED',NULL,1),
('BOFORMS_SAVE_FAILED_ABTESTING',NULL,1),
('BOFORMS_ABTESTING_DIG',NULL,1),
('BOFORMS_ABTESTING_POPIN_TITLE',NULL,1),
('BOFORMS_EMPTY_FIELD',NULL,1),
('BOFORMS_ABTESTING_DIG_SEND',NULL,1),	
('BOFORMS_ABTESTING_TITLE_EXIST',NULL,1),	
('BOFORMS_ETATFORMSPARPAYS',NULL,1),
('BOFORMS_REPORTINGEXPORT',NULL,1),
('BOFORMS_REPORTING_SELECTED_SITES',NULL,1),
('BOFORMS_FORM_SITE',NULL,1),
('BOFORMS_SUPPORTREQUEST',NULL,1),
('BOFORMS_ERROR_FORM_UNAVAILABLE',NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', NULL, 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', NULL, 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', NULL, 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', NULL,1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', NULL,1)
		;";     

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
('BOFORMS_ERROR_FORM_UNAVAILABLE', 1, 'Formulaires indisponibles, merci de bien vouloir réessayer ultérieurement.', 1),
('BOFORMS_ERROR_FORM_UNAVAILABLE', 2, 'Forms unavailable, please retry later.', 1),
('BOFORMS_VALIDATION_ERROR_GROUP_LABEL', 1, 'Veuillez indiquer un autre label', 1),
('BOFORMS_VALIDATION_ERROR_GROUP_LABEL', 2, 'Label already exists', 1),
('BOFORMS_FIELD_EXAMPLE', 1, 'Exemple: ', 1),
('BOFORMS_FIELD_EXAMPLE', 2, 'Example: ', 1),
('BOFORMS_CLOSE_POPUP', 1, 'Fermer', 1),
('BOFORMS_CLOSE_POPUP', 2, 'Close', 1),
('BOFORMS_TEXT_MENTION_LEGALE', 1, 'Texte des mentions légales', 1),
('BOFORMS_TEXT_MENTION_LEGALE', 2, 'Imprint text', 1),
('BOFORMS_TYPE_FORMULAIRE', 1, 'Type de formulaire', 1),
('BOFORMS_TYPE_FORMULAIRE', 2, 'Form type', 1),
('BOFORMS_TYPE_FORM_OBJECTIVE', 1, 'Objectif du formulaire', 1),
('BOFORMS_TYPE_FORM_OBJECTIVE', 2, 'Form objective', 1),		
('BOFORMS_CONTEXT', 1, 'Contexte', 1),
('BOFORMS_CONTEXT', 2, 'Context', 1),
('BOFORMS_TARGET', 1, 'Part/Pro', 1),
('BOFORMS_TARGET', 2, 'Indi/Pro', 1),
('BOFORMS_DEVICE', 1, 'Plateforme', 1),
('BOFORMS_DEVICE', 2, 'Device', 1),
('BOFORMS_SITE', 1, 'Site', 1),
('BOFORMS_SITE', 2, 'Site', 1),
('BOFORMS_SITE_S', 1, 'Site(s)', 1),
('BOFORMS_SITE_S', 2, 'Site(s)', 1),
('BOFORMS_GROUPE_REFERENT', 1, 'Site référent', 1),
('BOFORMS_GROUPE_REFERENT', 2, 'Referring site', 1),
('BOFORMS_LABEL', 1, 'Label', 1),
('BOFORMS_LABEL', 2, 'Label', 1),
('BOFORMS_COMMENTAIRE', 1, 'Commentaire', 1),
('BOFORMS_COMMENTAIRE', 2, 'Comment', 1),
('BOFORMS_I18N_EDIT_WEB', 1, 'Modifier les traductions WEB', 1),
('BOFORMS_I18N_EDIT_WEB', 2, 'Edit WEB translations', 1),		
('BOFORMS_I18N_EDIT_MOBILE', 1, 'Modifier les traductions mobile', 1),
('BOFORMS_I18N_EDIT_MOBILE', 2, 'Edit mobile translations', 1),
('BOFORMS_LOAD_SELECT', 1, 'Une version brouillon a été sauvegardé.', 1),
('BOFORMS_LOAD_SELECT', 2, 'A draft version was saved.', 1),		
('BOFORMS_LOAD_DRAFT_VERSION', 1, 'Charger le brouillon', 1),
('BOFORMS_LOAD_DRAFT_VERSION', 2, 'Load draft', 1),		
('BOFORMS_LOAD_XML_VERSION', 1, 'Charger les données publiées', 1),
('BOFORMS_LOAD_XML_VERSION', 2, 'Load the published data', 1),	
('BOFORMS_LISTESITEMODULE', 1, 'Gestion des groupes de site', 1),
('BOFORMS_LISTESITEMODULE', 2, 'Site group management', 1),	
('BOFORMS_COMPOSANTMODULE', 1, 'Traduction des composants avancées', 1),
('BOFORMS_COMPOSANTMODULE', 2, 'Advanced component translation', 1),
('BOFORMS_MODULE', 1, 'Gestion des formulaires', 1),
('BOFORMS_MODULE', 2, 'Form management', 1),
('BOFORMS_SITES_ASSOCIES', 1, 'Sites associés', 1),
('BOFORMS_LISTENER_LISTENING', 1, 'Cet élément écoute un autre composant, veillez à le positionner correctement', 1) ,
('BOFORMS_LISTENER_LISTENED', 1, 'Cet élément est écouté par un composant, veillez à le positionner correctement', 1),
('BOFORMS_LISTENER_LISTENING', 2, 'Listening', 1) ,
('BOFORMS_LISTENER_LISTENED', 2, 'Listener', 1),
('BOFORMS_SITES_ASSOCIES', 2, 'Related Sites', 1)	,
('BOFORMS_TAB_ORDER', 1, 'Sélectionnez une étape', 1)	,
('BOFORMS_TAB_ORDER', 2, 'Choose a step', 1)	,
('BOFORMS_TAB_VERSIONS', 1, 'Versions', 1)	,
('BOFORMS_TAB_VERSIONS', 2, 'Versions', 1)	,
('BOFORMS_FORM_REFERENCE', 1, 'Formulaire de référence', 1),	
('BOFORMS_FORM_REFERENCE', 2, 'Reference Form', 1)	,
('BOFORMS_NO_VERSIONS',1, 'Aucune version enregistrée', 1)	,
('BOFORMS_NO_VERSIONS', 2, 'No registered version', 1)	,
('BOFORMS_PREVIEW', 1, 'Prévisualiser', 1)	,
('BOFORMS_PREVIEW', 2, 'Preview', 1)	,
('BOFORMS_OVERWRITE_DRAFT', 2, 'Overwrite draft', 1),	
('BOFORMS_OVERWRITE_DRAFT', 1, 'Ecraser le brouillon', 1),	
('BOFORMS_PREVIEW_CONTEXT', 1, 'Prévisualiser les contextualisés PDV et Véhicules', 1),	
('BOFORMS_PREVIEW_CONTEXT', 2, 'Preview contextualized POS and Vehicles', 1),	
('BOFORMS_VERSION_N-1', 1, 'Version N-1', 1),	
('BOFORMS_VERSION_N-1', 2, 'N-1 Version', 1),	
('BOFORMS_VAR_ABTESTING', 1, 'Générateur de variantes pour A/B Testing', 1),	
('BOFORMS_VAR_ABTESTING', 2, 'Variants generator for A/B Testing', 1),	
('BOFORMS_NEW_VERSION', 1, 'Nouvelle version', 1),	
('BOFORMS_NEW_VERSION', 2, 'New version', 1),	
('BOFORMS_DRAFT_VERSION', 1, 'Version brouillon', 1),	
('BOFORMS_DRAFT_VERSION', 2, 'Draft version', 1),	
('BOFORMS_PUBLISHED_VERSION', 1, 'Version publiée', 1),	
('BOFORMS_PUBLISHED_VERSION', 2, 'Published version', 1),	
('BOFORMS_ORDER', 1, 'Retour', 1),	
('BOFORMS_ORDER', 2, 'Back', 1),	
('BOFORMS_STEP_PARAM', 1, 'Param. de l''étape', 1),	
('BOFORMS_STEP_PARAM', 2, 'Step param.', 1),	
('BOFORMS_MODIFY_FIELD', 1, 'Modifiez les champs', 1),	
('BOFORMS_MODIFY_FIELD', 2, 'Edit field', 1),	
('BOFORMS_ADD_FIELD', 1, 'Ajouter un champ', 1),	
('BOFORMS_ADD_FIELD', 2, 'Add a field', 1),	
('BOFORMS_FIELD_PARAM', 1, 'Param. du champ', 1),	
('BOFORMS_FIELD_PARAM', 2, 'Field param', 1),	
('BOFORMS_SELECTED_ITEMS', 1, 'Eléments sélectionnés', 1),	
('BOFORMS_SELECTED_ITEMS', 2, 'selected items', 1),	
('BOFORMS_BUTTON_PUBLISH', 1, 'Publier', 1),	
('BOFORMS_BUTTON_PUBLISH', 2, 'Publish', 1),	
('BOFORMS_BUTTON_SAVE', 1, 'Enregistrer', 1),	
('BOFORMS_BUTTON_SAVE', 2, 'Save', 1),	
('BOFORMS_BUTTON_RESET',1, 'Reset', 1),	
('BOFORMS_BUTTON_RESET', 2, 'Reset', 1),	
('BOFORMS_CONFIRM_CLOSE', 1, 'Etes-vous sûr(e) de fermer cette fenêtre ?', 1),	
('BOFORMS_CONFIRM_CLOSE', 2, 'Are you sure to close this window ?', 1),	
('BOFORMS_SUPPORT_CONTACT', 1, 'Contacter Support', 1),	
('BOFORMS_SUPPORT_CONTACT', 2, 'Contact support', 1),	
('BOFORMS_TITLE_SUPPORT_CONTACT', 1, 'Contacter le support', 1),	
('BOFORMS_TITLE_SUPPORT_CONTACT', 2, 'Contact support', 1),	
('BOFORMS_AVAILABLE_ITEMS',1, 'Eléments disponibles', 1),	
('BOFORMS_AVAILABLE_ITEMS', 2, 'Available items', 1),	
('BOFORMS_LABEL_CODE', 1, 'Code', 1),	
('BOFORMS_LABEL_CODE', 2, 'Code', 1),	
('BOFORMS_LABEL_ALIGNEMENT', 1, 'Alignement du titre', 1),	
('BOFORMS_LABEL_ALIGNEMENT', 2, 'Alignment title', 1),	
('BOFORMS_LABEL_ALIGNEMENT_LEFT', 1, 'Gauche', 1),	
('BOFORMS_LABEL_ALIGNEMENT_LEFT', 2, 'Left', 1),	
('BOFORMS_LABEL_ALIGNEMENT_TOP', 1, 'Haut', 1),	
('BOFORMS_LABEL_ALIGNEMENT_TOP', 2, 'Top', 1),		
('BOFORMS_LABEL_CHANGE_STEP', 1, 'Choix de l''étape', 1),	
('BOFORMS_LABEL_CHANGE_STEP', 2, 'Selection step', 1),	
('BOFORMS_DATEPICKER_START_DATE', 1, 'Date de début', 1),	
('BOFORMS_DATEPICKER_START_DATE', 2, 'Date start', 1),	
('BOFORMS_DATEPICKER_END_DATE', 1, 'Date de fin', 1),	
('BOFORMS_DATEPICKER_END_DATE', 2, 'Date end', 1),	
('BOFORMS_DATEPICKER_OPENING_START_DATE', 1, 'Heure d''ouverture', 1),	
('BOFORMS_DATEPICKER_OPENING_START_DATE', 2, 'Opening Start', 1),	
('BOFORMS_DATEPICKER_OPENING_END_DATE', 1, 'Heure de fermeture', 1),	
('BOFORMS_DATEPICKER_OPENING_END_DATE', 2, 'Opening end', 1),	
('BOFORMS_DATEPICKER_LIBELET_ENUM', 1, 'Libellés', 1),	
('BOFORMS_DATEPICKER_LIBELET_ENUM', 2, 'Labels', 1),	
('BOFORMS_DATEPICKER_DAYS', 1, 'Jours', 1),	
('BOFORMS_DATEPICKER_DAYS', 2, 'Days', 1),	
('BOFORMS_DATEPICKER_MONTH', 2, 'Months', 1),	
('BOFORMS_DATEPICKER_MONTH', 1, 'Mois', 1),	
('BOFORMS_DATEPICKER_FORBIDDEN_DAYS', 1, 'Jours fériés', 1),	
('BOFORMS_DATEPICKER_FORBIDDEN_DAYS', 2, 'Forbidden days', 1),	
('BOFORMS_DATEPICKER_YEARS', 1, 'Années', 1),	
('BOFORMS_DATEPICKER_YEARS', 2, 'Years', 1),	
('BOFORMS_DATEPICKER_WEEKDAY',1, 'Jour(s) non ouvré(s)', 1),	
('BOFORMS_DATEPICKER_WEEKDAY', 2, 'Weekday', 1),	
('BOFORMS_DATEPICKER_PERIOD', 1, 'Plage de jours non-ouvrés', 1),	
('BOFORMS_DATEPICKER_PERIOD', 2, 'Period', 1),	
('BOFORMS_DATEPICKER_RECURSIV_DAY', 1, 'Jour récursive', 1),	
('BOFORMS_DATEPICKER_RECURSIV_DAY', 2, 'Recursive Day', 1),	
('BOFORMS_DATEPICKER_HOUR_LABEL', 1, 'Libellé Heure', 1),	
('BOFORMS_DATEPICKER_HOUR_LABEL', 2, 'Hour Label', 1),	
('BOFORMS_SELECT_FIELD', 1, 'Sélectionner un champ', 1),	
('BOFORMS_SELECT_FIELD', 2, 'Select Field', 1)	,
('BOFORMS_CONFIRM_DRAFT_SAVED', 1, 'Votre brouillon a bien été sauvegardé', 1)	,
('BOFORMS_CONFIRM_DRAFT_SAVED', 2, 'Your draft has been saved', 1)	,
('BOFORMS_CONFIRM_PUBLISH_SAVED', 1, 'Publication terminée', 1)	,
('BOFORMS_CONFIRM_PUBLISH_SAVED', 2, 'Publication finished', 1)	,
('BOFORMS_CONFIRM_DELETE_ABTESTING', 1, 'êtes-vous sûr de vouloir supprimer l''ABtesting : ', 1)	,
('BOFORMS_CONFIRM_DELETE_ABTESTING', 2, 'Are you sure you want to delete ABtesting : ', 1)	,
('BOFORMS_CONFIRM_OVERWRITE_DRAFT', 1, 'êtes-vous sûr de vouloir écraser la version brouillon ?', 1)	,
('BOFORMS_CONFIRM_OVERWRITE_DRAFT', 2, 'Are you sure you want to overwrite the draft version ?', 1),
('BOFORMS_CONFIRM_RESET', 1, 'êtes-vous sûr de vouloir revenir à la version d''origine ?', 1),
('BOFORMS_CONFIRM_RESET', 2, 'Are you sure you want to go back to the original version?', 1),
('BOFORMS_CONFIRM_RESET_DONE', 1, 'Retour à la version générique effectué', 1),
('BOFORMS_CONFIRM_RESET_DONE', 2, 'Back to the original version made', 1),
('BOFORMS_COMMENTARY_DEFAULT', 1, 'Ce formulaire est actuellement en cours de configuration', 1),
('BOFORMS_COMMENTARY_DEFAULT', 2, 'This form is temporarily unavailable', 1),
('BOFORMS_INFO', 1, 'Info', 1),
('BOFORMS_INFO', 2, 'Info', 1),
('BOFORMS_INFO_ACTIVATED', 1, 'Activé', 1),
('BOFORMS_INFO_ACTIVATED', 2, 'Activated', 1),
('BOFORMS_INFO_DISABLED', 1, 'Désactivé', 1),
('BOFORMS_INFO_DISABLED', 2, 'Disabled', 1),
('BOFORMS_BUTTON_PERSO_LP', 1, 'Personnaliser la LP', 1),
('BOFORMS_BUTTON_PERSO_LP', 2, 'Customize LP', 1),
('BOFORMS_SAVE_FAILED', 1, 'échec de l''enregistrement', 1),
('BOFORMS_SAVE_FAILED', 2, 'save failed', 1),
('BOFORMS_ABTESTING_DIG', 1, 'Soumettre à DIG', 1),
('BOFORMS_ABTESTING_DIG', 2, 'Submit to DIG', 1),
('BOFORMS_ABTESTING_POPIN_TITLE', 1, 'Information A/B Testing', 1),
('BOFORMS_ABTESTING_POPIN_TITLE', 2, 'Information A/B Testing', 1),
('BOFORMS_EMPTY_FIELD', 1, 'Ce champs doit être rempli', 1),
('BOFORMS_EMPTY_FIELD', 2, 'This field must be completed', 1),
('BOFORMS_ABTESTING_TITLE_EXIST', 1, 'Ce titre est déjà utilisé', 1),
('BOFORMS_ABTESTING_TITLE_EXIST', 2, 'This title is already in use', 1),
('BOFORMS_ABTESTING_DIG_SEND', 1, 'Demande envoyée', 1),
('BOFORMS_ABTESTING_DIG_SEND', 2, 'Request sent', 1),
('BOFORMS_ETATFORMSPARPAYS', 1, 'Etat des formulaires par pays', 1),
('BOFORMS_ETATFORMSPARPAYS', 2, 'State forms by country', 1),
('BOFORMS_REPORTINGEXPORT', 1, 'Export des données',1),
('BOFORMS_REPORTINGEXPORT', 2, 'Data export',1),
('BOFORMS_REPORTINGEXPORT_SITES', 1, 'Site(s)',1),
('BOFORMS_REPORTINGEXPORT_SITES', 2, 'Site(s)',1),
('BOFORMS_REPORTINGEXPORT_LANGUES', 1, 'Langue(s)',1),
('BOFORMS_REPORTINGEXPORT_LANGUES', 2, 'Language(s)',1),
('BOFORMS_REPORTINGEXPORT_TYPES_FORM', 1, 'Type(s) de formulaires',1),
('BOFORMS_REPORTINGEXPORT_TYPES_FORM', 2, 'Form type(s)',1),
('BOFORMS_REPORTINGEXPORT_CONTEXTS_FORM', 1, 'Contexte(s) des formulaires',1),
('BOFORMS_REPORTINGEXPORT_CONTEXTS_FORM', 2, 'Form context(s)',1),
('BOFORMS_REPORTINGEXPORT_TYPE_CLIENT', 1, 'Type(s) de client',1),
('BOFORMS_REPORTINGEXPORT_TYPE_CLIENT', 2, 'Customer Type(s)',1),
('BOFORMS_REPORTINGEXPORT_DATE_DEBUT_EXTRACT', 1, 'Date de début d''extraction',1),
('BOFORMS_REPORTINGEXPORT_DATE_DEBUT_EXTRACT', 2, 'Extraction start date',1),
('BOFORMS_REPORTINGEXPORT_DATE_FIN_EXTRACT', 1, 'Date de fin d''extraction',1),
('BOFORMS_REPORTINGEXPORT_DATE_FIN_EXTRACT', 2, 'Extraction end date',1),
('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT', 1, 'Sélectionner tout',1),
('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT', 2, 'Select all',1),
('BOFORMS_REPORTING_SELECTED_SITES',1,'Site(s) Sélectionné(s)',1),
('BOFORMS_REPORTING_SELECTED_SITES',2,'Selected Site(s)',1),
('BOFORMS_FORM_SITE',1,'Site',1),
('BOFORMS_FORM_SITE',2,'Site',1),
('BOFORMS_FORM_SITE',1,'Site',1),
('BOFORMS_FORM_SITE',2,'Site',1),
('BOFORMS_SUPPORTREQUEST',1,'Demande de support',1),
('BOFORMS_SUPPORTREQUEST',2,'Support Request',1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 1, 'Demande de RDV', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 2, 'Request an appointment', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 4, 'Pedir cita', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 1, 'Demande de RDV département services', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 2, 'Request service department appointment', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 4, 'Solicitud departamento de servicio al nombramiento', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 1, 'Demande de rachat', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 2, 'Request a buyback', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 4, 'Solicite una recompra de acciones', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 1, 'Demande de pièce de rechange', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 2, 'Request spare part or accesory', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 4, 'Solicite una parte o accesorio de repuesto', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 1, 'RLC', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 2, 'RLC', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 4, 'RLC', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 1, 'Préempter un véhicule', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 2, 'Preempt a vehicle', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 4, 'Adelantarse a un vehículo', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 1, 'Keep in touch', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 2, 'Keep in touch', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 4, 'Keep in touch', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 1, 'EDEALER', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 2, 'EDEALER', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 4, 'EDEALER', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 1, 'WEBSTORE', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 2, 'WEBSTORE', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 4, 'WEBSTORE', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 1, 'Formulaire technique', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 2, 'Technical form', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 4, 'Ficha técnica', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 1, 'Formulaire de test', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 2, 'Test form', 1),
('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 4, 'Formulario de prueba', 1)
		;";


		// support request (lot2)
		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
('BOFORMS_LABEL_MODIFY_OPTIN_TEXT', NULL,1),		
('BOFORMS_FIELD_BLOCK_INSTRUCTION', NULL,1),
('BOFORMS_CONFIRM_RESTORE_PREVIOUS_VERSION', NULL,1), 
('BOFORMS_DELETE', NULL,1),
('BOFORMS_RESTORE_PREVIOUS_VERSION', NULL,1),
('BOFORMS_ADD_BLOCK', NULL,1),
('BOFORMS_DELETE_BLOCK', NULL,1),		
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE', NULL,1),
('BOFORMS_LABEL_BTN_NAME', NULL,1),				
('BOFORMS_PREFIX_TESTDRIVE', NULL,1),
('BOFORMS_PREFIX_OFFER', NULL,1),
('BOFORMS_BTN_SELECT', NULL,1),		
('BOFORMS_WARNING_EMPTY_SESSION', NULL,1),
('BOFORMS_CONFMODULE', NULL,1),
('BOFORMS_JIRA_USERNAME', NULL,1),
('BOFORMS_JIRA_PASSWORD', NULL,1),
('BOFORMS_TYPE_CONF', NULL,1),
('BOFORMS_REFERENTIAL_CONF_TYPE_PROXY', NULL,1),
('BOFORMS_REFERENTIAL_CONF_TYPE_JIRA', NULL,1),		
('BOFORMS_LABEL_MULTIFORMS_CHOICE', NULL,1),
('BOFORMS_LABEL_SBS USER OFFER', NULL,1),
('BOFORMS_LABEL_SBS_USR_OFFER_2', NULL,1),
('BOFORMS_LABEL_REQUEST_CALLBACK', NULL,1),
('BOFORMS_LABEL_GET_MYNDP', NULL,1),
('BOFORMS_BEFORE', NULL,1),
('BOFORMS_AFTER', NULL,1),
('BOFORMS_SEARCH_POS_CHOOSE', NULL,1),
('BOFORMS_SEARCH_POS', NULL,1),
('BOFORMS_LEGEND_OP', NULL,1),
('BOFORMS_LEGEND_OC', NULL,1),
('BOFORMS_LEGEND_F', NULL,1),
('BOFORMS_LEGEND_X', NULL,1),
('BOFORMS_LEGEND', NULL,1),		
('BOFORMS_REPORTING_NODATAS', NULL,1),		
('BOFORMS_POPIN_REPORTINGACTIVITY_MODIFICATION', NULL,1),		
('BOFORMS_TITLE_POPIN_REPORTINGACTIVITY', NULL,1), 
('BOFORMS_REPORTINGACTIVITY', NULL,1),
('BOFORMS_REPORTINGSTATUSFIELDSBYFORMS', NULL,1),
('BOFORMS_SHORT_MONTHS_JANUARY', NULL,1),
('BOFORMS_SHORT_MONTHS_FEBRUARY', NULL,1),
('BOFORMS_SHORT_MONTHS_MARCH', NULL,1),
('BOFORMS_SHORT_MONTHS_APRIL', NULL,1),
('BOFORMS_SHORT_MONTHS_MAY', NULL,1),
('BOFORMS_SHORT_MONTHS_JUNE', NULL,1),
('BOFORMS_SHORT_MONTHS_JULY', NULL,1),
('BOFORMS_SHORT_MONTHS_AUGUST', NULL,1),
('BOFORMS_SHORT_MONTHS_SEPTEMBER', NULL,1),
('BOFORMS_SHORT_MONTHS_OCTOBER', NULL,1),
('BOFORMS_SHORT_MONTHS_NOVEMBER',NULL,1),
('BOFORMS_SHORT_MONTHS_DECEMBER', NULL,1),
('BOFORMS_MONTHS_JANUARY', NULL,1),
('BOFORMS_MONTHS_FEBRUARY', NULL,1),
('BOFORMS_MONTHS_MARCH', NULL,1),
('BOFORMS_MONTHS_APRIL', NULL,1),
('BOFORMS_MONTHS_MAY', NULL,1),
('BOFORMS_MONTHS_JUNE', NULL,1),
('BOFORMS_MONTHS_JULY', NULL,1),
('BOFORMS_MONTHS_AUGUST', NULL,1),
('BOFORMS_MONTHS_SEPTEMBER', NULL,1),
('BOFORMS_MONTHS_OCTOBER', NULL,1),
('BOFORMS_MONTHS_NOVEMBER',NULL,1),
('BOFORMS_MONTHS_DECEMBER', NULL,1),
('BOFORMS_REPORTINGSYNTHESE',NULL,1),
('BOFORMS_REPORTING_SYNTHESE_CHOOSE_MONTH', NULL,1), 
('BOFORMS_LABEL_TYPE_RADIO', NULL,1),
('BOFORMS_LABEL_TYPE_DROPDOWN', NULL,1),
('BOFORMS_ANOMALY_TITLE_HELP', NULL,1),
('BOFORMS_LABEL_SBS_COM_OFFER', NULL,1),
('BOFORMS_LABEL_SBS_COM_OFFER_2', NULL,1),
('BOFORMS_LABEL_SBS_USR_OFFER', NULL,1),
('BOFORMS_LABEL_REQUEST_INTEREST_FINANCING', NULL,1),
('BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE', NULL,1),
('BOFORMS_LABEL_REQUEST_INTEREST_SERVICE', NULL,1),
('BOFORMS_LABEL_UNS_NWS_CPP_MOTIF', NULL,1),
('BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER', NULL,1),
('BOFORMS_SUPPORT_BTN_SEND_CENTRAL_VALIDATION', NULL,1),
('BOFORMS_LABEL_CLEAR_DEFAULT_VALUE', NULL,1),		
('BOFORMS_TRANSLATE_LIST_SITES', NULL,1),		
('BOFORMS_TRANSLATE_LIST_MANAGE_FORMS', NULL,1),
('BOFORMS_TRANSLATE_MANAGE_FORMS', NULL,1),
('BOFORMS_TRANSLATE_LIST_SITE_GROUP', NULL,1),
('BOFORMS_TRANSLATE_SITE_GROUP', NULL,1),
('BOFORMS_TRANSLATE_LIST_ADVANCED_COMPONENTS', NULL,1),		
('BOFORMS_TRANSLATE_ADVANCED_COMPONENTS', NULL,1),
('BOFORMS_CHANGE_FIELD_TYPE', NULL,1),
('BOFORMS_SUPPORT_CHOOSE_MODIFICATION_TYPE', NULL,1), 
('BOFORMS_JIRA_URL', NULL,1),
('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER', NULL,1),
('BOFORMS_VALIDATION_HELP_TITLE_NEW_FORM', NULL,1),
('BOFORMS_VALIDATION_HELP_TITLE_ANOMALY', NULL,1),
('BOFORMS_VALIDATION_HELP_TITLE_EVOLUTION', NULL,1),
('BOFORMS_SUPPORT_ANOMALY_ALL_FIELDS', NULL,1),
('BOFORMS_VALIDATION_HELP_DESCRIBE_ANOMALY', NULL,1),
('BOFORMS_VALIDATION_PICK_COMPONENT_AND_EXPLAIN', NULL,1),
('BOFORMS_SUPPORT_JUSTIFY_UPDATE_COMPONENT', NULL,1),
('BOFORMS_VALIDATION_PLEASE_FILL_THIS_FORM', NULL,1),
('BOFORMS_VALIDATION_PLEASE_FILL_ABOVE_FIELDS', NULL,1),
('BOFORMS_VALIDATION_ADD_A_NOTIFICATION', NULL,1),
('BOFORMS_VALIDATION_PICK_VALUE_AND_JUSTIFY', NULL,1),
('BOFORMS_VALIDATION_FILL_THIS_FIELD', NULL,1),
('BOFORMS_VALIDATION_REQUIRED_FIELD', NULL,1),
('BOFORMS_VALIDATION_SELECT_FIELDS', NULL,1),
('BOFORMS_REQUEST_FIELD',NULL,1),
('BOFORMS_REQUEST_CENTRAL_NOTIFICATION_CREATED', NULL,1),
('BOFORMS_REQUEST_JIRA_NEW_FORM_CREATED', NULL,1),
('BOFORMS_REQUEST_JIRA_ANOMALY_CREATED', NULL,1),
('BOFORMS_REQUEST_JIRA_EVOLUTION_CREATED', NULL,1), 
('BOFORMS_REQUEST_ANOMALY_NOT_LINK_FIELD', NULL,1),
('BOFORMS_REQUEST_FIELDNAME', NULL,1),
('BOFORMS_REQUEST_FILLEDTEXT', NULL,1),
('BOFORMS_REQUEST_OPPORTUNITY', NULL,1),
('BOFORMS_REQUEST_QUESTION', NULL,1),
('BOFORMS_REQUEST_DESCRIPTION', NULL,1),
('BOFORMS_SUPPORT_ENVIRONMENT', NULL,1),
('BOFORMS_SUPPORT_PRIORITY', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_FORM_NOT_FOUND', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_AVAILABLE_FIELDS', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_CHOOSE_FORM_TYPE', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE', NULL,1),
('BOFORMS_POPUP_CREATE_NEW_FORM_TITLE', NULL,1), 
('BOFORMS_POPUP_CREATE_NEW_FORM_LINK_TEXT', NULL,1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_TOOLTIP', NULL,1),
('BOFORMS_SUPPORT_JUSTIFY_ADD_TOOLTIP', NULL,1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_FIELD', NULL,1),	
('BOFORMS_SUPPORT_CHOOSE_REQUEST_TYPE', NULL,1),
('BOFORMS_SUPPORT_CHOOSE_PRIORITY', NULL,1),
('BOFORMS_SUPPORT_CHOOSE_NOTIFICATION_TYPE', NULL,1),
('BOFORMS_SUPPORT_ADD_FILE', NULL,1),
('BOFORMS_SUPPORT_COUNTRY', NULL,1),
('BOFORMS_SUPPORT_WEBMASTER_NAME', NULL,1),
('BOFORMS_SUPPORT_REQUEST_DESCRIPTION', NULL,1),
('BOFORMS_SUPPORT_XML_SAVED_VERSION', NULL,1),
('BOFORMS_SUPPORT_REQUEST_TITLE', NULL,1),
('BOFORMS_SUPPORT_MODIFICATION_TYPE', NULL,1),
('BOFORMS_SUPPORT_JUSTIFY_REQUEST', NULL,1),
('BOFORMS_SUPPORT_EXPLAIN_REQUEST', NULL,1),
('BOFORMS_SUPPORT_DESCRIBE_NEEDS', NULL,1),
('BOFORMS_SUPPORT_DESCRIBE_ANOMALY', NULL,1),
('BOFORMS_SUPPORT_BTN_SEND_SUPPORT_REQUEST', NULL,1),
('BOFORMS_SUPPORT_BTN_ADD_REQUEST', NULL,1),
('BOFORMS_NOTIFICATION_NEW_FIELDS', NULL,1),
('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD', NULL,1),
('BOFORMS_NOTIFICATION_ADD_TOOLTIP', NULL,1),
('BOFORMS_NOTIFICATION_DEL_TOOLTIP', NULL,1),
('BOFORMS_NOTIFICATION_MODIFY_IMPRINT', NULL,1),
('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT', NULL,1),
('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE', NULL,1),
('BOFORMS_NOTIFICATION_MODIFY_OPT_IN', NULL,1),
('BOFORMS_NOTIFICATION_OTHER_REQUEST', NULL,1),
('BOFORMS_REQUEST_TYPE_CENTRAL_VALIDATION', NULL,1),
('BOFORMS_REQUEST_TYPE_FORM_EVOLUTION', NULL,1),
('BOFORMS_REQUEST_TYPE_NOTIFY_ANOMALY', NULL,1),
('BOFORMS_REQUEST_BLOCKING', NULL,1),
('BOFORMS_REQUEST_MAJOR', NULL,1),
('BOFORMS_SUPPORT_REQUIRED_FIELDS', NULL,1),
('BOFORMS_REQUEST_MINOR', NULL,1),
('BOFORMS_ALL_COUNTRY', NULL,1),
('BOFORMS_REPORTINGSTATUSFORMS', NULL,1),
('BORFORMS_REPORTINGEXPORT', NULL,1),
('BOFORMS_REPORTINGEXPORT_SITES', NULL,1),
('BOFORMS_REPORTINGEXPORT_LANGUES', NULL,1),
('BOFORMS_REPORTINGEXPORT_TYPES_FORM', NULL,1),
('BOFORMS_REPORTINGEXPORT_CONTEXTS_FORM', NULL,1),
('BOFORMS_REPORTINGEXPORT_TYPE_CLIENT', NULL,1),
('BOFORMS_REPORTINGEXPORT_DATE_DEBUT_EXTRACT',NULL,1),
('BOFORMS_REPORTINGEXPORT_DATE_FIN_EXTRACT', NULL,1),
('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_FORM', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_DEACTIVATE', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ACTIVATE', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_SELECTION', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLDCHECK', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLCHECK', NULL,1),
('BOFORMS_CHANGESTATUS_TITLE_POPIN', NULL,1),
('BOFORMS_CHANGESTATUS_LABEL_FIELD', NULL,1),
('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE', NULL,1),
('BOFORMS_CHANGESTATUS_CONFIRM_DISABLED', NULL,1),

('BOFORMS_REPORTING_FILL_CULTURE', NULL,1),
('BOFORMS_REPORTING_FILL_SITE', NULL,1),
('BOFORMS_REPORTING_FILL_TYPE', NULL,1),
('BOFORMS_REPORTING_FILL_CONTEXT', NULL,1),
('BOFORMS_REPORTING_FILL_TARGET', NULL,1),
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE2', NULL,1)


		;";     

		
		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
('BOFORMS_LABEL_MODIFY_OPTIN_TEXT', '1', 'Texte de l\'Optin', 1),
('BOFORMS_LABEL_MODIFY_OPTIN_TEXT', '2', 'Optin text', 1),
('BOFORMS_FIELD_BLOCK_INSTRUCTION', '1', 'Instructions',1),
('BOFORMS_FIELD_BLOCK_INSTRUCTION', '2', 'Instructions',1),		
('BOFORMS_CONFIRM_RESTORE_PREVIOUS_VERSION', '1', 'Voulez-vous publier la version N-1 ?', 1),
('BOFORMS_CONFIRM_RESTORE_PREVIOUS_VERSION', '2', 'Do you want to publish the version N-1 ?', 1),
('BOFORMS_DELETE', '1','Supprimer',1),
('BOFORMS_DELETE', '2','Delete',1),
('BOFORMS_RESTORE_PREVIOUS_VERSION', '1', 'Publier',1),
('BOFORMS_RESTORE_PREVIOUS_VERSION', '2', 'Publish',1),
('BOFORMS_ADD_BLOCK', '1', 'Ajouter un bloc', 1),
('BOFORMS_ADD_BLOCK', '2', 'Add a new block', 1),
('BOFORMS_DELETE_BLOCK', '1', 'Supprimer ce bloc', 1),		
('BOFORMS_DELETE_BLOCK', '2', 'Delete this block', 1),		
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE', '1', 'Listener parametre message',1), 
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE', '2', 'Listener message parameter',1),
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE2', '1', 'Texte d''authentification NDPID',1),
('BOFORMS_USR_EMAIL_LISTENER_MESSAGE2', '2', 'NDPID authentication text',1),
('BOFORMS_LABEL_BTN_NAME','1','Nom du bouton',1),		
('BOFORMS_LABEL_BTN_NAME','2','Button name',1),
('BOFORMS_PREFIX_TESTDRIVE', '1', '(Essai) ', 1),
('BOFORMS_PREFIX_TESTDRIVE', '2', '(Test) ', 1),
('BOFORMS_PREFIX_OFFER', '1', '(Offre) ', 1),
('BOFORMS_PREFIX_OFFER', '2', '(Offer) ', 1),
('BOFORMS_BTN_SELECT', '1', 'Sélectionner',1),
('BOFORMS_BTN_SELECT', '2', 'Choose',1),
('BOFORMS_CHANGESTATUS_CONFIRM_DISABLED', '1', 'Les formulaires ont été bloqués', 1),
('BOFORMS_CHANGESTATUS_CONFIRM_DISABLED', '2', 'The forms were blocked', 1),
('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE', '1', 'Les formulaires ont été débloqués', 1),
('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE', '2', 'The forms were made available', 1),
('BOFORMS_CHANGESTATUS_LABEL_FORM', '1', 'les formulaires', 1),
('BOFORMS_CHANGESTATUS_LABEL_FORM', '2', 'forms', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_DEACTIVATE', '1', 'bloquer', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_DEACTIVATE', '2', 'disable', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ACTIVATE', '1', 'débloquer', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ACTIVATE', '2', 'Activate', 1),
('BOFORMS_CHANGESTATUS_LABEL_SELECTION', '1', 'Pour la séléction', 1),
('BOFORMS_CHANGESTATUS_LABEL_SELECTION', '2', 'For the selection', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLDCHECK', '1', 'Tout décocher', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLDCHECK', '2', 'Uncheck All', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLCHECK', '1', 'Tout cocher', 1),
('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLCHECK', '2', 'Check all', 1),
('BOFORMS_CHANGESTATUS_TITLE_POPIN', '1', 'Paramétrage', 1),
('BOFORMS_CHANGESTATUS_TITLE_POPIN', '2', 'setting', 1),
('BOFORMS_CHANGESTATUS_LABEL_FIELD', '1', 'Saisir le message d''information', 1),
('BOFORMS_CHANGESTATUS_LABEL_FIELD', '2', 'Enter the information message', 1),

('BOFORMS_WARNING_EMPTY_SESSION', '1', 'La session a expiré, veuillez-vous reconnecter au back-office', 1),
('BOFORMS_WARNING_EMPTY_SESSION', '2', 'Session has expired, please, log in again to the back office', 1),
('BOFORMS_CONFMODULE', '1', 'BOFORMS configuration', 1),
('BOFORMS_CONFMODULE', '2', 'BOFORMS configuration', 1),
('BOFORMS_JIRA_USERNAME', '1', 'ID utilisateur JIRA (RPI)',1),
('BOFORMS_JIRA_USERNAME', '2', 'JIRA user id (RPI)',1),
('BOFORMS_JIRA_PASSWORD', '1', 'Mot de passe JIRA',1),
('BOFORMS_JIRA_PASSWORD', '2', 'JIRA password',1),
('BOFORMS_TYPE_CONF', '1','Configuration BOFORMS',1),
('BOFORMS_TYPE_CONF', '2','BOFORMS Configuration',1),
('BOFORMS_REFERENTIAL_CONF_TYPE_PROXY', '1', 'Configuration PROXY', 1),
('BOFORMS_REFERENTIAL_CONF_TYPE_PROXY', '2', 'PROXY configuration', 1),
('BOFORMS_REFERENTIAL_CONF_TYPE_JIRA', '1', 'Configuration JIRA', 1),
('BOFORMS_REFERENTIAL_CONF_TYPE_JIRA', '2', 'JIRA configuration', 1),
('BOFORMS_LABEL_MULTIFORMS_CHOICE', '1','Offre commerciale OU Demande d’essai',1),
('BOFORMS_LABEL_MULTIFORMS_CHOICE', '2','Commercial offer OR Test Request',1),
('BOFORMS_LABEL_SBS_USR_OFFER_2', '1','Opt-in commercial',1),
('BOFORMS_LABEL_SBS_USR_OFFER_2', '2','Opt-in commercial',1),
('BOFORMS_LABEL_SBS_USER_OFFER', '1','Opt-in commercial ',1),
('BOFORMS_LABEL_SBS_USER_OFFER', '2','Business Opt-in',1),
('BOFORMS_LABEL_REQUEST_CALLBACK', '1', 'Souhaitez-vous être contacté par un PDV ?', 1),
('BOFORMS_LABEL_REQUEST_CALLBACK', '2', 'Would you like to be contacted by a POS ?', 1),
('BOFORMS_LABEL_GET_MYNDP', '1', 'Opt-in MYNDP', 1),
('BOFORMS_LABEL_GET_MYNDP', '2', 'Opt-in MYNDP', 1),
('BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER', '1', 'Mentions légales', 1),
('BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER', '2', 'Legal mention', 1),
('BOFORMS_BEFORE', '1', 'Avant', 1),
('BOFORMS_BEFORE', '2', 'Before', 1),
('BOFORMS_AFTER', '1', 'Après', 1),
('BOFORMS_AFTER', '2', 'After', 1),		
('BOFORMS_SEARCH_POS', '1', 'Saisir l''adresse du point de vente', 1),
('BOFORMS_SEARCH_POS', '2', 'Fill point of sale address', 1),
('BOFORMS_SEARCH_POS_CHOOSE', '1', 'Cliquer pour sélectionner un point de vente:', 1),
('BOFORMS_SEARCH_POS_CHOOSE', '2', 'Click to choose a point of sale:', 1),
('BOFORMS_LEGEND_OC', '1', 'Obligatoire CRM', 1),
('BOFORMS_LEGEND_OC', '2', 'CRM Required', 1),		
('BOFORMS_LEGEND_OP', '1', 'Obligatoire pays', 1),
('BOFORMS_LEGEND_OP', '2', 'Country required ', 1),		
('BOFORMS_LEGEND_F', '1', 'Facultatif', 1),
('BOFORMS_LEGEND_F', '2', 'Optional', 1),		
('BOFORMS_LEGEND_X', '1', 'Le champ n’est pas activé', 1),
('BOFORMS_LEGEND_X', '2', 'Field not activated', 1),		
('BOFORMS_LEGEND', '1', 'Légende', 1),
('BOFORMS_LEGEND', '2', 'Legend', 1),		
('BOFORMS_REPORTING_NODATAS', '1', 'Pas de données', 1),
('BOFORMS_REPORTING_NODATAS', '2', 'No datas found', 1),
('BOFORMS_POPIN_REPORTINGACTIVITY_MODIFICATION', '1', 'Modification', 1),
('BOFORMS_POPIN_REPORTINGACTIVITY_MODIFICATION', '2', 'Update', 1),
('BOFORMS_TITLE_POPIN_REPORTINGACTIVITY', '1', 'Détail journal d’activité', 1),
('BOFORMS_TITLE_POPIN_REPORTINGACTIVITY', '2', 'Activity log detail', 1),
('BOFORMS_REPORTINGACTIVITY', '1', 'Journal d’activité',1),
('BOFORMS_REPORTINGACTIVITY', '2', 'Activity report',1),		
('BOFORMS_REPORTINGSTATUSFIELDSBYFORMS', '1', 'Etat des champs par formulaire', 1),
('BOFORMS_REPORTINGSTATUSFIELDSBYFORMS', '2', 'Form field state', 1),
('BOFORMS_SHORT_MONTHS_JANUARY', '1', 'Jan', 1),
('BOFORMS_SHORT_MONTHS_JANUARY', '2', 'Jan', 1),
('BOFORMS_SHORT_MONTHS_FEBRUARY', '1', 'Fév', 1),
('BOFORMS_SHORT_MONTHS_FEBRUARY', '2', 'Feb', 1),
('BOFORMS_SHORT_MONTHS_MARCH', '1', 'Mar', 1),
('BOFORMS_SHORT_MONTHS_MARCH', '2', 'Mar', 1),
('BOFORMS_SHORT_MONTHS_APRIL', '1', 'Avr', 1),
('BOFORMS_SHORT_MONTHS_APRIL', '2', 'Apr', 1),
('BOFORMS_SHORT_MONTHS_MAY', '1', 'Mai', 1),
('BOFORMS_SHORT_MONTHS_MAY', '2', 'May', 1),
('BOFORMS_SHORT_MONTHS_JUNE', '1', 'Juin', 1),
('BOFORMS_SHORT_MONTHS_JUNE', '2', 'June', 1),
('BOFORMS_SHORT_MONTHS_JULY', '1', 'Juil', 1),
('BOFORMS_SHORT_MONTHS_JULY', '2', 'July', 1),
('BOFORMS_SHORT_MONTHS_AUGUST', '1', 'Août', 1),
('BOFORMS_SHORT_MONTHS_AUGUST', '2', 'Aug', 1),
('BOFORMS_SHORT_MONTHS_SEPTEMBER', '1', 'Sept', 1),
('BOFORMS_SHORT_MONTHS_SEPTEMBER', '2', 'Sept', 1),
('BOFORMS_SHORT_MONTHS_OCTOBER', '1', 'Oct', 1),
('BOFORMS_SHORT_MONTHS_OCTOBER', '2', 'Oct', 1),
('BOFORMS_SHORT_MONTHS_NOVEMBER', '1', 'Nov', 1),
('BOFORMS_SHORT_MONTHS_NOVEMBER', '2', 'Nov', 1),
('BOFORMS_SHORT_MONTHS_DECEMBER', '1', 'Déc', 1),
('BOFORMS_SHORT_MONTHS_DECEMBER', '2', 'Dec', 1),
('BOFORMS_MONTHS_JANUARY', '1', 'Janvier', 1),
('BOFORMS_MONTHS_JANUARY', '2', 'January', 1),
('BOFORMS_MONTHS_FEBRUARY', '1', 'Février', 1),
('BOFORMS_MONTHS_FEBRUARY', '2', 'February', 1),
('BOFORMS_MONTHS_MARCH', '1', 'Mars', 1),
('BOFORMS_MONTHS_MARCH', '2', 'March', 1),
('BOFORMS_MONTHS_APRIL', '1', 'Avril', 1),
('BOFORMS_MONTHS_APRIL', '2', 'April', 1),
('BOFORMS_MONTHS_MAY', '1', 'Mai', 1),
('BOFORMS_MONTHS_MAY', '2', 'May', 1),
('BOFORMS_MONTHS_JUNE', '1', 'Juin', 1),
('BOFORMS_MONTHS_JUNE', '2', 'June', 1),
('BOFORMS_MONTHS_JULY', '1', 'Juillet', 1),
('BOFORMS_MONTHS_JULY', '2', 'July', 1),
('BOFORMS_MONTHS_AUGUST', '1', 'Aout', 1),
('BOFORMS_MONTHS_AUGUST', '2', 'August', 1),
('BOFORMS_MONTHS_SEPTEMBER', '1', 'Septembre', 1),
('BOFORMS_MONTHS_SEPTEMBER', '2', 'September', 1),
('BOFORMS_MONTHS_OCTOBER', '1', 'Octobre', 1),
('BOFORMS_MONTHS_OCTOBER', '2', 'October', 1),
('BOFORMS_MONTHS_NOVEMBER', '1', 'Novembre', 1),
('BOFORMS_MONTHS_NOVEMBER', '2', 'November', 1),
('BOFORMS_MONTHS_DECEMBER', '1', 'Décembre', 1),
('BOFORMS_MONTHS_DECEMBER', '2', 'December', 1),
('BOFORMS_REPORTINGSYNTHESE', '1', 'Synthèse des leads', 1),
('BOFORMS_REPORTINGSYNTHESE', '2', 'Form leads report', 1),		
('BOFORMS_REPORTING_SYNTHESE_CHOOSE_MONTH', '1' , 'Choisissez un mois', 1),
('BOFORMS_REPORTING_SYNTHESE_CHOOSE_MONTH', '2' , 'Choose a month', 1),
('BOFORMS_LABEL_TYPE_RADIO','1','Bouton radio',1),
('BOFORMS_LABEL_TYPE_RADIO','2','Radio button',1),
('BOFORMS_LABEL_TYPE_DROPDOWN','1','Liste déroulante',1),
('BOFORMS_LABEL_TYPE_DROPDOWN','2','Dropdown List',1),
('BOFORMS_ANOMALY_TITLE_HELP', '1', '(Site concerné / Plateforme (web ou mobile)/ Nom du pays / titre de l''anomalie)', 1),
('BOFORMS_ANOMALY_TITLE_HELP', '2', '(Site / Plateform (web or mobile)/ Country name / Anomaly title)', 1),
('BOFORMS_REPORTINGSTATUSFORMS', '1', 'Etat détaillé d''un formulaire', 1),
('BOFORMS_REPORTINGSTATUSFORMS', '2', 'Form detailed state', 1),
('BOFORMS_ALL_COUNTRY', '1','Tous pays',1),
('BOFORMS_ALL_COUNTRY', '2','All countries',1),
('BOFORMS_LABEL_SBS_COM_OFFER', '1','Opt-in commercial',1),
('BOFORMS_LABEL_SBS_COM_OFFER', '2','Business Opt-in',1),
('BOFORMS_LABEL_SBS_COM_OFFER_2', '1','Opt-in commercial',1),
('BOFORMS_LABEL_SBS_COM_OFFER_2', '2','Business Opt-in',1),
('BOFORMS_LABEL_SBS_USR_OFFER_2_LP', '1','E-brochure',1),
('BOFORMS_LABEL_SBS_USR_OFFER_2_LP', '2','E-brochure',1),
('BOFORMS_LABEL_SBS_USR_OFFER', '1','Opt-in commercial',1),
('BOFORMS_LABEL_SBS_USR_OFFER', '2','Business Opt-in ',1),
('BOFORMS_LABEL_REQUEST_INTEREST_FINANCING',  '1','Une offre de financement',1),
('BOFORMS_LABEL_REQUEST_INTEREST_FINANCING',  '2','A financing offer',1),
('BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE', '1','Une offre d''assurance auto',1),
('BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE', '2','An insurance car offer',1),
('BOFORMS_LABEL_REQUEST_INTEREST_SERVICE', '1','Un contrat de service',1),
('BOFORMS_LABEL_REQUEST_INTEREST_SERVICE', '2','A service contract',1),
('BOFORMS_LABEL_UNS_NWS_CPP_MOTIF', '1','Motif désinscription',1),
('BOFORMS_LABEL_UNS_NWS_CPP_MOTIF', '2','Reason for unsubscription',1),
('BOFORMS_SUPPORT_BTN_SEND_CENTRAL_VALIDATION', '1', 'Envoyer la demande', 1),
('BOFORMS_SUPPORT_BTN_SEND_CENTRAL_VALIDATION', '2', 'Send request', 1),
('BOFORMS_LABEL_CLEAR_DEFAULT_VALUE','1', 'Supprimer les valeurs par défaut',1),
('BOFORMS_LABEL_CLEAR_DEFAULT_VALUE','2', 'Clear default value',1),
('BOFORMS_TRANSLATE_LIST_SITES', '1' , 'Liste des sites', 1),
('BOFORMS_TRANSLATE_LIST_SITES', '2' , 'Site list', 1),
('BOFORMS_TRANSLATE_LIST_MANAGE_FORMS', '1' , 'Liste des formulaires', 1),
('BOFORMS_TRANSLATE_LIST_MANAGE_FORMS', '2' , 'Form list', 1),
('BOFORMS_TRANSLATE_MANAGE_FORMS', '1' , 'Gestion des formulaires', 1),
('BOFORMS_TRANSLATE_MANAGE_FORMS', '2' , 'Manage forms', 1),
('BOFORMS_TRANSLATE_LIST_SITE_GROUP', '1' , 'Liste des groupes de site', 1),
('BOFORMS_TRANSLATE_LIST_SITE_GROUP', '2' , 'Site groups list', 1),
('BOFORMS_TRANSLATE_SITE_GROUP', '1' , 'Plugins > Gestion des groupes de site', 1),
('BOFORMS_TRANSLATE_SITE_GROUP', '2' , 'Plugins > Manage site groups', 1),
('BOFORMS_TRANSLATE_LIST_ADVANCED_COMPONENTS', '1' , 'Liste des composants avancés', 1),
('BOFORMS_TRANSLATE_LIST_ADVANCED_COMPONENTS', '2' , 'Advanced components list', 1),
('BOFORMS_TRANSLATE_ADVANCED_COMPONENTS', '1' ,'Plugins > Traduction des composants avancés', 1),
('BOFORMS_TRANSLATE_ADVANCED_COMPONENTS', '2' ,'Plugins > Advanced components translation', 1),
('BOFORMS_CHANGE_FIELD_TYPE', '1', 'Type', 1),
('BOFORMS_CHANGE_FIELD_TYPE', '2', 'Type', 1),
('BOFORMS_JIRA_URL', '1', 'URL de la jira', 1),
('BOFORMS_JIRA_URL', '2', 'JIRA''s URL', 1),
('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER', '1', 'Modification de l''ordre des étapes', 1),
('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER', '2', 'Modify step order', 1),
('BOFORMS_VALIDATION_HELP_TITLE_NEW_FORM', '1', 'aide pour le champ title new form', 1),
('BOFORMS_VALIDATION_HELP_TITLE_NEW_FORM', '2', 'title''s new form help', 1),
('BOFORMS_VALIDATION_HELP_TITLE_ANOMALY', '1', 'aide pour le champ title anomalie', 1),
('BOFORMS_VALIDATION_HELP_TITLE_ANOMALY', '2', 'title''s anomaly help', 1),
('BOFORMS_VALIDATION_HELP_TITLE_EVOLUTION','1', 'aide pour le champ title evolution',1),
('BOFORMS_VALIDATION_HELP_TITLE_EVOLUTION','2', 'title''s evolution help',1),
('BOFORMS_SUPPORT_ANOMALY_ALL_FIELDS', '1', 'Liste des champs du formulaire', 1),
('BOFORMS_SUPPORT_ANOMALY_ALL_FIELDS', '2', 'Form''s field list', 1),
('BOFORMS_VALIDATION_HELP_DESCRIBE_ANOMALY', '1', 'Merci de bien vouloir renseigner les informations suivantes dans la description de votre anomalie (navigateur(s) utilisé(s), device / Os utilisés pour les formulaires mobiles, décrire les étapes permettant de reproduire l''anomalie rencontrée, le comportement obtenu et attendu)',1),
('BOFORMS_VALIDATION_HELP_DESCRIBE_ANOMALY', '2', 'Merci de bien vouloir renseigner les informations suivantes dans la description de votre anomalie (navigateur(s) utilisé(s), device / Os utilisés pour les formulaires mobiles, décrire les étapes permettant de reproduire l''anomalie rencontrée, le comportement obtenu et attendu)',1),
('BOFORMS_VALIDATION_PICK_COMPONENT_AND_EXPLAIN', '1', 'Selectionner au moins une valeur et précisez la/les demande(s)', 1),
('BOFORMS_VALIDATION_PICK_COMPONENT_AND_EXPLAIN', '2', 'Pick a value and explain the request', 1),
('BOFORMS_SUPPORT_JUSTIFY_UPDATE_COMPONENT', '1', 'Modifier le composant', 1),
('BOFORMS_SUPPORT_JUSTIFY_UPDATE_COMPONENT', '2', 'Update the component', 1),
('BOFORMS_VALIDATION_PLEASE_FILL_THIS_FORM', '1' , 'Veuillez renseigner le formulaire', 1),
('BOFORMS_VALIDATION_PLEASE_FILL_THIS_FORM', '2' , 'Please fill this form', 1),
('BOFORMS_VALIDATION_PLEASE_FILL_ABOVE_FIELDS','1', 'Veuillez renseigner les champs ci-dessous', 1),
('BOFORMS_VALIDATION_PLEASE_FILL_ABOVE_FIELDS','2', 'Please fill above fields', 1),
('BOFORMS_VALIDATION_ADD_A_NOTIFICATION','1','Il faut ajouter au moins un type de notification', 1),
('BOFORMS_VALIDATION_ADD_A_NOTIFICATION','2','You should add a notification type', 1),
('BOFORMS_VALIDATION_PICK_VALUE_AND_JUSTIFY', '1', 'Selectionner au moins une valeur et justifier la/les demande(s)', 1),
('BOFORMS_VALIDATION_PICK_VALUE_AND_JUSTIFY', '2', 'Pick a value and justify', 1),
('BOFORMS_VALIDATION_FILL_THIS_FIELD', '1', 'Veuillez renseigner ce champ', 1),
('BOFORMS_VALIDATION_FILL_THIS_FIELD', '2', 'Please fill this field', 1),
('BOFORMS_VALIDATION_REQUIRED_FIELD', '1', 'Ce champ est obligatoire',1),
('BOFORMS_VALIDATION_REQUIRED_FIELD', '2', 'This field is required',1),
('BOFORMS_VALIDATION_SELECT_FIELDS', '1', 'Veuillez sélectionner des champs',1),
('BOFORMS_VALIDATION_SELECT_FIELDS', '2', 'Please select some fields',1),
('BOFORMS_REQUEST_FIELD', '1', 'Champ', 1),
('BOFORMS_REQUEST_FIELD', '2', 'Field', 1),
('BOFORMS_REQUEST_CENTRAL_NOTIFICATION_CREATED', '1', 'La demande a été envoyée au central', 1),
('BOFORMS_REQUEST_CENTRAL_NOTIFICATION_CREATED', '2', 'Central validation mail sent successfully', 1),
('BOFORMS_REQUEST_JIRA_NEW_FORM_CREATED', '1', 'Votre demande de création XXXXX a bien été prise en compte', 1),
('BOFORMS_REQUEST_JIRA_NEW_FORM_CREATED', '2', 'Form''s Creation request XXXXX created successfully', 1),
('BOFORMS_REQUEST_JIRA_ANOMALY_CREATED', '1', 'Votre notification d''anomalie XXXXX a bien été créée', 1),
('BOFORMS_REQUEST_JIRA_ANOMALY_CREATED', '2', 'Anomaly''s notification request XXXXX created successfully', 1),
('BOFORMS_REQUEST_JIRA_EVOLUTION_CREATED', '1', 'Votre demande d\'évolution XXXXX a bien été créée', 1),
('BOFORMS_REQUEST_JIRA_EVOLUTION_CREATED', '2', 'Evolution request XXXXX created successfully', 1),
('BOFORMS_REQUEST_ANOMALY_NOT_LINK_FIELD', '1', 'Anomalie non liée à un champ', 1),
('BOFORMS_REQUEST_ANOMALY_NOT_LINK_FIELD', '2', 'Anomaly not linked to a field', 1),
('BOFORMS_REQUEST_FIELDNAME', '1', 'Nom du champ', 1),
('BOFORMS_REQUEST_FIELDNAME', '2', 'Field name', 1),
('BOFORMS_REQUEST_FILLEDTEXT', '1', 'Texte saisi', 1),
('BOFORMS_REQUEST_FILLEDTEXT', '2', 'Text value', 1),
('BOFORMS_REQUEST_OPPORTUNITY', '1', 'Opportunité', 1),
('BOFORMS_REQUEST_OPPORTUNITY', '2', 'Opportunity', 1),
('BOFORMS_REQUEST_QUESTION', '1', 'Question', 1),
('BOFORMS_REQUEST_QUESTION', '2', 'Question', 1),
('BOFORMS_REQUEST_DESCRIPTION', '1', 'Description', 1),
('BOFORMS_REQUEST_DESCRIPTION', '2', 'Description', 1),
('BOFORMS_POPUP_CREATE_NEW_FORM_FORM_NOT_FOUND', '1', 'Formulaire non trouvé',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_FORM_NOT_FOUND', '2', 'Form not found',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_AVAILABLE_FIELDS', '1', 'Champs disponibles',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_AVAILABLE_FIELDS', '2', 'Available fields',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD', '1','Rajouter des champs',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD', '2','Add new fields',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE', '1','Donner un exemple d’un formulaire en local',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE', '2','Give an example of local form',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_CHOOSE_FORM_TYPE', '1', 'Indiquer le formulaire existant qui se rapproche le plus du besoin',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_CHOOSE_FORM_TYPE', '2', 'Choose a similar form',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW', '1','Indiquer le parcours',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW', '2','Workflow ?',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET', '1','Indiquer la cible',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET', '2','Target ?',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE', '1','Indiquer le device',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE', '2','Device ?',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_TITLE', '1','Créer un nouveau formulaire',1), 
('BOFORMS_POPUP_CREATE_NEW_FORM_TITLE', '2','Create a new form',1), 
('BOFORMS_POPUP_CREATE_NEW_FORM_LINK_TEXT', '1','Créer un nouveau formulaire',1),
('BOFORMS_POPUP_CREATE_NEW_FORM_LINK_TEXT', '2','Create a new form',1),
('BOFORMS_SUPPORT_JUSTIFY_ADD_TOOLTIP', '1', 'Justifier ajouter tooltip',1),
('BOFORMS_SUPPORT_JUSTIFY_ADD_TOOLTIP', '2', 'Justify add tooltip',1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_TOOLTIP', '1', 'Justifier supprimer tooltip',1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_TOOLTIP', '2', 'Justify supprimer tooltip',1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_FIELD', '1', 'Justifier la suppression', 1),
('BOFORMS_SUPPORT_JUSTIFY_REMOVE_FIELD', '2', 'Justify the removal', 1),
('BOFORMS_SUPPORT_REQUIRED_FIELDS', '1', 'Champs obligatoires', 1),
('BOFORMS_SUPPORT_REQUIRED_FIELDS', '2', 'Required fields', 1),
('BOFORMS_SUPPORT_CHOOSE_REQUEST_TYPE', '1', 'Choisissez le type de demande',1),
('BOFORMS_SUPPORT_CHOOSE_REQUEST_TYPE', '2', 'Choose the type of request',1),
('BOFORMS_SUPPORT_CHOOSE_PRIORITY', '1', 'Choisissez la priorité',1),
('BOFORMS_SUPPORT_CHOOSE_PRIORITY', '2', 'Choose the priority',1),
('BOFORMS_SUPPORT_CHOOSE_MODIFICATION_TYPE', '1', 'Choississez le type de modification', 1),
('BOFORMS_SUPPORT_CHOOSE_MODIFICATION_TYPE', '2', 'Choose modification type', 1),
('BOFORMS_SUPPORT_CHOOSE_NOTIFICATION_TYPE', '1', 'Choisissez le type de notification', 1),
('BOFORMS_SUPPORT_CHOOSE_NOTIFICATION_TYPE', '2', 'Choose the notification type', 1),
('BOFORMS_SUPPORT_ADD_FILE', '1', 'Ajouter une pièce-jointe',1),
('BOFORMS_SUPPORT_ADD_FILE', '2', 'Add a file',1),
('BOFORMS_SUPPORT_COUNTRY','1', 'Pays',1),
('BOFORMS_SUPPORT_COUNTRY', '2','Country',1),
('BOFORMS_SUPPORT_PRIORITY', '1','Priorité',1),
('BOFORMS_SUPPORT_PRIORITY', '2','Priority',1),
('BOFORMS_SUPPORT_ENVIRONMENT','1', 'Environnement',1),
('BOFORMS_SUPPORT_ENVIRONMENT', '2','Environment',1),
('BOFORMS_SUPPORT_WEBMASTER_NAME','1', 'Nom du webmaster',1),
('BOFORMS_SUPPORT_WEBMASTER_NAME', '2','Webmaster\'s name',1),
('BOFORMS_SUPPORT_REQUEST_DESCRIPTION','1', 'Descriptif de la demande',1),
('BOFORMS_SUPPORT_REQUEST_DESCRIPTION', '2','Describe the request',1),
('BOFORMS_SUPPORT_XML_SAVED_VERSION','1', 'XML de la version enregistrée (nom du fichier)',1),
('BOFORMS_SUPPORT_XML_SAVED_VERSION', '2','XML of the saved version (name of the file)',1),
('BOFORMS_SUPPORT_REQUEST_TITLE', '1', 'Titre de la demande',1),
('BOFORMS_SUPPORT_REQUEST_TITLE',  '2','Request title',1),
('BOFORMS_SUPPORT_MODIFICATION_TYPE', '1', 'Type de modification',1),
('BOFORMS_SUPPORT_MODIFICATION_TYPE',  '2','Modification type',1),
('BOFORMS_SUPPORT_JUSTIFY_REQUEST','1',  'Précision et justification de la demande',1),
('BOFORMS_SUPPORT_JUSTIFY_REQUEST', '2', 'Describe and justify the request',1),
('BOFORMS_SUPPORT_EXPLAIN_REQUEST', '1', 'Précision de la demande',1),
('BOFORMS_SUPPORT_EXPLAIN_REQUEST',  '2','Explain request',1),
('BOFORMS_SUPPORT_DESCRIBE_NEEDS', '1', 'Description du besoin',1),
('BOFORMS_SUPPORT_DESCRIBE_NEEDS',  '2','Describe needs',1),
('BOFORMS_SUPPORT_DESCRIBE_ANOMALY', '1', 'Description de l\'anomalie',1),
('BOFORMS_SUPPORT_DESCRIBE_ANOMALY',  '2','Describe the anomaly',1),
('BOFORMS_SUPPORT_BTN_SEND_SUPPORT_REQUEST', '1', 'Envoyer la demande',1),
('BOFORMS_SUPPORT_BTN_SEND_SUPPORT_REQUEST', '2', 'Send the request',1),
('BOFORMS_SUPPORT_BTN_ADD_REQUEST','1',  'Ajouter une demande',1),
('BOFORMS_SUPPORT_BTN_ADD_REQUEST',  '2','Add a request',1),

('BOFORMS_NOTIFICATION_NEW_FIELDS', '1', 'Nouveaux champs' ,1),
('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD', '1', 'Suppression champs obligatoire',1),
('BOFORMS_NOTIFICATION_ADD_TOOLTIP', '1', 'Ajout d’une bulle d’aide',1),
('BOFORMS_NOTIFICATION_DEL_TOOLTIP', '1', 'Suppression d’une bulle d’aide',1),
('BOFORMS_NOTIFICATION_MODIFY_IMPRINT', '1', 'Modification de la présentation des mentions légales',1),
('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT', '1', 'Evolution d’un composant métier',1),
('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE', '1', 'Evolution du graphisme',1),
('BOFORMS_NOTIFICATION_MODIFY_OPT_IN', '1', 'Modification opt-in',1),
('BOFORMS_NOTIFICATION_OTHER_REQUEST', '1', 'Autre demande',1),

('BOFORMS_NOTIFICATION_NEW_FIELDS', '2', 'New fields',1),
('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD', '2', 'Remove mandatory field',1),
('BOFORMS_NOTIFICATION_ADD_TOOLTIP', '2', 'Add tooltip',1),
('BOFORMS_NOTIFICATION_DEL_TOOLTIP', '2', 'Remove tooltip',1),
('BOFORMS_NOTIFICATION_MODIFY_IMPRINT', '2', 'Modify imprint',1),
('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT', '2', 'Update business component',1),
('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE', '2', 'Update user interface',1),
('BOFORMS_NOTIFICATION_MODIFY_OPT_IN', '2', 'Modify opt-in',1),
('BOFORMS_NOTIFICATION_OTHER_REQUEST', '2', 'Other request',1),

('BOFORMS_REQUEST_TYPE_CENTRAL_VALIDATION', '1','Demande de validation au Central',1),
('BOFORMS_REQUEST_TYPE_FORM_EVOLUTION', '1','Demande d’évolution du formulaire',1),
('BOFORMS_REQUEST_TYPE_NOTIFY_ANOMALY', '1','Notification d’anomalie',1),

('BOFORMS_REQUEST_TYPE_CENTRAL_VALIDATION', '2','Request central validation',1),
('BOFORMS_REQUEST_TYPE_FORM_EVOLUTION', '2','Request form evolution',1),
('BOFORMS_REQUEST_TYPE_NOTIFY_ANOMALY', '2','Notify an anomaly',1),

('BOFORMS_REQUEST_BLOCKING', '1','Bloquante',1),
('BOFORMS_REQUEST_MAJOR',  '1','Majeure',1),
('BOFORMS_REQUEST_MINOR',  '1','Mineure',1),

('BOFORMS_REQUEST_BLOCKING', '2','Blocking',1),
('BOFORMS_REQUEST_MAJOR',  '2','Major',1),
('BOFORMS_REQUEST_MINOR',  '2','Minor',1),


('BOFORMS_REPORTING_FILL_CULTURE',  '1','Renseignez au moins une langue',1),
('BOFORMS_REPORTING_FILL_CULTURE',  '2','Fill at least one language',1),
('BOFORMS_REPORTING_FILL_SITE',  '1','Renseignez au moins un site',1),
('BOFORMS_REPORTING_FILL_SITE',  '2','Fill at least one site',1),
('BOFORMS_REPORTING_FILL_TYPE',  '1','Renseignez au moins un type de formulaire',1),
('BOFORMS_REPORTING_FILL_TYPE',  '2','Fill at least one form type',1),
('BOFORMS_REPORTING_FILL_CONTEXT',  '1','Renseignez au moins un contexte',1),
('BOFORMS_REPORTING_FILL_CONTEXT',  '2','Fill at least one context',1),
('BOFORMS_REPORTING_FILL_TARGET',  '1','Renseignez au moins un type de client',1),
('BOFORMS_REPORTING_FILL_TARGET',  '2','Fill at least one customer type',1)
;";
		
		$sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
		('BO_FORMS_NDP', NULL,1),
		('BOFORMS_LABEL_CA', NULL,1),
		('BOFORMS_LABEL_DS', NULL,1),
		('BOFORMS_LABEL_PA', NULL,1),
		('BOFORMS_CONF_MODULE_LINK_REINIT', NULL,1),
		('BOFORMS_CONF_MODULE_CONFIRM_REINIT', NULL,1),
		('BOFORMS_CONF_MODULE_CONF_LOADED', NULL,1),
		('BOFORMS_AC_PROXY0URL', NULL,1),
		('BOFORMS_AC_PROXY0LOGIN', NULL,1),
		('BOFORMS_AC_PROXY0PWD', NULL,1),
		('BOFORMS_AC_PROXY0CURLPROXY_HTTP', NULL,1),
		('BOFORMS_AP_PROXY0URL', NULL,1),
		('BOFORMS_AP_PROXY0LOGIN', NULL,1),
		('BOFORMS_AP_PROXY0PWD', NULL,1),
		('BOFORMS_AP_PROXY0CURLPROXY_HTTP', NULL,1),
		('BOFORMS_DS_PROXY0URL', NULL,1),
		('BOFORMS_DS_PROXY0LOGIN', NULL,1),
		('BOFORMS_DS_PROXY0PWD', NULL,1),
		('BOFORMS_DS_PROXY0CURLPROXY_HTTP', NULL,1),
        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_BOFORMS', NULL,1),
        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_I18N', NULL,1),
        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_DEALERSERVICE', NULL,1),
        ('BOFORMS_REFERENTIAL_CONF_TYPE_CONFIGURATION_GENERALE', NULL,1),
        ('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_SUPER_ADMIN', NULL,1),
        ('BOFORMS_JIRA0PROJECT_KEY', NULL,1),
        ('BOFORMS_JIRA0ISSUE_URL', NULL,1),
        ('BOFORMS_JIRA0ASSIGNEE_NAME', NULL,1),
        ('BOFORMS_JIRA0OTHER_ASSIGNEE', NULL,1),
        ('BOFORMS_AIDE_CONF_ADMIN', NULL,1),
        ('AC_SERVICE_BOFORMS0PARAMETERS0LOCATION', NULL,1),
        ('AC_SERVICE_BOFORMS0PARAMETERS0WSDL', NULL,1),
        ('DS_SERVICE_BOFORMS0PARAMETERS0LOCATION', NULL,1),
        ('DS_SERVICE_BOFORMS0PARAMETERS0WSDL', NULL,1),
        ('AP_SERVICE_BOFORMS0PARAMETERS0LOCATION', NULL,1),
        ('AP_SERVICE_BOFORMS0PARAMETERS0WSDL', NULL,1),
        ('CITROEN_SERVICE_I18N0PARAMETERS0LOCATION', NULL,1),
        ('CITROEN_SERVICE_I18N0PARAMETERS0WSDL', NULL,1),
        ('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0LOCATION', NULL,1),
        ('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0WSDL', NULL,1),
        ('BOFORMS_URL_CLEARCACHE', NULL,1),
        ('BOFORMS_URL_CLEARCACHE_KEY', NULL,1),
        ('BOFORMS_URL_LP', NULL,1),
        ('BOFORMS_URL_RENDERER', NULL,1),
        ('BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', NULL,1),
        ('BOFORMS_BRAND_ID', NULL,1),
        ('BOFORMS_CONSUMER', NULL,1),
        ('BOFORMS_FORM_XSD', NULL,1),
        ('BOFORMS_LOG_PATH', NULL,1),
        ('BOFORMS_USER_SUPER_ADMIN', NULL,1),
        ('BOFORMS_BRAND_AC', NULL,1),
		('BOFORMS_BRAND_AC', NULL,1),
		('BOFORMS_BRAND_DS', NULL,1),
		('BOFORMS_BRAND_DS', NULL,1),
		('BOFORMS_BRAND_AP', NULL,1),
		('BOFORMS_BRAND_AP', NULL,1),
		('BOFORMS_NEW_BLOCK', NULL,1),
		('BOFORMS_REFERENTIAL_CONF_TYPE_CUSCO', NULL,1),
		('BOFORMS_REFERENTIAL_CONF_TYPE_GDO', NULL,1),
		('BOFORMS_WS_OTHER_LOCATION', NULL,1),
		('BOFORMS_WS_OTHER_WSDL', NULL,1),
		('BOFORMS_DUPL_INSTANCE_FORM_NAME_ALREADY_EXIST', NULL,1),
		('BOFORMS_DUPL_INSTANCE_INSTANCE_NAME_ALREADY_EXIST', NULL,1),
		('BOFORMS_DUPL_INSTANCE_XSD_VALIDATION_FAILED', NULL,1),
		('BOFORMS_DUPL_INSTANCE_NULL_NOT_PERMITTED', NULL,1),
		('BOFORMS_DUPL_INSTANCE_ERROR', NULL,1),
		('BOFORMS_DUPLICATION_SUCCESS', NULL,1),
		('BOFORMS_WS_DEFAULT_BRAND_IS', NULL,1),
		('BOFORMS_CULTURE', NULL,1),
		('BOFORMS_BRAND_TARGET', NULL,1),
		('BOFORMS_DUPLICATIONMODULE', NULL,1),
		('BOFORMS_DUPLICATE_ACTION', NULL,1),
		('BOFORMS_SEARCH_FORM_TO_DUPLICATE', NULL,1),
		('BOFORMS_DUPLICATE_RESULT', NULL,1),
		('BOFORMS_DUPLICATE_FILL_VALUES', NULL,1),
		('BOFORMS_FORM_TO_DUPLICATE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_BRAND_SITE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_PERSONAL_SPACE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_LANDING_PAGE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_LANDING_PAGE_v2', NULL,1),
		('BOFORMS_FORMSITE_LABEL_CONFIGURATOR', NULL,1),
		('BOFORMS_FORMSITE_LABEL_EDEALER', NULL,1),
		('BOFORMS_FORMSITE_LABEL_STORE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_DERIVED_PRODUCT', NULL,1),
		('BOFORMS_FORMSITE_LABEL_SMEG', NULL,1),
		('BOFORMS_FORMSITE_LABEL_MULTICITY', NULL,1),
		('BOFORMS_FORMSITE_LABEL_COMMUNITY', NULL,1),
		('BOFORMS_CLIENT_TYPE', NULL,1),
		('BOFORMS_FORMSITE_LABEL_CRMPDV', NULL,1),
		('BOFORMS_FORMSITE_LABEL_CUSCO', NULL,1),
		('BOFORMS_FORMSITE_LABEL_PMS', NULL,1),
		('BOFORMS_LABEL_LEGAL_MENTION_ANSWER', NULL,1),
		('BOFORMS_VISIBLE', NULL,1),
		('BOFORMS_LABEL_TARGET_FORM', NULL,1),
		('BOFORMS_LABEL_CONFIRM_DUPLICATE', NULL,1),
		('BOFORMS_LABEL_RESULT', NULL,1),
		('BOFORMS_DUPLICATE_OTHER_FORM', NULL,1),
		('BOFORMS_FORM_DUPLICATION_PARAMETERS', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', NULL,1),
		('BOFORMS_INTRO', NULL,1),
		('BOFORMS_AVAILABLE_STEP', NULL,1),
		('BOFORMS_SELECTED_STEP', NULL,1),
		('BOFORMS_BTN_CLEAR_INSTANCES', NULL,1),
		('BOFORMS_CLEAR_INSTANCES_DONE', NULL,1),
		('BOFORMS_CLEAR_INSTANCES_CONFIRM', NULL,1),
		('BOFORMS_LABEL_CONTENT', NULL,1),
		('BOFORMS_STEP_CONFIGURATION', NULL,1),
		('BOFORMS_STEP_CONFIGURATION_GENERIQUE', NULL,1),
		('BOFORMS_CONFIGURATION_NEXT_LABEL', NULL,1),
		('BOFORMS_CONFIGURATION_PREVIOUS_LABEL', NULL,1),
		('BOFORMS_URL_BOLP', NULL,1),
		('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_URL_BOLP', NULL,1),
		('BOFORMS_REFERENTIAL_FORM_TYPE_PRELEAD', NULL,1)
        ;";

		$sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
			('BO_FORMS_NDP', 1, 'BO FORMS Ndp', 1),
			('BO_FORMS_NDP', 2, 'BO FORMS Ndp', 1),
			('BOFORMS_BTN_CLEAR_INSTANCES', 1, 'Vider les instances', 1),
			('BOFORMS_BTN_CLEAR_INSTANCES', 2, 'Clear instances', 1),
			('BOFORMS_CLEAR_INSTANCES_DONE', 1, 'Suppression réussie', 1),
			('BOFORMS_CLEAR_INSTANCES_DONE', 2, 'Clear done', 1),
			('BOFORMS_CLEAR_INSTANCES_CONFIRM', 1, 'Attention ! Cette opération va supprimer les instances, le journal d''activité et les groupes de site du site pays. Confirmer vous cette opération ?', 1),
			('BOFORMS_CLEAR_INSTANCES_CONFIRM', 2, 'Warning ! This will delete the instances, the activity log and site groups. Could you confirm this opération ?', 1),
			('BO_FORMS_CITROEN', 1, 'BO FORMS Citroën', 1),
			('BO_FORMS_CITROEN', 2, 'BO FORMS Citroën', 1),
			('BOFORMS_LABEL_CA', 1, 'BO FORMS Ndp', 1),
			('BOFORMS_LABEL_CA', 2, 'BO FORMS Ndp', 1),
			('BOFORMS_LABEL_DS', 1, 'BO FORMS DS', 1),
			('BOFORMS_LABEL_DS', 2, 'BO FORMS DS', 1),
			('BOFORMS_LABEL_PA', 1, 'BO FORMS Peugeot', 1),
			('BOFORMS_LABEL_PA', 2, 'BO FORMS Peugeot', 1),
			('BOFORMS_CONF_MODULE_LINK_REINIT', 1, 'Réinitialiser la configuration', 1),
			('BOFORMS_CONF_MODULE_LINK_REINIT', 2, 'Initialize the configuration', 1),
			('BOFORMS_CONF_MODULE_CONFIRM_REINIT', 1, 'Confirmer la réinitialisation des configurations', 1),
			('BOFORMS_CONF_MODULE_CONFIRM_REINIT', 2, 'Confirm reinit of the configuration', 1),
			('BOFORMS_CONF_MODULE_CONF_LOADED', 1, 'Configuration réinitialisée avec succès', 1),
			('BOFORMS_CONF_MODULE_CONF_LOADED', 2, 'Configuration has been reset successfully', 1),
			('BOFORMS_AC_PROXY0URL', 1, 'URL du PROXY Citroen', 1),
			('BOFORMS_AC_PROXY0URL', 2, 'Citroen PROXY URL', 1),
			('BOFORMS_AC_PROXY0LOGIN', 1, 'Identifiant Citroen', 1),
			('BOFORMS_AC_PROXY0LOGIN', 2, 'Citroen Login', 1),
			('BOFORMS_AC_PROXY0PWD', 1, 'Mot de passe Citroen', 1),
			('BOFORMS_AC_PROXY0PWD', 2, 'Citroen Password', 1),
			('BOFORMS_AC_PROXY0CURLPROXY_HTTP', 1, 'PROXY HTTP Citroen', 1),
			('BOFORMS_AC_PROXY0CURLPROXY_HTTP', 2, 'Citroen HTTP PROXY', 1), 
			('BOFORMS_AP_PROXY0URL', 1, 'URL du PROXY Peugeot', 1),
			('BOFORMS_AP_PROXY0URL', 2, 'Peugeot PROXY URL', 1),
			('BOFORMS_AP_PROXY0LOGIN', 1, 'Identifiant Peugeot', 1),
			('BOFORMS_AP_PROXY0LOGIN', 2, 'Peugeot Login', 1),
			('BOFORMS_AP_PROXY0PWD', 1, 'Mot de passe Peugeot', 1),
			('BOFORMS_AP_PROXY0PWD', 2, 'Peugeot Password', 1),
			('BOFORMS_AP_PROXY0CURLPROXY_HTTP', 1, 'PROXY HTTP Peugeot', 1),
			('BOFORMS_AP_PROXY0CURLPROXY_HTTP', 2, 'Peugeot HTTP PROXY', 1),
			('BOFORMS_DS_PROXY0URL', 1, 'URL du PROXY DS', 1),
			('BOFORMS_DS_PROXY0URL', 2, 'DS PROXY URL', 1),
			('BOFORMS_DS_PROXY0LOGIN', 1, 'Identifiant DS', 1),
			('BOFORMS_DS_PROXY0LOGIN', 2, 'DS Login', 1),
			('BOFORMS_DS_PROXY0PWD', 1, 'Mot de passe DS', 1),
			('BOFORMS_DS_PROXY0PWD', 2, 'DS Password', 1),
			('BOFORMS_DS_PROXY0CURLPROXY_HTTP', 1, 'PROXY HTTP DS', 1),
			('BOFORMS_DS_PROXY0CURLPROXY_HTTP', 2, 'DS HTTP PROXY', 1), 
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_BOFORMS', 1, 'Web Service BOFORMS', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_BOFORMS', 2, 'BOFORMS Web service', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_I18N', 1, 'Web Services i18n', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_I18N', 2, 'i18n web services', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_DEALERSERVICE', 1, 'Web Service Dealerservice', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_WEBSERVICE_DEALERSERVICE', 2, 'Dealerservice Web Service', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_CONFIGURATION_GENERALE', 1, 'Configuration Générale', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_CONFIGURATION_GENERALE', 2, 'General Configuration', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_SUPER_ADMIN', 1, 'Administration des super administrateurs', 1),
	        ('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_SUPER_ADMIN', 2, 'Super administrator administration ', 1),
	        ('BOFORMS_JIRA0PROJECT_KEY', 1, 'Projet JIRA', 1),
	        ('BOFORMS_JIRA0PROJECT_KEY', 2, 'JIRA project', 1),
	        ('BOFORMS_JIRA0ISSUE_URL', 1, 'URL de l''API JIRA', 1),
	        ('BOFORMS_JIRA0ISSUE_URL', 2, 'Issue URL', 1),
	        ('BOFORMS_JIRA0ASSIGNEE_NAME', 1, 'Compte utilisateur pour attribution', 1),
	        ('BOFORMS_JIRA0ASSIGNEE_NAME', 2, 'Assignee user acount', 1),
	        ('BOFORMS_JIRA0OTHER_ASSIGNEE', 1, 'Comptes utilisateurs pour autres destinataires', 1),
	        ('BOFORMS_JIRA0OTHER_ASSIGNEE', 2, 'Other assignees user acount', 1),
	        ('BOFORMS_AIDE_CONF_ADMIN', 1, 'Séparez les éléments par des virgules, exemple :<br/>E452XXX,E452YYY,E452ZZZ', 1),
	        ('BOFORMS_AIDE_CONF_ADMIN', 2, 'Separate elements with coma, ie :<br/>E452XXX,E452YYY,E452ZZZ', 1),
	        ('BOFORMS_SERVICE0PARAMETERS0LOCATION', 1, 'Web service BOFORMS', 1),
	        ('BOFORMS_SERVICE0PARAMETERS0LOCATION', 2, 'BOFORMS web service', 1),
	        ('BOFORMS_SERVICE0PARAMETERS0WSDL', 1, 'WSDL du web service BOFORMS', 1),
	        ('BOFORMS_SERVICE0PARAMETERS0WSDL', 2, 'WSDL BoForms web service', 1),
			('AC_SERVICE_BOFORMS0PARAMETERS0LOCATION', 1, 'Web service Citroen', 1),
	        ('AC_SERVICE_BOFORMS0PARAMETERS0LOCATION', 2, 'Web service Citroen', 1),
	        ('AC_SERVICE_BOFORMS0PARAMETERS0WSDL', 1, 'WSDL du web service Citroen', 1),
	        ('AC_SERVICE_BOFORMS0PARAMETERS0WSDL', 2, 'WSDL web service Citroen', 1),
	        ('DS_SERVICE_BOFORMS0PARAMETERS0LOCATION', 1, 'Web service DS', 1),
	        ('DS_SERVICE_BOFORMS0PARAMETERS0LOCATION', 2, 'Web service DS', 1),
	        ('DS_SERVICE_BOFORMS0PARAMETERS0WSDL', 1, 'WSDL du web service DS', 1),
	        ('DS_SERVICE_BOFORMS0PARAMETERS0WSDL', 2, 'WSDL web service DS', 1),
	        ('AP_SERVICE_BOFORMS0PARAMETERS0LOCATION', 1, 'Web service Peugeot', 1),
	        ('AP_SERVICE_BOFORMS0PARAMETERS0LOCATION', 2, 'Web service Peugeot', 1),
	        ('AP_SERVICE_BOFORMS0PARAMETERS0WSDL', 1, 'WSDL du web service Peugeot', 1),
	        ('AP_SERVICE_BOFORMS0PARAMETERS0WSDL', 2, 'WSDL web service Peugeot', 1),
	        ('CITROEN_SERVICE_I18N0PARAMETERS0LOCATION', 1, 'Web service i18n', 1),
	        ('CITROEN_SERVICE_I18N0PARAMETERS0LOCATION', 2, 'i18n web service', 1),
	        ('CITROEN_SERVICE_I18N0PARAMETERS0WSDL', 1, 'WSDL du web service i18n', 1),
	        ('CITROEN_SERVICE_I18N0PARAMETERS0WSDL', 2, 'WSDL for i18n web service', 1),
	        ('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0LOCATION', 1, 'Web Service du Dealer Service',1),
	        ('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0LOCATION', 2, 'Dealer service web service',1),
        	('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0WSDL', 1, 'WSDL du Web Service du Dealer Service',1),
        	('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0WSDL', 2, 'WSDL Dealer Service web service',1),
	        ('BOFORMS_NDP_SERVICE_I18N0PARAMETERS0LOCATION', 1, 'Web service i18n', 1),
	        ('BOFORMS_NDP_SERVICE_I18N0PARAMETERS0LOCATION', 2, 'i18n web service', 1),
	        ('BOFORMS_NDP_SERVICE_I18N0PARAMETERS0WSDL', 1, 'WSDL du web service i18n', 1),
	        ('BOFORMS_NDP_SERVICE_I18N0PARAMETERS0WSDL', 2, 'WSDL for i18n web service', 1),
	        ('BOFORMS_NDP_SERVICE_DEALERSERVICE0PARAMETERS0LOCATION', 1, 'Web Service du Dealer Service',1),
	        ('BOFORMS_NDP_SERVICE_DEALERSERVICE0PARAMETERS0LOCATION', 2, 'Dealer service web service',1),
        	('BOFORMS_NDP_SERVICE_DEALERSERVICE0PARAMETERS0WSDL', 1, 'WSDL du Web Service du Dealer Service',1),
        	('BOFORMS_NDP_SERVICE_DEALERSERVICE0PARAMETERS0WSDL', 2, 'WSDL Dealer Service web service',1),
	        ('BOFORMS_URL_CLEARCACHE', 1, 'URL pour vider le cache', 1),
	        ('BOFORMS_URL_CLEARCACHE', 2, 'Clear cache URL', 1),
	        ('BOFORMS_URL_CLEARCACHE_KEY', 1, 'Clé pour vider le cache', 1),
	        ('BOFORMS_URL_CLEARCACHE_KEY', 2, 'Clear cache key', 1),
	        ('BOFORMS_URL_LP', 1, 'URL LP', 1),
	        ('BOFORMS_URL_LP', 2, 'LP URL', 1),
	        ('BOFORMS_URL_RENDERER', 1, 'URL RENDERER', 1),
	        ('BOFORMS_URL_RENDERER', 2, 'RENDERER URL', 1),
	        ('BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', 1, 'Central validation email', 1),
	        ('BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', 2, 'Central validation email', 1),
	        ('BOFORMS_BRAND_ID', 1, 'ID de la marque', 1),
	        ('BOFORMS_BRAND_ID', 2, 'Brand ID', 1),
	        ('BOFORMS_CONSUMER', 1, 'Client', 1),
	        ('BOFORMS_CONSUMER', 2, 'Consumer', 1),
	        ('BOFORMS_FORM_XSD', 1, 'Url du fichier xsd', 1),
	        ('BOFORMS_FORM_XSD', 2, 'XSD file url', 1),
	        ('BOFORMS_LOG_PATH', 1, 'Chemin des fichiers de logs', 1),
	        ('BOFORMS_LOG_PATH', 2, 'Path for the log file', 1),
	        ('BOFORMS_USER_SUPER_ADMIN', 1, 'Liste des ID des super administrateurs BOFORMS (RPI)', 1),
	        ('BOFORMS_USER_SUPER_ADMIN', 2, 'List of admin user''s id', 1),
	        ('BOFORMS_BRAND_AC',1, 'Citroën', 1),
			('BOFORMS_BRAND_AC',2, 'Citroën', 1),
			('BOFORMS_BRAND_DS',1, 'DS', 1),
			('BOFORMS_BRAND_DS',2, 'DS', 1),
			('BOFORMS_BRAND_AP',1, 'Peugeot', 1),
			('BOFORMS_BRAND_AP',2, 'Peugeot', 1),
			('BOFORMS_NEW_BLOCK',1, 'Nouveau bloc', 1),
			('BOFORMS_NEW_BLOCK',2, 'New block', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_CUSCO', 1, 'CUSCO', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_CUSCO', 2, 'CUSCO', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_GDO', 1, 'GDO, WS Base Locale', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_GDO', 2, 'GDO, WS Base Locale', 1),
			('BOFORMS_WS_OTHER_LOCATION', 1, 'Web service', 1),
			('BOFORMS_WS_OTHER_LOCATION', 2, 'Web service', 1),
			('BOFORMS_WS_OTHER_WSDL', 1, 'WSDL du web service', 1),
			('BOFORMS_WS_OTHER_WSDL', 2, 'WSDL du web service', 1),
			('BOFORMS_DUPL_INSTANCE_FORM_NAME_ALREADY_EXIST', 1, 'Le nom du formulaire existe déjà', 1),
			('BOFORMS_DUPL_INSTANCE_FORM_NAME_ALREADY_EXIST', 2, 'This form name already exist', 1),
			('BOFORMS_DUPL_INSTANCE_INSTANCE_NAME_ALREADY_EXIST', 1, 'Ce nom d1instance existe déjà', 1),
			('BOFORMS_DUPL_INSTANCE_INSTANCE_NAME_ALREADY_EXIST', 2, 'This instance name already exists', 1),
			('BOFORMS_DUPL_INSTANCE_XSD_VALIDATION_FAILED', 1, 'Erreur de validation xsd', 1),
			('BOFORMS_DUPL_INSTANCE_XSD_VALIDATION_FAILED', 2, 'XSD validation error', 1),
			('BOFORMS_DUPL_INSTANCE_NULL_NOT_PERMITTED', 1, 'Valeur nulle non autorisée', 1),
			('BOFORMS_DUPL_INSTANCE_NULL_NOT_PERMITTED', 2, 'Null not permitted', 1),
			('BOFORMS_DUPL_INSTANCE_ERROR', 1, 'Une erreur est survenue', 1),
			('BOFORMS_DUPL_INSTANCE_ERROR', 2, 'An erreur occured', 1),				
			('BOFORMS_DUPLICATION_SUCCESS', 1, 'Duplication de formulaire réussie', 1),
			('BOFORMS_DUPLICATION_SUCCESS', 2, 'Successful form duplication', 1),	
			('BOFORMS_WS_DEFAULT_BRAND_IS', 1, 'La marque par défaut pour ce site est', 1),
			('BOFORMS_WS_DEFAULT_BRAND_IS', 2, 'The default brand for this site is', 1),
			('BOFORMS_CULTURE', 1, 'Langue', 1),
			('BOFORMS_CULTURE', 2, 'Language', 1),
			('BOFORMS_BRAND_TARGET', 1, 'Marque cible', 1),
			('BOFORMS_BRAND_TARGET', 2, 'Marque cible', 1),
			('BOFORMS_DUPLICATIONMODULE', 1, 'BOFORMS duplication', 1),
			('BOFORMS_DUPLICATIONMODULE', 2, 'BOFORMS duplicate', 1),
			('BOFORMS_DUPLICATE_ACTION', 1, 'Dupliquer', 1),
			('BOFORMS_DUPLICATE_ACTION', 2, 'Duplicate', 1),
			('BOFORMS_SEARCH_FORM_TO_DUPLICATE', 1, 'Rechercher le formulaire à dupliquer', 1),
			('BOFORMS_SEARCH_FORM_TO_DUPLICATE', 2, 'Search the form to duplicate', 1),
			('BOFORMS_DUPLICATE_RESULT', 1, 'Résultat de la duplication', 1),
			('BOFORMS_DUPLICATE_RESULT', 2, 'Result of duplication', 1),
			('BOFORMS_DUPLICATE_FILL_VALUES',1, 'Remplir les valeurs pour le nouveau formulaire', 1),
			('BOFORMS_DUPLICATE_FILL_VALUES',2, 'Fill the values for the new form', 1),
			('BOFORMS_FORM_TO_DUPLICATE', 1, 'Formulaire à dupliquer', 1),
			('BOFORMS_FORM_TO_DUPLICATE', 2, 'Form to duplicate', 1),

			('BOFORMS_FORMSITE_LABEL_BRAND_SITE', 1, 'Site de la marque', 1), 	
			('BOFORMS_FORMSITE_LABEL_BRAND_SITE', 2, 'Brand site', 1),
			('BOFORMS_FORMSITE_LABEL_PERSONAL_SPACE', 1, 'Espace personnel', 1), 	
			('BOFORMS_FORMSITE_LABEL_PERSONAL_SPACE', 2, 'Personal space', 1),
			('BOFORMS_FORMSITE_LABEL_LANDING_PAGE', 1, 'Landing Page', 1), 	
			('BOFORMS_FORMSITE_LABEL_LANDING_PAGE', 2, 'Landing Page', 1),
			('BOFORMS_FORMSITE_LABEL_LANDING_PAGE_v2', 1, 'Landing Page v2', 1), 	
			('BOFORMS_FORMSITE_LABEL_LANDING_PAGE_v2', 2, 'Landing Page v2', 1),
			('BOFORMS_FORMSITE_LABEL_CONFIGURATOR', 1, 'Configurator', 1), 	
			('BOFORMS_FORMSITE_LABEL_CONFIGURATOR', 2, 'Configurator', 1),
			('BOFORMS_FORMSITE_LABEL_EDEALER', 1, 'E-Dealer', 1), 	
			('BOFORMS_FORMSITE_LABEL_EDEALER', 2, 'E-Dealer', 1),
			('BOFORMS_FORMSITE_LABEL_STORE', 1, 'Magasin', 1), 	
			('BOFORMS_FORMSITE_LABEL_STORE', 2, 'Store', 1),
			('BOFORMS_FORMSITE_LABEL_DERIVED_PRODUCT', 1, 'Produit dérivé', 1), 	
			('BOFORMS_FORMSITE_LABEL_DERIVED_PRODUCT', 2, 'Derived product', 1),
			('BOFORMS_FORMSITE_LABEL_SMEG', 1, 'SMEG', 1), 	
			('BOFORMS_FORMSITE_LABEL_SMEG', 2, 'SMEG', 1),
			('BOFORMS_FORMSITE_LABEL_MULTICITY', 1, 'Multicity', 1), 	
			('BOFORMS_FORMSITE_LABEL_MULTICITY', 2, 'Multicity', 1),
			('BOFORMS_FORMSITE_LABEL_COMMUNITY', 1, 'Community', 1), 	
			('BOFORMS_FORMSITE_LABEL_COMMUNITY', 2, 'Community', 1),
			('BOFORMS_CLIENT_TYPE', 1, 'Type de client', 1), 	
			('BOFORMS_CLIENT_TYPE', 2, 'Client type', 1),
			('BOFORMS_FORMSITE_LABEL_CRMPDV', 1, 'CRMPDV', 1), 	
			('BOFORMS_FORMSITE_LABEL_CRMPDV', 2, 'CRMPDV', 1),
			('BOFORMS_FORMSITE_LABEL_CUSCO', 1, 'CUSCO', 1), 	
			('BOFORMS_FORMSITE_LABEL_CUSCO', 2, 'CUSCO', 1),
			('BOFORMS_FORMSITE_LABEL_PMS', 1, 'PMS', 1), 	
			('BOFORMS_FORMSITE_LABEL_PMS', 2, 'PMS', 1),
			('BOFORMS_LABEL_LEGAL_MENTION_ANSWER', 1, 'Opt-in mentions légales', 1), 	
			('BOFORMS_LABEL_LEGAL_MENTION_ANSWER', 2, 'Legal mention Opt-in', 1),
			('BOFORMS_VISIBLE', 1, 'Visible', 1),
			('BOFORMS_VISIBLE', 2, 'Visible', 1),			
			('BOFORMS_LABEL_TARGET_FORM', 1, 'Formulaire cible', 1), 	
			('BOFORMS_LABEL_TARGET_FORM', 2, 'Choose the target form', 1),
			('BOFORMS_LABEL_CONFIRM_DUPLICATE', 1, 'Confirmer la duplication?', 1), 	
			('BOFORMS_LABEL_CONFIRM_DUPLICATE', 2, 'Confirm the duplication?', 1),
			('BOFORMS_LABEL_RESULT', 1, 'Résultat', 1), 	
			('BOFORMS_LABEL_RESULT', 2, 'Result', 1),
			('BOFORMS_DUPLICATE_OTHER_FORM', 1, 'Dupliquer un autre formulaire', 1), 	
			('BOFORMS_DUPLICATE_OTHER_FORM', 2, 'Duplicate another form', 1),
			('BOFORMS_FORM_DUPLICATION_PARAMETERS', 1, 'Paramètres de duplication', 1),
			('BOFORMS_FORM_DUPLICATION_PARAMETERS', 2, 'Duplication parameters', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 1, 'Demande de RDV', 1), 
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 2, 'Request an appointment', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_AN_APPOINTMENT', 4, 'Pedir cita', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 1, 'Demande de RDV département services', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 2, 'Request service department appointment', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SERVICE_DEPARTMENT_APPOINTMENT', 4, 'Solicitud departamento de servicio al nombramiento', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 1, 'Demande de rachat', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 2, 'Request a buyback', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_A_BUYBACK', 4, 'Solicite una recompra de acciones', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 1, 'Demande de pièce de rechange', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 2, 'Request spare part or accesory', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_REQUEST_SPARE_PART_OR_ACCESORY', 4, 'Solicite una parte o accesorio de repuesto', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 1, 'RLC', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 2, 'RLC', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_RLC', 4, 'RLC', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 1, 'Préempter un véhicule', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 2, 'Preempt a vehicle', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_PREEMPT_A_VEHICLE', 4, 'Adelantarse a un vehículo', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 1, 'Keep in touch', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 2, 'Keep in touch', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_KEEP_IN_TOUCH', 4, 'Keep in touch', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 1, 'EDEALER', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 2, 'EDEALER', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_EDEALER_FORM', 4, 'EDEALER', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 1, 'WEBSTORE', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 2, 'WEBSTORE', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_WEBSTORE_FORM', 4, 'WEBSTORE', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 1, 'Formulaire technique', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 2, 'Technical form', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TECHNICAL_FORM', 4, 'Ficha técnica', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 1, 'Formulaire de test', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 2, 'Test form', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_TEST_FORM', 4, 'Formulario de prueba', 1),
			('BOFORMS_INTRO', 1, 'Texte d''introduction', 1),
			('BOFORMS_INTRO', 2, 'Introduction text', 1),
			('BOFORMS_AVAILABLE_STEP', 1, 'Eléments disponibles par références', 1),
			('BOFORMS_AVAILABLE_STEP', 2, 'Elements available by references', 1),
			('BOFORMS_SELECTED_STEP', 1, 'Eléments sélectionnés par personnalisation', 1),
			('BOFORMS_SELECTED_STEP', 2, 'Selected items by customization', 1),
			('BOFORMS_STEP_CONFIGURATION', 1, 'Boutons précédent / suivant', 1),
			('BOFORMS_STEP_CONFIGURATION', 2, 'Next / previous buttons', 1),
			('BOFORMS_STEP_CONFIGURATION_GENERIQUE', 1, 'Boutons précédent / suivant (Générique)', 1),
			('BOFORMS_STEP_CONFIGURATION_GENERIQUE', 2, 'Next / previous buttons (Generic)', 1),
			('BOFORMS_CONFIGURATION_NEXT_LABEL', 1, 'Next label', 1),
			('BOFORMS_CONFIGURATION_NEXT_LABEL', 2, 'Next label', 1),
			('BOFORMS_CONFIGURATION_PREVIOUS_LABEL', 1, 'Previous label', 1),
			('BOFORMS_CONFIGURATION_PREVIOUS_LABEL', 2, 'Previous label', 1),
			('BOFORMS_URL_BOLP', 1, 'Url BOLP', 1),
			('BOFORMS_URL_BOLP', 2, 'BOLP url', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_URL_BOLP', 1, 'Gestion des url BOLP par site', 1),
			('BOFORMS_REFERENTIAL_CONF_TYPE_ADMINISTRATION_URL_BOLP', 2, 'Manage BOLP url per site', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_PRELEAD', 1, 'Prelead', 1),
			('BOFORMS_REFERENTIAL_FORM_TYPE_PRELEAD', 2, 'Prelead', 1),
			
			('BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_HTML', 1, 'Copier/coller depuis la version HTML', 1),
			('BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_HTML', 2, 'Copy/paste from HTML version', 1),
			('BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_MOBILE', 1, 'Copier/coller depuis la version MOBILE', 1),
			('BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_MOBILE', 2, 'Copy/paste from MOBILE version', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_HTML', 1, 'Confirmer le copier/coller depuis la version HTML?', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_HTML', 2, 'Confirm copy/paste from HTML version?', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_MOBILE', 1, 'Confirmer le copier/coller depuis la version MOBILE?', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_MOBILE', 2, 'Confirm copy/paste from MOBILE version?', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_INFO', 1, 'Vous venez de faire un copier/coller mais les modifications n''ont pas été enregistrées. Pour valider ces modification il faut cliquer sur enregistrer.', 1),
			('BOFORMS_MSG_OVERRIDE_TRANSLATION_INFO', 2, 'You have just done a copy/paste but the change will be effective only when pressing the save button', 1),			
			
			('BOFORMS_TRAD_REF_KEY', 1, 'Element de référentiel', 1),
			('BOFORMS_TRAD_REF_KEY', 2, 'Referential item', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS', 1, 'Trad référentiels', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS', 2, 'Translate the referentials', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS_LIST', 1, 'Traduction des référentiels - Liste', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS_LIST', 2, 'Translate the referentials - List', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS_FOR_THIS_SITE', 1, 'Traductions pour ce site:', 1),
			('BOFORMS_TRADUCTIONREFERENTIELS_FOR_THIS_SITE', 2, 'Translations for this site:', 1),			
			('BOFORMS_TAG_GTM_LABEL', 1, 'Label du tag GTM', 1),
			('BOFORMS_TAG_GTM_LABEL', 2, 'Tag GTM label', 1),
			('BOFORMS_GLOBAL_PAGE_ERROR_MESSAGE', 1, 'Message d''erreur global', 1),
			('BOFORMS_GLOBAL_PAGE_ERROR_MESSAGE', 2, 'Global error message', 1),
			('BOFORMS_STEP_TAGS_UNDER_QUESTION', 1, 'Précisez les labels pour les tag gtm', 1),
			('BOFORMS_STEP_TAGS_UNDER_QUESTION', 2, 'Specify labels for gtm tags', 1)			
			;";

		

$sql[] = "UPDATE #pref#_label set LABEL_BO=1 where `LABEL_ID` LIKE 'BOFORMS_%';";
$sql[] = "UPDATE #pref#_label set LABEL_BO=1 where `LABEL_ID` LIKE 'BO_FORMS_%';";
				
		
		foreach ($sql as $query) {
			$oConnection->query($query);
		}


		
		/*** Référential ***/
		
		/*$oConnection = Pelican_Db::getInstance ();
		
		if($_ENV["TYPE_ENVIRONNEMENT"]!='DEV' && is_array(Pelican::$config['BOFORMS_REFERENTIAL_TYPE']) && !empty(Pelican::$config['BOFORMS_REFERENTIAL_TYPE']))
		{
			
			foreach (Pelican::$config['BOFORMS_REFERENTIAL_TYPE'] as $key=>$aReferencial)
			{


				$ref_type= $key;

				try {
					$serviceParams = array(
							'referentialType' => $ref_type
					);

					$service = \Itkg\Service\Factory::getService('NDP_SERVICE_BOFORMS', array());

					$response = $service->call('getReferential', $serviceParams);



				} catch(\Exception $e) {
					echo $e->getMessage();
				}


					
				$RefConst = Pelican::$config['BOFORMS_REFERENTIAL_TYPE'][$key];
					

				if(is_array($response))
				{

					$sSqlDelete = "DELETE FROM #pref#_".$RefConst['table'];

					$oConnection->query($sSqlDelete);

					$sSqlRef = "INSERT INTO #pref#_".$RefConst['table']." (".$RefConst['prefix']."ID,".$RefConst['prefix']."KEY)
								 VALUES (:refCode,:label)";
					$sSqlCulture = "INSERT INTO #pref#_".$RefConst['table']." (LANGUE_ID, CULTURE_LABEL,".$RefConst['prefix']."ID,".$RefConst['prefix']."KEY)
								 VALUES (:LANGUE_ID,:CULTURE_LABEL,:refCode,:label)";

					$sSQLanguage = "select LANGUE_ID, LANGUE_LABEL from #pref#_language where LANGUE_CODE=:label";

					
					foreach($response as $k=>$ref)
					{
						$aBind=array();
						$aBind[':refCode'] = (int)$ref->refCode;
						$aBind[':label'] = $oConnection->strToBind($ref->label);
							
						if($key=='CULTURE')
						{

							$sSqlRef='';
								
							$aLang=$oConnection->queryRow($sSQLanguage,$aBind);
								
							if(is_array($aLang) && !empty($aLang))
							{
								$aBind[':LANGUE_ID'] = $aLang['LANGUE_ID'];
								$aBind[':CULTURE_LABEL'] = $oConnection->strToBind($aLang['LANGUE_LABEL']);
									
								$sSqlRef=$sSqlCulture;
									
							}

						}elseif($key=='SITE')
						{
							//sites inséré en dur
						}
						 


						if($sSqlRef)
						{
							$oConnection->query($sSqlRef,$aBind);							
						}
							
					}

				}
					
			}
		}*/

	}


	/**
	 * Ã  lancer lors de la dÃ©sinstatllation
	 * - suppression de tables
	 * - suppression de donnÃ©es
	 */
	public function uninstall ()
	{
		$oConnection = Pelican_Db::getInstance();

		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_state_history;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_brand;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_draft;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_ligne;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_ligne_traduction;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_composant_type;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_context;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_culture;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_device;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire_site;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_formulaire_version;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_groupe;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_groupe_formulaire;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_opportunite;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_target;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_trace;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_country;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_conf;";
		$sql[] = "DROP TABLE IF EXISTS #pref#_boforms_list_conf;";
		 
		$sql[] = "DELETE FROM `#pref#_label_langue_site` WHERE `LABEL_ID` LIKE 'BOFORMS_%'";
		$sql[] = "DELETE FROM `#pref#_label` WHERE `LABEL_ID` LIKE 'BOFORMS_%'";

		foreach ($sql as $query) {
			$oConnection->query($query);
		}
	}
}