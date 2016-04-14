<?php

include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta/Interface.php';

/**
 * Gestion des cta d'une liste deroulante d'un contenu.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 23/04/2015
 */
class Ndp_Content_Version_Cta_Cta extends Ndp_Content_Version_Cta implements Ndp_Content_Version_Cta_Interface
{
    const TABLE_NAME = 'content_version_cta_cta';

    /**
     * Retourne les données de la table page_content_version_cta_cta.
     *
     * @return array
     */
    public function getValues()
    {
        $values = [];
        if ($this->getContentId() != self::CONTENT_ID_NEW) {
            $bind[':PAGE_ID'] = $this->getPageId();
            $bind[':LANGUE_ID'] = $this->getLangueId();
            $bind[':CONTENT_VERSION'] = $this->getContentVersion();
            $bind[':CONTENT_ID'] = $this->getContentId();
            $bind[':PAGE_ZONE_CTA_TYPE'] = $this->getType();
            $bind[':PAGE_ZONE_CTA_ID'] = $this->getParentId();
            $sql = 'SELECT c.*, pcvcc.* ,
                  CASE
                    WHEN pcvcc.TARGET = ""
                    THEN c.TARGET
                    ELSE pcvcc.TARGET
                END as TARGET
                FROM #pref#_'.self::TABLE_NAME.' pcvcc
                LEFT JOIN psa_cta c ON pcvcc.cta_id = c.id and c.IS_REF = 0
                WHERE pcvcc.PAGE_ID = :PAGE_ID
                AND pcvcc.LANGUE_ID = :LANGUE_ID
                AND pcvcc.CONTENT_VERSION = :CONTENT_VERSION
                AND pcvcc.CONTENT_ID = :CONTENT_ID
                AND pcvcc.PAGE_ZONE_CTA_TYPE = ":PAGE_ZONE_CTA_TYPE"
                AND pcvcc.PAGE_ZONE_CTA_ID = :PAGE_ZONE_CTA_ID
                ORDER BY pcvcc.PAGE_ZONE_CTA_ORDER';
            $values = $this->connection->queryTab($sql, $bind);
        }

        return $values;
    }

    /**
     * @return Ndp_Content_Version_Cta_Cta
     */
    public function save()
    {
        $values = Pelican_Db::$values;

        if ($values['multi_display'] == self::MULTI_IS_DISPLAYED) {
            $values['LANGUE_ID'] = $this->getLangueId();
            $values['CONTENT_VERSION'] = $this->getContentVersion();
            $values['PAGE_ID'] = $this->getPageId();
            $values['CONTENT_ID'] = $this->getContentId();
            $values['PAGE_ZONE_CTA_TYPE'] = $this->getType();
            $values['PAGE_ZONE_CTA_ID'] = $this->getParentId();
            $values['PAGE_ZONE_CTA_ORDER'] = $this->getId();
            $values['PAGE_ZONE_CTA_CTA_ID'] = $this->getId();
            $values = $this->addFieldCta($values);

            Ndp_Content_Version_Cta::saveCta($values, self::TABLE_NAME);
        }

        return $this;
    }

    /**
     * Supprime des données dans la table page_zone_cta_cta.
     *
     * @return \Page_Zone_Cta_Cta
     */
    public function delete()
    {
        $bind[':LANGUE_ID'] = $this->getLangueId();
        $bind[':CONTENT_VERSION'] = $this->getContentVersion();
        $bind[':PAGE_ID'] = $this->getPageId();
        $bind[':CONTENT_ID'] = $this->getContentId();
        $bind[':PAGE_ZONE_CTA_TYPE'] = $this->getType();
        $bind[':PAGE_ZONE_CTA_ID'] = $this->getParentId();
        $bind[':PAGE_ZONE_CTA_CTA_ID'] = $this->getId();

        if ($this->getContentId() != null && $this->getContentId() != self::CONTENT_ID_NEW) {
            $sql = 'DELETE pcvcc,c FROM #pref#_'.self::TABLE_NAME.' pcvcc, psa_cta c ';
            $sql2 = 'DELETE pcvcc FROM #pref#_'.self::TABLE_NAME.' pcvcc';

            $where2 = ' AND pcvcc.cta_id = c.id and c.IS_REF = 0';
            $where = ' WHERE pcvcc.LANGUE_ID = :LANGUE_ID
                    AND pcvcc.CONTENT_VERSION = :CONTENT_VERSION
                    AND pcvcc.PAGE_ID = :PAGE_ID
                    AND pcvcc.CONTENT_ID = :CONTENT_ID
                    AND pcvcc.PAGE_ZONE_CTA_TYPE = ":PAGE_ZONE_CTA_TYPE"
                    AND pcvcc.PAGE_ZONE_CTA_CTA_ID = :PAGE_ZONE_CTA_CTA_ID
                    AND pcvcc.PAGE_ZONE_CTA_ID = :PAGE_ZONE_CTA_ID';
            //$this->connection->query($sql.$where.$where2, $bind);

            $this->connection->query($sql2.$where, $bind);
        }

        return $this;
    }

    /**
     * set tous les membres de la classe parent Cta.
     *
     * @param int $pageId
     * @param int $langueId
     * @param int $pageVersion
     * @param int $contentId
     * @param int $ctaType
     * @param int $ctaId
     * 
     * @return \Ndp_Content_Version_Cta_Cta
     */
    public function setValues($pageId, $langueId, $pageVersion, $contentId, $ctaType, $ctaId)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setPageVersion($pageVersion);
        $this->setContentId($contentId);
        $this->setCtaType($ctaType);
        $this->setCtaId($ctaId);

        return $this;
    }
}
