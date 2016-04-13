<?php

    /**
    *
    * Controller du composant Frontend iGoogle
    * @author rcarles
    *
    */
    class Igoogle_Cms_Page_Bloc_Controller extends Pelican_Controller_Front
    {
        /**
        *
        * Action par d�faut
        */
        public function indexAction ()
        {
            $script = '<script src="' . rawurldecode('http://www.gmodules.com/ig/ifr?');

            $zone = $this->getParams();

            $aParams = unserialize(base64_decode($zone["ZONE_TEXTE"]));

            if (! $aParams && $zone['parameter_url']) {
                foreach ($zone as $key => $value) {
                    if (substr($key, 0, 10) == 'parameter_') {
                        $aParams[substr($key, 10, strlen($key))] = $value;
                    }
                }
            }

            $aParams['title'] = $zone["ZONE_TITRE"];
            $aParams['synd'] = 'open';
            $aParams['output'] = 'js';

            $script .= http_build_query($aParams) . '"></script>';

            $this->assign('script', $script, false);
            $this->assign('aParams', $aParams);
            $this->assign("zone", $zone);
            $this->fetch();
        }
    }
