<?php

/**
 * Tranche PF42 Selectionneur de teinte 360°
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author David Moaté <david.moate@businessdecision.com>
 * @since 29/05/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 *
 */
class Cms_Page_Ndp_Pf42SelectionneurDeTeinte360 extends Cms_Page_Ndp
{

    const FIELD_MODEL = "PAGE_GAMME_VEHICULE";
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $form  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createDescription(t('NDP_MSG_SELECTIONNEUR_DE_TEINTE_DISPLAY_CONDITION'));
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('TITLE'), 60, '', false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 30, false, '', 'text', [], false, '', "60 ".t('NDP_MAX_CAR'));
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE2', t('NDP_SOUS_TITRE'), 60, '', false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 30, false, '', 'text', [], false, '', "60 ".t('NDP_MAX_CAR'));

        $controller->zoneValues['MODELE'] = self::getModeleRegroupementSilhouette($controller);
        $form .= $controller->oForm->createInput($controller->multi.'MODELE', t('NDP_MODELE_REGROUPEMENT_DE_SILHOUETTES'), 25, '', true, $controller->zoneValues['MODELE'], true, 100);

        $form .= $controller->oForm->createComboFromList($controller->multi."ZONE_PARAMETERS", t('NDP_VERSION_VEHICULE'), self::getListVersionVehicule($controller), $controller->zoneValues['ZONE_PARAMETERS'], true, (Cms_Page_Ndp::isTranslator() || $controller->readO), 1, false, 400, true);

        $form .= self::jsDynamiqueVersion($controller);

        return $form;
    }

    public static function jsDynamiqueVersion(Pelican_Controller $controller)
    {
        $gammeFieldName = self::FIELD_MODEL;
        $vehiculeFieldName = $controller->multi."ZONE_PARAMETERS";
        $script = <<<EOF

            function  refreshVehicule$controller->multi(mrs)
            {
                window.parent.showLoading('div#frame_right_middle', true);
                callAjax({
                  url: 'Cms_Page_Ajax/searchVersionsByModeleRegroupementSilhouette',
                  async: false,
                  type: 'POST',
                  data: {
                     'mrs': mrs
                  },
                success: function(e) {
                      if(e) {
                          var \$vehicule = $('#$vehiculeFieldName');
                          \$vehicule.find('option').remove();
                          for (var key in e) {
                              if (e.hasOwnProperty(key)) {

                                \$vehicule.append('<option value="'+key+'">'+e[key]+'</option>');
                              }
                          }
                          \$vehicule.parent().parent().prev('tr').find('td:eq(1)').html(mrs);

                      }
                      window.parent.showLoading('div#frame_right_middle', false);
                    }
                });
            }

            $(document).ready(function(){
             $('#${gammeFieldName}').change(function () {
                refreshVehicule$controller->multi($(this).val());
             });
            });


EOF;

        return Pelican_Html::script(array(type => 'text/javascript'), $script);
    }


    /**
     * Affiché comme suit :
     * « Par défaut (»  + [Code_LCDV16] + « - » + [libellé de la version] + « ) »
     * Exemple : Par défaut (1CB135VR567432567 - Nlle 308 SW GT Line 1,2L Pure Tech S&S 130 BVM6)
     * La liste déroulante est suivie de la liste de toutes les versions
     * du Modèle/Regroupement de silhouettes sélectionné du Webservice Moteur de configuration par ordre de prix croissant
     *
     * @return array
     */
    public static function getListVersionVehicule(Pelican_Controller $controller)
    {
        $service = $controller->getContainer()->get('configuration_engine_select');

        $list = [];
        if (!empty($controller->zoneValues['MODELE'])) {
            list($lcdv6, $regroupementSilhouette) = explode('-', $controller->zoneValues['MODELE']);
            $result = $service->getVersionsByRegroupementModeleRegroupementSilhouette($lcdv6, $regroupementSilhouette);
            $first = true;
            foreach ($result as $row) {
                if ($lcdv6 == substr($row['lcdv16'], 0, 6)) {
                    $label = '';
                    if ($first) {
                        $label = 'Par défaut (';
                    }
                    $label .= $row['lcdv16'].' - '.$row['name'];
                    if ($first) {
                        $label .= ')';
                        $first = false;
                    }
                    $list[$row['lcdv16']] = $label;
                }
            }
        }

        return $list;
    }

    public static function getModeleRegroupementSilhouette(Pelican_Controller $controller)
    {
        $infos = '';
        // page dynamique//
        if (!isset($controller->values['PAGE_PARENT_ID']) && !isset($controller->values['PAGE_ID']))
        {
            $pageIds = explode('/', $_SESSION[APP]['CURRENT_PAGE_PATH']);
            $controller->values['PAGE_ID'] = array_pop($pageIds);
        }

         // on regarde la config de la page courante sinon
        if ($controller->values['PAGE_ID'] > 0) {
            $bind = array(':PAGE_ID' => $controller->values['PAGE_ID']);
            $sql = 'SELECT
                pv.PAGE_GAMME_VEHICULE
                FROM #pref#_page as p
                INNER JOIN #pref#_page_version as pv
                    ON p.PAGE_ID = pv.PAGE_ID
                    AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                    AND p.LANGUE_ID = pv.LANGUE_ID
                WHERE p.PAGE_ID=:PAGE_ID
        ';
            $infos = self::$con->queryItem($sql, $bind);
        } elseif (count(explode('#', $controller->values['PAGE_PATH'])) > 2) {
        // on est sur une nouvelle page on récupère la config de la page parent
            $bind = array(':PAGE_PARENT_ID' => $controller->values['PAGE_PARENT_ID']);
            $sql = 'SELECT
                pv.PAGE_GAMME_VEHICULE
                FROM #pref#_page as p
                INNER JOIN #pref#_page_version as pv
                    ON p.PAGE_ID = pv.PAGE_ID
                    AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                    AND p.LANGUE_ID = pv.LANGUE_ID
                WHERE p.PAGE_ID=:PAGE_PARENT_ID
        ';
            $infos = self::$con->queryItem($sql, $bind);
        }

        return  $infos;

    }

}
