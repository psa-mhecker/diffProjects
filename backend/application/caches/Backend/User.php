<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Résultat de requête sur user.
     *
     * retour : id, lib
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 20/05/2006
     */
    class Backend_User extends Pelican_Cache
    {
        public $duration = WEEK;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $aBind[":SITE_ID"] = $this->params[0];

            $query = "SELECT
				u.USER_LOGIN as \"id\",
				USER_NAME as \"lib\"
				FROM
				#pref#_user u
				inner join #pref#_user_profile up on (u.USER_LOGIN=up.USER_LOGIN)
				inner join #pref#_profile p on (p.PROFILE_ID=up.PROFILE_ID)
				where
				u.SITE_ID = 1
				AND p.SITE_ID = :SITE_ID
				ORDER BY
				USER_NAME";
            $this->value = $oConnection->queryTab($query, $aBind);
        }
    }
