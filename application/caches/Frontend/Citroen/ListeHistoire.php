<?php
/**
 * Fichier de Pelican_Cache : Criteres
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_ListeHistoire extends Pelican_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue ()
    {
        

        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $aBind[":CONTENT_TYPE_ID"] = $this->params[2];
        
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        
        $full = false;
        if ($this->params[2] == 'all') {
            $full = true;
        }
        
        $strSql = "SELECT c.CONTENT_ID,
				cv.CONTENT_TITLE,
				cv.CONTENT_TEXT,
				cv.MEDIA_ID as IMAGE,
				cv.DOC_ID as FORMAT_IMAGE,
				cv.CONTENT_CODE as FORMAT_DATE,
				cv.CONTENT_CODE2 as VERSION_DATE,
				cv.PERSON_ID as BLOC,
				cv.VIGNETTE_PLAYER,
				CONTENT_CODE3 as VIDEO,
                cv.CONTENT_START_DATE,
                cv.CONTENT_END_DATE,
				YEAR(cv.CONTENT_DATE2) as ANNEE,
				DATE_FORMAT(cv.CONTENT_DATE2, '%d/%m/%Y') as DATE_FR,
				DATE_FORMAT(cv.CONTENT_DATE2, '%m/%d/%Y') as DATE_UK
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)";
        $strSql .= "
				WHERE ";
        if (!$full) {
            $strSql .= " c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
				AND ";
        }
        $strSql .= " c.LANGUE_ID = :LANGUE_ID
				AND c.SITE_ID = :SITE_ID
				AND c.CONTENT_STATUS = 1
				AND cv.STATE_ID = 4
				ORDER BY cv.CONTENT_DATE2, c.CONTENT_ID DESC";
        
        $result = $oConnection->getTab($strSql, $aBind);
        
        $this->value = $result;
    }
}