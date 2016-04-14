<?php

class TranslationByLabelIdAndSiteIdAndLangueId extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet � mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":LABEL_ID"] = $oConnection->strToBind($this->params['LABEL_ID']);
        $aBind[":LANGUE_ID"] = $this->params['LANGUE_ID'];

        $sSql = "
            SELECT
                label.label_id,
                ifnull(lab_lang_site.LABEL_TRANSLATE, lab_lang.LABEL_TRANSLATE) LABEL_TRANSLATE,
                lab_lang_site.LANGUE_ID
            FROM
                #pref#_label AS label
            LEFT JOIN #pref#_label_langue AS lab_lang
                ON (lab_lang.LABEL_ID = label.LABEL_ID)
            LEFT JOIN #pref#_label_langue_site AS lab_lang_site
                ON (lab_lang_site.LABEL_ID = label.LABEL_ID)";

        $sSql .= "
            WHERE
				label.LABEL_ID = :LABEL_ID
				AND lab_lang_site.LANGUE_ID = :LANGUE_ID
			";

        $this->value = $oConnection->queryRow($sSql, $aBind);

    }
}
