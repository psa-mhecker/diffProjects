<?php
class Layout_Model_Flash_Coverflow_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		$this->setParam ( 'ZONE_TITRE', 'Coverflow' );
		
	        if (! empty(Pelican_Request::$userAgentFeatures)) {
            if (Pelican_Request::$userAgentFeatures['flash'] && ! Pelican_Request::$userAgentFeatures['flash']) {
                $xml = simplexml_load_file(Pelican::$config['LIB_ROOT'] . '/External/flashxml/cover-flow/images.xml');
                $i = 0;
                foreach ($xml as $image) {
                    $data[$i]['lib'] = (string) $image;
                    $data[$i]['url'] = (string) $image['url'];
                    $data[$i]['image'] = Pelican::$config['MEDIA_HTTP'] . Pelican::$config['LIB_PATH'] . '/External/flashxml/cover-flow/' . ((string) $image['bigimage']);
                    $i ++;
                }
                $this->assign("img", $data);
            }
        }
		
	
		$this->model ();
		$this->fetch ();
	}
}  


