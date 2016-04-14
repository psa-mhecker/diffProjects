<?php
/**
 * Tranche PF33 - Car compatibility table_ connected services specific
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Pierre POTTIE <pierre.pottie@businessdecision.com>
 * @since 03/08/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Cta/ListeDeroulante.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
use PsaNdp\MappingBundle\Object\Block\Pf33CarCompatibility;
/**
 * Cms_Page_Ndp_Pf33CarCompatibility.
 */
class Cms_Page_Ndp_Pf33CarCompatibility extends Cms_Page_Ndp
{

    const YES = 1;
    const NO = 0;
    const SHOW_VERSION = 'showVersion';
   
    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {

        return self::getForm($controller);
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getForm(Pelican_Controller $controller)
    {

        if ($controller->zoneValues['ZONE_ATTRIBUT2'] == Pf33CarCompatibility::BENEFICE) {
            $controller->zoneValues['ZONE_MOBILE_READO'] = true;
            $controller->zoneValues['ZONE_MOBILE'] = 0;
        }
        $form  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $form .= $controller->oForm->createInput(
            $controller->multi."ZONE_TITRE", t('TITLE'), 60, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70, false, '', 'text', [], false, '', '60'.t('NDP_MAX_CAR'));

        $form .= $controller->oForm->createInput(
            $controller->multi."ZONE_TITRE2", t('NDP_SOUS_TITRE'), 120, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 100, false, '', 'text', [], false, '', '120'.t('NDP_MAX_CAR'));

        $targetsYesNo = array(
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO')
        );
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT', self::YES);
        $form .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT', t('NDP_SHOW_DETAILS'), $targetsYesNo, $controller->zoneValues['ZONE_ATTRIBUT'], true, $controller->readO, 'h', false);

        $type = $controller->multi.self::SHOW_VERSION;
        $jsContainerShow = self::addJsContainerRadio($type);
        // la page n'est dispo en mobile que pour le cas ou on a choisi un service connecté
        $jsContainerShow .= self::addJsMobileShow($controller->multi);
        $targetsVersion = array(
            Pf33CarCompatibility::BENEFICE => t('NDP_BENEFICE'),
            Pf33CarCompatibility::CONNECTED_SERVICES => t('NDP_CONNECTED_SERVICES')
        );
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_ATTRIBUT2', Pf33CarCompatibility::BENEFICE);
        $form .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT2', t('NDP_VERSION'), $targetsVersion, $controller->zoneValues['ZONE_ATTRIBUT2'], true, $controller->readO, 'h', false, $jsContainerShow);


        $form .= self::addHeadContainer(Pf33CarCompatibility::BENEFICE, $controller->zoneValues['ZONE_ATTRIBUT2'], $type);
        $form .= self::getFormBeneficeVersion($controller);
        $form .= self::addFootContainer();


        $form .= self::addHeadContainer(Pf33CarCompatibility::CONNECTED_SERVICES, $controller->zoneValues['ZONE_ATTRIBUT2'], $type);
        $form .= self::getFormConnectedServiceVersion($controller);
        $form .= self::addFootContainer();


        return $form;
    }

    public static function  addJsMobileShow($multi)
    {
            //multiZone150_0_ZONE_MOBILE
        $js = ' onchange="updateMobile'.$multi.'(this);"';

        return $js;
    }

    /**
     * 
     * @return string
     */
    public static function getSqlListBenefices()
    {
        $sql = "
                SELECT
                    ID,
                    LABEL
                FROM
                    #pref#_benefice WHERE
            SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            ORDER BY LABEL";

        return $sql;
    }

    /**
     * 
     * @return array
     */
    public static function getBindSiteLangue()
    {
        $bind = [
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];

        return $bind;
    }

    /**
     * 
     * @return string
     */
    public static function getSqlListConnectedServices()
    {
        $query = 'SELECT ID, LABEL FROM #pref#_services_connect
            WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID
            ORDER BY LABEL';

        return $query;
    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getFormBeneficeVersion(Pelican_Controller $controller)
    {
        $connection = Pelican_Db::getInstance();
        $values = self::getValuesListConnectedServicesByBenefices();
         $js = 'onChange="updateBenef'.$controller->multi.'(this)"';
        $form = $controller->oForm->createComboFromSql($connection, $controller->multi.'ZONE_ATTRIBUT3', t('NDP_BENEFICE'), self::getSqlListBenefices(), $controller->zoneValues['ZONE_ATTRIBUT3'], true, $controller->readO, "1", false, "", true, false, $js, "", self::getBindSiteLangue());


        $aSelected = array();
        if ($controller->zoneValues['ZONE_PARAMETERS'] != '') {
            $aSelected = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
        }

        $dataValues = [];
        if (!empty($controller->zoneValues['ZONE_ATTRIBUT3']) && isset($values[$controller->zoneValues['ZONE_ATTRIBUT3']])) {
            $dataValues = $values[$controller->zoneValues['ZONE_ATTRIBUT3']];
        }

        $form .= $controller->oForm->createAssocFromList(
            '', $controller->multi.'ZONE_PARAMETERS', t('NDP_SERVICE_CONNECTE'), $dataValues, $aSelected, true, true, $controller->readO, 5, 200, false, '', '', self::getBindSiteLangue(), true);

        $form .= self::addJsForBenefice($values, $controller->multi);

        return $form;
    }

    public static function  getValuesListConnectedServicesByBenefices() {

        $return = [];
        $connection = Pelican_Db::getInstance();
        $query = 'SELECT ID, LABEL, BENEFICES FROM #pref#_services_connect
            WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID
            ORDER BY LABEL ASC';

        $results = $connection->queryTab($query, self::getBindSiteLangue());

        foreach ($results as $result) {
            $benefices = explode('#', $result['BENEFICES']);

            foreach ($benefices as $benefice) {
                if (!isset($return[$benefice])) {
                    $return[$benefice] = [];
                }

                $return[$benefice][$result['ID']] = $result['LABEL'];
            }
        }

        return $return;
    }

    public function addJsForBenefice($values, $multi)
    {

        $script = '
               function updateBenef'.$multi.'(el)  {
               var benef = $(el).val();
               ';
        $script .= ' var id="'.$multi.'ZONE_PARAMETERS";'."\n";
        $script .= '$("#"+id).get(0).options.length = 0;'."\n";
        // vidage du champs src
        $script .= 'var $src =  $("#src"+id);'."\n";
        $script .= '$src.get(0).options.length = 0;'."\n";
        foreach ($values as $idBenef=>$comboValues) {
               // vidage du champs dest
               $script .= 'if (benef == "'.$idBenef.'") { '."\n";
               foreach ($comboValues as $id=>$label) {
                   $script .= '$src.append("<option value=\"'.$id.'\">'.str_replace('"', "&quot;", $label).'</option>");'."\n";
               }
               $script .= '} '."\n";

        }
        $script .= '}'."\n";

        $script .= '
          function updateMobile'.$multi.'(el) {
                var $mobileRadio = $(\'input[name='.$multi.'ZONE_MOBILE]\');
                var selectedRadio =  $(el).val();
                if (selectedRadio == \''.Pf33CarCompatibility::BENEFICE.'\') {
                      // on sauvegarde l\'etat actuel du bouton mobile
                      $mobileRadio.data(\'oldvalue\',$mobileRadio.prop(\'checked\'));
                      //on desactive le bouton mobile
                      $mobileRadio.prop(\'checked\',false).prop(\'disabled\',true);
                }
                //
               if (selectedRadio == \''.Pf33CarCompatibility::CONNECTED_SERVICES.'\') {
                   // on reactive le bouton
                   $mobileRadio.prop(\'disabled\',false);
                   //on restaure son état
                   if ($mobileRadio.data(\'oldvalue\')) {
                    $mobileRadio.prop(\'checked\', $mobileRadio.data(\'oldvalue\'));
                   }
                }
           }

        ';

        return Pelican_Html::script(array('type' => 'text/javascript'), $script);


    }

    /**
     * 
     * @param Pelican_Controller $controller
     * 
     * @return string
     */
    public static function getFormConnectedServiceVersion(Pelican_Controller $controller)
    {
        $connection = Pelican_Db::getInstance();

        $form = $controller->oForm->createComboFromSql($connection, $controller->multi.'ZONE_CRITERIA_ID', t('NDP_SERVICE_CONNECTE'), self::getSqlListConnectedServices(), $controller->zoneValues['ZONE_CRITERIA_ID'], true, $controller->readO, "1", false, "", true, false, "", "", self::getBindSiteLangue());

        return $form;
    }

    /**
     * Save.
     *
     */
    public static function save()
    {
        $oldValues = Pelican_Db::$values;

        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
        Pelican_Db::$values = $oldValues;
    }
}
