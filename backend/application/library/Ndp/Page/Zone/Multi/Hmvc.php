<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Interface.php';

/**
 * Gestion des page zone multi.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 18/03/2015
 */
class Ndp_Page_Zone_Multi_Hmvc extends Ndp_Multi implements Ndp_Page_Multi_Interface
{

    const TABLE_NAME = 'page_zone_multi';

    /**
     * Retourne les données de la table page_zone_multi
     *
     * @return array
     */
    public function getValues()
    {
        $this->connection = Pelican_Db::getInstance();
        $values = [];
        if ($this->getPageId() != self::PAGE_ID_NEW) {
            $bind[":PAGE_ID"] = $this->getPageId();
            $bind[":LANGUE_ID"] = $this->getLangueId();
            $bind[":PAGE_VERSION"] = $this->getPageVersion();
            $bind[":ZONE_TEMPLATE_ID"] = $this->getZoneTemplateId();
            $bind[":PAGE_ZONE_MULTI_TYPE"] = $this->getMultiType();
            $sql = 'SELECT *
                FROM #pref#_'.self::TABLE_NAME.'                
                WHERE PAGE_ID = :PAGE_ID
                AND LANGUE_ID = :LANGUE_ID
                AND PAGE_VERSION = :PAGE_VERSION
                AND ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                AND PAGE_ZONE_MULTI_TYPE = ":PAGE_ZONE_MULTI_TYPE"
                ORDER BY PAGE_ZONE_MULTI_ORDER';
            $values = $this->connection->queryTab($sql, $bind);
        }

        return $values;
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi à enregistrer dans
     * la table page_zone_multi.
     *
     */
    public function save()
    {
        $dataSaved = Pelican_Db::$values;
        readMulti($this->getMultiType(), $this->getMultiType());
        $multisValues = Pelican_Db::$values[$this->getMulti().$this->getMultiType()];
        $multisValues = $this->setAllMultiId($multisValues);
        $this->hydrate($dataSaved);
        if (!is_array($multisValues) || empty($multisValues)) {
            return $this;
        }
        $id = 1;
        foreach ($multisValues as $multiValues) {
            if ($multiValues['multi_display'] == self::MULTI_IS_DISPLAYED) {
                $this->setMultiId($id)
                    ->saveMulti($multiValues)
                    ->saveChilds($dataSaved, $multiValues, $id);

                $id++;
            }
        }
        Pelican_Db::$values = $dataSaved;

        return $this;
    }

    /**
     * 
     * @param array $dataSaved
     * @param array $multiValues
     * @param integer $id
     * 
     * @return \Ndp_Page_Zone_Multi_Hmvc
     */
    public function saveChilds($dataSaved, $multiValues, $id)
    {
        $childs = $this->getChilds();
        if (empty($childs) || !is_array($childs)) {

            return $this;
        }
        $savedDataParent = Pelican_Db::$values;
        $multiValues['LANGUE_ID'] = $this->getLangueId();
        $multiValues['PAGE_VERSION'] = $this->getPageVersion();
        $multiValues['PAGE_ID'] = $this->getPageId();
        $multiValues['ZONE_TEMPLATE_ID'] = $this->getZoneTemplateId();
        $multiValues['PAGE_ZONE_MULTI_TYPE'] = $this->getMultiType();
        $multiValues['PAGE_ZONE_MULTI_ID'] = $this->getMultiId();
        Pelican_Db::$values = $multiValues;
        foreach ($childs as $child) {
            $child->setId($id)
                ->hydrate($dataSaved)
                ->save();
        }
        Pelican_Db::$values = $savedDataParent;
    }

    /**
     * 
     * @param array $multiValues

     * @return \Ndp_Page_Zone_Multi_Hmvc
     */
    private function saveMulti(array $multiValues)
    {
        $this->connection = Pelican_Db::getInstance();
        
        $multiValues['LANGUE_ID'] = $this->getLangueId();
        $multiValues['PAGE_VERSION'] = $this->getPageVersion();
        $multiValues['PAGE_ID'] = $this->getPageId();
        $multiValues['ZONE_TEMPLATE_ID'] = $this->getZoneTemplateId();
        $multiValues['PAGE_ZONE_MULTI_TYPE'] = $this->getMultiType();
        $multiValues['PAGE_ZONE_MULTI_ID'] = $this->getMultiId();
        $multiValues = $this->addFieldMulti($multiValues);
        $saved = Pelican_Db::$values;
        Pelican_Db::$values = $multiValues;
        $this->connection->insertQuery(sprintf('#pref#_%s', self::TABLE_NAME));
        Pelican_Db::$values = $saved;

        return $this;
    }

    /**
     * Supprime des données dans la table page_zone_multi
     *
     * @return $this
     */
    public function delete()
    {
        $this->connection = Pelican_Db::getInstance();
        
        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $bind[':PAGE_ZONE_MULTI_TYPE'] = $this->connection->strToBind($this->getMultiType());
        $childs = $this->getChilds();
        if (is_array($childs) && !empty($childs)) {
            foreach ($childs as $child) {
                $child->delete();
            }
        }
        if (Pelican_Db::$values['PAGE_ID'] != self::PAGE_ID_NEW) {
            $sql = 'DELETE FROM #pref#_'.self::TABLE_NAME.'
                    WHERE LANGUE_ID = :LANGUE_ID
                    AND PAGE_VERSION = :PAGE_VERSION
                    AND PAGE_ID = :PAGE_ID
                    AND ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                    AND PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE';
            $this->connection->query($sql, $bind);
        }

        return $this;
    }

    /**
     * set tous les membres de la classe parent multi
     * @param integer $pageId
     * @param integer $langueId
     * @param integer $pageVersion
     * @param integer $zoneTemplateId
     * @param string $multiType
     * @param integer $multiId
     * @param integer $zoneOrder
     * 
     * @return $this
     */
    public function setValues($pageId, $langueId, $pageVersion, $zoneTemplateId, $multiType, $multiId, $zoneOrder)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setPageVersion($pageVersion);
        $this->setZoneTemplateId($zoneTemplateId);
        $this->setMultiType($multiType);
        $this->setMultiId($multiId);

        return $this;
    }
}
