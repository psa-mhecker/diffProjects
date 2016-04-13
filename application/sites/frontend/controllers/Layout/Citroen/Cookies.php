<?php
class Layout_Citroen_Cookies_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams(); 
        $this->assign('aData', $aData);  
        $this->assign('url', $_SERVER["HTTP_REFERER"]);  

            $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
            ));

            /*
             *  Utilisation des cookies
             */
            $aCookies = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['COOKIE'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));

         $this->assign("TYPE_COOKIE", $aCookies["ZONE_PARAMETERS"]);
         $this->assign("displayBtn", Backoffice_Cookie_Helper::getCookie('USING_COOKIES'));
        $this->fetch();
    }
    
    /**
     * Méthode permettant d'accepter les cookies sur le site, l'Ajax modifie des
     * données de session indiquant que l'utilisateur à accepter les cookies et qu'il
     * n'est plus nécessaires d'afficher le bandeau d'information
     */
    public function acceptcookiesAction()
    {
        /* Cookies acceptés */
        $_SESSION[APP]['USE_COOKIES'] = true;
        /* Masquage du bandeau d'informations */
        $_SESSION[APP]['SHOW_COOKIES_LAYER'] = false;
        Backoffice_Cookie_Helper::setCookie('USING_COOKIES' , true , time() + (10 * 365 * 24 * 60 * 60));
    }
}
?>
