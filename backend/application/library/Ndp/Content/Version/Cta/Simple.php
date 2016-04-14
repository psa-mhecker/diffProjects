<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta/Interface.php';

/**
 * Gestion des page Content zone cta.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 23/04/2015
 */
class Ndp_Content_Version_Cta_Simple extends Ndp_Content_Version_Cta implements Ndp_Content_Version_Cta_Interface
{

    const TABLE_NAME = 'content_version_cta';

    /**
     * Retourne les données de la table page_content_version_cta
     * @return array
     */
    public function getValues()
    {
        $values = [];
        if ($this->getContentId() != self::CONTENT_ID_NEW) {
            $bind[":PAGE_ID"] = $this->getPageId();
            $bind[":LANGUE_ID"] = $this->getLangueId();
            $bind[":CONTENT_VERSION"] = $this->getContentVersion();
            $bind[":CONTENT_ID"] = $this->getContentId();
            $bind[":PAGE_ZONE_CTA_TYPE"] = $this->getType();
            $sql = 'SELECT *,
                CASE
                    WHEN pcvc.TARGET = ""
                    THEN c.TARGET
                    ELSE pcvc.TARGET
                END as TARGET
                FROM #pref#_'.self::TABLE_NAME.' pcvc
                LEFT JOIN psa_cta c ON pcvc.cta_id = c.id and c.IS_REF = 0
                WHERE PAGE_ID = :PAGE_ID
                AND pcvc.LANGUE_ID = :LANGUE_ID
                AND pcvc.CONTENT_VERSION = :CONTENT_VERSION
                AND pcvc.CONTENT_ID = :CONTENT_ID
                AND pcvc.PAGE_ZONE_CTA_TYPE = ":PAGE_ZONE_CTA_TYPE"
                ORDER BY PAGE_ZONE_CTA_ORDER';
            $values = $this->getConnection()->queryTab($sql, $bind);
        }

        return parent::getValues($values);
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi cta à enregistrer dans
     * la table page_content_version_cta.
     * @return \Ndp_Content_Version_Cta
     */
    public function save()
    {
        $values = array_merge(Pelican_Db::$values, Pelican_Db::$values[$this->getType()]);
        if (empty($values['PAGE_ZONE_CTA_ID'])) {
            $values['PAGE_ZONE_CTA_ID'] = $this->getId();
        }

        Ndp_Content_Version_Cta::saveCta($values, self::TABLE_NAME);

        return $this;
    }

    /**
     * Supprime des données dans la table page_zone_cta
     * @return \Ndp_Content_Version_Cta
     */
    public function delete()
    {
        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':CONTENT_VERSION'] = Pelican_Db::$values['CONTENT_VERSION'];
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':CONTENT_ID'] = Pelican_Db::$values['CONTENT_ID'];
        $bind[':PAGE_ZONE_CTA_TYPE'] = $this->connection->strToBind($this->getType());
        if (Pelican_Db::$values['CONTENT_ID'] != self::CONTENT_ID_NEW) {
            $sql = 'DELETE c,pcvc FROM #pref#_'.self::TABLE_NAME.' pcvc, psa_cta c ';
            $sql2 = 'DELETE pcvc FROM #pref#_'.self::TABLE_NAME.' pcvc';

            $where2 = ' AND pcvc.cta_id = c.id and c.IS_REF = 0';
            $where = ' WHERE pcvc.LANGUE_ID = :LANGUE_ID
                    AND pcvc.CONTENT_VERSION = :CONTENT_VERSION
                    AND pcvc.PAGE_ID = :PAGE_ID
                    AND pcvc.CONTENT_ID = :CONTENT_ID
                    AND pcvc.PAGE_ZONE_CTA_TYPE = :PAGE_ZONE_CTA_TYPE';
            $this->connection->query($sql.$where.$where2, $bind);
            $this->connection->query($sql2.$where, $bind);
        }

        return $this;
    }

    /**
     * set tous les membres de la classe parent Cta
     * @param integer $pageId
     * @param integer $langueId
     * @param integer $contentVersion
     * @param integer $contentId
     * @param integer $ctaType
     * @param integer $ctaId
     *
     * @return \Ndp_Content_Version_Cta
     */
    public function setValues($pageId, $langueId, $contentVersion, $contentId, $ctaType, $ctaId)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setContentVersion($contentVersion);
        $this->setContentId($contentId);
        $this->setType($ctaType);
        $this->setId($ctaId);

        return $this;
    }
}
