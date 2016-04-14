<?php

// include_once(Pelican::$config['PLUGIN_ROOT'] . '/configurator/conf/services.ini.php');
include_once Pelican::$config['PLUGIN_ROOT'].'/configurator/conf/configurator.ini.php';

/*
include_once(Pelican::$config['PLUGIN_ROOT'] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config['PLUGIN_ROOT'] . '/boforms/conf/local.ini.php');
include (Pelican::$config['PLUGIN_ROOT'] . '/boforms/library/JiraUtil.php');
include (Pelican::$config['PLUGIN_ROOT'] . '/boforms/library/FunctionsUtils.php');
*/

/**
 * - load
 * - install
 * - uninstall
 * Appel au Pelican_Cache avec id du plugin.
 */
class Module_Configurator extends Pelican_Plugin
{
    /**
     * Defintion de constantes ou traitements d'initialisation du plugin au chargement.
     */
    public function load()
    {
    }

    /**
     * a lancer lors de l'installation du plugin :
     * - insertion de donnees
     * - creation d'une table
     * - creation de repertoires etc...
     */
    public function install()
    {
        $oConnection = Pelican_Db::getInstance();

        $oConnection->query('CREATE TABLE IF NOT EXISTS `#pref#_configurator_general_configuration` (
                              `CONF_KEY` varchar(255) collate utf8_swedish_ci NOT NULL,
                              `CONF_SITE_ID` int(11) NOT NULL,
                              `CONF_VALUE` varchar(255) collate utf8_swedish_ci NOT NULL,
                              PRIMARY KEY  (CONF_KEY, CONF_SITE_ID)
                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci ;
        ');

        // =============== TRADUCTION VARIABLES CONFIGURATION GENERALE EN BO ==============

        $sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_BO`) VALUES
             ('CONFIGURATOR_CONFIGURATION_SAVE', NULL, 1),
             ('CONFIGURATOR_CURRENCY', NULL, 1),
             ('CONFIGURATOR_CONFIGURATOR', NULL, 1),
             ('CONFIGURATOR_CONFIG_GAL_SAVED', NULL,1),
             ('CONFIGURATOR_GENERAL_CONFIGURATION', NULL,1),
             ('CONFIGURATOR_CHOOSE_PRICE_DISPLAY', NULL, 1),
             ('CONFIGURATOR_COST_PRICE', NULL,1),
             ('CONFIGURATOR_MONTHLY_PAYMENT', NULL,1);";

        $sql[] = "REPLACE INTO `#pref#_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
             ('CONFIGURATOR_CONFIGURATION_SAVE',  1, 'Sauver la configuration' , ''),
             ('CONFIGURATOR_CONFIGURATION_SAVE',  2, 'Save configuration' , ''),
             ('CONFIGURATOR_CURRENCY',  1, 'Code de la devise (trois lettres)' , ''),
             ('CONFIGURATOR_CURRENCY',  2, 'Currency code (three letters)' , ''),
             ('CONFIGURATOR_CONFIGURATOR',  1, 'Configurator - configuration' , ''),
             ('CONFIGURATOR_CONFIGURATOR',  2, 'Configurator - configuration' , ''),
             ('CONFIGURATOR_CONFIG_GAL_SAVED',  1, 'Formulaire sauvegardé avec succès' , ''),
             ('CONFIGURATOR_CONFIG_GAL_SAVED',  2, 'Form saved successfully' , ''),
             ('CONFIGURATOR_GENERAL_CONFIGURATION',  1, 'Configurator - paramètres de configuration' , ''),
             ('CONFIGURATOR_GENERAL_CONFIGURATION',  2, 'Configurator - configuration parameters' , ''),
             ('CONFIGURATOR_CHOOSE_PRICE_DISPLAY',  1, 'Affichage du prix' , ''),
             ('CONFIGURATOR_CHOOSE_PRICE_DISPLAY',  2, 'Price display mode' , ''),
             ('CONFIGURATOR_COST_PRICE',  1, 'Prix comptant' , ''),
             ('CONFIGURATOR_COST_PRICE',  2, 'Cost price' , ''),
             ('CONFIGURATOR_MONTHLY_PAYMENT',  1, 'Prix par mois' , ''),
             ('CONFIGURATOR_MONTHLY_PAYMENT',  2, 'Price by month' , '');";

        // =============== TRADUCTION TRANCHE DF 41 ===================

        $sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_FO`) VALUES
             ('CONFIGURATOR_DF41_LOOK_FEATURE_0M', NULL,1),
             ('CONFIGURATOR_DF41_LOOK_FEATURE_0P', NULL,1),
             ('CONFIGURATOR_DF41_LOOK_FEATURE_DEFAULT', NULL,1),
             ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION', NULL,1),
             ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN', NULL,1),
             ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLOR', NULL, 1),
             ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS', NULL,1),
             ('CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX', NULL,1),
             ('CONFIGURATOR_DF41_COLOR_MORE_DETAILS', NULL,1),
             ('CONFIGURATOR_DF41_BADGE_NEW', NULL,1),
             ('CONFIGURATOR_DF41_PRICE_INCLUDED', NULL,1),
             ('CONFIGURATOR_DF41_PRICE_FROM', NULL,1),
             ('CONFIGURATOR_DF41_ROOF_AVALAIBLE', NULL,1),
             ('CONFIGURATOR_DF41_CAR_BASE_COLOR', NULL,1),
             ('CONFIGURATOR_DF41_ROOF_COLOR', NULL,1),
             ('CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN', NULL,1),
             ('CONFIGURATOR_DF41_OTHER_EXISTING_COLORS', NULL,1),
             ('CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR', NULL,1);";


                $sql[] = "REPLACE INTO `#pref#_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
             ('CONFIGURATOR_DF41_LOOK_FEATURE_0M',  1, 'Peinture métalisée' , ''),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0M',  2, 'Metallic paint' , ''),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0P',  1, 'Peinture nacrée' , ''),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0P',  2, 'Matt paint' ,  ''),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_DEFAULT',  1, 'Autres peintures', ''),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_DEFAULT',  2, 'Other paints' , ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION',  1, 'Le modèle présenté est équipé de la teinte ##TEINTE##. Personnalisez-le en sélectionnant la teinte de votre choix.' , ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION',  2, 'The model is equipped with the color ##TEINTE##. Personalize it by choosing the color of your choice.' ,  ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN',  1, 'VOIR' , ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN',  2, 'WATCH' , ''),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLOR', 1, 'TEINTE DISPONIBLE', ''),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLOR', 1, 'AVAILABLE COLOR', ''),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS',  1, 'TEINTES DISPONIBLES' , ''),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS',  2, 'AVALAIBLE COLORS' , ''),
            ('CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX', 1, 'sélectionner' , ''),
            ('CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX',  2, 'choose' ,  ''),
            ('CONFIGURATOR_DF41_COLOR_MORE_DETAILS',  1, ' en détails' , ''),
            ('CONFIGURATOR_DF41_COLOR_MORE_DETAILS',  2, ' more details', ''),
            ('CONFIGURATOR_DF41_BADGE_NEW',   1, 'Nouveau' , ''),
            ('CONFIGURATOR_DF41_BADGE_NEW',  2, 'New' , ''),
            ('CONFIGURATOR_DF41_PRICE_INCLUDED',  1, 'Inclus' , ''),
            ('CONFIGURATOR_DF41_PRICE_INCLUDED',  2, 'Included' , ''),
            ('CONFIGURATOR_DF41_PRICE_FROM',  1, 'A partir de ##PRICE## €' , ''),
            ('CONFIGURATOR_DF41_PRICE_FROM',  2, 'From ##PRICE## €' , ''),
            ('CONFIGURATOR_DF41_ROOF_AVALAIBLE',  1, 'Toit disponible' , ''),
            ('CONFIGURATOR_DF41_ROOF_AVALAIBLE',  2, 'Roof available' , ''),
            ('CONFIGURATOR_DF41_CAR_BASE_COLOR',  1, 'Couleur de caisse' , ''),
            ('CONFIGURATOR_DF41_CAR_BASE_COLOR',  2, 'Car base color' , ''),
            ('CONFIGURATOR_DF41_ROOF_COLOR',  1, 'Couleur de toît' , ''),
            ('CONFIGURATOR_DF41_ROOF_COLOR',  2, 'Roof color' , ''),
            ('CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN',  1, 'selectionner' , ''),
            ('CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN',  2, 'choose' , ''),
            ('CONFIGURATOR_DF41_OTHER_EXISTING_COLORS',  1, 'autres teintes existantes' , ''),
            ('CONFIGURATOR_DF41_OTHER_EXISTING_COLORS',  2, 'other existing colors' , ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR',  1, 'Cette couleur est incompatible ...' , ''),
            ('CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR',  2, 'Incompatible color detected ...' , '');";

        $sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0M',  1, 'Peinture métalisée' , 3),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0M',  2, 'Metallic paint' , 3),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0P',  1, 'Peinture nacrée' , 3),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_0P',  2, 'Matt paint' ,  3),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_DEFAULT',  1, 'Autres peintures', 3),
            ('CONFIGURATOR_DF41_LOOK_FEATURE_DEFAULT',  2, 'Other paints' , 3),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION',  1, 'Le modèle présenté est équipé de la teinte ##TEINTE##. Personnalisez-le en sélectionnant la teinte de votre choix.' , 3),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION',  2, 'The model is equipped with the color ##TEINTE##. Personalize it by choosing the color of your choice.' ,  3),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN',  1, 'VOIR' , 3),
            ('CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN',  2, 'WATCH' , 3),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS',  1, 'TEINTES DISPONIBLES' , 3),
            ('CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS',  2, 'AVALAIBLE COLORS' , 3),
            ('CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX', 1, 'sélectionner' , 3),
            ('CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX',  2, 'choose' ,  3),
            ('CONFIGURATOR_DF41_COLOR_MORE_DETAILS',  1, ' en détails' , 3),
            ('CONFIGURATOR_DF41_COLOR_MORE_DETAILS',  2, ' more details', 3),
            ('CONFIGURATOR_DF41_BADGE_NEW',   1, 'Nouveau' , 3),
            ('CONFIGURATOR_DF41_BADGE_NEW',  2, 'New' , 3),
            ('CONFIGURATOR_DF41_PRICE_INCLUDED',  1, 'Inclus' , 3),
            ('CONFIGURATOR_DF41_PRICE_INCLUDED',  2, 'Included' , 3),
            ('CONFIGURATOR_DF41_PRICE_FROM',  1, 'A partir de ##PRICE## €' , 3),
            ('CONFIGURATOR_DF41_PRICE_FROM',  2, 'From ##PRICE## €' , 3),
            ('CONFIGURATOR_DF41_ROOF_AVALAIBLE',  1, 'Toit disponible' , 3),
            ('CONFIGURATOR_DF41_ROOF_AVALAIBLE',  2, 'Roof available' , 3),
            ('CONFIGURATOR_DF41_CAR_BASE_COLOR',  1, 'Couleur de caisse' , 3),
            ('CONFIGURATOR_DF41_CAR_BASE_COLOR',  2, 'Car base color' , 3),
            ('CONFIGURATOR_DF41_ROOF_COLOR',  1, 'Couleur de toît' , 3),
            ('CONFIGURATOR_DF41_ROOF_COLOR',  2, 'Roof color' , 3),
            ('CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN',  1, 'selectionner' , 3),
            ('CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN',  2, 'choose' , 3),
            ('CONFIGURATOR_DF41_OTHER_EXISTING_COLORS',  1, 'autres teintes existantes' , 3),
            ('CONFIGURATOR_DF41_OTHER_EXISTING_COLORS',  2, 'other existing colors' , 3),
            ('CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR',  1, 'Cette couleur est incompatible ...' , 3),
            ('CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR',  2, 'Incompatible color detected ...' , 3);";

        // =========== TRADUCTION TRANCHE CF41 ===============

        $sql[] = "REPLACE INTO `#pref#_label` (`LABEL_ID`, `LABEL_INFO`,`LABEL_FO`) VALUES
        ('CONFIGURATOR_CF41_NOUS_VOUS_PRESENTONS_LA_FINITION', NULL,1),
        ('CONFIGURATOR_CF41_AVEC_LA_TEINTE', NULL,1),
        ('CONFIGURATOR_CF41_VOUS_POUVEZ_SELECTIONNER_UNE_AUTRE_TEINTE', NULL,1),
        ('CONFIGURATOR_CF41_TEINTES_DISPONIBLES', NULL,1),
        ('CONFIGURATOR_CF41_TEINTE_DISPONIBLE', NULL,1),
        ('CONFIGURATOR_CF41_CHOISIR', NULL,1),
        ('CONFIGURATOR_CF41_A_PARTIR_DE', NULL,1),
        ('CONFIGURATOR_CF41_INCLUS', NULL,1),
        ('CONFIGURATOR_CF41_MOIS', NULL,1),
        ('CONFIGURATOR_CF41_PLUS', NULL,1),
        ('CONFIGURATOR_CF41_DE', NULL,1),
        ('CONFIGURATOR_CF41_DETAILS', NULL,1),
        ('CONFIGURATOR_CF41_MASQUER', NULL,1),
        ('CONFIGURATOR_CF41_AUTRES_TEINTES', NULL,1),
        ('CONFIGURATOR_CF41_SEE_AUTRES_TEINTES', NULL,1);";

        $sql[] = "REPLACE INTO `#pref#_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
        ('CONFIGURATOR_CF41_NOUS_VOUS_PRESENTONS_LA_FINITION',  2, 'we present the finish', ''),
        ('CONFIGURATOR_CF41_AVEC_LA_TEINTE',  2, 'with exterior feature', ''),
        ('CONFIGURATOR_CF41_VOUS_POUVEZ_SELECTIONNER_UNE_AUTRE_TEINTE',  2, 'You can select another exterior feature', ''),
        ('CONFIGURATOR_CF41_TEINTES_DISPONIBLES',  2, 'Available exterior features', '3'),
        ('CONFIGURATOR_CF41_TEINTE_DISPONIBLE',  2, 'Available exterior feature', ''),
        ('CONFIGURATOR_CF41_CHOISIR',  2, 'Choose', ''),
        ('CONFIGURATOR_CF41_A_PARTIR_DE',  2, 'From', ''),
        ('CONFIGURATOR_CF41_INCLUS',  2, 'Included', ''),
        ('CONFIGURATOR_CF41_MOIS',  2, 'month', ''),
        ('CONFIGURATOR_CF41_PLUS',  2, 'More', ''),
        ('CONFIGURATOR_CF41_DE',  2, '', ''),
        ('CONFIGURATOR_CF41_DETAILS',  2, 'details', ''),
        ('CONFIGURATOR_CF41_MASQUER',  2, 'Hide', ''),
        ('CONFIGURATOR_CF41_AUTRES_TEINTES',  2, 'Other exterior features', ''),
        ('CONFIGURATOR_CF41_SEE_AUTRES_TEINTES',  2, 'See other exterior features', '')";

        $sql[] = "REPLACE INTO `#pref#_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `SITE_ID`) VALUES
        ('CONFIGURATOR_CF41_NOUS_VOUS_PRESENTONS_LA_FINITION',  2, 'we present the finish' , 3),
        ('CONFIGURATOR_CF41_AVEC_LA_TEINTE',  2, 'with exterior feature' , 3),
        ('CONFIGURATOR_CF41_VOUS_POUVEZ_SELECTIONNER_UNE_AUTRE_TEINTE',  2, 'You can select another exterior feature' , 3),
        ('CONFIGURATOR_CF41_TEINTES_DISPONIBLES',  2, 'Available exterior features' , 3),
        ('CONFIGURATOR_CF41_TEINTE_DISPONIBLE',  2, 'Available exterior feature' , 3),
        ('CONFIGURATOR_CF41_CHOISIR',  2, 'Choose' , 3),
        ('CONFIGURATOR_CF41_A_PARTIR_DE',  2, 'From' , 3),
        ('CONFIGURATOR_CF41_INCLUS',  2, 'Included' , 3),
        ('CONFIGURATOR_CF41_MOIS',  2, 'month' , 3),
        ('CONFIGURATOR_CF41_PLUS',  2, 'More' , 3),
        ('CONFIGURATOR_CF41_DE',  2, '' , 3),
        ('CONFIGURATOR_CF41_DETAILS',  2, 'details' , 3),
        ('CONFIGURATOR_CF41_MASQUER',  2, 'Hide' , 3),
        ('CONFIGURATOR_CF41_AUTRES_TEINTES',  2, 'Other exterior features' , 3),
        ('CONFIGURATOR_CF41_SEE_AUTRES_TEINTES',  2, 'See other exterior features' , 3)";

        foreach ($sql as $query) {
            $oConnection->query($query);
        }

    }

    /**
     * a lancer lors de la desinstatllation :
     * - suppression de tables
     * - suppression de donnees.
     */
    public function uninstall()
    {
        $oConnection = Pelican_Db::getInstance();

        $oConnection->query('DROP TABLE `#pref#_configurator_general_configuration`');


        $sql[] = "DELETE FROM `#pref#_label_langue_site` WHERE `LABEL_ID` LIKE 'CONFIGURATOR_%'";
        $sql[] = "DELETE FROM `#pref#_label_langue` WHERE `LABEL_ID` LIKE 'CONFIGURATOR_%'";
        $sql[] = "DELETE FROM `#pref#_label` WHERE `LABEL_ID` LIKE 'CONFIGURATOR_%'";

        foreach ($sql as $query) {
            $oConnection->query($query);
        }
    }
}
