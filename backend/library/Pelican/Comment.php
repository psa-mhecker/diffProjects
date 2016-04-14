<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once pelican_path('Html.Form');
pelican_import('Text');

/**
 * __DESC__.
 *
 * @author Patrick Deroubaix <patrick.deroubaix@businessdecision.fr>
 *
 * @todo : readOnly , commentaire , les bundles , verifier les placements des connections
 */
class Pelican_Comment
{
    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var __TYPE__
     */
    protected $moderation = 1;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $sStyleLib = "formlib";

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @var __TYPE__
     */
    public static $frequency = 30; //secondes


    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $moderation __DESC__
     *
     * @return __TYPE__
     */
    public function Pelican_Comment($moderation)
    {
        $this->moderation = $moderation;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function saveComment()
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values = self::parseBlacklist(Pelican_Db::$values);
        Pelican_Db::$values['COMMENT_STATUS'] = ($this->moderation == '-1' ? 0 : 1);
        $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_comment");
        $oConnection->commit();
        Pelican_Cache::clean('Comment/Object', array(Pelican_Db::$values["OBJECT_ID"]));
        Pelican_Cache::clean('Comment/Last', array(Pelican_Db::$values['OBJECT_TYPE_ID']));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $values __DESC__
     *
     * @return __TYPE__
     */
    public function parseBlacklist($values)
    {
        $return = $values;
        $blacklist = Pelican_Cache::fetch('Comment/Blacklist');
        foreach ($return as $key => $val) {
            $return[$key] = str_replace($blacklist, '#supprimé#', $val);
            $return[$key] = Pelican_Security::escapeXSS($val);
        }

        return $return;
    }

    /**
     * Récupere la liste des tags pour un object avec fichier de Pelican_Cache ou pas.
     *
     * @access public
     *
     * @param int      $objectId     __DESC__
     * @param int      $objectTypeId __DESC__
     * @param string   $nbrComment   (option) __DESC__
     * @param __TYPE__ $pageCount    (option) __DESC__
     * @param bolean   $useCache     (option) [optional] utiliser false pour le BO
     *
     * @return Array
     */
    public function getCommentForObject($objectId, $objectTypeId, $nbrComment = 0, $pageCount = 1, $useCache = true)
    {
        if ($useCache) {
            $aResult = Pelican_Cache::fetch("Comment/Object", array($objectId, $objectTypeId, $nbrComment, $pageCount, false));

            return $aResult;
        } else {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":OBJECT_ID"] = $objectId;
            $aBind[":OBJECT_TYPE_ID"] = $objectTypeId;
            $query = "select c.OBJECT_ID,COMMENT_PSEUDO,COMMENT_ID,COMMENT_EMAIL,COMMENT_TITLE,COMMENT_TEXT,".$oConnection->dateSqlToString(COMMENT_CREATION_DATE, true)." as DATEJ from #pref#_comment c where
                           c.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND c.COMMENT_STATUS=1 ";
            if ($nbrComment) {
                $start = ($pageCount - 1);
                $query = $oConnection->getLimitedSQL($query, $nbrComment * $start + 1, $nbrComment);
            }
            $aResult = $oConnection->queryTab($query, $aBind);

            return $aResult;
        }
    }

    /**
     * Récupere la liste des tags pour un object avec fichier de Pelican_Cache ou pas.
     *
     * @access public
     *
     * @param int    $objectId     __DESC__
     * @param int    $objectTypeId __DESC__
     * @param bolean $useCache     (option) [optional] utiliser false pour le BO
     *
     * @return Array
     */
    public function getCountForObject($objectId, $objectTypeId, $useCache = true)
    {
        if ($useCache) {
            $aResult = Pelican_Cache::fetch("Comment/Object", array($objectId, $objectTypeId, 0, 0, true));

            return $aResult;
        } else {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":OBJECT_ID"] = $objectId;
            $aBind[":OBJECT_TYPE_ID"] = $objectTypeId;
            $query = "select count(1) as count from #pref#_comment c where
                           c.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND c.COMMENT_STATUS=1 ";
            $aResult = $oConnection->queryRow($query, $aBind);

            return $aResult;
        }
    }
}
