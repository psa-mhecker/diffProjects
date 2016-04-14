<?php
/**
 */
pelican_import('Hierarchy');

/**
 * Fichier de Pelican_Cache : Hiérarchie du menu Front en fonction du page_id.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 02/09/2004
 */
class Frontend_Page_Navigation extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[1];
        $aBind[":PAGE_VERSION"] = $this->params[2];
        $aBind[":LANGUE_ID"] = $this->params[3];
        if (! empty($this->params[4])) {
            $limit = $this->params[4];
        }
        $mediaHttp = ($this->params[4]) ? $this->params[4] : (Pelican::$config["HTTP_MEDIA"]);

        $sSQL = "
				SELECT Tn.*, Tm.*,
				NAVIGATION_ID as \"id\",
				NAVIGATION_PARENT_ID as \"pid\",
				NAVIGATION_TITLE as \"lib\",
				NAVIGATION_ORDER as \"order\",
				NAVIGATION_PARAMETERS as \"param\"
				FROM #pref#_navigation Tn
				LEFT JOIN #pref#_media Tm on (Tn.NAVIGATION_MEDIA_ID = Tm.MEDIA_ID)
				WHERE PAGE_ID = :PAGE_ID
				AND LANGUE_ID = :LANGUE_ID
				AND PAGE_VERSION = :PAGE_VERSION
				AND ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				ORDER BY NAVIGATION_ORDER";
        if (isset($limit)) {
            $sSQL = $oConnection->getLimitedSql($sSQL, 1, $limit, true, $aBind);
        }
        $MENU = $oConnection->queryTab($sSQL, $aBind);

        $oTree = Pelican_Factory::getInstance('Hierarchy', "menu".$aBind[":ZONE_TEMPLATE_ID"], "id", "pid");
        $oTree->addTabNode($MENU);
        $oTree->setOrder("order", "ASC");
        $i = - 1;
        foreach ($oTree->aNodes as $menu) {
            if ($menu->level == 2) {
                $i ++;
                $aMenu[$i]["menu"] = $this->getTreeParams($menu);
            } else {
                if ($menu->id) {
                    $aMenu[$i]["ssmenu"][] = $this->getTreeParams($menu);
                }
            }
        }
        $this->value = $aMenu;
    }

    public function getTreeParams($tree)
    {
        $return["id"] = $tree->id;
        $return["pid"] = $tree->pid;
        $return["lib"] = $tree->lib;
        $return["param"] = (! empty($tree->param) ? $tree->param : '');
        if (! empty($tree->bold)) {
            $return["lib"] = "<b>".$return["lib"]."</b>";
        }
        $return["url"] = (! empty($tree->NAVIGATION_URL) ? $tree->NAVIGATION_URL : '');
        $return["target"] = (! empty($tree->target) ? $tree->target : '');
        if ((substr_count($return["url"], "http://") || substr_count($return["url"], "https://")) && ! substr_count($return["url"], "=http://")) {
            $return["target"] = "_blank";
        }
        $return["img"] = $tree->NAVIGATION_IMG;
        /* Contrôle du lien vers les images */
        if ($return["img"] && substr_count($return["img"], "/images")) {
            $return["img"] = Pelican::$config["DESIGN_HTTP"].$return["img"];
        } elseif ($return["img"]) {
            $return["img"] = Pelican::$config["MEDIA_HTTP"].$return["img"];
        }

        return $return;
    }

    public function query_str($params)
    {
        $str = '';
        foreach ($params as $key => $value) {
            $str .= (strlen($str) < 1) ? '' : '&';
            $str .= $key.'='.rawurlencode($value);
        }
        //$str = str_replace("pid=pid", "pid=#pid#", $str);
        return ($str);
    }
}
