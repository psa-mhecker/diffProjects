<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/MultiInterface.php';

/**
 * Gestion des page zone cta en mode Hmvc
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 21/03/2015
 */
class Ndp_Page_Zone_Cta_Hmvc extends Ndp_Page_Zone_Cta implements Ndp_Page_Cta_Interface
{

    const TABLE_NAME = 'page_zone_cta';

    /**
     * Méthode statique de sauvegarde des données d'un multi cta à enregistrer dans
     * la table page_zone_cta.
     * @return \Page_Zone_Cta
     */
    public function save()
    {
        $saved = Pelican_Db::$values;
        $forceStyle = "";
        if ($this->isCtaDropDown()) {
            $forceStyle = Pelican_Db::$values[$this->getCtaType()]['STYLE'];
        }
        if ($this->getTypeCtaDropDown()) {
            $forceStyle = Pelican_Db::$values[$this->getTypeCtaDropDown()]['STYLE'];
        }

        readMulti($this->getCtaType(), $this->getCtaType());
        $ctasValuesDb = Pelican_Db::$values[$this->getMulti().$this->getCtaType()];
        if (empty($ctasValuesDb)) {

            return $this;
        }

        $ctasValues = $this->setAllCtaId($ctasValuesDb);
        $this->hydrate($saved);

        if (is_array($ctasValues) && !empty($ctasValues)) {
            $ctaId = 1;
            foreach ($ctasValues as $ctaValues) {
                if (is_array($ctaValues) && !empty($ctaValues)) {

                    if (!empty($forceStyle)) {
                        $ctaValues['STYLE'] = $forceStyle;
                    }
                    $this->setId($ctaId)
                        ->saveCta($ctaValues);
                    $ctaId++;
                }
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
            $ctaValues['PAGE_VERSION'] = $this->getPageVersion();
            $ctaValues['PAGE_ID'] = $this->getPageId();
            $ctaValues['ZONE_TEMPLATE_ID'] = $this->getZoneTemplateId();
            $ctaValues['PAGE_ZONE_CTA_TYPE'] = $this->getType();
            $ctaValues['PAGE_ZONE_CTA_ID'] = $this->getId();
            $ctaValues = $this->addFieldCta($ctaValues);
            $saved = Pelican_Db::$values;
            Pelican_Db::$values = $ctaValues;
            Ndp_Cta::saveCta($ctaValues, self::TABLE_NAME);

            Pelican_Db::$values = $saved;
        }
    }
}
