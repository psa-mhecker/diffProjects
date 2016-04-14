<?php

use Itkg\Utils\FormatHelper;
use PsaNdp\MappingBundle\Object\Block\Pc23Object\StructureManager;
use PsaNdp\MappingBundle\Object\Block\Pc23MurMedia;

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Streamlike.php';


/**
 *
 */
class Cms_Page_Ndp_Pc23MurMedia extends Cms_Page_Ndp
{
    const TYPE_STRUCTURES = 'MUR_MEDIA_STRUCTURES';

    /**
     * @var StructureManager
     */
    protected static $structureManager;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        self::$structureManager = new StructureManager();

        // si un model selectionné en général et pas de config dans la tranche on récupere le showroom correspoondant au model
        if (!empty($controller->pageValues['PAGE_GAMME_VEHICULE']) && empty($controller->zoneValues['ZONE_ATTRIBUT'])) {
            $page = self::getWelcomePageId();
            $controller->zoneValues['ZONE_ATTRIBUT'] = $page;
        }
        /** @var Pelican_View $view */
        $view = Pelican_Factory::newInstance('View');
        $template = Pelican::$config['APPLICATION_VIEWS'].'/Cms/Page/Ndp/Pc23MurMedia/edit.tpl';

        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $areaId = (isset($controller->pageValues['TEMPLATE_PAGE_ID'])) ? self::getDynamicAreaIdByTemplateId($controller->pageValues['TEMPLATE_PAGE_ID']): $controller->pageValues['AREA_ID'];
        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITRE'),
            60,
            'text',
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            60
        );
        $form .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE2',
            t('SOUS_TITRE'),
            60,
            'text',
            false,
            $controller->zoneValues['ZONE_TITRE2'],
            $controller->readO,
            60
        );
        if (empty($controller->zoneValues['ZONE_ATTRIBUT'])) {
         $form .= $controller->oForm->createDescription(t('NDP_MSG_SELECT_SHOWROOM'));
        }
        $form .= $controller->oForm->createComboFromSql(
            self::$con,
            $controller->multi.'ZONE_ATTRIBUT',
            t('SHOWROOM'),
            self::getSqlShowroomPage(),
            $controller->zoneValues['ZONE_ATTRIBUT'],
            true,
            $controller->readO,
            1,
            false,
            250,
            true
        );
        $form .= "<tbody><tr><td class='formlib'></td><td class='formval'>";
        $form .= '</td></tr></tbody>';
        $isZoneDynamique = (int) Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $form .= self::addJs($controller, $isZoneDynamique);
        $medias = self::getMedias($controller, $areaId);
        self::overrideFormats();
        $structuresValues = self::getStructures($controller, $isZoneDynamique, $medias);
        $view->assign('jsMurMedia', Pelican::$config['LIB_PATH']."/public/js/murmedia.js");
        $view->assign('form', $form, false);
        $view->assign('structures', self::$structureManager->getAvailableStructures());
        $view->assign('multi', $controller->multi);
        $view->assign('msgErreurStructureIncomplete', t('NDP_MSG_ERROR_STRUCTURE_INCOMPLETE'));
        $view->assign('msgErreurNoMediasShowroom', t('NDP_MSG_ERROR_NO_MEDIAS_SHOWROOM'));
        $view->assign('msgAlertVideo', t('NDP_MSG_ALERT_VIDEO'));
        $view->assign('msgAlertFormat', t('NDP_MSG_ALERT_FORMAT'));
        $view->assign('medias', htmlspecialchars(json_encode($medias), ENT_QUOTES, 'UTF-8'), false);

        $formats = self::getImageFormats(self::$structureManager->getAvailableStructures());
        $view->assign('formats', $formats, false);
        $view->assign('structuresValues', htmlspecialchars(json_encode($structuresValues), ENT_QUOTES, 'UTF-8'), false);
        $view->assign('jsonFormat', htmlspecialchars(json_encode($formats), ENT_QUOTES, 'UTF-8'), false);
        $return = $view->fetch($template);

        return $return;
    }

    private static function overrideFormats() {
        $mediaHelper = new FormatHelper();
        foreach(self::$structureManager->getAvailableStructures() as $structure)
        {
            $formats = $structure->getFormats();
            foreach($formats as $idx=> $format) {
                if(1 == count($format['size'])) {
                    $info =Pelican_Cache::fetch('Media/MediaFormat', ['MEDIA_FORMAT_LABEL' => $format['size']['default']]);
                    $newSize = [] ;
                    $newSize['label'] = t('DESKTOP_AND_MOBILE');
                    $newSize['formatName'] = $format['size']['default'];
                    $newSize['formatId'] = $info['MEDIA_FORMAT_ID'];
                    $newSize['dim'] = $mediaHelper->getFormatDimension($info);
                    $formats[$idx]['size']['default'] = $newSize;
                } else {
                    foreach($format['size'] as $name=>$size) {
                        $info =Pelican_Cache::fetch('Media/MediaFormat', ['MEDIA_FORMAT_LABEL' => $size]);
                        $newSize = [];
                        $newSize['label'] = t($name);
                        $newSize['formatName'] = $size;
                        $newSize['formatId'] = $info['MEDIA_FORMAT_ID'];
                        $newSize['dim'] = $mediaHelper->getFormatDimension($info);
                        $formats[$idx]['size'][$name] = $newSize;

                    }
                }
            }

            $structure->setImages($formats);
        }
    }

    /**
     * @return null|int
     */
    private static function getWelcomePageId()
    {
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $parentPages = explode('/', $_SESSION[APP]['CURRENT_PAGE_PATH']);
        if ($_SESSION[APP]['PAGE_ID'] != Pelican_db::DATABASE_INSERT_ID) {
            array_pop($parentPages);
        }
        $templatesShowRoom = implode(',', Pelican::$config['TEMPLATE_PAGE_SHOWROOM']);
        $sql = 'SELECT
              p.PAGE_ID
            FROM #pref#_page p
            INNER JOIN #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION =pv.PAGE_VERSION
            WHERE
               pv.TEMPLATE_PAGE_ID  IN ('.$templatesShowRoom.')
               AND p.PAGE_ID IN ('.implode(',', $parentPages).')
               AND p.LANGUE_ID=:LANGUE_ID
               AND p.SITE_ID=:SITE_ID
            LIMIT 0,1';
        $con = Pelican_Db::getInstance();
        $item = $con->queryItem($sql, $bind);

        return $item;
    }
    /**
     * @param array $structures
     *
     * @return array
     */
    public static function getImageFormats(array $structures)
    {
        $return = [];
        /** @var  \PsaNdp\MappingBundle\Object\Block\Pc23Object\StructureInterface $structure */
        foreach ($structures as $structure) {
            $formats = $structure->getFormats();
            foreach ($formats as $format) {
                if (!isset($return[$format['formatId']])) {
                    $return[$format['formatId']] = Pelican_Cache::fetch('Media/MediaFormat', ['MEDIA_FORMAT_LABEL' => $format['formatId']]);
                }
            }
        }

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     * @param $isZoneDynamique
     * @param $medias
     *
     * @return array
     */
    public static function getStructures(Pelican_Controller $controller, $isZoneDynamique, $medias)
    {
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        if (!isset(self::$structureManager)) {
            self::$structureManager = new StructureManager();
        }
        $structuresValues = $multi->setMultiType(Pc23MurMedia::TYPE_MUR_MEDIA)
            ->setMulti($controller->multi)
            ->hydrate($controller->zoneValues)
            ->getValues();

        if (empty($structuresValues)) {
            $structuresValues = self::$structureManager->autoFill($medias);
        }

        return $structuresValues;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$structureManager = new StructureManager();
        $mediaNames = self::$structureManager->getMediaNames();

        self::$con = Pelican_Db::getInstance();
        $oldValues = Pelican_Db::$values;
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        $isZoneDynamique = (int) Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $prefix = '';
        if ($isZoneDynamique) {
            $prefix = 'multi_';
        }
        $isZoneDynamique = (int) Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        //nettoyage des anciennes entrées
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $multi->setMultiType(Pc23MurMedia::TYPE_MUR_MEDIA)
            ->setMulti($controller->multi)
            ->delete();
        // saubvegarde manuelle
        foreach (Pelican_Db::$values['STRUCTURE'] as $idx => $structure) {
            $data = [];
            $data['PAGE_ID'] = $oldValues['PAGE_ID'];
            $data['LANGUE_ID'] = $oldValues['LANGUE_ID'];
            $data['PAGE_VERSION'] = $oldValues['PAGE_VERSION'];
            $data['AREA_ID'] = $oldValues['AREA_ID'];
            $data['ZONE_ORDER'] = $oldValues['ZONE_ORDER'];
            $data['PAGE_ZONE_MULTI_ID'] = $idx;
            $data['PAGE_ZONE_MULTI_TYPE'] = Pc23MurMedia::TYPE_MUR_MEDIA;
            $data['PAGE_ZONE_MULTI_ORDER'] = $structure['order'];
            $data['PAGE_ZONE_MULTI_VALUE'] = $structure['type'];
            foreach ($structure['ID'] as $indice => $value) {
                $data[$mediaNames[$indice]] = $value;
            }

            $preInsert = Pelican_Db::$values;
            Pelican_Db::$values = $data;
            self::$con->insertQuery('#pref#_page_'.$prefix.'zone_multi');
            Pelican_Db::$values = $preInsert;
        }

        parent::save();
        Pelican_Db::$values = $oldValues;
    }

    public static function getSmallestFormat($formats)
    {
        $smallest = current($formats);
        foreach($formats as $format) {
            if($format['MEDIA_FORMAT_HEIGHT'] <= $smallest['MEDIA_FORMAT_HEIGHT']
                && $format['MEDIA_FORMAT_WIDTH'] <= $smallest['MEDIA_FORMAT_WIDTH']) {
                $smallest = $format;
            }
        }

        return $smallest;
    }

    /**
     * @param $controller
     * @param $areaId
     * 
     * @return mixed
     */
    public static function getMedias($controller, $areaId)
    {
        $isZoneDynamique = (int)Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $pageId = (isset($controller->zoneValues['PAGE_ID'])) ? $controller->zoneValues['PAGE_ID'] : $_SESSION[APP]['PAGE_ID'];
        $langueId = (isset($controller->zoneValues['LANGUE_ID'])) ? $controller->zoneValues['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID'];
        $zoneValues = $controller->zoneValues;
        $zoneValues['PAGE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_ATTRIBUT'];
        $zoneValues['PAGE_ID'] = $pageId;
        $zoneValues['LANGUE_ID'] = $langueId;
        /** @var  PsaNdp\MappingBundle\Utils\MurMediaMediaFinder $mediaFinder */
        $structureManager = new StructureManager();
        $mediaFinder = Pelican_Application::getContainer()->get('psa_ndp_mur_media_media_finder');
        $smallest = self::getSmallestFormat(self::getImageFormats($structureManager->getAvailableStructures()));
        $mediaFinder->setSmallest($smallest);
        pelican_import('Controller.Back');
        //1) on recupere toutes les images du showroom et des tranches filles
        //  a) zone
        $mediaZone = Cms_Page_Ndp::getMediaValues($zoneValues);
        //  b) zone_multi
        $mediaZoneMulti = Cms_Page_Ndp::getMediaValues($zoneValues, '_multi');
        //  c) multi_zone
        $mediaMultiZone = Cms_Page_Ndp::getMultiMediaValues($zoneValues, $areaId, '');
        //  d) multi_zone_multi
        $mediaMultiZoneMulti = Cms_Page_Ndp::getMultiMediaValues($zoneValues, $areaId, '_multi');
        //2) on fusionne avec ce qui a été save
        // recherce des medias existant
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $existingMedias = $multi->setMultiType(Pc23MurMedia::TYPE_MUR_MEDIA)
            ->hydrate($zoneValues)
            ->getValues();
        // si les medias ne proviennent pas du meme showroom on ne les prend pas en compte
        if (isset($existingMedias[0]['PAGE_ZONE_MULTI_ATTRIBUT2']) && $existingMedias[0]['PAGE_ZONE_MULTI_ATTRIBUT2'] != $zoneValues['PAGE_TEMPLATE_ID']) {
            $existingMedias = [];
        }
        $mediaFinder->addMedias($mediaZone)
            ->addMedias($mediaZoneMulti)
            ->addMedias($mediaMultiZone)
            ->addMedias($mediaMultiZoneMulti)
            ->addMedias($existingMedias)
        ;
        $medias = $mediaFinder->buildMedias();


        return $medias;
    }

    /**
     * @param Pelican_Controller $controller
     * @param $isZoneDynamique
     *
     * @return string
     */
    private static function addJs(Pelican_Controller $controller, $isZoneDynamique)
    {
        $pageId = (isset($controller->zoneValues['PAGE_ID'])) ? $controller->zoneValues['PAGE_ID'] : $_SESSION[APP]['PAGE_ID'];
        $langueId = (isset($controller->zoneValues['LANGUE_ID'])) ? $controller->zoneValues['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID'];
        $jsText = '
             function refreshMedia'.$controller->multi."(val)
             {
                 window.parent.showLoading('div#frame_right_middle', true);
                 //call ajax
                 var values = {};
                 values['ZONE_ATTRIBUT'] = val;
                 values['PAGE_ID'] = '".$pageId."';
                 values['LANGUE_ID'] = '".$langueId."';
                 values['PAGE_VERSION'] = '".$controller->zoneValues['PAGE_VERSION']."';
                 values['ZONE_TEMPLATE_ID'] = '".$controller->zoneValues['ZONE_TEMPLATE_ID']."';
                 values['AREA_ID'] = '".$controller->zoneValues['AREA_ID']."';
                 values['ZONE_ORDER'] = '".$controller->zoneValues['ZONE_ORDER']."';
                 values['ZONE_ATTRIBUT_ORIGINAL'] = '".$controller->zoneValues['ZONE_ATTRIBUT']."';
                 callAjax({
                  url: 'Cms_Page_MurMediaAjax/searchMedias',
                  async: false,
                  type: 'POST',
                  data: {
                      'multi': '".$controller->multi."',
                      'readO': '".$controller->readO."',
                      'isZoneDynamique':  $isZoneDynamique,
                      'zoneValues': values
                  },
                 success: function(data) {
                 console.log(data);
                 refrehMurMediaJs(data);
                 window.parent.showLoading('div#frame_right_middle', false);
                 return true;
                }
                });
             };
                $(document).ready(function(){
                 var btActualiser = $('#".$controller->multi."ZONE_ATTRIBUT');
              if( ! btActualiser.hasClass('js-mur-media'))
                {
                btActualiser.addClass('js-mur-media');
                btActualiser.on('change',function () {
                     refreshMedia".$controller->multi.'($(this).val());
                });
                }
             });
         ';

        return Pelican_Html::script(array(type => 'text/javascript'), $jsText);
    }
}
