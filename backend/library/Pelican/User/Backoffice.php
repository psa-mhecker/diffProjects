<?php
pelican_import('User');
/**
 * Classe de description de l'utilisateur courant.
 */
class Pelican_User_Backoffice extends Pelican_User
{
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    public $session_label = 'backoffice';

    public static function getInstance()
    {
        static $_instance;

        if (!is_object($_instance)) {
            $_instance = new self();
        }

        return $_instance;
    }

    /**
     * * GENERAL.
     */
    /**
     * * Récupération des toutes les informations de la base Administration.
     */
    public function getFullInfos()
    {
        $return = "";

        if ($this->isLoggedIn()) {
            $oConnection = Pelican_Db::getInstance();

            $query = "select
				#pref#_user.USER_LOGIN,
				USER_NAME,
				USER_EMAIL,
				USER_FULL,
				#pref#_profile.PROFILE_ID,
				PROFILE_LABEL,
				PROFILE_ADMIN,
				#pref#_profile.SITE_ID,
				#pref#_directory.DIRECTORY_ID,
				DIRECTORY_PARENT_ID,
				".$oConnection->getConcatClause(array("#pref#_profile.PROFILE_ID", "'.'", "#pref#_profile.SITE_ID", "'.'", "#pref#_directory.DIRECTORY_ID"))." as \"id\",
				".$oConnection->getCaseClause("DIRECTORY_PARENT_ID", array("NULL" => "NULL"), $oConnection->getConcatClause(array("#pref#_profile.PROFILE_ID", "'.'", "#pref#_profile.SITE_ID", "'.'", "DIRECTORY_PARENT_ID")))." as \"pid\",
				DIRECTORY_LABEL as \"lib\",
				DIRECTORY_ICON as \"image\",
				DIRECTORY_ADMIN,
				TEMPLATE_ID,
				TEMPLATE_COMPLEMENT as \"TEMPLATE_COMPLEMENT\",
				".$oConnection->getConcatClause(array("#pref#_profile.PROFILE_ID", "'.'", "#pref#_profile.SITE_ID", "'.'", "PROFILE_DIRECTORY_ORDER"))." as \"order\",
				DIRECTORY_LEFT_LABEL
				FROM
				#pref#_user,
				#pref#_user_profile,
				#pref#_profile
				left join #pref#_profile_directory on (#pref#_profile.PROFILE_ID = #pref#_profile_directory.PROFILE_ID)
				left join #pref#_directory on (#pref#_profile_directory.DIRECTORY_ID=#pref#_directory.DIRECTORY_ID)
				WHERE
				#pref#_user.USER_LOGIN=#pref#_user_profile.USER_LOGIN
				and #pref#_user_profile.PROFILE_ID=#pref#_profile.PROFILE_ID
				and #pref#_user.USER_LOGIN=:1
				and USER_ENABLED=1
				ORDER BY DIRECTORY_PARENT_ID";
            $aBind[":1"] = $oConnection->strToBind($this->get('id'));

            $return = $oConnection->getTab($query, $aBind);
        }

        return $return;
    }
}
