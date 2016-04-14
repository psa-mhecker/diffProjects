<?php

/**
 * Fichier de Pelican_Cache : Récupération des terms pour un objet.
 *
 * @author Patrick.deroubaix@businessdecision.fr
 *
 * @since 07/09/2009
 */
class Comment_Object extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet é mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $oConnection = Pelican_Db::getInstance();
        $aBind[":OBJECT_ID"] = $this->params[0];
        $aBind[":OBJECT_TYPE_ID"] = $this->params[1];
        $nbrComment = $this->params[2];
        $pageCount = $this->params[3];
        $count = $this->params[4];

        if (! $count) {
            $query = "select COMMENT_PSEUDO,COMMENT_ID,COMMENT_EMAIL,COMMENT_TITLE,COMMENT_TEXT,".$oConnection->dateSqlToString(COMMENT_CREATION_DATE, true)." as DATEJ, COMMENT_RATING from #pref#_comment c where
		                           c.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND c.COMMENT_STATUS=1 ";
            if ($nbrComment) {
                $start = ($pageCount - 1);
                $query = $oConnection->getLimitedSQL($query, $nbrComment * $start + 1, $nbrComment);
            }
            $aResult = $oConnection->queryTab($query, $aBind);
        } else {
            $query = "select count(1) as count from #pref#_comment c where
                           c.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND c.COMMENT_STATUS=1 ";

            $aResult = $oConnection->queryRow($query, $aBind);
            $aResult;
        }

        $this->value = $aResult;
    }
}
