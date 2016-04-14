<?php
/**
 * Fichier de Pelican_Cache : liste des tags TAG associé à un site.
 *
 * @author Raphael Carles <rcarles@businessdecision.com>
 *
 * @since 14/06/2005
 */
class Frontend_Rewrite extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        if (preg_match('/\/([pid|cid|tpl]+)([0-9]+)\/(.*).html/i', $this->params[0], $match)) {
            $return = "/index.php?".$match[1]."=".$match[2];
        } else {
            if ($this->params[0] && $this->params[0] != "/") {
                $oConnection = Pelican_Db::getInstance();
                $aBind = array();
                $aBind[":REWRITE_URL1"] = str_replace("//", "/", $oConnection->strToBind($this->params[0]));
                $aBind[":REWRITE_URL2"] = str_replace("//", "/", $oConnection->strToBind($this->params[0]."/"));
                $aBind[":SITE_ID"] = $this->params[1];
                $sSql = "select * from #pref#_rewrite
				WHERE SITE_ID=:SITE_ID
				AND (REWRITE_URL=:REWRITE_URL1 OR REWRITE_URL=:REWRITE_URL2)";
                $result = $oConnection->queryRow($sSql, $aBind);
                if ($result) {
                    if ($result['PAGE_ID']) {
                        $return = $this->getUrl($result['PAGE_ID'], "PAGE", $this->params[1]);
                    } elseif ($result['CONTENT_ID']) {
                        $return = $this->getUrl($result['CONTENT_ID'], "CONTENT", $this->params[1]);
                    }
                }
            }
        }
        $this->value = $return;
    }

    public function getUrl($id, $type, $site)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $site;
        $aBind[":".$type."_ID"] = $id;

        $sSql = "select ".$type."_CLEAR_URL, ".$type."_TITLE_BO
					FROM ".Pelican::$config['FW_PREFIXE_TABLE'].strtolower($type)." p
					INNER JOIN ".Pelican::$config['FW_PREFIXE_TABLE'].strtolower($type)."_version pv on (p.".$type."_ID=pv.".$type."_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.".$type."_CURRENT_VERSION=pv.".$type."_VERSION)
					WHERE
					SITE_ID=:SITE_ID
					AND p.LANGUE_ID = 1
					AND p.".$type."_ID=:".$type."_ID";
        $result = $oConnection->queryRow($sSql, $aBind);

        $return = ($result["".$type."_CLEAR_URL"] ? $result["".$type."_CLEAR_URL"] : makeClearUrl($result["".$type."_ID"], "pid", $result["".$type."_TITLE_BO"]));

        return $return;
    }
}
