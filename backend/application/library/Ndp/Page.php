<?php

/**
 * Gestion des pages.
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
class Ndp_Page
{
    protected $oConnection;
    protected $idVersionCurrent;
    protected $idVersionDraft;
    protected $aDatasTables = array();
    // Liste des pid des pages filles mises à jour lors de la suppression de leur page parente
    public static $trashUpdatedPages = array();

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->oConnection = Pelican_Db::getInstance();
    }

    /**
     * @param array $datas
     *
     * @param $table
     */
    public function insertMultipleData(array $datas, $table) {
        foreach ($datas as $data) {
            Pelican_Db::$values = $data;
            if (!empty(Pelican_Db::$values)) {
                $this->oConnection->insertQuery($table);
            }
        }
    }

    /**
     * @param $aData
     * @param $table
     *
     * @return
     */
    public function insertData(array $aData, $table)
    {
        Pelican_Db::$values = $aData;
        $this->oConnection->insertQuery($table);

        return $this->oConnection->getLastOid();
    }

    public function replaceData($aData, $table)
    {
        $this->oConnection = Pelican_Db::getInstance();
        if (is_array($aData)) {
            if ($table == '#pref#_page' || $table == '#pref#_content') {
                Pelican_Db::$values = $aData;
                $key = '';
                if ($table == '#pref#_page') {
                    $key = 'PAGE_ID';
                }
                if ($table == '#pref#_content') {
                    $key = 'CONTENT_ID';
                }

                $aBind = [':'.$key => Pelican_Db::$values[$key]];
                $count = $this->oConnection->queryItem('select count(*)  from '.$table." where $key = :".$key, $aBind);
                if ($count) {
                    $action = 'update';
                    $this->oConnection->updateQuery($table);

                    return Pelican_Db::$values[$key];
                } else {
                    $action = 'insert';
                    $this->oConnection->insertQuery($table);

                    return mysql_insert_id();
                }

                $this->oConnection->insertQuery($table);
            } else {
                foreach ($aData as $data) {
                    Pelican_Db::$values = $data;
                    if (isset(Pelican_Db::$values) && !empty(Pelican_Db::$values)) {
                        $this->oConnection->insertQuery($table);
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function save()
    {
        if (is_array($this->getDatasTables())) {
            foreach ($this->getDatasTables() as $table => $aInfosTable) {
                $this->insertMultipleData($aInfosTable, $table);
            }
        }
    }

    /**
     *
     */
    public function updateData($aData, $table)
    {
        $this->oConnection = Pelican_Db::getInstance();
        if (is_array($aData[0])) {
            foreach ($aData as $data) {
                Pelican_Db::$values = $data;
                if (isset(Pelican_Db::$values) && !empty(Pelican_Db::$values)) {
                    $this->oConnection->updateQuery($table);
                }
            }
        } else {
            Pelican_Db::$values = $aData;
            if (isset(Pelican_Db::$values) && !empty(Pelican_Db::$values)) {
                $this->oConnection->updateQuery($table);
            }
        }
    }

    /**
     *
     */
    public function update()
    {
        if (is_array($this->getDatasTables())) {
            foreach ($this->getDatasTables() as $table => $aInfosTable) {
                $this->updateData($aInfosTable, $table);
            }
        }
    }

    /**
     * Retourne.
     *
     * @return array
     */
    public function getIdVersionCurrent()
    {
        return $this->idVersionCurrent;
    }

    /**
     * Retourne.
     *
     * @return array
     */
    public function getIdVersionDraft()
    {
        return $this->idVersionDraft;
    }

    /**
     * Retourne.
     *
     * @return array
     */
    public function getDatasTables($idContent = '')
    {
        if (empty($idContent)) {
            return $this->aDatasTables;
        }

        return $this->aDatasTables[$idContent];
    }

    /**
     * Retourne.
     *
     * @param $aDatasTables
     *
     * @return $this
     */
    public function setDatasTables($aDatasTables)
    {
        $this->aDatasTables = $aDatasTables;

        return $this;
    }

    public function getPathByPageId($pageId)
    {
        $oConnection = Pelican_Db::getInstance();
        if(!empty($pageId)) {
            $sSQL = 'SELECT DISTINCT p.PAGE_PATH FROM #pref#_page p WHERE PAGE_ID = '.$pageId;
            $newPageParentId = $oConnection->queryRow($sSQL);
            if (!empty($newPageParentId['PAGE_PATH'])) {
                return $newPageParentId['PAGE_PATH'];
            }
        }

        return false;
    }

    public function updatePathParentAndChildren($iParentId, $iPageId, $iOrder = null)
    {
        $oConnection = Pelican_Db::getInstance();
        $sFindPagesSql = 'SELECT  
								 DISTINCT p.LANGUE_ID,
								  p.PAGE_ID,
								  p.PAGE_PATH,
								  p.PAGE_LIBPATH,
								  p.LANGUE_ID,
								  pv.PAGE_TITLE_BO					  
		 FROM #pref#_page p INNER JOIN #pref#_page_version pv 
		 ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
		 WHERE p.PAGE_ID = :PAGE_ID
		 GROUP BY p.langue_id
		 ';

        $aBind = array(
            ':PAGE_ID' => $iPageId,
            ':PARENT_ID' => $iParentId,
            ':ORDER' => $iOrder,
        );

        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

        $aPagesByLang = array();

        foreach ($aPages as $page) {
            $aPagesByLang[$page['LANGUE_ID']] = $page;
            $this->addCmsPageParentByLanguage($iParentId, $page['LANGUE_ID']);
        }

        $sFindPagesSql = 'SELECT  
								 DISTINCT p.LANGUE_ID,
								  p.PAGE_ID,
								  p.PAGE_PATH,
								  p.PAGE_LIBPATH,
								  p.LANGUE_ID,
								  pv.PAGE_TITLE_BO					  
		 FROM #pref#_page p INNER JOIN #pref#_page_version pv 
		 ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
		 WHERE p.PAGE_ID = :PAGE_ID
		 GROUP BY p.langue_id
		 ';


        $aBind[':PAGE_ID'] = $iParentId;
        $aParents = $oConnection->queryTab($sFindPagesSql, $aBind);
        //Update des donn�es de la page
        //on prends le premier page path vu que c'est des valeurs numeriques
        $sNewPagePath = sprintf('%s#%s', $aParents[0]['PAGE_PATH'], $iPageId);

        foreach ($aParents as $parent) {
            if (isset($aPagesByLang[$parent['LANGUE_ID']])) {
                $sNewPagePathLib = sprintf(
                    '%s#%s|%s', $parent['PAGE_LIBPATH'], $iPageId, trim(
                        $aPagesByLang[$parent['LANGUE_ID']]['PAGE_TITLE_BO']
                    )
                );

                $aBind = array(
                    ':PAGE_ID' => $iPageId,
                    ':PAGE_PARENT_ID' => intval($parent['PAGE_ID']),
                    ':PAGE_PATH' => $oConnection->strtobind($sNewPagePath),
                    ':PAGE_LIBPATH' => $oConnection->strtobind($sNewPagePathLib),
                    ':LANGUE_ID' => $parent['LANGUE_ID'],
                );

                $sUpdatePageSql = 'UPDATE #pref#_page set PAGE_PARENT_ID=:PAGE_PARENT_ID,PAGE_PATH=:PAGE_PATH, PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID';
                $oConnection->query($sUpdatePageSql, $aBind);
                $aBind = array(
                    ':PAGE_PARENT_ID' => $iPageId,
                );
                $sGetChildrenSql = 'SELECT p.PAGE_ID FROM #pref#_page as p WHERE p.PAGE_PARENT_ID=:PAGE_PARENT_ID GROUP BY PAGE_ID';
                $aChildren = $oConnection->queryTab($sGetChildrenSql, $aBind);

                if (count($aChildren)) {
                    foreach ($aChildren as $aChildPage) {
                        $this->updatePathParentAndChildren($iPageId, $aChildPage['PAGE_ID']);
                    }
                }
            }
        }
    }

    protected function addCmsPageParentByLanguage($pid, $langue_id)
    {
        if ($pid != '') {
            $oConnection = Pelican_Db::getInstance();
            $aBindParent[':ID'] = $pid;
            $aBindParent[':LANGUE_ID'] = $langue_id;
            $existsParent = $oConnection->queryItem('select count(*) from #pref#_page where PAGE_ID=:ID AND LANGUE_ID=:LANGUE_ID', $aBindParent);
            if (!$existsParent) {
                // si pas de page parente dans une langue, on copie les datas d'une autre langue
                $sql = 'select PAGE_PARENT_ID, TEMPLATE_PAGE_ID, PAGE_ORDER, PAGE_PATH, PAGE_GENERAL, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_SEARCH, PAGE_TITLE, PAGE_TITLE_BO from #pref#_page p inner join
							#pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_DRAFT_VERSION=pv.PAGE_VERSION AND p.LANGUE_ID=pv.LANGUE_ID)
							WHERE p.PAGE_ID=:ID';
                $valParentLangue = $oConnection->queryRow($sql, $aBindParent);

                $sPageLibPath = $this->createPageLibPath($valParentLangue, $langue_id);

                // PAGE
                $aBindParentAdd [':ID'] = $aBindParent [':ID'];
                $aBindParentAdd [':VERSION'] = 1;
                $aBindParentAdd [':LANGUE_ID'] = $aBindParent[':LANGUE_ID'];
                $aBindParentAdd [':SITE'] = $_SESSION [APP]['SITE_ID'];
                $aBindParentAdd [':PAGE_PARENT_ID'] = $valParentLangue ['PAGE_PARENT_ID'];
                $aBindParentAdd [':PAGE_ORDER'] = $valParentLangue ['PAGE_ORDER'];
                $aBindParentAdd [':PAGE_GENERAL'] = $valParentLangue ['PAGE_GENERAL'];
                $aBindParentAdd [':PAGE_PATH'] = $oConnection->strtobind($valParentLangue ['PAGE_PATH']);
                $aBindParentAdd [':PAGE_LIBPATH'] = $oConnection->strtobind($sPageLibPath);

                // page version
                $aBindParentAdd [':TEMPLATE_PAGE_ID'] = $valParentLangue ['TEMPLATE_PAGE_ID'];
                $aBindParentAdd [':PAGE_DISPLAY'] = $valParentLangue ['PAGE_DISPLAY'];
                $aBindParentAdd [':PAGE_DISPLAY_NAV'] = $valParentLangue ['PAGE_DISPLAY_NAV'];
                $aBindParentAdd [':PAGE_DISPLAY_SEARCH'] = $valParentLangue ['PAGE_DISPLAY_SEARCH'];
                $aBindParentAdd [':STATE_ID'] = 1;
                $aBindParentAdd [':TITLE'] = $oConnection->strtobind('['.$valParentLangue ['PAGE_TITLE'].']');
                $aBindParentAdd [':TITLE_BO'] = $oConnection->strtobind('['.$valParentLangue ['PAGE_TITLE_BO'].']');

                $sql = 'insert into #pref#_page (PAGE_ID, PAGE_DRAFT_VERSION, LANGUE_ID, PAGE_ORDER,SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL, PAGE_PATH,PAGE_LIBPATH) VALUES (
				:ID, :VERSION, :LANGUE_ID, :PAGE_ORDER,:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL, :PAGE_PATH,:PAGE_LIBPATH)';
                $oConnection->query($sql, $aBindParentAdd);
                // PAGE_LIB_PATH est fait lors d'un enregistrement reel par la suite

                $sql = 'insert into #pref#_page_version
					(PAGE_ID, PAGE_VERSION, LANGUE_ID, TEMPLATE_PAGE_ID, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_SEARCH, STATE_ID, PAGE_TITLE_BO, PAGE_TITLE
					) VALUES (
					:ID, :VERSION, :LANGUE_ID, :TEMPLATE_PAGE_ID,:PAGE_DISPLAY,:PAGE_DISPLAY_NAV,:PAGE_DISPLAY_SEARCH, :STATE_ID, :TITLE_BO,:TITLE
					)';
                $oConnection->query($sql, $aBindParentAdd);

                // on continue recursivement
                $this->addCmsPageParentByLanguage($valParentLangue ['PAGE_PARENT_ID'], $langue_id);
            }
        }
    }

    protected function createPageLibPath($originalPage, $iLangueId)
    {
        $oConnection = Pelican_Db::getInstance();
        $aParentsIds = explode('#', $originalPage['PAGE_PATH']);

        //fetch pages for language

        $sFindPagesSql = 'SELECT  
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO					  
			 	FROM #pref#_page p INNER JOIN #pref#_page_version pv 
			 	ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 	WHERE p.PAGE_ID IN (:PAGES_IDS) AND p.LANGUE_ID=:LANGUE_ID
			 	';

        $aBind = array(
            ':LANGUE_ID' => $iLangueId,
            ':PAGES_IDS' => $oConnection->strtobind(implode(',', $aParentsIds)),
        );
        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);
        $aPageLibPath = array();
        foreach ($aParentsIds as $iParentId) {
            foreach ($aPages as $aPage) {
                if ($aPage['PAGE_ID'] == $iParentId) {
                    $aPageLibPath[] = sprintf('%s|%s', $aPage['PAGE_ID'], $aPage['PAGE_TITLE_BO']);
                }
            }
        }
        $sPageLibPath = implode('#', $aPageLibPath);
        $sPageLibPath = sprintf('%s#%s|[%s]', $sPageLibPath, $originalPage['PAGE_ID'], $originalPage['PAGE_TITLE_BO']);

        return $sPageLibPath;
    }
    /*
     *  mise a jour, l'etat historique des version de la page est gardée
     *  seules la dernier version de brouillon PAGE_DRAFT_VERSION et (si elle existe)
     *     la derniere version de publication PAGE_CURRENT_VERSION
     *  sont passées en status corbeille le tout en prenant en compte la langue
     * 
     * Retire une page de la corbeille à partir d'un parent (de restoreAction)
     * @param int $pageID id de la page à restaurer
     */

    public static function _updateChildPage($pageID, $stateId)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind = [
            ':STATE_ID' => $stateId,
            ':PAGE_ID' => $pageID,
        ];
        $stmt = 'SELECT LANGUE_ID, PAGE_ID, PAGE_DRAFT_VERSION,PAGE_CURRENT_VERSION FROM #pref#_page  WHERE PAGE_PARENT_ID = :PAGE_ID OR PAGE_ID = :PAGE_ID  group by  PAGE_ID,LANGUE_ID';
        $result = $oConnection->queryTab($stmt, $aBind);

        $pidList = array();
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                if (empty($val['PAGE_ID'])) {
                    continue;
                }
                $pidList[$val['LANGUE_ID'].'-'.$val['PAGE_ID']] = [
                    'PAGE_CURRENT_VERSION' => $val['PAGE_CURRENT_VERSION'],
                    'PAGE_DRAFT_VERSION' => $val['PAGE_DRAFT_VERSION'],
                ];
                self::$trashUpdatedPages[$val['PAGE_ID']] = $val['PAGE_ID'];
            }
        }
        // Mise à jour des enfants et des contenus
        if (is_array($pidList)) {
            foreach ($pidList as $langue_pid => $page_version) {
                list($langue_id, $pid) = explode('-', $langue_pid);
                if (!empty($page_version['PAGE_CURRENT_VERSION']) || ($page_version['PAGE_CURRENT_VERSION'] ==  $page_version['PAGE_DRAFT_VERSION'] && $stateId == Pelican::$config['DEFAULT_STATE'])) {
                    self::_updateOnlineStatus($pid, $langue_id, 0);
                }

                //appel reccursif sur les enfants

                if ($pid != $pageID) {
                    self::_updateChildPage($pid, $stateId);
                }
                $aBind[':PAGE_ID'] = $pid;
                $aBind[':LANGUE_ID'] = $langue_id;
                $aBind[':PAGE_CURRENT_VERSION'] = $page_version['PAGE_CURRENT_VERSION'];
                $aBind[':PAGE_DRAFT_VERSION'] = $page_version['PAGE_DRAFT_VERSION'];

                $sqlUpdate2 = 'update #pref#_page_version
                                set
                                    STATE_ID = :STATE_ID
                                where
                                    PAGE_ID  = :PAGE_ID
                                    AND LANGUE_ID = :LANGUE_ID
                                    AND (
                                        PAGE_VERSION = :PAGE_CURRENT_VERSION
                                        OR
                                        PAGE_VERSION = :PAGE_DRAFT_VERSION
                                        )';

                $oConnection->query($sqlUpdate2, $aBind);

                self::_updateContentState($pid, $stateId);
            }
        }
    }

    private static function _updateOnlineStatus($pid, $langue_id, $online = 1)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':PAGE_ID'] = $pid;
        $aBind[':LANGUE_ID'] = $langue_id;
        $aBind[':PAGE_STATUS'] = $online;
        $sqlUpdate2 = 'update #pref#_page
                        set
                            PAGE_STATUS = :PAGE_STATUS
                        where
                            PAGE_ID  = :PAGE_ID
                            AND LANGUE_ID = :LANGUE_ID';
        $oConnection->query($sqlUpdate2, $aBind);
    }
    /*
     * Retire une page de la corbeille à partir d'un parent (de restoreAction)
     * @param int $pageID id de la page ou des contenus sont à restaurer
     * @param int $stateId changement d'etat (corbeille => à publier)
     */

    public static function _updateContentState($pageID, $stateId)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind = [
            ':STATE_ID' => $stateId,
            ':PAGE_ID' => $pageID,
        ];

        $stmt = 'SELECT CONTENT_ID, MAX(CONTENT_VERSION)as CONTENT_VERSION FROM #pref#_content_version  WHERE PAGE_ID =:PAGE_ID';
        $result = $oConnection->queryTab($stmt, $aBind);
        $cidList = array();
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                if (empty($val['CONTENT_ID'])) {
                    continue;
                }
                $cidList[$val['CONTENT_ID']] = $val['CONTENT_VERSION'];
            }
        }

        if (is_array($cidList)) {
            foreach ($cidList as $cid => $content_version) {
                $aBind[':CONTENT_ID'] = $cid;
                $aBind[':CONTENT_VERSION'] = $content_version;

                $sqlUpdate = 'update #pref#_content_version
                            set
                                STATE_ID = :STATE_ID
                            where
                                CONTENT_ID = :CONTENT_ID
                                AND CONTENT_VERSION = :CONTENT_VERSION
                                ';
                $oConnection->query($sqlUpdate, $aBind);
            }
        }
    }
}
