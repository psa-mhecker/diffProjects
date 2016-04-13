<?php

/**
 * Fichier de Pelican_Cache : Récupération des derniers commentaires en ligne
 *
 * @package Pelican_Cache
 * @subpackage Pelican_Index_Comment
 * @author Raphael Carles <raphael.carles@businessdecision.com>
 * @since 01/02/2010
 */
class Comment_Blacklist extends Pelican_Cache
{

    
    var $duration = DAY;

    /** Valeur ou objet é mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $oConnection = Pelican_Db::getInstance();
        
        $query = "select * from #pref#_comment_blacklist";
        
         $oConnection->query($query);
        
        $this->value = $oConnection->data['COMMENT_BLACKLIST_WORD'];
    }
}
?>