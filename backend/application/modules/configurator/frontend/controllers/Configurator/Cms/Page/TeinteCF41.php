<?php

include_once Pelican::$config['PLUGIN_ROOT'].'/configurator/library/Configurator.php';

class Configurator_Cms_Page_TeinteCF41_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $head = $this->getView()->getHead();
        if ($this->isMobile()) {
            $folder = 'AC/mobile/';

            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'css/main.css');
            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/slick/slick.css');
            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/slick/slick-theme.css');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/jquery/dist/jquery.js');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/core.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/main.js');
            $head->setJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.popin.js');
            $head->setJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/scroll.js');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/slick-carousel/slick/slick.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/iscroll/build/iscroll.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/classie/classie.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/ModalEffects/js/modalEffects.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/modernizer/modernizr.js');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/pubsub.js');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.ajax.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.handlers.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.response.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.load.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.toggle.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.slick.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.iscroll.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/slicebis.js');

            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice09bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice14bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice17bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice41bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice42bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice53bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice58bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice88bis.js');
            $head->setJS(Pelican_Plugin::getMediaPath('configurator').$folder.'js/slices/slice98bis.js');
        } else {
            $folder = 'AC/desktop/';

            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'css/main.css');
            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'css/main-kit.css');
            $head->setCss(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/slick-carousel/slick/slick.css');

            $head->setJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/modernizer/modernizr.js');

            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/jquery/dist/jquery.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/jquery-selectBox/jquery.selectBox.min.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/underscore/underscore-min.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/slick-carousel/slick/slick.min.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/jquery-lazy/jquery.lazy.min.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/js-polyfills/typedarray.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'bower_components/three.js/three.min.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/pubsub.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.expand.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.expandLame.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/core.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/main.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/video-js.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/forms.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.saveconfig.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.accordeonDs.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/comparatorTable.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/monteeGamme.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso-FormSaveConfig.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.toggle.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.popin.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.form.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/scroll.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/iso.infobulle.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/common/lames.js');

            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/Projector.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/CanvasRenderer.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/f02.sim.namespace.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/f02.sim.pointofinterest.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/f02.sim.cube.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/f02.sim.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/f02/f02.js');

            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cn14.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cc87.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf9.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf53.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf57.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf58.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf91.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cc97.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf65.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf43.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf42.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf56.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cf61.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cc88.js');
            $head->endJs(Pelican_Plugin::getMediaPath('configurator').$folder.'js/tranches/cc90.js');
        }

        //$paramsGlocalTmp = Pelican_Cache::fetch('GeneralConfiguration', array(), '', 'configurator');
        $paramsGlocal = array('DEVISE_PAYS' => '€', 'DEVISE_CODE' => 'EUR', 'ACTIVATION_PRIX_MENSUALISE' => false, 'ACTIVATION_PRIX_COMPTANT' => true, 'TYPE_TAXE' => 'TTC');

        $paramsWS = array(
            'client' => ($_GET['client'])?$_GET['client']:'websimulator',
            'brandId' => ($_GET['brand'])?$_GET['brand']:'AC',
            'date' => ($_GET['date'])?$_GET['date']:'2014-11-05',
            'country' => ($_GET['country'])?$_GET['country']:'FR',
            'language' => ($_GET['lang'])?$_GET['lang']:'fr',
            'lang' => ($_GET['lang'])?$_GET['lang']:'fr',
            'tariffCode' => ($_GET['tariffCode'])?$_GET['tariffCode']:'TC',
            'taxIncluded' => ($_GET['taxIncluded'])?$_GET['taxIncluded']:'true',
            'professionalUse' => ($_GET['professionalUse'])?$_GET['professionalUse']:'false'
        );
        $angleView = ($_GET['view'])?$_GET['view']:'001';


        $grbodystyle = ($_GET['grbodystyle'])?$_GET['grbodystyle']:'00000001';
        $idVersions = Configurator::getVersionsFromGRBodystyle($grbodystyle, $paramsWS);
        $idVersionPreSelect = ($_GET['lcdv16'])?$_GET['lcdv16']:$idVersions[rand(0, count($idVersions)-1)]; //from GDV

        $versionData = Configurator::getVersionData($idVersionPreSelect, $paramsWS, true);
        $data = Configurator::getTeintesData($idVersionPreSelect, $paramsWS);

        if (is_array($data['teintesCompatibles']) && count($data['teintesCompatibles']) > 0) {
            foreach($data['teintesCompatibles']['0M'] as $id=>$d) {
                if($_GET['color']) {
                      if ($d['id'] == $_GET['color']) {
                          $idPreSelect = $id;
                      }
                } else {
                    $idPreSelect = '2';
                }
            }
        }

        //$mentionsLegales = Configurator::getMLData($versionData, $paramsWS);

            /* WS en REST
            $serviceParams2 = array(
            'ranges' => 'VP',
            'brands' => 'C',
            'countries' => 'ES',
            'languages' => 'ES',
            'contexts' => 'E',
        );

        $service2 = \Itkg\Service\Factory::getService('SERVICE_CARS', array());
        $responseXml2 = simplexml_load_string($service2->call('cars', $serviceParams2));
        $responseArray2 = self::objectsIntoArray($responseXml2);*/

        $this->assign('etapeParcoursCookie', $_COOKIE['etapeParcours']);
        $this->assign('nbTeintesCompatibles', $data['nbTeintesCompatibles']);

        $this->assign('teintesCompatibles', $data['teintesCompatibles']);
        $this->assign('teintesIncompatibles', $data['teintesIncompatibles']);
        $this->assign('idPreSelect', $idPreSelect);
        $this->assign('angleView', $angleView);
        $this->assign('idVersionPreSelect', $idVersionPreSelect);
        $this->assign('idModelPreSelect', $data['idModelPreSelect']);
        $this->assign('idBodystylePreSelect', $data['idBodystylePreSelect']);
        $this->assign('paramsGlocal', $paramsGlocal);
        $this->fetch();
    }
}
