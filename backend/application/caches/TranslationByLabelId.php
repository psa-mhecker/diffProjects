<?php

class TranslationByLabelId extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet � mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":LABEL_ID"] = $oConnection->strToBind($this->params[0]);
        $aBind[":SITE_ID"] = $this->params[1];
        $sTable = "";
        if ($this->params[2] == 'FRONT') {
            $sTable = "#pref#_label_langue_site";
        } else {
            $sTable = "#pref#_label_langue";
        }

        $sSql = "
            SELECT
                psa_label.label_id,
                ifnull(psa_label_langue_site.LABEL_TRANSLATE, psa_label_langue.LABEL_TRANSLATE) LABEL_TRANSLATE,
                psa_label_langue.LANGUE_ID
            FROM
                #pref#_label
            LEFT JOIN #pref#_label_langue
                ON (#pref#_label_langue.LABEL_ID=#pref#_label.LABEL_ID)";
        if ($this->params[2] == 'FRONT') {
            $sSql = "
                SELECT
                psa_label.label_id,
                psa_label_langue_site.LABEL_TRANSLATE,
                psa_label_langue_site.LANGUE_ID
            FROM
                #pref#_label
            LEFT JOIN #pref#_label_langue_site
                ON (
                    #pref#_label_langue_site.LABEL_ID = #pref#_label.LABEL_ID)";
        }
        $sSql .= "
            WHERE
				#pref#_label.LABEL_ID = :LABEL_ID AND psa_label_langue_site.SITE_ID = :SITE_ID
			";
        $aResult = $oConnection->queryTab($sSql, $aBind);
        $aLabels = array();
        if (is_array($aResult)) {
            foreach ($aResult as $result) {
                $aLabels[$result["LANGUE_ID"]] = $result["LABEL_TRANSLATE"];
            }
        }

        $this->value = $aLabels;
    }
}
