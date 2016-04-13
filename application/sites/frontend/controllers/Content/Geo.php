<?php
include_once (Pelican::$config ['APPLICATION_CONTROLLERS'] . '/Content.php');

class Content_Geo_Controller extends Content_Controller {
	
	public function indexAction() {
		
		if (! empty ( Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_ID'] )) {
			$head = $this->getView ()->getHead ();
			$head->setJs ( str_replace ( '#KEY#', Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_KEY'], Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_SCRIPT'] ) );
			$head->setJs ( '/library/External/mymap/mymap_' . Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_CODE'] . '.js' );
		}
		$this->assign ( "map", Pelican_Request::$userAgentFeatures ['map'] );
		
		$appid = '';
		$key = Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_KEY'];
		$tmp = explode ( '##', Pelican::$config ["SITE"] ["INFOS"] ['MAP_PROVIDER_KEY'] );
		if (count ( $tmp ) > 1) {
			$appid = $tmp [0];
			$key = $tmp [1];
		}
		$this->assign ( 'map_appid', $appid );
		$this->assign ( 'map_key', $key );
		parent::indexAction ();
	}
}