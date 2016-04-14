<?php
// use Citroen\Event;
// use Symfony\Component\EventDispatcher\EventDispatcher;

pelican_import('Profiler');
require_once pelican_path('Layout');
require_once pelican_path('Translate');

class Index_Controller extends Pelican_Controller_Front
{
    protected $_layout;

    protected $_dispatcher;

    public function __construct(Pelican_Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @return the $_layout
     */
    public function getLayout()
    {
        if (empty($this->_layout)) {
            $this->_layout = Pelican_Factory::getInstance('Layout');
        }

        return $this->_layout;
    }

    /**
     * @param field_type $_layout
     */
    public function setLayout($_layout)
    {
        $this->_layout = $_layout;
    }

    public function indexAction()
    {
        if (!empty($_GET['lang']) && !empty($_GET['site'])) {
            $_SESSION[$_SERVER['HTTP_HOST']]['LANGUE_ID'] = $_GET['lang'];
            $_SESSION[$_SERVER['HTTP_HOST']]['SITE_ID'] = $_GET['site'];
        }

        $aParams = $this->getParams();

        // RÃ©cupÃ©ration des informations du site
        $aSite = Pelican_Cache::fetch("Frontend/Site", array(
            $_SESSION[APP]['SITE_ID'],
        ));

        // profiling
        Pelican_Profiler::start('header', 'page');

        // head
        $head = $this->getView()->getHead();

        // chargement jquery pour le choix du device

        // layout
        $layout = $this->getLayout();

        // Site initialisation
        $layout->getInfos();
        $layout->getMetaTag();

        $output['SITE']['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $output['SITE']['CODE_PAYS'] = $_SESSION[APP]['CODE_PAYS'];
        $output['SITE']['HOME_PAGE_ID'] = $_SESSION[APP]['HOME_PAGE_ID'];
        $output['SITE']['HOME_PAGE_VERSION'] = $_SESSION[APP]['HOME_PAGE_VERSION'];
        $output['SITE']['GLOBAL_PAGE_ID'] = $_SESSION[APP]['GLOBAL_PAGE_ID'];
        $output['SITE']['GLOBAL_PAGE_VERSION'] = $_SESSION[APP]['GLOBAL_PAGE_VERSION'];
        $output['SITE']['HOME_PAGE_SHORT_URL'] = $_SESSION[APP]['HOME_PAGE_SHORT_URL'];
        $output['SITE']['HOME_PAGE_URL'] = $_SESSION[APP]['HOME_PAGE_URL'];
        $output['SITE'] = array_merge($output['SITE'], $aSite);
        unset($output['SITE']['DNS']);
        unset($output['SITE']['SITE_PERSO_DURATION_PRODUIT_PREFERE']);
        unset($output['SITE']['SITE_PERSO_DURATION_COOKIE']);
        unset($output['SITE']['SITE_MAIL_WEBMASTER']);
        unset($output['SITE']['id']);
        unset($output['SITE']['lib']);

        $output['META'] = $this->getView()->getHead()->meta;

        // $output['PAGE'] = $layout->aPage;

        $layout->getMetaTag();

        $head->setTitle($layout->getPageTitle());

        Pelican_Profiler::stop('header', 'page');

        Pelican_Profiler::start('zones', 'page');
        $layout->pageType = 'orchestra';
        $zone = $layout->getZones();

        $output['PAGE'] = $zone['PAGE'];
        $output['AREAS'] = $zone['AREAS'];

        Pelican_Profiler::stop('zones', 'page');

        Pelican_Profiler::start('fetch', 'page');
        // $body .= $layout->getCybertag();

        // Récupération des informations du site
        $aSite = Pelican_Cache::fetch("Frontend/Site", array(
            $_SESSION[APP]['SITE_ID'],
        ));
        $aPageInfo = $this->getParams();

        if ($this->isMobile()) {
        } else {
        }

        $head->setMetaRobots(Pelican::$config['ROBOTS_SEO_FO'][$layout->aPage['PAGE_META_ROBOTS']]);

        // $this->assign('doctype', $head->getDocType());

        // $aSession = current($_SESSION);
        // $this->assign('lang', $aSession['LANGUE_CODE']);

        $this->assign('body', json_encode($output), false);

        // Génération & transmission du tag HTML Google Tag Manager au template
        // $this->assign('gtmTag', Frontoffice_Analytics_Helper::getGtmTag(), false);

        /*
         * affichage de la vue
         */
        $this->fetch();
        Pelican_Profiler::stop('fetch', 'page');
    }

    public function robotsAction()
    {
        $device = $this->isMobile() ? 'mob' : 'web';
        $file = '/robots_'.$device.'.txt';
        $robotsTxt = @file_get_contents(Pelican::$config["DOCUMENT_INIT"]."/var/robots/".$_SESSION[APP]['CODE_PAYS'].$file);
        $robotsTxt = str_replace('##SITEMAP##', '/sitemap.xml', $robotsTxt);

        header("Content-Type:text/plain");
        echo $robotsTxt;
    }
}
