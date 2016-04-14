<?php
/**
 * Class Cms_Page_Module.
 */

/**
 * Class Cms_Page_Module.
 */
class Cms_Page_Module
{

    public static $con;
    public static $decacheBack;
    public static $decachePublication;
    public static $decacheBackOrchestra;
    public static $decachePublicationOrchestra;
    protected static $displayAlways = false;

    const IS_BEING_CREATED = -2;
    const MULTI_IS_DISPLAYED = 1;

    /**
     * @return boolean
     */
    public static function getDisplayAlways()
    {
        return self::$displayAlways;
    }

    /**
     * @param boolean $displayAlways
     */
    public static function setDisplayAlways($displayAlways)
    {
        self::$displayAlways = $displayAlways;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function addCache(Pelican_Controller $controller)
    {
        if (isset(self::$decacheBack)) {
            if (!isset($controller->decacheBack)) {
                $controller->decacheBack = self::$decacheBack;
            } else {
                $controller->decacheBack = array_merge($controller->decacheBack, self::$decacheBack);
            }
        }

        if (isset(self::$decacheBackOrchestra)) {
            if (!isset($controller->decacheBackOrchestra)) {
                $controller->decacheBackOrchestra = self::$decacheBackOrchestra;
            } else {
                $controller->decacheBackOrchestra = array_merge($controller->decacheBackOrchestra, self::$decacheBackOrchestra);
            }
        }
        if (isset(self::$decachePublication)) {
            if (!isset($controller->decachePublication)) {
                $controller->decachePublication = self::$decachePublication;
            } else {
                $controller->decachePublication = array_merge($controller->decachePublication, self::$decachePublication);
            }
        }
        if (isset(self::$decachePublicationOrchestra)) {
            if (!isset($controller->decachePublicationOrchestra)) {
                $controller->decachePublicationOrchestra = self::$decachePublicationOrchestra;
            } else {
                $controller->decachePublicationOrchestra = array_merge($controller->decachePublicationOrchestra, self::$decachePublicationOrchestra);
            }
        }
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function render(Pelican_Controller $controller)
    {
        
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller = null)
    {
        self::$con = Pelican_Db::getInstance();

        /* Paramètres par défaut */
        if (!Pelican_Db::$values['MEDIA_FORMAT_ID']) {
            Pelican_Db::$values['MEDIA_FORMAT_ID'] = 1;
        }

        if (is_array(Pelican_Db::$values['CONTENT_ID'])) {
            Pelican_Db::$values['CONTENT_ID'] = Pelican_Db::$values['CONTENT_ID'][0];
        }

        if (!self::$displayAlways) {
            if (!isset(Pelican_Db::$values['ZONE_WEB'])) {
                Pelican_Db::$values['ZONE_WEB'] = '0';
            }

            if (!isset(Pelican_Db::$values['ZONE_MOBILE'])) {
                Pelican_Db::$values['ZONE_MOBILE'] = '0';
            }
        }

        $DBVALUES_SAVE = Pelican_Db::$values;
        /* Mise à jour de page_zone */
        /* minisite */
        Pelican_Db::$values['ZONE_MS_JOB_ID'] = Pelican_Db::$values['ZONE_ID'];
        /* minisite */
        Pelican_Db::$values['MEDIA_PATH'] = Pelican_Media::getMediaPath(Pelican_Db::$values['MEDIA_ID']);
        if (Pelican_Db::$values['MEDIA_ID2']) {
            Pelican_Db::$values['MEDIA_PATH2'] = Pelican_Media::getMediaPath(Pelican_Db::$values['MEDIA_ID2']);
        }

        if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
            self::$con->insertQuery('#pref#_page_multi_zone');
        } else {
            self::$con->insertQuery('#pref#_page_zone');
        }

        /* Mise à jour de page_zone_content */
        Pelican_Db::$values = $DBVALUES_SAVE;
        for ($i = 0; $i < sizeof(Pelican_Db::$values['CONTENU']); $i ++) {
            Pelican_Db::$values['CONTENT_ID'] = Pelican_Db::$values['CONTENU'][$i];
            Pelican_Db::$values['PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
            Pelican_Db::$values['ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
            Pelican_Db::$values['PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
            Pelican_Db::$values['LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
            Pelican_Db::$values['COMPTEUR'] = $i;
            if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
                self::$con->insertQuery('#pref#_page_multi_zone_content');
            } else {
                self::$con->insertQuery('#pref#_page_zone_content');
            }
        }

        /* Mise à jour de page_zone_media */
        Pelican_Db::$values = $DBVALUES_SAVE;
        for ($i = 0; $i < 3; $i ++) {
            if (Pelican_Db::$values['MEDIA_ID'.$i]) {
                Pelican_Db::$values['MEDIA_ID'] = Pelican_Db::$values['MEDIA_ID'.$i];
                Pelican_Db::$values['PAGE_ZONE_MEDIA_LABEL'] = Pelican_Db::$values['MEDIA_LIB'.$i];
                if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
                    self::$con->insertQuery('#pref#_page_multi_zone_media');
                } else {
                    self::$con->insertQuery('#pref#_page_zone_media');
                }
            }
        }

        Pelican_Db::$values = $DBVALUES_SAVE;
    }

    /**
     * Récupère tous les contenus attacher a une zone en les groupant par leur nom.
     *
     * @param array $bind
     *
     * @see getDefaultBinding
     *
     * @return array
     */
    public static function getAllContents($bind)
    {
        //Récupération des contenus
        $results = array();
        $sSQLContent = 'SELECT
                pzc.PAGE_ZONE_PARAMETERS,
                pzc.CONTENT_ID,
                cv.CONTENT_TITLE
            FROM #pref#_page_zone_content pzc
                INNER JOIN #pref#_content c  ON (c.CONTENT_ID = pzc.CONTENT_ID   and c.LANGUE_ID = pzc.LANGUE_ID)
                INNER JOIN #pref#_content_version cv ON (cv.CONTENT_ID = c.CONTENT_ID and cv.CONTENT_VERSION = c.CONTENT_CURRENT_VERSION          and cv.LANGUE_ID = c.LANGUE_ID            )
            WHERE
                pzc.PAGE_ID = :PAGE_ID
                AND pzc.PAGE_VERSION = :PAGE_VERSION
                AND pzc.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                AND pzc.LANGUE_ID = :LANGUE_ID
            ';
        self::$con->query($sSQLContent, $bind);
        if (!empty(self::$con->data)) {
            foreach (self::$con->data['PAGE_ZONE_PARAMETERS'] as $key => $paramName) {
                if (!isset($results[$paramName])) {
                    $results[$paramName] = array();
                }
                $results[$paramName][self::$con->data['CONTENT_ID'][$key]] = self::$con->data['CONTENT_TITLE'][$key];
            }
        }

        return $results;
    }

    /**
     * Sauve Tous les contenus donc le nom est passé dans le tableau $contents.
     *
     * @param array $contents
     */
    protected static function saveContents($contents)
    {
        if (!empty($contents)) {
            foreach ($contents as $contentName) {
                if (!empty(Pelican_Db::$values[$contentName])) {
                    self::saveContentsArray($contentName);
                }
            }
        }
    }

    /**
     * Save les contenus $contentName.
     *
     * @param string $contentName
     */
    private static function saveContentsArray($contentName)
    {
        if (!is_array(Pelican_Db::$values[$contentName])) {
            Pelican_Db::$values[$contentName] = array(Pelican_Db::$values[$contentName]);
        }
        foreach (Pelican_Db::$values[$contentName] as $contentId) {
            Pelican_Db::$values['CONTENT_ID'] = $contentId;
            Pelican_Db::$values['PAGE_ZONE_PARAMETERS'] = $contentName;

            self::$con->updateTable(Pelican::$config['DATABASE_INSERT'], '#pref#_page_zone_content');
        }
    }

    /**
     * Supprime tous les contenus de la zone courante.
     */
    protected static function deleteAllContents()
    {
        $bind = self::getDefaultBinding();
        $sqlDelete = ' DELETE
            FROM #pref#_page_zone_content
            WHERE PAGE_ID = :PAGE_ID
                AND PAGE_VERSION = :PAGE_VERSION
                AND LANGUE_ID = :LANGUE_ID
                AND ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        self::$con->query($sqlDelete, $bind);
    }

    /**
     * Recupère le tableau de binding par defaut d'une zone.
     *
     * @param Pelican_Controller $controller
     *
     * @return type
     */
    protected static function getDefaultBinding(Pelican_Controller $controller = null)
    {
        $bind = array();
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $bind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];

        if (!is_null($controller)) {
            $bind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
            $bind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
            $bind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
            $bind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        }

        return $bind;
    }

    /**
     * delete contents by names for the current zone.
     *
     * @param array $contents
     */
    protected static function deleteContents($contents)
    {
        if (!empty($contents)) {
            $list = "'".implode("','", $contents)."'";
            $bind = self::getDefaultBinding();
            $sqlDelete = ' DELETE
                FROM #pref#_page_zone_content
                WHERE PAGE_ID = :PAGE_ID
                    AND PAGE_VERSION = :PAGE_VERSION
                    AND LANGUE_ID = :LANGUE_ID
                    AND ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                    AND PAGE_ZONE_PARAMETERS IN ('.$list.')';
            self::$con->query($sqlDelete, $bind);
        }
    }

    /**
     * Suppression des enregistrements des multi.
     *
     * @param string $type : type de multi
     */
    public static function deletePageZoneMulti($type = '')
    {
        // remove relations with cta
        self::deletePageZoneMultiCta($type);

        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $bind[':PAGE_ZONE_MULTI_TYPE'] = self::$con->strToBind($type);

        $sqlDelete = 'DELETE FROM #pref#_page_zone_multi
                      WHERE
                        LANGUE_ID = :LANGUE_ID
                        and PAGE_VERSION = :PAGE_VERSION
                        and PAGE_ID = :PAGE_ID
                        and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                        and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                      ';
        self::$con->query($sqlDelete, $bind);
    }

    /**
     * Ajout d'un élément multi.
     *
     * @param array $values
     */
    public static function addPageZoneMulti($values)
    {
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = $values;
        self::$con->insertQuery('#pref#_page_zone_multi');
        Pelican_Db::$values = $saved;
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi à enregistrer dans
     * la table page_zone_multi.
     *
     * @param string $sMultiName libellé du multi
     * @param string $sMultiType
     */
    public static function savePageZoneMultiValues($sMultiName, $sMultiType = '')
    {
        /* Initialisation des variables */
        $saved = Pelican_Db::$values;
        $iMultiId = 0;

        self::deletePageZoneMulti($sMultiType);
        /* Remise en place des données du values */
        Pelican_Db::$values = $saved;
        /* Intégration dans un tableau des données du multi */
        readMulti($sMultiName, $sMultiName);
        $aMultiFormValues = Pelican_Db::$values[$sMultiName];
        // Récupération max PAGE_ZONE_MULTI_ID & indexation des éléments qui n'ont pas de PAGE_ZONE_MULTI_ID
        $pageZoneMultiIds = array();
        foreach ($aMultiFormValues as $key => $val) {
            if (isset($val['PAGE_ZONE_MULTI_ID']) && is_numeric($val['PAGE_ZONE_MULTI_ID'])) {
                $pageZoneMultiIds[] = intval($val['PAGE_ZONE_MULTI_ID']);
            }
        }
        $cptId = !empty($pageZoneMultiIds) ? max($pageZoneMultiIds) : 0;
        foreach ($aMultiFormValues as $key => $val) {
            if (!isset($val['PAGE_ZONE_MULTI_ID'])) {
                $aMultiFormValues[$key]['PAGE_ZONE_MULTI_ID'] = ++$cptId;
            }
        }

        /* Enregistrement des données du multi et organise tableau multi */
        if (is_array($aMultiFormValues) && !empty($aMultiFormValues)) {
            foreach ($aMultiFormValues as $aOneMulti) {
                $aOneMulti['LANGUE_ID'] = $saved['LANGUE_ID'];
                $aOneMulti['PAGE_VERSION'] = $saved['PAGE_VERSION'];
                $aOneMulti['PAGE_ID'] = $saved['PAGE_ID'];
                $aOneMulti['ZONE_TEMPLATE_ID'] = $saved['ZONE_TEMPLATE_ID'];
                /* Seuls les éléments non masqués sont insérés dans la table */
                if ($aOneMulti['multi_display'] == self::MULTI_IS_DISPLAYED) {
                    $iMultiId++;
                    $aOneMulti['PAGE_ZONE_MULTI_ID'] = $iMultiId;
                    $aOneMulti['PAGE_ZONE_MULTI_TYPE'] = $sMultiType;
                    $temp = $aOneMulti;

                    /* Ajout des champs supplémentaires */
                    foreach ($aOneMulti as $sKeyFieldName => $sValue) {
                        if (is_string($sKeyFieldName) && !empty($sKeyFieldName)) {
                            //Ajout pour gérer automatiquement l'enregistrement des listes associatives
                            if (is_array($sValue) && !empty($sValue)) {
                                $sValue = implode(',', $sValue);
                            }

                            $temp[$sKeyFieldName] = $sValue;
                        }
                    }
                    /* Intégration dans les Values pour l'utilisation des méthodescu FW */
                    self::addPageZoneMulti($temp);
                    self::savePageZoneMultiCta($aOneMulti);
                }
            }
        }
        /* Remise en place des données du values */
        Pelican_Db::$values = $saved;
    }

    /**
     * @param Pelican_Controller $controller
     * @param string             $multiType
     *
     * @return array
     */
    public static function getPageZoneMediaValues($controller, $multiType)
    {
        $multiValues = array();

        if ($values['PAGE_ID'] != self::IS_BEING_CREATED) {
            $bind = self::getDefaultBinding($controller);
            $bind[':PAGE_ZONE_MEDIA_TYPE'] = $multiType;
            $sSql = "SELECT *
                    FROM #pref#_page_zone_media pzm
                    LEFT JOIN #pref#_media m ON pzm.media_id = m.media_id
                    WHERE pzm.page_id = :PAGE_ID
                    AND pzm.langue_id = :LANGUE_ID
                    AND pzm.PAGE_VERSION = :PAGE_VERSION
                    AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                    AND pzm.PAGE_ZONE_MEDIA_TYPE = ':PAGE_ZONE_MEDIA_TYPE'
                    ORDER BY pzm.PAGE_ZONE_MEDIA_ORDER";

            $multiValues = self::$con->queryTab($sSql, $bind);
        }

        return $multiValues;
    }

    /**
     * Suppression des enregistrements des medias associes.
     *
     * @param string $type : type d'utilisation
     */
    public static function deletePageZoneMedia($type = '')
    {
        $bind = self::getDefaultBinding();
        $bind[':PAGE_ZONE_MEDIA_TYPE'] = self::$con->strToBind($type);

        $sqlDelete = 'DELETE FROM #pref#_page_zone_media
                      WHERE
                        LANGUE_ID = :LANGUE_ID
                        and PAGE_VERSION = :PAGE_VERSION
                        and PAGE_ID = :PAGE_ID
                        and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                        and PAGE_ZONE_MEDIA_TYPE = :PAGE_ZONE_MEDIA_TYPE
                      ';
        self::$con->query($sqlDelete, $bind);
    }

    /**
     * Ajout d'un élément multi.
     *
     * @param array $values
     */
    public static function addPageZoneMedia($values)
    {
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = $values;
        self::$con->insertQuery('#pref#_page_zone_media');
        Pelican_Db::$values = $saved;
    }

    /**
     * Méthode statique de sauvegarde des medias multi à enregistrer dans
     * la table page_zone_media.
     *
     * @param string     $multiName         libellé du multi
     * @param string     $pageZoneMediaType categorisation
     * @param int|string $mediaFormatId     Format des medias (si commun)
     */
    public static function savePageZoneMediaValues($multiName, $pageZoneMediaType = '', $mediaFormatId = 1)
    {
        /* Initialisation des variables */
        $saved = Pelican_Db::$values;

        //self::deletePageZoneMedia($pageZoneMediaType);
        /* Remise en place des donnees du values */
        Pelican_Db::$values = $saved;
        /* Intégration dans un tableau des données du multi */
        readMulti($multiName, $multiName);
        $multiFormValues = Pelican_Db::$values[$multiName];

        // Recuperation max PAGE_ZONE_MULTI_ORDER & indexation des elements qui n'ont pas de PAGE_ZONE_MULTI_ORDER
        $pageZoneMultiIds = array();
        foreach ($multiFormValues as $val) {
            if (isset($val['PAGE_ZONE_MULTI_ORDER']) && is_numeric($val['PAGE_ZONE_MULTI_ORDER'])) {
                $pageZoneMultiIds[] = intval($val['PAGE_ZONE_MULTI_ORDER']);
            }
        }
        $cptId = !empty($pageZoneMultiIds) ? max($pageZoneMultiIds) : 0;
        foreach ($multiFormValues as $key => $val) {
            if (!isset($val['PAGE_ZONE_MULTI_ORDER'])) {
                $multiFormValues[$key]['PAGE_ZONE_MULTI_ORDER'] = ++$cptId;
            }
        }

        /* Enregistrement des donnees du multi et organise tableau multi */
        if (is_array($multiFormValues) && !empty($multiFormValues)) {
            foreach ($multiFormValues as $oneMulti) {
                $oneMulti['PAGE_ID'] = $saved['PAGE_ID'];
                $oneMulti['LANGUE_ID'] = $saved['LANGUE_ID'];
                $oneMulti['PAGE_VERSION'] = $saved['PAGE_VERSION'];
                $oneMulti['ZONE_TEMPLATE_ID'] = $saved['ZONE_TEMPLATE_ID'];
                /* Seuls les elements non masques et possedant une image sont inseres dans la table */
                if ($oneMulti['multi_display'] == self::MULTI_IS_DISPLAYED && $oneMulti['MEDIA_ID'] > 0) {
                    $oneMulti['PAGE_ZONE_MEDIA_ORDER'] = $oneMulti['PAGE_ZONE_MULTI_ORDER'];
                    $oneMulti['PAGE_ZONE_MEDIA_TYPE'] = $pageZoneMediaType;
                    if (!isset($oneMulti['MEDIA_FORMAT_ID']) || !is_int($oneMulti['MEDIA_FORMAT_ID']) || $oneMulti['MEDIA_FORMAT_ID'] < 1) {
                        $oneMulti['MEDIA_FORMAT_ID'] = $mediaFormatId;
                    }
                    $temp = $oneMulti;

                    /* Ajout des champs supplémentaires */
                    foreach ($oneMulti as $keyFieldName => $val) {
                        if (is_string($keyFieldName) && !empty($keyFieldName)) {
                            //Ajout pour gerer automatiquement l'enregistrement des listes associatives
                            if (is_array($val) && !empty($val)) {
                                $val = implode(',', $val);
                            }

                            $temp[$keyFieldName] = $val;
                        }
                    }
                    /* Integration dans les Values pour l'utilisation des methodes du FW */
                    self::addPageZoneMedia($temp);
                }
            }
        }
        /* Remise en place des donnees du values */
        Pelican_Db::$values = $saved;
    }

    /**
     * Suppression des enregistrements des multi.
     *
     * @param string $type : type de multi
     */
    public static function deletePageZoneMultiCta($type = '')
    {
        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $bind[':PAGE_ZONE_MULTI_TYPE'] = self::$con->strToBind($type);

        $sqlDelete = 'DELETE FROM #pref#_page_zone_multi_cta
                      WHERE
                        LANGUE_ID = :LANGUE_ID
                        and PAGE_VERSION = :PAGE_VERSION
                        and PAGE_ID = :PAGE_ID
                        and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                        and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                        ';
        self::$con->query($sqlDelete, $bind);
    }

    /**
     * save the the relation between a multi and a cta.
     *
     * @param array $multi
     */
    public static function addSelectedPageZoneMultiCta($multi)
    {
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = array_merge($multi, $multi['SELECT_CTA']);
        self::$con->insertQuery('#pref#_page_zone_multi_cta');
        Pelican_Db::$values = $saved;
    }

    /**
     *  Create as CTA and then save relation with multi cta.
     *
     * @param array $multi
     */
    public static function addNewPageZoneMultiCta($multi)
    {
        $id = self::saveCta($multi['NEW_CTA']);
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = array_merge($multi, $multi['NEW_CTA']);
        Pelican_Db::$values['CTA_ID'] = $id;
        self::$con->insertQuery('#pref#_page_zone_multi_cta');
        Pelican_Db::$values = $saved;
    }

    /**
     *  Save data to CTA table.
     *
     * @param array $cta
     *
     * @return int
     */
    public static function saveCta($cta)
    {
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = $cta;
        Pelican_Db::$values['USED_COUNT'] = 0;
        Pelican_Db::$values['IS_REF'] = 0;
        $where = ' ID=0 ';
        if (isset($cta['CTA_ID'])) {
            $where = ' ID = '.intval($cta['CTA_ID']);
            Pelican_Db::$values['ID'] = $id = $cta['CTA_ID'];
        }
        self::$con->replaceQuery('#pref#_cta', $where);
        if (!isset($cta['CTA_ID'])) {
            $id = self::$con->getLastOid();
        }
        Pelican_Db::$values = $saved;

        return $id;
    }

    /**
     * @param array $multi
     */
    public static function savePageZoneMultiCta($multi)
    {
        if (isset($multi['TYPE_CTA'])) {
            switch ($multi['TYPE_CTA']) {
                case 'select':
                    self::addSelectedPageZoneMultiCta($multi);
                    break;
                case 'new';

                    self::addNewPageZoneMultiCta($multi);
                    break;
                default:
                //do nothing
            }
        }
    }

    /**
     * if not isset, set $defaultValue  to $this->values[$valueName] 
     * @param array &$values
     * @param string $valueName
     * @param mixed $defaultValue
     * @param boolean $forceSetValue (option) default false
     */
    public static function setDefaultValueTo(&$values, $valueName, $defaultValue, $forceSetValue = false)
    {
        if (!isset($values[$valueName]) || $forceSetValue) {
            $values[$valueName] = $defaultValue;
        }
    }
}
