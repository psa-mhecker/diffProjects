<?php

abstract class Cms_Content_Module
{

    public static $decachePublication;
    public static $decacheBack;
    public static $decacheBackOrchestra = [];
    public static $decachePublicationOrchestra =   [  // vide tous les cache lié a un contenu quand on le publie
        'strategy' => array(
            'strategy' => array(
                'content',
                'siteId',
                'locale',
            ),
        ),
    ];

    abstract public static function render(Pelican_Controller $controller);

    public static function save(Pelican_Controller $controller)
    {
        
    }

    public static function beforeSave(Pelican_Controller $controller)
    {
        
    }

    public static function addCache(Pelican_Controller $controller)
    {
    }

    /**
     * @param string $multi
     * @param string $type
     * @param mixed  $value
     *
     * @return string
     */
    public static function addHeadContainer($selected, $value, $type)
    {
        $display = 'display:none;';
        $class = ' isNotRequired';
        if ($selected == $value) {
            $display = '';
            $class = '';
        }

        return sprintf('<tbody id="%s_%s" style="%s" class="%s %s"><tr><td colspan="2"><table class="form">', $type, $selected, $display, $type, $class);
    }

    /**
     * @param string $multi
     * @param string $type
     * @param mixed  $value
     *
     * @return string
     */
    public static function addFootContainer()
    {
        $footContainer = '</table>';
        $footContainer .= '</td></tr>';
        $footContainer .= '</tbody>';

        return $footContainer;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerRadio($type)
    {
        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();
                    $(\'#'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'#'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                "';

        return $js;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerCombo($type)
    {
        $js = 'onchange="
                      $(\'.'.$type.'\').hide();
                      $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    var selected =   $(this).val();
                   
                    if( $(\'#'.$type.'_\' + selected).length == 1 ){     
                        
                        $(\'#'.$type.'_\' + selected).show();
                        $(\'#'.$type.'_\' + selected).removeClass(\'isNotRequired\');
                             
                    }
                "';

        return $js;
    }

    /**
     * Suppression des enregistrements des medias.
     *
     * @param $contentMediaType string : CONTENT_MEDIA_TYPE
     */
    public static function deleteContentVersionMedia($contentMediaType = '')
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":CONTENT_ID"] = Pelican_Db::$values['CONTENT_ID'];
        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[":CONTENT_VERSION"] = Pelican_Db::$values['CONTENT_VERSION'];
        $aBind[":CONTENT_MEDIA_TYPE"] = $oConnection->strToBind($contentMediaType);

        $sqlDelete = "DELETE FROM #pref#_content_version_media
                                WHERE
                                LANGUE_ID = :LANGUE_ID
                                and CONTENT_VERSION = :CONTENT_VERSION
                                and CONTENT_ID = :CONTENT_ID
                                and CONTENT_MEDIA_TYPE = :CONTENT_MEDIA_TYPE
                                ";

        $oConnection->query($sqlDelete, $aBind);
    }

    /**
     * Méthode statique de sauvegarde des medias associes au contenu dans
     * la table content_version_media.
     *
     * @param string $sMultiName libellé du multi
     * @param $contentMediaType string : CONTENT_MEDIA_TYPE
     */
    public static function saveContentVersionMediaValues($sMultiName, $contentMediaType = '')
    {
        /* Initialisation des variables */
        $sMultiName = (string) $sMultiName;
        $aSaveValues = Pelican_Db::$values;

        self::deleteContentVersionMedia($contentMediaType);

        /* Intégration dans un tableau des donnees du multi */
        readMulti($sMultiName, $sMultiName);

        $aMultiFormValues = Pelican_Db::$values[$sMultiName];

        if (is_array($aMultiFormValues) && !empty($aMultiFormValues)) {
            $i = 0;
            $doublon = array();
            foreach ($aMultiFormValues as $aOneMulti) {
                /* Seuls les éléments non masqués sont insérés dans la table */
                /* les doublons sont ignores */

                if ($aOneMulti['multi_display'] == 1 && !in_array($aOneMulti['MEDIA_ID'], $doublon)) {
                    $i++;
                    $saveValues['CONTENT_ID'] = $aSaveValues['CONTENT_ID'];
                    $saveValues['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                    $saveValues['CONTENT_VERSION'] = $aSaveValues['CONTENT_VERSION'];
                    $saveValues['MEDIA_ID'] = $aOneMulti['MEDIA_ID'];
                    $saveValues['CONTENT_MEDIA_TYPE'] = $contentMediaType;
                    $saveValues['MEDIA_TYPE_ID'] = $aOneMulti['MEDIA_TYPE_ID'];
                    $saveValues['CONTENT_MEDIA_ORDER'] = $i;
                    self::addContentVersionMedia($saveValues);
                    $doublon[] = $saveValues['MEDIA_ID'];
                }
            }
        }

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
    }

    /**
     * Ajout d'un élément media dans table content_version_media.
     */
    public static function addContentVersionMedia($values)
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values = array();

        Pelican_Db::$values["CONTENT_ID"] = $values['CONTENT_ID'];
        Pelican_Db::$values["LANGUE_ID"] = $values['LANGUE_ID'];
        Pelican_Db::$values["CONTENT_VERSION"] = $values['CONTENT_VERSION'];
        Pelican_Db::$values["MEDIA_ID"] = $values['MEDIA_ID'];
        Pelican_Db::$values["CONTENT_MEDIA_TYPE"] = $values['CONTENT_MEDIA_TYPE'];
        Pelican_Db::$values["MEDIA_TYPE_ID"] = $values['MEDIA_TYPE_ID'];
        Pelican_Db::$values["CONTENT_MEDIA_ORDER"] = $values['CONTENT_MEDIA_ORDER'];

        $oConnection->insertQuery('#pref#_content_version_media');
    }

    /**
     * Méthode statique récupérant les enregistrements présents dans content_version_media
     *
     * @param Pelican_Controller $controller Objet controller
     * @param NULL/string        $contentMediaType
     *
     * @return array Tableau des medias
     */
    public static function getContentVersionMediaValues(Pelican_Controller $controller, $contentMediaType = '')
    {
        $return = array();
        $oConnection = Pelican_Db::getInstance();

        if (is_array($controller->values) && !empty($controller->values)) {
            $aBind[':LANGUE_ID'] = $controller->values['LANGUE_ID'];
            $aBind[':CONTENT_VERSION'] = $controller->values['CONTENT_VERSION'];
            $aBind[':CONTENT_ID'] = $controller->values['CONTENT_ID'];
            if ($contentMediaType != '') {
                $aBind[':CONTENT_MEDIA_TYPE'] = $oConnection->strToBind($contentMediaType);
                $where = ' AND CONTENT_MEDIA_TYPE = :CONTENT_MEDIA_TYPE';
            }

            $sSql = 'SELECT
                        *
                    FROM
                        #pref#_content_version_media cvm
                    WHERE
                        CONTENT_ID = :CONTENT_ID
                        AND LANGUE_ID = :LANGUE_ID
                        AND CONTENT_VERSION = :CONTENT_VERSION
                    '
                .$where.'
                    ORDER BY CONTENT_MEDIA_ORDER
                    ';

            $return = $oConnection->queryTab($sSql, $aBind);
        }
        return $return;
    }

    /**
     * 
     * @param string $multi
     * @param string $type
     */
    public static function saveCtaHMVC($multi, $type)
    {
        $saved = Pelican_Db::$values;
        $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaHmvc->setCtaType($type)
            ->setMulti($multi)
            ->delete();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            $ctaHmvc->save();
        }
        Pelican_Db::$values = $saved;
    }
    /*     * *
     * 
     */

    public static function cleanAllParameters()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':CONTENT_ID' => Pelican_Db::$values['CONTENT_ID'],
            ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
            ':VERSION_ID' => Pelican_Db::$values['CONTENT_VERSION'],
        ];

        $sql = "DELETE FROM  #pref#_content_version_attribut 
                    WHERE CONTENT_ID = :CONTENT_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND CONTENT_VERSION= :VERSION_ID";

        $connection->query($sql, $bind);
    }

    /**
     * @param array $fieldsAvailable
     * @param string $fieldName
     * @param string $type
     * @param mixed $value
     */
    public static function saveParameter($fieldsAvailable ,$fieldName, $type = null, $value = null)
    {
        $connection = Pelican_Db::getInstance();
        $saved = Pelican_Db::$values;
        if ($value === null) {
            if (empty($type)) {
                $value = Pelican_Db::$values[$fieldName];
            }
            if (!empty($type)) {
                $value = Pelican_Db::$values[$type][$fieldName];
            }
        }

        if (isset($fieldsAvailable[$fieldName])) {
            if (!empty($type)) {
                $type .= '_';
            }
            Pelican_Db::$values = [
                'CONTENT_ID' => $saved['CONTENT_ID'],
                'LANGUE_ID' => $saved['LANGUE_ID'],
                'CONTENT_VERSION' => $saved['CONTENT_VERSION'],
                'CONTENT_ATTRIBUT_NAME' => $type.$fieldName,
               $fieldsAvailable[$fieldName] => ($value)
            ];

            if (!empty($value)) {

                $connection->insertQuery('#pref#_content_version_attribut');
            }
        }
        Pelican_Db::$values = $saved;
    }
}
