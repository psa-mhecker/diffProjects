<?php

class Citroen_View_Helper_Cookie
{

	/**
	 * Création d'un cookie
	 *
	 * @param $name
	 * @param $value
	 * @param $expire
	 * @param $path
	 * @param $domain
	 * @param $secure
	 * @param $httponly
	 * @param $serialize
	 *
	 * @return array
	 */
	public static function setCookie($name, $value, $expire = 0, $path = "/", $domain = "", $secure = false, $httponly = false, $serialize = false)
	{	
		if ($_SESSION[APP]['USE_COOKIES'] == true || $_SESSION[APP]['USE_COOKIES'] == 1) {
		
			//durée du cookie
			if ($expire == 0 && $name != 'CPPV2_perso') {

				$page = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateIdPage", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					"CURRENT",
					Pelican::$config['ZONE_TEMPLATE_ID']['CONNEXION']
				));
				//time()+60*60*24*30 30 jours
				if ($page["ZONE_TITRE"] > 0) {
					$expire = time() + 60 * 60 * 24 * $page["ZONE_TITRE"];
				}
			}
			elseif($name == 'CPPV2_perso')
			{
				//durée perso
				  $aSite = Pelican_Cache::fetch("Frontend/Site", array(
                    $_SESSION[APP]['SITE_ID']
        			));
					$expire = time() + 60 * 60 * 24 * $aSite["SITE_PERSO_DURATION_COOKIE"];
				
			}

			//Si true serialisation des données
			if ($serialize == true) {
				$value = serialize($value);
			}
			if ($domain != "") {
				//définition du cookie
				setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
			} else {
				//définition du cookie
				setcookie($name, $value, $expire, $path);
			}
		}
	}

	/**
	 * Récupération d'un cookie
	 *
	 * @param $name
	 * @param $unserialize
	 *
	 * @return array
	 */
	public static function getCookie($name, $unserialize = false)
	{
		if (isset($_COOKIE[$name])) {
			if ($unserialize == true) {
				$cookie = unserialize($_COOKIE[$name]);
			} else {
				$cookie = $_COOKIE[$name];
			}
		} else {
			$cookie = null;
		}
		return $cookie;
	}

}

?>
