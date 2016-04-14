<?php

class Request_Redirect extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Cache.
     */
    public function getValue()
    {
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
                if ($result['REWRITE_RESPONSE'] == '301') {
                    if ($result['REWRITE_TYPE'] == 'PAGE') {
                        $return['url'] = getUrl($result['REWRITE_ID'], "PAGE", $this->params[1], $result['LANGUE_ID']);
                        $return['code'] = $result['REWRITE_RESPONSE'];
                    } elseif ($result['REWRITE_TYPE'] == 'CONTENT') {
                        $return['url'] = getUrl($result['REWRITE_ID'], "CONTENT", $this->params[1], $result['LANGUE_ID']);
                        $return['code'] = $result['REWRITE_RESPONSE'];
                    }
                } else {
                    $return['TYPE'] = $result['REWRITE_TYPE'];
                    $return['ID'] = $result['REWRITE_ID'];
                }
                $return['LANGUE_ID'] = $result['LANGUE_ID'];
            }

            // cas des raccourcis de gabarit
            $result = $oConnection->queryRow("select p.PAGE_ID as REWRITE_ID, 'PAGE' as REWRITE_TYPE, p.LANGUE_ID from #pref#_page p
			inner join #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID and p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION and p.LANGUE_ID=pv.LANGUE_ID and p.SITE_ID=:SITE_ID)
			inner join #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
			inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where ".$oConnection->getConcatClause(array(
                "PAGE_TYPE_SHORTCUT",
                "'/'",
            ))."=:REWRITE_URL2", $aBind);

            if ($result) {
                $return['TYPE'] = $result['REWRITE_TYPE'];
                $return['ID'] = $result['REWRITE_ID'];
                $return['LANGUE_ID'] = $result['LANGUE_ID'];
            }
        }

        $this->value = $return;
    }
}

function getUrl($id, $type, $site, $langue)
{
    $oConnection = Pelican_Db::getInstance();

    $aBind[":SITE_ID"] = $site;
    $aBind[":LANGUE_ID"] = $langue;
    $aBind[":".$type."_ID"] = $id;

    $sSql = "select ".$type."_CLEAR_URL, ".$type."_TITLE_BO
					FROM #pref#_".strtolower($type)." p
					INNER JOIN #pref#_".strtolower($type)."_version pv on (p.".$type."_ID=pv.".$type."_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.".$type."_CURRENT_VERSION=pv.".$type."_VERSION)
					WHERE
					SITE_ID=:SITE_ID
					AND p.LANGUE_ID = :LANGUE_ID
					AND p.".$type."_ID=:".$type."_ID";
    $result = $oConnection->queryRow($sSql, $aBind);

    $return = ($result["".$type."_CLEAR_URL"] ? $result["".$type."_CLEAR_URL"] : makeClearUrl($result["".$type."_ID"], "pid", $result["".$type."_TITLE_BO"]));

    return $return;
}
