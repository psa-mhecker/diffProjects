<?php

/**
 * Gestion des pages
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
include_once(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Page.php');
include_once(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Content.php');

class Ndp_Page_PageComposite
{

    /**
     * @var bool
     */
    private $keepHierarchy;

    /**
     * @var array
     */
    private $ancestors = [];
    /**
     * @var array
     */
    private $pageBySiteLang = [];

    /**
     * @var array
     */
    private $aPage = array();

    /**
     * @var array
     */
    private $mappedPage = [];

    /**
     * @return boolean
     */
    public function isKeepHierarchy()
    {
        return $this->keepHierarchy;
    }

    /**
     * @param boolean $keepHierarchy
     * @return Ndp_Page_PageComposite
     */
    public function setKeepHierarchy($keepHierarchy)
    {
        $this->keepHierarchy = $keepHierarchy;

        return $this;
    }


    /**
     * @param $siteId
     * @param $langueId
     *
     * @return array
     *
     */
    public function getPagesParentes($siteId, $langueId)
    {
        if(!isset($this->ancestors[$siteId][$langueId])) {
            $connection = Pelican_Db::getInstance();
            $bind = [];
            $bind[':SITE_ID'] = $siteId;
            $bind[':LANGUE_ID'] = $langueId;
            $sql = 'SELECT
                        DISTINCT p.PAGE_PARENT_ID, p.PAGE_ID
                      FROM #pref#_page p
                      WHERE
                        p.LANGUE_ID =:LANGUE_ID
                        AND p.SITE_ID=:SITE_ID
                        AND p.PAGE_ID IN ('.implode(',',$this->pageBySiteLang[$siteId][$langueId]).')';
            $res = $connection->queryTab($sql, $bind);
            $this->ancestors[$siteId][$langueId] = [];
            foreach ($res  as $row) {
                $this->ancestors[$siteId][$langueId][$row['PAGE_ID']] = $row['PAGE_PARENT_ID'];
            }
        }


        return $this->ancestors[$siteId][$langueId];
    }

    public function getNewPageIdParent($idPageParent)
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT DISTINCT p.PAGE_ID FROM #pref#_page p WHERE PAGE_ID_SOURCE = ".$idPageParent;
        $newPageParentId = $oConnection->queryRow($sSQL);
        if (isset($newPageParentId['PAGE_ID']) && !empty($newPageParentId['PAGE_ID'])) {
            return $newPageParentId['PAGE_ID'];
        }
        return null;
    }

    /**
     * @param $pageIdSource
     * @param $langIdSource
     * @param $siteIdSource
     * @param $langIdCible
     * @param $siteIdCible
     * @param $bXml
     * @param $etat
     * @param $surchargeTitleBo
     * @param $bDiffusion
     * @param string $pageIdCible
     */
    public function addPage($pageIdSource, $langIdSource, $siteIdSource, $langIdCible, $siteIdCible, $bXml, $etat, $surchargeTitleBo, $bDiffusion, $pageIdCible = '')
    {
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['SITE_ID_SOURCE'] = $siteIdSource;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['LANG_ID_SOURCE'] = $langIdSource;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['ETAT'] = $etat;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['SURCHARGE_TITLE_BO'] = $surchargeTitleBo;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['XML'] = $bXml;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['DIFFUSION'] = $bDiffusion;
        $this->aPage[$pageIdSource][$siteIdCible][$langIdCible]['PAGE_ID_CIBLE'] = $pageIdCible;
        $this->pageBySiteLang[$siteIdSource][$langIdSource][] = $pageIdSource;
    }

    /**
     *
     */
    public function save()
    {


        if (!empty($this->aPage)) {
            $aPage = array();

            foreach ($this->aPage as $pageIdSource => $aPageInfos) {
                foreach ($aPageInfos as $siteIdCible => $aPageInfo) {
                    unset($idNewsPage);
                    unset($aIdsContent);

                    foreach ($aPageInfo as $langIdCible => $pageInfo) {

                        $oPage = new Ndp_Page_Page();
                        $siteIdSource = $pageInfo['SITE_ID_SOURCE'];
                        $langIdSource = $pageInfo['LANG_ID_SOURCE'];
                        $bXml = $pageInfo['XML'];
                        $etat = $pageInfo['ETAT'];
                        $surchargeTitleBo = $pageInfo['SURCHARGE_TITLE_BO'];
                        $bDiffusion = $pageInfo['DIFFUSION'];
                        $pageIdParentCible = $pageInfo['PAGE_ID_CIBLE'];
                        if (empty($pageInfo['PAGE_ID_CIBLE'])) {
                            $pageIdParentCible = $oPage->getPageIdAccueilBySiteId($siteIdCible);
                        }

                        if($this->isKeepHierarchy()) {
                            $parents = $this->getPagesParentes($siteIdSource, $langIdSource);

                            $parentIdSource = $parents[$pageIdSource];
                            if(isset($this->mappedPage[$siteIdCible][$langIdCible][$parentIdSource]))
                            {
                                $pageIdParentCible = $this->mappedPage[$siteIdCible][$langIdCible][$parentIdSource];
                            }
                        }

                        if ($pageIdParentCible) {
                            $aDataPageCopie = $oPage->copie($pageIdSource, $langIdSource, $siteIdSource);
                            $newPageParentId = $this->getNewPageIdParent($pageIdParentCible);
                            if (isset($newPageParentId) && !empty($newPageParentId)) {
                                $pageIdParentCible = $newPageParentId;
                            }
                            if (!isset($idNewsPage)) {
                                unset($aDataPageCopie['PAGE_ID']);
                            } else {
                                $aDataPageCopie['PAGE_ID'] = $idNewsPage;
                            }
                            $aDataPageCopie['PAGE_ID_SOURCE'] = $pageIdSource;
                            $aDataPageCopie['PAGE_ORDER'] = $pageIdSource;
                            $idNewsPage = $oPage->colle($aDataPageCopie, $langIdCible, $siteIdCible, $pageIdParentCible, $bXml, $etat, $surchargeTitleBo, $bDiffusion);
                            if (isset($idNewsPage) && !empty($idNewsPage)) {
                                $this->mappedPage[$siteIdCible][$langIdCible][$pageIdSource]= $idNewsPage;
                                $aDataPageCopie['PAGE_ID'] = $idNewsPage;
                                $page['PAGE']['PAGES_ID_NEWS'][] = $idNewsPage;
                                
                                $page['PAGE']['PAGES_ID_RACINE'][] = $pageIdParentCible;

                                $ctaManager = new Ndp_Page_Cta();
                                $ctaManager->setDatasTables($oPage->getDatasTables())
                                           ->copie($langIdSource)
                                           ->colle($langIdCible, $siteIdCible);
                                $oPage->setDatasTables($ctaManager->getDatasTables());

                                $oPage->save();
                                $oContent = new Ndp_Page_Content($idNewsPage);
                                $aDataContentsCopie = $oContent->copie($pageIdSource, $langIdCible);

                                if (!empty($aDataContentsCopie)) {
                                    foreach ($aDataContentsCopie as $key => $aDataContentCopie) {
                                        $aDataContentsCopie[$key]['CONTENT_ID_OLD'] = $aDataContentsCopie[$key]['CONTENT_ID'];
                                        if (!isset($aIdsContent)) {
                                            unset($aDataContentsCopie[$key]['CONTENT_ID']);
                                        } else {
                                            $aDataContentsCopie[$key]['CONTENT_ID'] = $aIdsContent[$key];
                                        }
                                    }
                                    $aIdsContent = $oContent->colle($aDataContentsCopie, $langIdCible, $siteIdCible, true);
                                }
                                $dataTables = $oPage->getDatasTables();
                                $aPage['ERROR'] = false;
                                $aPage['PAGE'][$siteIdCible][$aDataPageCopie['PAGE_ID']] = $dataTables;

                                // Mémorisation du pid de chaque nouvelle page crée
                                if (isset($dataTables['#pref#_page_version'])) {
                                    foreach ($dataTables['#pref#_page_version'] as $key => $val) {
                                        if (empty($aPage['NEW_PID']) || !in_array($val['PAGE_ID'], $aPage['NEW_PID'])) {
                                            $aPage['NEW_PID'][] = $val['PAGE_ID'];
                                        }
                                    }
                                }
                            } else {
                                $aPage['ERROR'][$pageIdSource][$langIdSource]['PAGE_ID_NEW'] = true;
                            }
                        } else {
                            $aPage['ERROR'][$pageIdSource][$langIdSource]['PAGE_ID_PARENT_CIBLE'] = true;
                        }
                    }
                }
            }
            $this->resetPagesIdSourceEtPageOrder($page['PAGE']['PAGES_ID_NEWS']);
            
            $pathCible = $oPage->getPathByPageId($page['PAGE']['PAGES_ID_RACINE'][0]);
            if (isset($pathCible) && !empty($pathCible)) {
                $oPage->updatePathParentAndChildren($page['PAGE']['PAGES_ID_RACINE'][0], $page['PAGE']['PAGES_ID_NEWS'][0]);
            }
            unset($page['PAGE']);
            return $aPage;

        }
    }

    public function resetPagesIdSourceEtPageOrder($aIdPage)
    {
        $oConnection = Pelican_Db::getInstance();
        if (is_array($aIdPage)) {
            $sSQL = "UPDATE #pref#_page SET PAGE_ID_SOURCE = NULL, PAGE_ORDER = PAGE_ID WHERE `PAGE_ID`IN (".implode(',', $aIdPage).")";
            $oConnection->query($sSQL);
        }
        return null;
    }

    public function verifGabaritUnique($pageIdSource, $langIdSource, $langIdCible, $siteIdCible)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind = array(
            ':PAGE_ID' => $pageIdSource,
            ':LANGUE_ID' => $langIdSource
        );

        $sql = "
            SELECT pv.template_page_id, pt.page_type_id, pt.page_type_unique, pt.page_type_one_use
            FROM
                #pref#_page p
                INNER JOIN #pref#_page_version pv
                    on (p.page_id = pv.page_id and p.langue_id = pv.langue_id and p.page_draft_version = pv.page_version)
                INNER JOIN #pref#_template_page tp
                    on (pv.template_page_id = tp.template_page_id)
                INNER JOIN #pref#_page_type pt
                    on (tp.page_type_id = pt.page_type_id)
            WHERE p.page_id = :PAGE_ID
            AND p.langue_id = :LANGUE_ID";
        $result = $oConnection->queryRow($sql, $aBind);

        if ($result && $result['page_type_one_use']) {
            $aBind = array(
                ':PAGE_TYPE_ID' => $result['page_type_id'],
                ':LANGUE_ID' => $langIdCible,
                ':SITE_ID' => $siteIdCible
            );
            $sqlCible = "
                SELECT p.page_id
                FROM 
                    #pref#_page p
                    INNER JOIN #pref#_page_version pv
                        on (p.page_id = pv.page_id and p.langue_id = pv.langue_id and p.page_draft_version = pv.page_version)
                    INNER JOIN #pref#_template_page tp
                        on (pv.template_page_id = tp.template_page_id)
                    INNER JOIN #pref#_page_type pt
                        on (tp.page_type_id = pt.page_type_id)
                WHERE pt.page_type_id = :PAGE_TYPE_ID
                AND pv.langue_id = :LANGUE_ID
                AND p.site_id = :SITE_ID
            ";
            $resultUse = $oConnection->queryRow($sqlCible, $aBind);
            if ($resultUse['page_id'] != '') {
                return true;
            }
        }
        return false;
    }
}
