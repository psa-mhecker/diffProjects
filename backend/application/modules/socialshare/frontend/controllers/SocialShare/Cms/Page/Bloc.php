<?php

    class SocialShare_Cms_Page_Bloc_Controller extends Pelican_Controller_Front
    {
        public function indexAction()
        {
            $this->setParam('ZONE_TITRE', '');

            include Pelican::$config['PLUGIN_ROOT'].'/socialshare/library/SocialShare.php';

            $zone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
            $_SESSION[APP]["GLOBAL_PAGE_ID"],
                1646,
                '',
                $_SESSION[APP]["LANGUE_ID"], ));

            //icones
            $tmp = (array) json_decode($zone["ZONE_TEXTE2"]);
            $aLike = $tmp['LIKE'];
            $aShare = $tmp['SHARE'];

            // global
            if ($zone["ZONE_TEXTE"]) {
                $aSelected = explode('#', $zone["ZONE_TEXTE"]);
            }

            $this->assign("mediaPath", Pelican_Plugin::getMediaPath("socialshare"));
            $this->assign("showLabel", $zone['ZONE_TITRE2']);
            if (is_array($aLike)) {
                $this->assign("like", $aLike);
            } else {
                $this->assign("like", '');
            }
            if (is_array($aShare)) {
                $this->assign("share", $aShare);
            } else {
                $this->assign("share", '');
            }
            if (is_array($aSelected)) {
                $this->assign("prioritize", '"'.implode('","', $aSelected).'"');
            } else {
                $this->assign("prioritize", '');
            }
            $this->assign('data', $zone);
            $this->fetch();
        }
    }
