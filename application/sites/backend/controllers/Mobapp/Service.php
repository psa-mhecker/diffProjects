<?php 
class Mobapp_Service_Controller extends Pelican_Controller {
	
public function companiesAction() {
		$sql = "SELECT *,m1.MEDIA_PATH as logo, m2.MEDIA_PATH as banner from #pref#_mobapp_site ms
		inner join #pref#_site s on (s.SITE_ID=ms.SITE_ID)
		left join #pref#_media m1 on (m1.MEDIA_ID=ms.MEDIA_LOGO_ID)
		left join #pref#_media m2 on (m2.MEDIA_ID=MEDIA_BANNER_ID)";
		$oConnection = Pelican_Db::getInstance();
		$result = $oConnection->queryTab($sql, $this->aBind);
		$return['customers'] = array();
		foreach($result as $key=>$company) {
			$return['customers'][$key]['id'] = $company['SITE_ID'];
			$return['customers'][$key]['name'] = $company['MOBAPP_SITE_TITLE'];
			$return['customers'][$key]['logo'] = Pelican::$config['MEDIA_HTTP'].$company['logo'];
			$return['customers'][$key]['banner'] = Pelican::$config['MEDIA_HTTP'].$company['banner'];
			$return['customers'][$key]['description'] = $company['MOBAPP_SITE_TEXT'];
		}
		$this->setResponse(Zend_Json::encode($return));
	}
}

?>