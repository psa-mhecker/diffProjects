<?php
class Layout_Test2_Controller extends Pelican_Controller_Front {
	
	public function importAction() {
		echo 'cest bon';
                require_once(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/GammeFinition/Gamme.php');
               // require_once(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/GammeFinition/Gamme_Modele.class.php');
                error_reporting(E_ALL ^ E_NOTICE); 
                ini_set('display_errors', '1');
                ini_set('error_log', '/etc/httpd/logs/cppv2_import_csv'.date('Ymd-His').'.log');
                $oGamme = new \Citroen\GammeFinition\Gamme;
//                $oGamme->importAllCSVData();
                $oGamme->getTableIndexes('psa_ws_modele');
                //$oGamme->getExportFormattedData('Gamme_Modele');
             // var_dump($oGamme->getWSData());
	}
}