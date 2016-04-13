<?php
	/**
	* @package Cache
	* @subpackage Config
	*/

	/**
	* Fichier de Pelican_Cache : Résultat de requête sur user
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage Config
	* @author Gilles Lenormand <glenormand@businessdecision.com>
	* @since 29/05/2007
	*/
	class Frontend_User extends Pelican_Cache {

		
		var $duration = WEEK;

		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
/*
			$aBind[":SITE_ID"] = $this->params[0];
			
			$query = "SELECT
				u.USER_LOGIN as \"id\",
				USER_NAME as \"lib\"
				FROM
				#pref#_user u
				inner join #pref#_user_profile up on (u.USER_LOGIN=up.USER_LOGIN)
				inner join #pref#_profile p on (p.PROFILE_ID=up.PROFILE_ID)
				where
				SITE_ID = :SITE_ID
				ORDER BY
				USER_NAME";
			$this->value = $oConnection->queryTab($query, $aBind);
*/

		$userlogin=$this->params[0];
		$userpassword=$this->params[1];
		
		$query = "select *
					FROM #pref#_user
					WHERE USER_LOGIN=:1 
					AND USER_ENABLED=1";
			
		if($userpassword){
			$query .= " AND USER_PASSWORD=:2";
			if ($alreadyencoded) {
				$aBind[":2"] = $oConnection->strToBind($userpassword);
			} else {
				$aBind[":2"] = $oConnection->strToBind(md5($userpassword));
			}
		}
		$aBind[":1"] = $oConnection->strToBind($userlogin);
		
		//Récup des infos utilisateur 
		$this->value = $oConnection->queryRow($query, $aBind);
		
		}
	}
?>