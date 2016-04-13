<?php
	/** Classe de paramétrage du calendrier
	*
	* Elle permet d'initialiser tous les paramètres d'affichage et d'action pour l'appel de l'objet Pelican_Calendar_Extended
	*
	* @version 1.0
	* @author Benoit de Jacobet <Benoit.dejacobet@businessdecision.com>
	* @since 28/11/2006
	* @package Pelican
	* @subpackage Date
	*/
	 
	/** Fichier de configuration */
	include_once('config.php');
	 
	/** Classe Pelican_Calendar_Extended */
	require_once("Extended.php");
	 
	 
	class Pelican_Calendar_Display {
		 
		var $month;
		var $year;
		 
		/**
		* Constructeur
		* @param string nameModuleCalendar : nom du module Pelican_Calendar(répertoire)
		* @param string pathModuleCalendar : chemin de mon module Pelican_Calendar
		* @param int $month
		* @param int $year
		* @param booléen $longDayName
		*
		* @return DisplayCalendar
		*/
		function Pelican_Calendar_Display($nameModuleCalendar, $pathModuleCalendar, $month , $year, $longDayName = true) {
			 
			if ($month == '') $month = date('n');
			if ($year == '') $year = date('Y');
			 
			$this->nameModuleCalendar = $nameModuleCalendar;
			$this->pathModuleCalendar = $pathModuleCalendar;
			$this->month = $month;
			$this->year = $year;
			$this->longDayName = $longDayName;
		}
		 
		/**
		* Initialise les paramètres d'action et d'affichage
		* Lance l'appel de la génération du Pelican_Html du Pelican_Calendar_Extended
		*
		* @return string $HTMLCalendarext
		*/
		function Perform() {
			 
			$calendarext = new Pelican_Calendar_Extended();
			$calendarext->SetWindow();
			$calendarext->SetLongDayName($this->longDayName);
			 
			 
			$HTMLCalendarext = $this->Render($calendarext);
			 
			return $HTMLCalendarext;
		}
		 
		/**
		* Génération du Pelican_Html du Pelican_Calendar_Extended
		*
		* @return string $HTMLCalendarext
		*/
		function Render($calendarext) {
			$HTMLCalendarext = $calendarext->SetMonth($this->month, $this->year);
			 
			return $HTMLCalendarext;
		}
		 
		/**
		* Génération de l'include de la feuille de style du module
		*
		* @return string $includeCSS
		*/
		function includeCSS() {
			$includeCSS = '
				<link rel="stylesheet" type="text/css" href="'.$this->pathModuleCalendar.'css/style.css" />
				';
			 
			return $includeCSS;
		}
		 
		/**
		* Génération des variables javascript du module
		*
		* @return string $variablesJS
		*/
		function variablesJS() {
			$variablesJS = '
				<!-- variables JS Pelican_Calendar  -->
				<script type="text/javascript">
				var nameModule = "'.$this->nameModuleCalendar.'";
				var pathModule = "'.$this->pathModuleCalendar.'";
				</script>
				';
			 
			return $variablesJS;
		}
		 
		/**
		* Génération d'une variable javascript particulière
		* @ param string $nameVariable
		* @ param string $valueVariable
		*
		* @return string $variableSpecificJS
		*/
		function variableSpecificJS($nameVariable, $valueVariable) {
			$variableSpecificJS = '
				<!-- variable JS particuliere  -->
				<script type="text/javascript">
				var '.$nameVariable.' = "'.$valueVariable.'";
				</script>
				';
			 
			return $variableSpecificJS;
		}
		 
		/**
		* Génération de l'include des .js
		* - .js prototype
		* - .js scriptaculous
		* - .js module
		* - .js controles Pelican_Form
		*
		* @return string $includesJS
		*/
		function includesJS() {
			
			 
			$includesJS = '
				<!-- JS prototype  -->
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].'/js/prototype.js"></script>
				<!-- JS pour le drag and drop  -->
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].'/js/scriptaculous/scriptaculous.js"></script>
				<!-- JS pour le caklendarext  -->
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_DATE'].'/js/calendarext.js"></script>
				<!-- JS spécifique du module  -->
				<script type="text/javascript" src="'.$this->pathModuleCalendar.'js/'.$this->nameModuleCalendar.'.js"></script>
				<!-- JS pour les controles de formulaire  -->
				<script type="text/javascript">
				var dateLanguageFormat=\'DD/MM/YYYY\';
				var libDir = \''.Pelican::$config["LIB_PATH"].'\';
				</script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].'/Pelican/Translate/public/js/language.js.php" type="text/javascript"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_text_controls.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_date_controls.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_calendar_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_popup_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_crosstab_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_list_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_ordered_list_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_popup_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_sub_fonctions.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_num_controls.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_text_controls.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/xt_toggle.js"></script>
				<script type="text/javascript" src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/editor/js/codesweep.js"></script>
				';
			 
			return $includesJS;
		}
		 
		/**
		* Génération de l'include d'un .js spécifique
		*@param string pathJS
		*
		* @return string $includesJS
		*/
		function includeSpecificJS($pathJS) {
			 
			$includesJS = '
				<!-- JS spécifique du module  -->
				<script type="text/javascript" src="'.$pathJS.'"></script>
				';
			 
			return $includesJS;
		}
		 
	}
	 
?>