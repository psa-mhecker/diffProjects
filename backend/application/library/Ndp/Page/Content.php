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
class Ndp_Page_Content extends Ndp_Page
{
    protected $idPage = '';
    protected $idLang = '';
    protected $aSqlTablesDependance = array();
    protected $aSqlTable = array();
    protected $idPageNew;

    /**
     * Constructeur.
     */
    public function __construct($idPageNew)
    {
        $this->aSqlTable['#pref#_content'] = 'SELECT * FROM #pref#_content WHERE LANGUE_ID = :LANGUE_ID AND CONTENT_ID IN (:CONTENT_ID)';
        $this->idPageNew = $idPageNew;
        parent::__construct();
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public static function getTableDependance()
    {
        return array(
            '#pref#_content_version',
                        '#pref#_content_version_attribut',
                        '#pref#_content_version_content',
                        '#pref#_content_version_cta',
                        '#pref#_content_version_cta_cta',
                        '#pref#_content_version_media',
        );
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function init()
    {
        foreach (self::getTableDependance() as $table) {
            if (!empty($this->aBind[':CONTENT_DRAFT_VERSION']) && !empty($this->aBind[':CONTENT_CURRENT_VERSION'])) {
                $this->aSqlTablesDependance[$table] = 'SELECT * FROM '.$table.' WHERE CONTENT_ID = :CONTENT_ID AND LANGUE_ID = :LANGUE_ID AND CONTENT_VERSION IN ( :CONTENT_CURRENT_VERSION,:CONTENT_DRAFT_VERSION) ';
            } elseif (!empty($this->aBind[':CONTENT_DRAFT_VERSION'])) {
                $this->aSqlTablesDependance[$table] = 'SELECT * FROM '.$table.' WHERE CONTENT_ID = :CONTENT_ID AND LANGUE_ID = :LANGUE_ID AND CONTENT_VERSION = :CONTENT_DRAFT_VERSION ';
            } elseif (!empty($this->aBind[':CONTENT_CURRENT_VERSION'])) {
                $this->aSqlTablesDependance[$table] = 'SELECT * FROM '.$table.' WHERE CONTENT_ID = :CONTENT_ID AND LANGUE_ID = :LANGUE_ID AND CONTENT_VERSION = :CONTENT_CURRENT_VERSION ';
            }
        }
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function setInfosTables()
    {
        foreach (self::getTableDependance() as $table) {
            if (isset($this->aSqlTablesDependance[$table])) {
                $this->aDatasTables[$table] = $this->oConnection->queryTab($this->aSqlTablesDependance[$table], $this->aBind);
            }
        }
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function getIdNew()
    {
        return $this->idNew;
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function getIdPageNew()
    {
        return $this->idPageNew;
    }

    /**
     *
     */
    public function copieDependanceTable($langIdCible = 0, $siteIdCible = 0, $idNewsContent = 0)
    {
        $this->init();
        $this->setInfosTables();
        if ($langIdCible == 0 || $siteIdCible == 0 || $idNewsContent == 0) {
            return $this;
        }
        foreach (self::getTableDependance() as $table) {
            if (is_array($this->aDatasTables[$table])) {
                foreach ($this->aDatasTables[$table] as $key => $dataTable) {
                    if (isset($this->aDatasTables[$table][$key]['SITE_ID'])) {
                        $this->aDatasTables[$table][$key]['SITE_ID'] = $siteIdCible;
                    }
                    if (isset($this->aDatasTables[$table][$key]['LANGUE_ID'])) {
                        $this->aDatasTables[$table][$key]['LANGUE_ID'] = $langIdCible;
                    }
                    if (isset($this->aDatasTables[$table][$key]['CONTENT_ID'])) {
                        $this->aDatasTables[$table][$key]['CONTENT_ID'] = $idNewsContent;
                    }
                    if (isset($this->aDatasTables[$table][$key]['PAGE_ID'])) {
                        $this->aDatasTables[$table][$key]['PAGE_ID'] = $this->getIdPageNew();
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function copieDependanceTableXml($langIdCible, $siteIdCible, $idNewsContent, $idContentSource)
    {
        $this->setDatasTables($this->getDatasTables($idContentSource));
        foreach (self::getTableDependance() as $table) {
            if (is_array($this->aDatasTables[$table])) {
                foreach ($this->aDatasTables[$table] as $key => $dataTable) {
                    if (isset($this->aDatasTables[$table][$key]['SITE_ID'])) {
                        $this->aDatasTables[$table][$key]['SITE_ID'] = $siteIdCible;
                    }
                    if (isset($this->aDatasTables[$table][$key]['LANGUE_ID'])) {
                        $this->aDatasTables[$table][$key]['LANGUE_ID'] = $langIdCible;
                    }
                    if (isset($this->aDatasTables[$table][$key]['CONTENT_ID'])) {
                        $this->aDatasTables[$table][$key]['CONTENT_ID'] = $idNewsContent;
                    }
                    if (isset($this->aDatasTables[$table][$key]['PAGE_ID'])) {
                        $this->aDatasTables[$table][$key]['PAGE_ID'] = $this->getIdPageNew();
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function copie($pageIdSource, $langIdSource)
    {
        $aContentId = array();
        $aContent = $this->getAllIdContentByPageId($pageIdSource, $langIdSource);

        if (is_array($aContent)) {
            foreach ($aContent as $contentId) {
                $aContentId[] = $contentId['CONTENT_ID'];
            }
            $this->aBind[':CONTENT_ID'] = implode(',', $aContentId);
            $this->aBind[':LANGUE_ID'] = $langIdSource;
            $aDataContentCopie = $this->oConnection->queryTab($this->aSqlTable['#pref#_content'], $this->aBind);
        }
        if (isset($aDataContentCopie) && !empty($aDataContentCopie)) {
            return $aDataContentCopie;
        }

        return false;
    }

    /**
     *
     */
    public function colle($aDataContentCopie, $langIdCible, $siteIdCible, $contentIdParentCible = '', $bXml = false, $idContentSource = '')
    {
        if (is_array($aDataContentCopie)) {
            foreach ($aDataContentCopie as $dataContentCopie) {
                $this->aBind[':CONTENT_ID'] = $dataContentCopie['CONTENT_ID_OLD'];
                $dataContentCopie['LANGUE_ID'] = $langIdCible;
                $dataContentCopie['SITE_ID'] = $siteIdCible;
                $this->aBind[':CONTENT_CURRENT_VERSION'] = $dataContentCopie['CONTENT_CURRENT_VERSION'];
                $this->aBind[':CONTENT_DRAFT_VERSION'] = $dataContentCopie['CONTENT_DRAFT_VERSION'];
                if ($contentIdParentCible) {
                    $idContentNews = $this->insertData($dataContentCopie, '#pref#_content');
                } else {
                    $this->updateData($dataContentCopie, '#pref#_content');
                    $idContentNews = $dataContentCopie['CONTENT_ID'];
                }
                $aIdsContent[] = $idContentNews;
                if ($bXml) {
                    $this->copieDependanceTableXml($langIdCible, $siteIdCible, $idContentNews, $idContentSource);
                } else {
                    $this->copieDependanceTable($langIdCible, $siteIdCible, $idContentNews);
                }

                if ($contentIdParentCible) {
                    $this->save();
                } else {
                    $this->update();
                }
            }

            return $aIdsContent;
        }

        return false;
    }

    /**
     * Retourne ????
     *
     * @return array
     */
    public function getAllIdContentByPageId($pageId, $langId)
    {
        $this->aBind[':PAGE_ID'] = $pageId;
        $this->aBind[':LANGUE_ID'] = $langId;
        $sql = 'SELECT distinct CONTENT_ID FROM #pref#_content_version WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID';
        $aIdContent = $this->oConnection->queryTab($sql, $this->aBind);
        if (is_array($aIdContent) && !empty($aIdContent)) {
            return $aIdContent;
        }

        return false;
    }
}
