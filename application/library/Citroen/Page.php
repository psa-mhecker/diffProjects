<?php

/**
 * Gestion des pages
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
class Citroen_Page
{

	private $oConnection;
	protected $idVersionCurrent;
	protected $idVersionDraft;
	protected $aDatasTables = array();

	/**
	 * Constructeur
	 *
	 * @access public
	 * @return void
	 */
	public function __construct($idPage, $idLang)
	{
		$this->oConnection = Pelican_Db::getInstance();
	}

	/**
	 *
	 */
	public function insertData($aData, $table)
	{
		$this->oConnection = Pelican_Db::getInstance();
		if (is_array($aData)) {
			if ($table == '#pref#_page' || $table == '#pref#_content') {
                                if($table == '#pref#_page'){
                                    $aData['PAGE_CURRENT_VERSION'] = '';
                                }
                                if($table == '#pref#_content'){
                                    $aData['CONTENT_CURRENT_VERSION'] = '';
                                }
				Pelican_Db::$values = $aData;
				$this->oConnection->insertQuery($table);
				return $this->oConnection->getLastOid();
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
				$this->insertData($aInfosTable, $table);
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
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getIdVersionCurrent()
	{
		return $this->idVersionCurrent;
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getIdVersionDraft()
	{
		return $this->idVersionDraft;
	}

	/**
	 * Retourne
	 *
	 * @access public
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
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function setDatasTables($aDatasTables)
	{
		return $this->aDatasTables = $aDatasTables;
	}
	
	public function getPathByPageId($pageId){
		$oConnection = Pelican_Db::getInstance();

		$sSQL = "SELECT DISTINCT p.PAGE_PATH FROM #pref#_page p WHERE PAGE_ID = " . $pageId;
		$newPageParentId	=	$oConnection->queryRow($sSQL);
		if(isset($newPageParentId['PAGE_PATH']) && !empty($newPageParentId['PAGE_PATH'])){
			return $newPageParentId['PAGE_PATH'];
		}
		return false;
	}
	
	public function updatePathParentAndChildren($iParentId,$sParentPath,$iPageId,$iOrder=null,$aPagesByLang=null){
		$oConnection = Pelican_Db::getInstance();
		$sFindPagesSql = "SELECT  
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
		 ";

		$aBind=array(
			':PAGE_ID'=>$iPageId,
			':PARENT_ID'=>$iParentId,
			':ORDER'=>$iOrder
			);

		$aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

		$aPagesByLang = array();

		foreach ($aPages as $page) {
			$aPagesByLang[$page['LANGUE_ID']] =$page;
			$this->addCmsPageParentByLanguage($iParentId,$page['LANGUE_ID']);
		}

		$sFindPagesSql = "SELECT  
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
		 ";

		$aParentIds = explode('#',$sParentPath);//what for?

		$aBind[':PAGE_ID'] = $iParentId;
		$aParents = $oConnection->queryTab($sFindPagesSql, $aBind);
		//Update des donn�es de la page
		//on prends le premier page path vu que c'est des valeurs numeriques
		$sNewPagePath = sprintf('%s#%s',$aParents[0]['PAGE_PATH'],$iPageId);

	
		foreach ($aParents as $parent) {
			
			if(isset($aPagesByLang[$parent['LANGUE_ID']])){
				$sNewPagePathLib = sprintf(
					'%s#%s|%s',
					$parent['PAGE_LIBPATH'],
					$iPageId,
					trim(
						$aPagesByLang[$parent['LANGUE_ID']]['PAGE_TITLE_BO']
						)
					);

				$aBind=array(
					':PAGE_ID'=>$iPageId,
					':PAGE_PARENT_ID'=>intval($parent['PAGE_ID']),
					':PAGE_PATH'=>$oConnection->strtobind($sNewPagePath),
					':PAGE_LIBPATH'=>$oConnection->strtobind($sNewPagePathLib),
					':LANGUE_ID'=>$parent['LANGUE_ID']
					);

				$sUpdatePageSql = "UPDATE #pref#_page set PAGE_PARENT_ID=:PAGE_PARENT_ID,PAGE_PATH=:PAGE_PATH, PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";				
				$oConnection->query($sUpdatePageSql, $aBind);			
				$aBind = array(
					':PAGE_PARENT_ID'=>$iPageId	
					);
				$sGetChildrenSql = "SELECT p.PAGE_ID FROM #pref#_page as p WHERE p.PAGE_PARENT_ID=:PAGE_PARENT_ID GROUP BY PAGE_ID";
				$aChildren = $oConnection->queryTab($sGetChildrenSql,$aBind);

				if(count($aChildren)){
					foreach ($aChildren as $aChildPage) {
						$this->updatePathParentAndChildren($iPageId,$aChildPage['PAGE_PATH'],$aChildPage['PAGE_ID']);
					}
				
				}
			}

		}
	}
	
	protected function addCmsPageParentByLanguage($pid, $langue_id) {
		if($pid != '') {
			$oConnection = Pelican_Db::getInstance ();
			$aBindParent[":ID"] = $pid;
			$aBindParent[":LANGUE_ID"] = $langue_id;
			$existsParent = $oConnection->queryItem ( "select count(*) from #pref#_page where PAGE_ID=:ID AND LANGUE_ID=:LANGUE_ID", $aBindParent );
			if(!$existsParent) {
				// si pas de page parente dans une langue, on copie les datas d'une autre langue
				$sql = "select PAGE_PARENT_ID, TEMPLATE_PAGE_ID, PAGE_ORDER, PAGE_PATH, PAGE_GENERAL, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_SEARCH, PAGE_TITLE, PAGE_TITLE_BO from #pref#_page p inner join
							#pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_DRAFT_VERSION=pv.PAGE_VERSION AND p.LANGUE_ID=pv.LANGUE_ID)
							WHERE p.PAGE_ID=:ID";
				$valParentLangue = $oConnection->queryRow ( $sql, $aBindParent);
		
				$sPageLibPath = $this->createPageLibPath($valParentLangue,$langue_id);
		
				// PAGE
				$aBindParentAdd [":ID"] = $aBindParent [":ID"];
				$aBindParentAdd [":VERSION"] = 1;
				$aBindParentAdd [":LANGUE_ID"] = $aBindParent[":LANGUE_ID"];
				$aBindParentAdd [":SITE"] = $_SESSION [APP]['SITE_ID'];
				$aBindParentAdd [":PAGE_PARENT_ID"] = $valParentLangue ["PAGE_PARENT_ID"];
				$aBindParentAdd [":PAGE_ORDER"] = $valParentLangue ["PAGE_ORDER"];
				$aBindParentAdd [":PAGE_GENERAL"] = $valParentLangue ["PAGE_GENERAL"];
				$aBindParentAdd [":PAGE_PATH"] = $oConnection->strtobind ($valParentLangue ["PAGE_PATH"]);
		$aBindParentAdd [':PAGE_LIBPATH'] =$oConnection->strtobind ($sPageLibPath);

				// page version
				$aBindParentAdd [":TEMPLATE_PAGE_ID"] = $valParentLangue ["TEMPLATE_PAGE_ID"];
				$aBindParentAdd [":PAGE_DISPLAY"] = $valParentLangue ["PAGE_DISPLAY"];
				$aBindParentAdd [":PAGE_DISPLAY_NAV"] = $valParentLangue ["PAGE_DISPLAY_NAV"];
				$aBindParentAdd [":PAGE_DISPLAY_SEARCH"] = $valParentLangue ["PAGE_DISPLAY_SEARCH"];
				$aBindParentAdd [":STATE_ID"] = 1;
				$aBindParentAdd [":TITLE"] = $oConnection->strtobind ( "[" . $valParentLangue ["PAGE_TITLE"] . "]" );
				$aBindParentAdd [":TITLE_BO"] = $oConnection->strtobind ( "[" . $valParentLangue ["PAGE_TITLE_BO"] . "]" );

		$sql = "insert into #pref#_page (PAGE_ID, PAGE_DRAFT_VERSION, LANGUE_ID, PAGE_ORDER,SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL, PAGE_PATH,PAGE_LIBPATH) VALUES (
				:ID, :VERSION, :LANGUE_ID, :PAGE_ORDER,:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL, :PAGE_PATH,:PAGE_LIBPATH)";
				$oConnection->query ( $sql, $aBindParentAdd );
				// PAGE_LIB_PATH est fait lors d'un enregistrement reel par la suite

				$sql = "insert into #pref#_page_version (PAGE_ID, PAGE_DRAFT_VERSION, LANGUE_ID, PAGE_ORDER,SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL) VALUES (
						:ID, :VERSION, :LANGUE_ID, :PAGE_ORDER,:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL)";
				$sql = "insert into #pref#_page_version
					(PAGE_ID, PAGE_VERSION, LANGUE_ID, TEMPLATE_PAGE_ID, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_SEARCH, STATE_ID, PAGE_TITLE_BO, PAGE_TITLE
					) VALUES (
					:ID, :VERSION, :LANGUE_ID, :TEMPLATE_PAGE_ID,:PAGE_DISPLAY,:PAGE_DISPLAY_NAV,:PAGE_DISPLAY_SEARCH, :STATE_ID, :TITLE_BO,:TITLE
					)";
				$oConnection->query ( $sql, $aBindParentAdd );

				// on continue recursivement
				$this->addCmsPageParentByLanguage($valParentLangue ["PAGE_PARENT_ID"], $langue_id);
			}
		}
	}
	
	protected function createPageLibPath($originalPage,$iLangueId){
		$oConnection = Pelican_Db::getInstance ();
		$aParentsIds = explode('#',$originalPage['PAGE_PATH']);

		//fetch pages for language

		$sFindPagesSql = "SELECT  
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO					  
			 	FROM #pref#_page p INNER JOIN #pref#_page_version pv 
			 	ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 	WHERE p.PAGE_ID IN (:PAGES_IDS) AND p.LANGUE_ID=:LANGUE_ID
			 	";

		$aBind = array(
			':LANGUE_ID'=>$iLangueId,
			':PAGES_IDS'=>$oConnection->strtobind(implode(',', $aParentsIds))
			);
		$aPages = $oConnection->queryTab ( $sFindPagesSql, $aBind);
		$aPageLibPath =array();
		foreach ($aParentsIds as $iParentId) {
			foreach ($aPages as $aPage) {
				if($aPage['PAGE_ID']==$iParentId){
			 		$aPageLibPath[] = sprintf('%s|%s',$aPage['PAGE_ID'],$aPage['PAGE_TITLE_BO']);	
			 	}
			}
		}
		$sPageLibPath = implode('#',$aPageLibPath);
		$sPageLibPath = sprintf('%s#%s|[%s]',$sPageLibPath,$originalPage['PAGE_ID'],$originalPage['PAGE_TITLE_BO']);	 	
		return $sPageLibPath;
	}

}