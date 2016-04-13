<?php
//pelican_import('Index.Frontoffice.Zone');
//require_once(pelican_path('External.Smarty'));

// A mettre dans la conf
Pelican::$config["SERVICE_CONTROLLERS_ROOT"] = Pelican::$config['DOCUMENT_ROOT']."/controllers/";
Pelican::$config["SERVICE_SMARTY_ROOT"] = Pelican::$config['DOCUMENT_ROOT']."/views/scripts/";

Pelican::$config ['SITE_ID'] = 2;

Pelican::$config['PT']['ZONE_TEMPLATE_ID']['LOGO'] = 2;
Pelican::$config['PT']['ZONE_TEMPLATE_ID']['HABILLAGE'] = 2132;

Pelican::$config['TEMPLATE_PAGE_ID']['PT']['COURSE'] 				= 15;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_CHEVAL']    		= 32;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_INDIVIDU']   		= 44;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ACTUALITE'] 				= 53;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ACTUALITE_DETAIL']   	= 54;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['HOME'] 					= 55;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['REUNION'] 				= 58;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_PERFORMANCE']  	= 60;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_INSCRIPTION']  	= 61;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['JEU']   					= 63;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ESPACE_PERSO']			= 64;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['CREER_ALERTE']			= 69;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ABONNEMENT']				= 91;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['TELECHARGER_JOURNAL']	= 96;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['PAGE_INSCRIPTION'] 		= 100;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_MDP_OUBLIE'] 		= 101;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_CLASSEMENT'] 		= 105;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['PT_ABO_PROCESS'] 		= 114;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_ALERTE'] 			= 120;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ENREGISTREMENT_ABO'] 	= 121;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['MENTIONS_LEGALES'] 		= 129;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['CHARTE_MODERATION'] 		= 131;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['REGLEMENT_DES_JEUX']		= 133;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['NOUS_CONTACTER']		 	= 134;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['AIDE']		 			= 138;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_PRONOSTICS']		= 142;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_NEWSLETTER']		= 145;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['RECHERCHE_PARTANT']		= 146;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['RECHERCHE_BDD']			= 147;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['DIAPORAMA']				= 151;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['INFO_MODE_PAIEMENT']		= 153;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['CONDITIONS_VENTE']		= 156;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['CONDITIONS_UTILISATION']	= 157;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_MESSAGES'] 		= 159;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_AIDE'] 			= 212;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['NON_AUTORISE'] 			= 163;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['RECHERCHE_EDITO'] 		= 170;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['CONFIRMATION'] 			= 183;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['LAYER_ABO_FIN_SUSPENSION'] 		= 189;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['INFO_MODE_PAIEMENT_CB'] 			= 199;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['INFO_MODE_PAIEMENT_INTERNET'] 	= 200;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['INFO_MODE_PAIEMENT_CHEQUE'] 		= 201;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ANNONCES']				 		= 202;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['ESPACE_PRO'] 					= 203;
Pelican::$config['TEMPLATE_PAGE_ID']['PT']['PARRAINAGE'] 					= 205;

//☺
Pelican::$config['CONTENT_TAG_EDITO_JEUX'] = 39;

/*Constante type jeux*/
Pelican::$config["TYPE_CHAMP_INTERNAUTE"] = 7;

class Pelican_Rest_Server_Result_Html extends Pelican_Rest_Server_Result_Abstract
{
    public function __construct()
    {
        Pelican::$config["SITE_URL"] = "francelibre.dev.paristurf";
        $site = Pelican_Cache::fetch("Frontend/Site/Init", array(Pelican::$config["SITE_URL"], $_SESSION[APP]['LANGUE_ID']));

        $_SESSION[APP]["HOME_PAGE_ID"] = $site["PAGE_ID"];
        $_SESSION[APP]["HOME_PAGE_VERSION"] = $site["PAGE_".Pelican::getPreviewVersion()."_VERSION"];
        $_SESSION[APP]["GLOBAL_PAGE_ID"] = $site["NAVIGATION_ID"];
        $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"]?$site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"]:1);
        if ($site["PARAMETERS"]) {
            foreach ($site["PARAMETERS"] as $key=>$param) {
                $_SESSION[APP][$key] = $param;
            }
        }

        $_SESSION[APP]['LANGUE_ID'] = $site['LANGUE_ID'];
    }

    public function handle($result)
    {
        error_log(' - Rendu Pelican_Html en cours, template : '.$result['template']);
        $view = Pelican_Factory::getView();

        if (!$view->is_cached($template)) {
            include(Pelican::$config["SERVICE_CONTROLLERS_ROOT"].$result['template'].'.php');
        }
        $output = $view->fetch(Pelican::$config["SERVICE_SMARTY_ROOT"].$result['template'].'.tpl');

        return $output;
    }

}
