<?php
/**
	* @package Cache
	* @subpackage Page
	*/

	pelican_import('Hierarchy');

/**
	* Fichier de Pelican_Cache : Hiérarchie des pages d'un site
	* @param string $this->params[0] ID du site
	*
	* @package Cache
	* @subpackage Page
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 02/09/2004
	*/
class Backend_Page extends Pelican_Cache {


	public $duration = WEEK;

	/** Valeur ou objet à mettre en Pelican_Cache */
	public function getValue() {

		$oConnection = Pelican_Db::getInstance();

		$aBind[":SITE_ID"] = $this->params[0];
		$aBind[":LANGUE_ID"] = $this->params[1];
		$aBind[":PAGE_ID"] = $this->params[2];
        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[3];

        $strSqlPage = "
				SELECT
				p.PAGE_ID as \"id\",";

        if ($this->params[3]) {
            $strSqlPage .= "REPLACE( REPLACE( p.page_libpath,  '#',  '/' ) ,  '|',  '' )as  \"lib\", ";
        }else{
            $strSqlPage .= "PAGE_TITLE_BO as \"lib\", ";
        }

		$strSqlPage .= "PAGE_PARENT_ID as \"pid\",
				PAGE_ORDER as \"order\"
                 FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
				WHERE p.SITE_ID=:SITE_ID
				AND p.LANGUE_ID=:LANGUE_ID
                AND pv.state_id != " . Pelican::$config["CORBEILLE_STATE"] . "
                AND p.PAGE_STATUS = 1
				AND (PAGE_GENERAL=0 OR PAGE_GENERAL IS NULL)";
		if ($this->params[2] || $this->params[2] != "") {
			$strSqlPage .= " AND p.PAGE_ID=:PAGE_ID";
		}
		if ($this->params[3]) {
			$strSqlPage .= " AND pv.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID";
		}


		if ($this->params[2] || $this->params[2] != "") {
			$this->value = $oConnection->queryRow($strSqlPage, $aBind);
		} elseif($this->params[3]){
            $this->value = $oConnection->queryTab($strSqlPage, $aBind);
        }else{
			$MENU = $oConnection->queryTab($strSqlPage, $aBind);

			$oTree = Pelican_Factory::getInstance('Hierarchy',"header", "id", "pid");
			$oTree->addTabNode($MENU);
			$oTree->setOrder("order", "ASC");
			$i = -1;
			foreach($oTree->aNodes as $menu) {
				$aMenu[] = $this->_getTreeParams($menu);
			}
			$this->value = $aMenu;
		}
	}

    protected function _getTreeParams($tree)
    {
	$limit = 60;
	$return["id"] = $tree->id;
	$return["pid"] = $tree->pid;
	if (strlen($tree->lib)>$limit) {
		$lib = substr($tree->lib,0,($limit-15)).".....".substr($tree->lib,-10);
	} else {
		$lib = $tree->lib;
	}
	/*if ($tree->level==3) {
		$lib = "-".$lib;
	}*/
	$return["lib"] = str_replace(" ", "&nbsp;", str_repeat("&nbsp;&nbsp;&nbsp;", ($tree->level-2)).$lib);
	$return["order"] = $tree->order;
	return $return;
}
}
