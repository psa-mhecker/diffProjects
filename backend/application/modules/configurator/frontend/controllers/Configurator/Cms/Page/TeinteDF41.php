<?php
    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/backend/controllers/Configurator/Administration/Configurator.php');

    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig.php');
    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Configuration.php');
    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Request.php');
    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Response.php');

    include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Configurator.php');


    class Configurator_Cms_Page_TeinteDF41_Controller extends Pelican_Controller_Front
    {
        // tableau contenant les categories de teintes
        private static function getTeintesCategories() {
            // TODO get this data from GDG

            $tbl_categories = array('0M', '0P');

            // gestion categorie par defaut
            array_push($tbl_categories, 'DEFAULT_PAINT');

            return $tbl_categories;
        }

        // tableau contenant les teintes exterieures regroupees par categorie de teinte
        private static function getTeintesFromResponse($responseXml, $tbl_category_exterior_features) {
            $tbl_result = array();
            $lookFeatures = $responseXml['LookFeatures']['ExteriorFeatures'];

            // TODO remove this lines
            $lookFeatures[2]['classe'] = '0P';
            $lookFeatures[3]['classe'] = '0P';
            $lookFeatures[5]['classe'] = 'TEST';

            // peintures connues
            for ($i = 0; $i < count($lookFeatures); $i++) {
                $look_feature_category = $lookFeatures[$i]['classe'];
                if (in_array($look_feature_category, $tbl_category_exterior_features)) {
                    $tbl_result[$look_feature_category][] = $lookFeatures[$i];
                }
            }

            // peintures inconnues
            if (in_array('DEFAULT_PAINT', $tbl_category_exterior_features)) {
                for ($i = 0; $i < count($lookFeatures); $i++) {
                    $look_feature_category = $lookFeatures[$i]['classe'];
                    if (! in_array($look_feature_category, $tbl_category_exterior_features)) {
                        $tbl_result['DEFAULT'][] = $lookFeatures[$i];
                    }
                }
            }

            return $tbl_result;
        }

        private static function getTeintes($idVersionPreSelect, $withDefault) {
            $params = array(
                'client' => 'NDP',
                'brandId' => 'P',
                'date' => '2014-11-05',
                'country' => 'FR',
                'tariffCode' => 'TC',
                'taxIncluded' => 'true',
                'professionalUse' => 'false',
            );

            return Configurator::getTeintesData($idVersionPreSelect, $params, $withDefault);
        }

        private function getDefaultTeinte($data, $idPreSelect) {
            $result = null;
            $first_seen = null;
            foreach($data as $category => $datas) {
                for ($i = 0; $i < count($datas); $i++) {
                    if ($first_seen == null) {
                        $first_seen = $datas[$i];
                    }

                    if ($datas[$i]['id'] == $idPreSelect) {
                        $result = $datas[$i];
                        break;
                    }
                }

                if ($result == null) {
                    return $first_seen;
                } else {
                    return $result;
                }
            }

            $thekey = $idPreSelect;
            if (!isset($data['teintesCompatibles'][$idPreSelect])) {
                foreach($data['teintesCompatibles'] as $key => $value) {
                    $thekey = $key;
                    break;
                }
            }
            print_r($data['teintesCompatibles'][$thekey]);
            return $data['teintesCompatibles'][$thekey];
        }

        private function formatTeintes($teintes) {
            $tbl_result  = array();
            foreach($teintes as $key => $teinte_data) {
                $category = substr($key, 0, 2);
                $tbl_result[$category][] = $teinte_data;
            }
            return $tbl_result;
        }

        private function getDisplayAngles() {
            $tbl_result = array();
            array_push($tbl_result, '001', '002', '003', '004', '005', '006', '007', '008', '009');
            return $tbl_result;
        }

        public function indexAction()
        {
            if ($this->isMobile()) {
                $this->assign('path_resources_ds', Pelican::$config['MEDIA_HTTP'] . '/modules/configurator/DS/mobile');
            } else {
                $this->assign('path_resources_ds', Pelican::$config['MEDIA_HTTP'] . '/modules/configurator/DS/desktop');
            }

            $this->assign('module_template_root', Pelican::$config["PLUGIN_ROOT"] . '/configurator/frontend/views/scripts/Configurator/Cms/Page/TeinteDF41/');

            //$this->assign('displayAngles', $this->getDisplayAngles());

            $grbodystyle =  ($_GET['grbodystyle'])?$_GET['grbodystyle']:'00000001';
            $idVersions = Configurator::getVersionsFromGRBodystyle($grbodystyle, $paramsWS);
            $idVersionPreSelect = ($_GET['lcdv16'])?$_GET['lcdv16']:$idVersions[rand(0, count($idVersions)-1)]; //from GDV
            $idPreSelect = ($_GET['color'])?$_GET['color']:'0MM00NE4'; //'0MM00N9B';
            $angleView = ($_GET['view'])?$_GET['view']:'001';

            $withDefault = true; // regroupement des categories de teintes non connues dans une rubrique DEFAULT

            $data = self::getTeintes($idVersionPreSelect, true);
            $paramsGlocal = array('DEVISE_PAYS' => '€', 'DEVISE_CODE' => 'EUR', 'ACTIVATION_PRIX_MENSUALISE' => false, 'ACTIVATION_PRIX_COMPTANT' => true, 'TYPE_TAXE' => 'TTC');

            $this->assign('paramsGlocal', $paramsGlocal);
            $this->assign('nbTeintes', $data['nbTeintesCompatibles']);
            $this->assign('teintesCompatibles', $data['teintesCompatibles']);
            //$this->assign('teintesIncompatibles',  array('0M' =>$data['teintesIncompatibles']));

            $this->assign('teintesIncompatibles',  $data['teintesIncompatibles']); // TODO remove this line
            $this->assign('idPreSelect', $idPreSelect);
            $this->assign('idVersionPreSelect', $idVersionPreSelect);
            $this->assign('angleView', $angleView);
            $this->assign('idModelPreSelect', $data['idModelPreSelect']);
            $this->assign('idBodystylePreSelect', $data['idBodystylePreSelect']);
            $this->assign('biton' , $data['biton']);

            $this->assign('base_url_v3d', 'http://visuel3d.citroen.com/V3DCentral/' . $data['idModelPreSelect'] . '/' . $data['idBodystylePreSelect'] . '/ThumbnailsV2/Colors/th_');
            $this->assign('base_url_v3d_image', 'http://visuel3d.citroen.com/V3DImage.ashx?height=228&ratio=1&format=jpg&quality=90&trim=01&back=2&width=403&version=' . $idVersionPreSelect);
            $this->assign('base_url_v3d_small_image', 'http://visuel3d.citroen.com/V3DImage.ashx?height=113&ratio=1&format=jpg&quality=90&trim=01&back=2&width=200&version=' . $idVersionPreSelect);
            $this->assign('teinteDefault', $this->getDefaultTeinte($data['teintesCompatibles'], $idPreSelect));

            // label pour la zone de notification
            if ($etatInitial == true) {
                $this->assign('notification_text', 'true');
            }

            $this->fetch();
        }
    }
