<?php

include_once( Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Page.php');

/**
 * Gestion des pages
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
class Citroen_Page_Page extends Citroen_Page
{

	protected $idPage = '';
	protected $idLang = '';
	protected $aSqlTablesDependance = array();
	protected $aSqlTable = array();
	protected $idNew;
	protected $pageIdVersionCurrent;
	protected $pageIdVersionDraft;

	/**
	 * Constructeur
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->oConnection = Pelican_Db::getInstance();
		$this->aSqlTable['#pref#_page'] = 'SELECT * FROM #pref#_page WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID AND SITE_ID = :SITE_ID';
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	static public function getTableDependance()
	{
		return array(
			'#pref#_page_version',
			'#pref#_page_version_content',
			'#pref#_page_zone',
			'#pref#_page_zone_multi',
			'#pref#_page_zone_media',
			'#pref#_page_multi',
			'#pref#_page_multi_zone',
			'#pref#_page_multi_zone_content',
			'#pref#_page_multi_zone_media',
			'#pref#_page_multi_zone_multi',
			'#pref#_navigation'
		);
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function init()
	{
		foreach (self::getTableDependance() as $table) {
			if (!empty($this->aBind[':PAGE_VERSION_DRAFT']) && !empty($this->aBind[':PAGE_VERSION_CURRENT'])) {
				$this->aSqlTablesDependance[$table] = 'SELECT * FROM ' . $table . ' WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID AND PAGE_VERSION IN ( :PAGE_VERSION_CURRENT,:PAGE_VERSION_DRAFT) ';
			} elseif (!empty($this->aBind[':PAGE_VERSION_DRAFT'])) {
				$this->aSqlTablesDependance[$table] = 'SELECT * FROM ' . $table . ' WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID AND PAGE_VERSION = :PAGE_VERSION_DRAFT ';
			} elseif (!empty($this->aBind[':PAGE_VERSION_CURRENT'])) {
				$this->aSqlTablesDependance[$table] = 'SELECT * FROM ' . $table . ' WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID AND PAGE_VERSION = :PAGE_VERSION_CURRENT ';
			}
		}
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function setInfosTables()
	{
		foreach (self::getTableDependance() as $table) {
			if (isset($this->aSqlTablesDependance[$table])) {
                                $this->oConnection = Pelican_Db::getInstance();
				$this->aDatasTables[$table] = $this->oConnection->queryTab($this->aSqlTablesDependance[$table], $this->aBind);
			}
		}
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getIdNew()
	{
		return $this->idNew;
	}

	public function copieDependanceTable($langIdCible = 0, $siteIdCible = 0, $idNewsPage = 0, $etat = 1, $surchargeTitleBo = '', $pageIdSource = '')
	{
		$this->aBind[':PAGE_VERSION_CURRENT'] = $this->getIdVersionCurrent();
		$this->aBind[':PAGE_VERSION_DRAFT'] = $this->getIdVersionDraft();
		$this->init();
		$this->setInfosTables();
		if ($langIdCible == 0 || $siteIdCible == 0 || $idNewsPage == 0) {
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
					if (isset($this->aDatasTables[$table][$key]['PAGE_ID'])) {
						$this->aDatasTables[$table][$key]['PAGE_ID'] = $idNewsPage;
					}
					if (isset($this->aDatasTables[$table][$key]['PAGE_TITLE_BO'])) {
						$this->aDatasTables[$table][$key]['PAGE_TITLE_BO'] = $this->aDatasTables[$table][$key]['PAGE_TITLE_BO'] . $surchargeTitleBo;
					}
					if (isset($this->aDatasTables[$table][$key]['STATE_ID'])) {
						$this->aDatasTables[$table][$key]['STATE_ID'] = $etat;
					}
					if (isset($this->aDatasTables[$table][$key]['PAGE_CLEAR_URL'])) {
						$this->aDatasTables[$table][$key]['PAGE_CLEAR_URL'] = preg_replace('/pid[0-9]+-/', 'pid' . $idNewsPage . '-', $this->aDatasTables[$table][$key]['PAGE_CLEAR_URL']);
					}
				}
			}
		}
	}

	/**
	 *
	 */
	public function copie($pageIdSource, $langIdSource, $siteIdSource)
	{
		$this->aBind[':PAGE_ID'] = $pageIdSource;
		$this->aBind[':LANGUE_ID'] = $langIdSource;
		$this->aBind[':SITE_ID'] = $siteIdSource;
                $this->oConnection = Pelican_Db::getInstance();
		$aDataPageCopie = $this->oConnection->queryRow($this->aSqlTable['#pref#_page'], $this->aBind);
		$this->idVersionCurrent = $aDataPageCopie['PAGE_CURRENT_VERSION'];
		$this->idVersionDraft = $aDataPageCopie['PAGE_DRAFT_VERSION'];
		if (!empty($aDataPageCopie)) {
			return $aDataPageCopie;
		}
		return false;
	}

	/**
	 *
	 */
	public function colle($aDataPageCopie, $langIdCible, $siteIdCible, $pageIdParentCible = 1, $bXml = false, $etat = 1, $surchargeTitleBo = '', $bDiffusion = false, $pageIdSource = '')
	{
		$pageIdSource = $aDataPageCopie['PAGE_ID'];
		$aDataPageCopie['LANGUE_ID'] = $langIdCible;
		$aDataPageCopie['SITE_ID'] = $siteIdCible;
		if(true == $bXml){
			$aDataPageCopie['PAGE_ORDER'] = $pageIdSource;
		}
		if ($pageIdParentCible !== null) {
			$aDataPageCopie['PAGE_PARENT_ID'] = $pageIdParentCible;
			$aDataPageCopie['PAGE_DIFFUSION'] = $bDiffusion;
			$idNewsPage = $this->insertData($aDataPageCopie, '#pref#_page');
		} else {
			$this->updateData($aDataPageCopie, '#pref#_page');
			$idNewsPage = $pageIdSource;
		}
		if ($bXml) {
			$this->copieDependanceTableXml($langIdCible, $siteIdCible, $idNewsPage, $etat, $surchargeTitleBo);
		} else {
			$this->copieDependanceTable($langIdCible, $siteIdCible, $idNewsPage, $etat, $surchargeTitleBo);
		}
		return $idNewsPage;
	}

	/**
	 *
	 */
	public function copieDependanceTableXml($langIdCible = 0, $siteIdCible = 0, $idNewsPage = 0, $etat = 1, $surchargeTitleBo = '', $pageIdSource = '')
	{
		foreach (self::getTableDependance() as $table) {
			if (is_array($this->aDatasTables[$table])) {
				foreach ($this->aDatasTables[$table] as $key => $dataTable) {
					if (isset($this->aDatasTables[$table][$key]['SITE_ID'])) {
						$this->aDatasTables[$table][$key]['SITE_ID'] = $siteIdCible;
					}
					if (isset($this->aDatasTables[$table][$key]['LANGUE_ID'])) {
						$this->aDatasTables[$table][$key]['LANGUE_ID'] = $langIdCible;
					}
					if (isset($this->aDatasTables[$table][$key]['PAGE_ID'])) {
						$this->aDatasTables[$table][$key]['PAGE_ID'] = $idNewsPage;
					}
					if (isset($this->aDatasTables[$table][$key]['PAGE_TITLE_BO'])) {
						$this->aDatasTables[$table][$key]['PAGE_TITLE_BO'] = $this->aDatasTables[$table][$key]['PAGE_TITLE_BO'] . $surchargeTitleBo;
					}
					if (isset($this->aDatasTables[$table][$key]['STATE_ID'])) {
						$this->aDatasTables[$table][$key]['STATE_ID'] = $etat;
					}
					if (isset($this->aDatasTables[$table][$key]['PAGE_CLEAR_URL'])) {
						$this->aDatasTables[$table][$key]['PAGE_CLEAR_URL'] = preg_replace('/pid[0-9]+-/', 'pid' . $idNewsPage . '-', $this->aDatasTables[$table][$key]['PAGE_CLEAR_URL']);
					}
				}
			}
		}
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getAllIdContentByPageId($pageId)
	{
		$this->aBind[':PAGE_ID'] = $pageId;
		$sql = 'SELECT CONTENT_ID FROM #pref#_content_version WHERE PAGE_ID = :PAGE_ID';
                $this->oConnection = Pelican_Db::getInstance();
		$aIdContent = $this->oConnection->queryTab($sql, $this->aBind);
		if (is_array($aIdContent) && !empty($aIdContent)) {
			return $aIdContent;
		}
		return false;
	}

	/**
	 * Retourne
	 *
	 * @access public
	 * @return array
	 */
	public function getPageIdAccueilBySiteId($siteId)
	{
		$this->aBind[':SITE_ID'] = $siteId;
		$this->aBind[':STATUT_ID'] = 1;
		$sql = 'SELECT PAGE_ID  FROM #pref#_page
                   WHERE SITE_ID      = :SITE_ID
                   AND PAGE_STATUS    = :STATUT_ID
                   AND PAGE_PARENT_ID is null
                   AND PAGE_PATH is not null
                   AND PAGE_GENERAL <> 1';
                $this->oConnection = Pelican_Db::getInstance();
		$aPageId = $this->oConnection->queryRow($sql, $this->aBind);        
		if (is_array($aPageId) && !empty($aPageId)) {
			return $aPageId['PAGE_ID'];
		}
		return false;
	}

}