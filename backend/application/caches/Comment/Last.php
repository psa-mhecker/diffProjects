<?php

/**
 * Fichier de Pelican_Cache : Récupération des derniers commentaires en ligne.
 *
 * @author Raphael Carles <raphael.carles@businessdecision.com>
 *
 * @since 01/02/2010
 */
class Comment_Last extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet é mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $oConnection = Pelican_Db::getInstance();
        $aBind[":OBJECT_TYPE_ID"] = $this->params[0];
        $nbrComment = $this->params[1];

        $query = "select OBJECT_ID, COMMENT_PSEUDO,COMMENT_ID,COMMENT_EMAIL,COMMENT_TITLE,COMMENT_TEXT,".$oConnection->dateSqlToString("COMMENT_CREATION_DATE", true)." as COMMENT_CREATION_DATE_LANGUE from #pref#_comment c where
		                           OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND c.COMMENT_STATUS=1
		                           order by COMMENT_CREATION_DATE DESC";
        if ($nbrComment) {
            $query = $oConnection->getLimitedSQL($query, 1, $nbrComment);
        }
        $aResult = $oConnection->queryTab($query, $aBind);

        $this->value = $aResult;
    }
}
