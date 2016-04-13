<?php

class Layout_Model_Flash_Carrousel_Controller extends Pelican_Controller_Front
{
    public function indexAction ()
    {
        $this->setParam('ZONE_TITRE', t('Carousel'));
        if (! empty(Pelican_Request::$userAgentFeatures)) {
            if (isset(Pelican_Request::$userAgentFeatures['flash']) && ! Pelican_Request::$userAgentFeatures['flash']) {
                $xml = simplexml_load_file(Pelican::$config['LIB_ROOT'] . '/External/flashxml/3d-carousel-menu/images.xml');
                $i = 0;
                foreach ($xml as $image) {
                    $data[$i]['lib'] = (string) $image;
                    $data[$i]['url'] = (string) $image['url'];
                    $data[$i]['image'] = Pelican::$config['MEDIA_HTTP'] . Pelican::$config['LIB_PATH'] . '/External/flashxml/3d-carousel-menu/' . ((string) $image['image']);
                    $i ++;
                }
                $this->assign("img", $data);
            }
        }
        
        $this->model();
        $this->fetch();
    }
}  