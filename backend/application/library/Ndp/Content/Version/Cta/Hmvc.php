<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta/Simple.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta/Interface.php';

/**
 * Gestion des  cta en mode Hmvc  dans une contenu
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 23/04/2015
 */
class Ndp_Content_Version_Cta_Hmvc extends Ndp_Content_Version_Cta_Simple implements Ndp_Content_Version_Cta_Interface
{

    const TABLE_NAME = 'content_version_cta';

    /**
     * Méthode statique de sauvegarde des données d'un multi cta à enregistrer dans
     * la table page_content_version_cta.
     * @return \page_content_version_cta
     */
    public function save()
    {
        $saved = Pelican_Db::$values;

        readMulti($this->getType(), $this->getType());
        $ctasValuesDb = Pelican_Db::$values[$this->getMulti().$this->getType()];
        if (empty($ctasValuesDb)) {

            return $this;
        }

        $ctasValues = $this->setAllCtaId($ctasValuesDb);
        $this->hydrate($saved);

        if (is_array($ctasValues) && !empty($ctasValues)) {
            $ctaId = 1;
            foreach ($ctasValues as $ctaValues) {
                $this->setId($ctaId);
                $this->saveCta($ctaValues);
                $ctaId++;
            }
        }
        Pelican_Db::$values = $saved;

        return $this;
    }

    /**
     *
     * @param array $ctaValues
     */
    public function saveCta(array $ctaValues)
    {
        if ($ctaValues['multi_display'] == self::MULTI_IS_DISPLAYED) {
            $ctaValues['LANGUE_ID'] = $this->getLangueId();
            $ctaValues['CONTENT_VERSION'] = $this->getContentVersion();
            $ctaValues['PAGE_ID'] = $this->getPageId();
            $ctaValues['CONTENT_ID'] = $this->getContentId();
            $ctaValues['PAGE_ZONE_CTA_TYPE'] = $this->getType();
            $ctaValues['PAGE_ZONE_CTA_ID'] = $this->getId();
            $ctaValues = $this->addFieldCta($ctaValues);
            $saved = Pelican_Db::$values;
            Pelican_Db::$values = $ctaValues;
            Ndp_Content_Version_Cta::saveCta($ctaValues, self::TABLE_NAME);

            Pelican_Db::$values = $saved;
        }
    }
}
