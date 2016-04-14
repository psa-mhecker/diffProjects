<?php

include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page.php';

/**
 * Gestion des pages.
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
class Ndp_Page_Cta extends Ndp_Page
{
    protected $idPage = '';
    protected $idLang = '';
    protected $aSqlTable = array();
    protected $ctaIdSource = [];
    protected $copiedCtas;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        // on ne copie que les cta qui ne sont pas dans le referentiel
        $this->aSqlTable['#pref#_cta'] = 'SELECT * FROM #pref#_cta WHERE IS_REF =0 AND LANGUE_ID = :LANGUE_ID AND ID IN (:CTA_ID)';
        $this->copiedCtas = [];
        $this->oConnection = Pelican_Db::getInstance();
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function getTableDependance()
    {
        return array(
            '#pref#_page_zone_cta',
            '#pref#_page_zone_cta_cta',
            '#pref#_page_zone_multi_cta',
            '#pref#_page_zone_multi_cta_cta',
            '#pref#_page_multi_zone_cta',
            '#pref#_page_multi_zone_cta_cta',
            '#pref#_page_multi_zone_multi_cta',
            '#pref#_page_multi_zone_multi_cta_cta',
        );
    }

    protected function searchCTA()
    {
        foreach ($this->getTableDependance() as $table) {
            if (is_array($this->aDatasTables[$table]) && !empty($this->aDatasTables[$table])) {
                foreach ($this->aDatasTables[$table] as $key => $dataTable) {
                    if (!empty($dataTable['CTA_ID']))
                    {
                        $this->ctaIdSource[] = $dataTable['CTA_ID'];
                    }
                }
            }
        }

        return $this;
    }



    /**
     * @param $langIdSource
     *
     * @return $this
     */
    public function copie($langIdSource)
    {
        $this->copiedCtas = [];
        $this->searchCTA();
        if (is_array($this->ctaIdSource) && !empty( $this->ctaIdSource)) {

            $this->aBind[':CTA_ID'] = implode(',', $this->ctaIdSource);
            $this->aBind[':LANGUE_ID'] = $langIdSource;
            $this->copiedCtas = $this->oConnection->queryTab($this->aSqlTable['#pref#_cta'], $this->aBind);
        }

        return  $this;
    }

    /**
     * @param int $langIdCible
     * @param int $siteIdCible

     * @return array
     *
     */
    public function colle($langIdCible, $siteIdCible)
    {
        $newIds = [];

        if (!empty($this->copiedCtas)) {
            foreach ($this->copiedCtas as $source) {
                // on doit créer un nouveau CTA pour le site cible langue cible
                $oldId = $source['ID'];
                unset($source['ID']);// on supprime l'id existant
                $source['LANGUE_ID'] = $langIdCible; // change la langue
                $source['SITE_ID'] = $siteIdCible; // change le site
                $source['ACTION'] = ''; // on vide l'url qui ne sera plus valide de toute façon
                $newCtaId = $this->insertData($source, '#pref#_cta');
                // on fait un mapping des id de cta
                $newIds[$oldId] = $newCtaId;

            }
            $this->updateDependanceTables($newIds);
        }

        return $newIds;
    }

    protected function updateDependanceTables($newIds)
    {
        foreach ($this->getTableDependance() as $table) {
            if (is_array($this->aDatasTables[$table]) && !empty($this->aDatasTables[$table])) {
                foreach ($this->aDatasTables[$table] as $key => $dataTable) {
                    $newValue = null;
                    if (!empty($dataTable['CTA_ID']) && !empty($newIds[$dataTable['CTA_ID']]))
                    {

                        $newValue = $newIds[$dataTable['CTA_ID']];
                    }
                    $this->aDatasTables[$table][$key]['CTA_ID']= $newValue;
                }
            }
        }

        return $this;
    }
}
