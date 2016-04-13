<?php

/** Fonctions globales de la plate-forme
 *
 * @package Pelican
 * @subpackage config
 * @author Raphaël Carles <rcarles@businessdecision.com>, Laurent Franchomme <laurent.franchomme@businessdecision.com>
 * @since ???, 16/12/08 (LFR) modif getSiteInfos (constante de langue initialement sur FrontOffice.php dans initSite)
 */

function valueExists($var = array(), $index = "") {
	$return = false;

	if ($var && $index) {
		/*
		 * if (isset($var[$index])) { if ($var[$index]) { $return = true; } }
		 */
		$return = ! empty ( $var [$index] );
	}
	return $return;
}

function initVar(&$var, $value = "") {
	if (! isset ( $var ))
		$var = $value;
}

/**
 * Enter description here...
 *
 * @param $plageIP string
 * @param $sonde string
 * @return string
 */
function verifIP($plageIP, $sonde) {

	$arPlageIP = explode ( ",", $plageIP );

	$remoteIP = ip2long ( $_SERVER ["REMOTE_ADDR"] );
	$minIP = ip2long ( $arPlageIP [0] );
	$maxIP = ip2long ( $arPlageIP [1] );

	if ($remoteIP >= $minIP && $remoteIP <= $maxIP) {
		return $sonde;
	}

	return false;
}

/**
 * getValuesFromArray, transforme le tableau passé en paramêtre en tableau de la
 * forme : array(ID=>LIB)
 *
 * @author Fairouz Bihler <fbihler@businessdecision.com>
 * @since 25/08/2004
 */
function getValuesFromArray($aDataValues) {
	$aOutPutArray = array ();
	if (is_array ( $aDataValues )) {
		foreach ( $aDataValues as $valeur ) {
			$aOutPutArray [$valeur [0]] = $valeur [1];
		}
	}
	return $aOutPutArray;
}

/**
 * Informations liées au navigateur et à l'OS de l'internaute ainsi qu'aux
 * versions applicatives côté serveur
 *
 * @static
 *
 * @access public
 * @return void
 */
function getAppVersion() {

	if (isset ( $_SESSION ["ENV"] )) {
		Pelican::$config ['ENV'] = $_SESSION ["ENV"];
	} else {
		/**
		 * Navigateur
		 */
		$userAgent = $_SERVER ['HTTP_USER_AGENT'];
		$logVersion = "";
		if (preg_match ( '/Lynx/', $userAgent )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['lynx'] = 1;
		} elseif (preg_match ( '#Firefox\/([0-9].[0-9]{1,2})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['firefox'] = $logVersion [1];
		} elseif (preg_match ( '#MSIE ([0-9]{1,2}.[0-9]{1,2})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['ie'] = $logVersion [1];
		} elseif (preg_match ( '#Opera(/| )([0-9].[0-9]{1,2})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['opera'] = $logVersion [1];
		} elseif (preg_match ( '#OmniWeb/([0-9].[0-9]{1,2})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['omniweb'] = $logVersion [1];
		} elseif (preg_match ( '#Netscape([0-9]{1})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['netscape'] = $logVersion [1];
		} elseif (preg_match ( '#Safari/([0-9]*)#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['safari'] = $logVersion [1];
		} elseif (preg_match ( '#(Konqueror/)(.*)(;)#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['konqueror'] = $logVersion [2];
		} elseif ((preg_match ( '#Nav#', $userAgent )) || (preg_match ( '/Mozilla\/4\./', $userAgent ))) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['netscape'] = 4;
		} elseif (preg_match ( '#Mozilla/([0-9].[0-9]{1,2})#', $userAgent, $logVersion )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['mozilla'] = $logVersion [1];
		} elseif (preg_match ( '#Gecko#', $userAgent )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['gecko'] = 1;
		}

		/**
		 * OS
		 */
		if (strstr ( $userAgent, 'Win' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['OS'] ['win'] = 1;
		} elseif (strstr ( $userAgent, 'Mac' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['OS'] ['mac'] = 1;
		} elseif (strstr ( $userAgent, 'Linux' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['OS'] ['linux'] = 1;
		} elseif (strstr ( $userAgent, 'Unix' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['OS'] ['unix'] = 1;
		} elseif (strstr ( $userAgent, 'OS/2' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['OS'] ['OS/2'] = 1;
		}

		/**
		 * Server
		 */
		if (strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Apache' ) || strstr ( $_SERVER ['SERVER_SOFTWARE'], 'LiteSpeed' )) {
			$version = 1;
			if (strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Apache/2' )) {
				$version = 2;
			}
			Pelican::$config ['ENV'] ['REMOTE'] ['SERVER'] ['apache'] = $version;
		}
		if (strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Microsoft-IIS' )) {
			Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['iis'] = 1;
		}

		/**
		 * moteur de recherche
		 */
		if (! isset ( $_ENV ['MACHTYPE'] )) {
			$_ENV ['MACHTYPE'] = "";
		}
		if (strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Unix' )) {
			if (strstr ( $_ENV ['MACHTYPE'], 'linux' )) {
				Pelican::$config ['ENV'] ['REMOTE'] ['OS'] ['linux'] = 1;
			} else {
				Pelican::$config ['ENV'] ['REMOTE'] ['OS'] ['unix'] = 1;
			}
		} elseif (strstr ( $_SERVER ['SERVER_SOFTWARE'], 'Win' )) {
			Pelican::$config ['ENV'] ['REMOTE'] ['OS'] ['win'] = 1;
		}
		if (preg_match ( '/Apache\/([0-9\.]*)/', $_SERVER ['SERVER_SOFTWARE'], $arr )) {
			Pelican::$config ['ENV'] ['REMOTE'] ['SERVER'] ['apache'] = $arr [1];
		}
		Pelican::$config ['ENV'] ['REMOTE'] ['SERVER'] ['php'] = phpversion ();
		$_SESSION ["ENV"] = Pelican::$config ['ENV'];
	}
	// On OS X Server, $_SERVER['REMOTE_ADDR'] is the server's address.
	// Workaround this
	// by using $_SERVER['HTTP_PC_REMOTE_ADDR'], which *is* the remote address.
	if (isset ( $_SERVER ['HTTP_PC_REMOTE_ADDR'] )) {
		$_SERVER ['REMOTE_ADDR'] = $_SERVER ['HTTP_PC_REMOTE_ADDR'];
	}
}

function makeClearUrl($id, $type, $title = "", $path = "", $mediaId = "", $externalLink = "", $xiti = "") {

	$return = "/_/Index/?" . $type . "=" . $id;
	if ($externalLink) {
		$return = $externalLink . "\" target=\"_blank";
		$file = basename ( $externalLink );
	} elseif ($mediaId) {
		include_once (pelican_path ( 'Media' ));
		$return = Pelican::$config ["MEDIA_HTTP"] . Pelican_Media::getMediaPath ( $mediaId );
		$file = basename ( $return );
	} elseif ($title) {
		$return = str_replace ( "-.html", ".html", "/" . $type . $id . "-" . Pelican_Text::cleanText ( $title, "-", false, false ) . ".html" );
		if ($path) {
			$tmp = explode ( "#", $path );

			/**
			 * suppression du premier niveau
			 */
			if (count ( $tmp ) > 1) {
				array_shift ( $tmp );

				// array_shift($tmp);
			}
			if ($tmp) {
				foreach ( $tmp as $rep ) {
					$aRep = explode ( "|", $rep );
					$aURL [] = Pelican_Text::cleanText ( $aRep [1], "-", false, false );
				}
			}
			if ($aURL) {
				if ($type == "pid") {
					array_pop ( $aURL );
				}
				// array_pop($aURL);

				$return0 = implode ( "/", $aURL );
			}
			$return = "/" . $return0 . $return;
		}
		$return = str_replace ( "-/", "/", $return );
		$return = str_replace ( "/-", "/", $return );
		$return = str_replace ( "-.", ".", $return );
		$return = str_replace ( "//", "/", $return );
		$return = str_replace ( "---", "-", $return );
		$return = str_replace ( "--", "-", $return );
	}
	if ($xiti && $file) {
		$return .= "\" onclick=\"xt_med('C','" . $xiti . "','" . $file . "','T');";
	}
	$return = strtolower ( $return );

	return $return;
}

function readIniFile($filename, $commentchar = ";", $arraychar = " | ") {
	$use = array ();
	$array1 = file ( $filename );
	$section = '';
	foreach ( $array1 as $filedata ) {
		$dataline = str_replace ( "\t", "", trim ( $filedata ) );
		$firstchar = substr ( $dataline, 0, 1 );
		if ($firstchar != $commentchar && $dataline) {
			// It's an entry (not a Pelican_Index_Comment and not a blank line)
			if ($firstchar == '[' && substr ( $dataline, - 1, 1 ) == ']') {
				// It's a section
				$section = substr ( $dataline, 1, - 1 );
			} else {
				// It's a key...
				$delimiter = strpos ( $dataline, '=' );
				if ($delimiter > 0) {
					// ...with a value
					$key = trim ( substr ( $dataline, 0, $delimiter ) );
					$value = trim ( substr ( $dataline, $delimiter + 1 ) );
					$value = stripcslashes ( $value );
					$tmp = explode ( " | ", $value );
					if (count ( $tmp ) > 1) {
						$value = array_map ( array ('Pelican_Text', 'trimCote' ), $tmp );
					} else {
						$value = Pelican_Text::trimCote ( $value );
					}

				} else {
					// ...without a value
					$value = '';
				}
				if ($use [$section] [$key]) {
					if ($use [$section] [$key] == 1)
						$array2 [$section] [$key] = array ($array2 [$section] [$key] );
					$array2 [$section] [$key] [] = $value;
				} else {
					$array2 [$section] [$key] = $value;
				}
				$use [$section] [$key] ++;
			}
		} else { // It's a Pelican_Index_Comment or blank line. Ignore.
		}
	}
	return $array2;
}

function arrayToCombo($aValues, $id = "id", $lib = "lib", $sort = "", $optgroup = "optgroup") {

	if ($aValues) {
		foreach ( $aValues as $valeur ) {
			if (! isset ( $valeur [$optgroup] )) {
				$valeur [$optgroup] = "";
			}
			$aDataValues [$valeur [$optgroup]] [$valeur [$id]] = $valeur [$lib];
		}
	}
	if ($aDataValues && isset ( $sort ) && $sort) {
		foreach ( $aDataValues as $key => $array ) {
			$aDataValues [$key] = asort ( $array, $sort );
		}
	}

	if (count ( $aDataValues ) == 1) {
		if (count ( @$aDataValues [""] )) {
			$aDataValues = $aDataValues [""];
		}
	}

	return $aDataValues;
}

function setCookieFW($id, $value = "") {
	$_COOKIE [$id] = $value;
	setCookie ( $id, $value );
}

function getOnlineStatus($field, $view = "CURRENT", $true = 1) {
	$return = ($view == "CURRENT" ? $true : $field);
	return $return;
}

/**
 * Enter description here...
 *
 * @param $page_template_id unknown_type
 * @return unknown
 */
function getPageTypeCode($page_type_id) {
	if ($page_type_id) {
		$aTypes = Pelican_Cache::fetch ( "Backend/Generic", array ("page_type", "", "PAGE_TYPE_ID=" . $page_type_id ) );
		$return = $aTypes [0] ['PAGE_TYPE_CODE'];
	}
	return $return;
}

/**
 * Enter description here...
 *
 * @param $code unknown_type
 * @return unknown
 */
function getPageTypeId($code) {
	if ($code) {
		$aTypes = Pelican_Cache::fetch ( "Backend/Generic", array ("page_type", "", "PAGE_TYPE_CODE='" . $code . "'" ) );
		$return = $aTypes [0] ['PAGE_TYPE_ID'];
	}
	return $return;
}

/**
 * Création d'un onglet
 *
 * @return void
 * @param $label string
 *       	 Titre de l'onglet
 * @param $param string
 *       	 id de l'onglet (concaténé avec "O_")
 * @param $title string
 *       	 Titre de la partie de gauche (peut être défini dans le formulaire
 *        	de gestion de l'onglet (ou menu))
 * @param $size string
 *       	 Identifiant de taille (vide par défaut, sinon "big", "small") pour
 *        	choisir l'image de fond
 */
function buildTab($label, $id = "", $activate = false, $link = "", $onclick = "", $title = "Rubriques", $size = "", $width = "", $bDirectOutput = true, $limit = "") {
	global $title_left, $intOnglet, $maxOnglet, $countOnglet;

	++ $countOnglet;

	$int = "";
	if ($intOnglet) {
		$int = "_int";
	}

	if ($size) {
		$size .= "_";
	}
	if ($width) {
		$width = " style=\"width:" . $width . "\"";
	}
	if ($link) {
		$link = " href=\"" . $link . "\"";
	}
	if ($onclick) {
		$onclick = " onclick=\"" . $onclick . "\"";
	}
	if ($id) {
		$id1 = " id=\"" . $id . "_1\"";
		$id2 = " id=\"" . $id . "_2\"";
		$id3 = " id=\"" . $id . "_3\"";
	}

	$etat = "off";
	$font = "";
	if ($activate) {
		$etat = "on";
		$font = "font-weight: bold;";
	}

	$imageLeft = Pelican::$frontController->skinPath . "/images/" . $size . "onglet_" . $etat . "_gauche" . ($countOnglet != 1 ? $int : "") . ".gif";
	$imageRight = Pelican::$frontController->skinPath . "/images/" . $size . "onglet_" . $etat . "_droite" . ($countOnglet != $maxOnglet ? $int : "") . ".gif";

	$return = "<div class=\"" . $size . "onglet\">";
	$return .= "<div class=\"" . $size . "onglet " . $size . "onglet_side\"><img" . $id1 . " border=\"0\" alt=\"\" src=\"" . $imageLeft . "\" /></div>";
	$return .= "<div" . $id2 . " class=\"" . $size . "onglet " . $size . "onglet_centre\" style=\"background-image: url(" . Pelican::$frontController->skinPath . "/images/" . $size . "onglet_" . $etat . "_centre.gif);" . $font . "\"" . $width . ">";

	if ($etat == "off" || $onclick) {
		$return .= "<a " . $link . $onclick . ">";
		$return .= $label;
		$return .= "</a>";
	} else {
		$return .= $label;
	}
	$return .= "</div>";
	$return .= "<div class=\"" . $size . "onglet " . $size . "onglet_side\"><img" . $id3 . " border=\"0\" alt=\"\" src=\"" . $imageRight . "\" /></div>";
	$return .= "</div>";

	if ($activate) {
		$title_left = $title;
	}

	if ($bDirectOutput) {
		echo ($return);
	} else {
		return $return;
	}
}

function getTabParamChat($param) {
	$values = array ();

	$pattern_start = "<service><nom><![CDATA[";
	$pattern_end = "]]></nom>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_NOM"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<prenom><![CDATA[";
	$pattern_end = "]]></prenom>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_PRENOM"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<url><![CDATA[";
	$pattern_end = "]]></url>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_URL"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<titrefo><![CDATA[";
	$pattern_end = "]]></titrefo>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_TITLE_FO"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<accroche><![CDATA[";
	$pattern_end = "]]></accroche>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_ACROCHE"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<desc><![CDATA[";
	$pattern_end = "]]></desc>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_DESC"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<date_chat><![CDATA[";
	$pattern_end = "]]></date_chat>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_DATE"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<time_start><![CDATA[";
	$pattern_end = "]]></time_start>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_TIME_START"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<time_end><![CDATA[";
	$pattern_end = "]]></time_end></service>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_CHAT_TIME_END"] = $aVal [0];
	$param = $aVal [1];

	return $values;
}

function getTabParamNewsletter($param) {
	$values = array ();

	$pattern_start = "<service><nom><![CDATA[";
	$pattern_end = "]]></nom>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_NEWSLETTER_NOM_EXP"] = $aVal [0];
	$param = $aVal [1];

	$pattern_start = "<email><![CDATA[";
	$pattern_end = "]]></email></service>";
	$aVal = explode ( $pattern_end, str_replace ( $pattern_start, "", $param ) );
	$values ["SERVICE_NEWSLETTER_EMAIL_EXP"] = $aVal [0];
	$param = $aVal [1];

	return $values;
}

/**
 * __DESC__
 *
 * @param $array __TYPE__
 *       	 __DESC__
 * @param $field string
 *       	 (option) __DESC__
 * @return __TYPE__
 */
function array_sort(&$array, $field = "") {
	if ($field && $array) {
		$i = 0;
		foreach ( $array as $line ) {
			$i ++;
			$temp [$line [$field] . $i] = $line;
		}
		if ($temp) {
			@ksort ( $temp );
			$array = array ();
			foreach ( $temp as $line ) {
				$array [] = $line;
			}
		}
	}
}

/**
 * Toutes les variables _PATH du tableau Pelican::$config sont automatiquement
 * transformées en chemin physiques _ROOT
 *
 * @return boolean
 */
function pathToRoot($const) {
	foreach ( $const as $key ) {
		$newKey = str_replace ( "_PATH", "_ROOT", $key );
		if (empty ( Pelican::$config [$newKey] )) {
			if (isset ( Pelican::$config [$key] )) {
				Pelican::$config [$newKey] = $_SERVER ["DOCUMENT_ROOT"] . Pelican::$config [$key];
			}
		}
	}
	return true;
}

/**
 * Transforme un chemin d'upload relatif en chemin physique
 *
 * @return string Chemin physique
 */
function getUploadRoot($path) {

	// fichiers externes
	if (strpos ( $path, 'http://' ) !== false) {
		if ($_SERVER['QUERY_STRING']) {
			$path .= '?'.$_SERVER['QUERY_STRING'];
		}
		$oldpath = $path;
		$path = Pelican::$config ["CACHE_FW_ROOT"] . '/external_media_' . md5 ( $path );
		if (! file_exists ( $path )) {
			$pathinfo = pathinfo ( $path );
			$path = Pelican::$config ["CACHE_FW_ROOT"] . '/external_media_' . md5 ( $path ).'.img';
			file_put_contents ( $path, file_get_contents ( $oldpath ) );
		}
	} else {
		if ($path) {
			if (substr_count ( '/' . $path, Pelican::$config ["LIB_PATH"] )) {
				if (Pelican::$config ["MEDIA_VAR"]) {
					$path = str_replace ( Pelican::$config ["LIB_PATH"], "", '/' . $path );
				}
				$path = str_replace ( '//', '/', Pelican::$config ["LIB_ROOT"] . str_replace ( Pelican::$config ["LIB_ROOT"], "", '/' . $path ) );
			} else {
				if (Pelican::$config ["MEDIA_VAR"]) {
					$path = str_replace ( Pelican::$config ["MEDIA_VAR"], "", $path );
				}
				$path = str_replace ( '//', '/', Pelican::$config ["MEDIA_ROOT"] . str_replace ( Pelican::$config ["MEDIA_ROOT"], "", '/' . $path ) );
			}
		}
	}
	return $path;
}

if (! function_exists ( 'http_build_url' )) {
	define ( 'HTTP_URL_REPLACE', 1 ); // Replace every part of the first URL when
	                               // there's one of the second URL
	define ( 'HTTP_URL_JOIN_PATH', 2 ); // Join relative paths
	define ( 'HTTP_URL_JOIN_QUERY', 4 ); // Join query strings
	define ( 'HTTP_URL_STRIP_USER', 8 ); // Strip any user authentication
	                                  // information
	define ( 'HTTP_URL_STRIP_PASS', 16 ); // Strip any Pelican_Security_Password
	                                   // authentication information
	define ( 'HTTP_URL_STRIP_AUTH', 32 ); // Strip any authentication information
	define ( 'HTTP_URL_STRIP_PORT', 64 ); // Strip explicit port numbers
	define ( 'HTTP_URL_STRIP_PATH', 128 ); // Strip complete path
	define ( 'HTTP_URL_STRIP_QUERY', 256 ); // Strip query string
	define ( 'HTTP_URL_STRIP_FRAGMENT', 512 ); // Strip any fragments (#identifier)
	define ( 'HTTP_URL_STRIP_ALL', 1024 ); // Strip anything but scheme and host

	// Build an URL
	                                    // The parts of the second URL will be
	                                    // merged into the first according to the
	                                    // flags argument.
	                                    //
	                                    // @param mixed (Part(s) of) an URL in
	                                    // Pelican_Form of a string or associative
	                                    // array like parse_url() returns
	                                    // @param mixed Same as the first
	                                    // argument
	                                    // @param int A bitmask of binary or'ed
	                                    // HTTP_URL constants
	                                    // (Optional)HTTP_URL_REPLACE is the
	                                    // default
	                                    // @param array If set, it will be filled
	                                    // with the parts of the composed url like
	                                    // parse_url() would return
	function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false) {
		$keys = array ('user', 'pass', 'port', 'path', 'query', 'fragment' );

		// HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
		if ($flags & HTTP_URL_STRIP_ALL) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
			$flags |= HTTP_URL_STRIP_PORT;
			$flags |= HTTP_URL_STRIP_PATH;
			$flags |= HTTP_URL_STRIP_QUERY;
			$flags |= HTTP_URL_STRIP_FRAGMENT;
		} 		// HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and
		  // HTTP_URL_STRIP_PASS
		else if ($flags & HTTP_URL_STRIP_AUTH) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
		}

		// Parse the original URL
		$parse_url = parse_url ( $url );

		// Scheme and Host are always replaced
		if (isset ( $parts ['scheme'] ))
			$parse_url ['scheme'] = $parts ['scheme'];
		if (isset ( $parts ['host'] ))
			$parse_url ['host'] = $parts ['host'];

			// (If applicable) Replace the original URL with it's new parts
		if ($flags & HTTP_URL_REPLACE) {
			foreach ( $keys as $key ) {
				if (isset ( $parts [$key] ))
					$parse_url [$key] = $parts [$key];
			}
		} else {
			// Join the original URL path with the new path
			if (isset ( $parts ['path'] ) && ($flags & HTTP_URL_JOIN_PATH)) {
				if (isset ( $parse_url ['path'] ))
					$parse_url ['path'] = rtrim ( str_replace ( basename ( $parse_url ['path'] ), '', $parse_url ['path'] ), '/' ) . '/' . ltrim ( $parts ['path'], '/' );
				else
					$parse_url ['path'] = $parts ['path'];
			}

			// Join the original query string with the new query string
			if (isset ( $parts ['query'] ) && ($flags & HTTP_URL_JOIN_QUERY)) {
				if (isset ( $parse_url ['query'] ))
					$parse_url ['query'] .= '&' . $parts ['query'];
				else
					$parse_url ['query'] = $parts ['query'];
			}
		}

		// Strips all the applicable sections of the URL
		// Note: Scheme and Host are never stripped
		foreach ( $keys as $key ) {
			if ($flags & ( int ) constant ( 'HTTP_URL_STRIP_' . strtoupper ( $key ) ))
				unset ( $parse_url [$key] );
		}

		$new_url = $parse_url;

		return ((isset ( $parse_url ['scheme'] )) ? $parse_url ['scheme'] . '://' : '') . ((isset ( $parse_url ['user'] )) ? $parse_url ['user'] . ((isset ( $parse_url ['pass'] )) ? ':' . $parse_url ['pass'] : '') . '@' : '') . ((isset ( $parse_url ['host'] )) ? $parse_url ['host'] : '') . ((isset ( $parse_url ['port'] )) ? ':' . $parse_url ['port'] : '') . ((isset ( $parse_url ['path'] )) ? $parse_url ['path'] : '') . ((isset ( $parse_url ['query'] )) ? '?' . $parse_url ['query'] : '') . ((isset ( $parse_url ['fragment'] )) ? '#' . $parse_url ['fragment'] : '');
	}
}

/**
 * Récup du chemin du Pelican_Media passé en paramètre
 *
 * @param $media_id int
 *       	 __DESC__
 * @return string
 */
function getPathMedia($media_id) {
	return Pelican::getPathMedia ( $media_id );
}

/**
 * Identifie la version à utiliser pour l'affichage (Publié ou draft)
 *
 * @return string
 */
function getPreviewVersion() {
	return Pelican::getPreviewVersion ();
}

/**
 * Fonction renvoyant le resultat de la class form ou le formulaire
 *
 * @param $oForm objet
 *       	 (objet de la class form)
 * @param $sForm string
 *       	 (string form)
 * @return string $result (retour du formulaire)
 */
function formToString($oForm, $sForm) {
	/*
	 * Si l'objet de la class form contient __tostring, on renvoie le resultat
	 * du __tostring de cette class
	 */
	if (is_object ( $oForm )) {
		$reflector = new ReflectionClass ( $oForm );
		if ($reflector && $reflector->hasMethod ( '__tostring' )) {
			$sForm = $oForm->__tostring ();
		}
	}

	return $sForm;
}

function dropaccent($string,$all_replace=false) {
return dropaccentIconv($string,$all_replace);
//return dropaccentStrReplace($string,$all_replace);
}

function dropaccenttranslit($string, $all_replace=false) {
    if(phpversion() >= 5.4) {
        //$rule = 'NFD; [:Nonspacing Mark:] Remove; NFC';
        $rule = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC';
        $myTrans = Transliterator::create($rule);
        $string = $myTrans->transliterate($string);
        $return = $string;
    } else {
        return dropaccentIconv($string,$all_replace);
    }
}

function dropaccentIconv($string,$all_replace=false) {

    if(phpversion() >= 5.4) {
        //$rule = 'NFD; [:Nonspacing Mark:] Remove; NFC';
        $rule = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC';
        $myTrans = Transliterator::create($rule);
        $string = $myTrans->transliterate($string);
    } else {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    }

    $search = array('__','+','!','@','?','|','quot;','\\','>>','>','=','<<','<< ','<',': ','/','...','.','--','-',',','(tm)','(r)','(c)','','','','','','','','','','\'','&quot;','&nbsp;','&','&#363;','%','$','#','"','""',' >>',' 1/2 ',' /',' ',' ');
    $replace = array('_','','','','','','','_','','_','','','','_','','_','','','_','_','','','','','y','t','o','n','l','i','h','d','_','_','','','','363_','','usd','','_','_','','','','_','');
    $string = str_replace($search, $replace, $string);
	//$string = preg_replace('#([^.a-z0-9]+)#i', '_', $string);

    if($all_replace)
    {
	    $string = preg_replace('#([^.a-z0-9]+)#i', '_', $string);
    }

    return strtolower($string);
}

function dropaccentStrReplace($string,$all_replace=false) {


$aSearch = array("¿","½","« "," »","§","¡ ","¿","°","«","»","¡","…","|","#",".","=","²"," /","%","º","/","\\","¨","®","©","‚","¬","","¯","¦","ª","™","¢","ƒ","Ć","ć","Ĉ","ĉ","Č","č","Ç","ç","ç","Ď","Đ","đ","Ð","ð","ð","é","é","È","É","è","Ẹ","Ệ","Ẽ","Ễ","è","Ê","ê","ê","Ë","ë","ë","Ě","ě","Ĕ","ĕ","Ē","ē","ẽ","Ę","ę","ế","ề","Ề","Ẻ","Ế","Ể","ễ","ẻ","ể","ẹ","ệ","ġ","Ĝ","ĝ","Ğ","ğ","Ģ","ģ","Ĥ","ĥ","ħ","ı","Í","í","Ì","ì","Ị","Î","î","Ï","ï","ǐ","Ĭ","ĭ","Ī","ī","Ĩ","ĩ","Į","į","ỉ","Ỉ","ị","Ĵ","ĵ","Ķ","ķ","Ĺ","ĺ","ŀ","ľ","Ļ","ļ","Ł","ł","Ń","ń","Ň","ň","Ñ","ñ","ñ","ņ","ŋ","º","Ó","ó","ó","Ò","ò","ò","Ô","ô","ô","Ö","ö","ö","ǒ","ŏ","ō","Õ","õ","õ","ő","ố","ồ","Ø","Ơ","Ồ","ø","ø","ỗ","Ỗ","ǿ","ỏ","Ỡ","ổ","Ổ","Ọ","Ộ","Ỏ","ọ","ớ","Ợ","Ờ","ờ","ỡ","ộ","Ở","ở","ợ","ŕ","Ř","ř","ŗ","Ś","ś","ŝ","Š","š","š","Ş","ş","Ť","Ţ","ţ","þ","Þ","Ŧ","ŧ","Ứ","Ú","Ù","ú","ú","ù","ù","Û","û","û","û","Ü","ü","ü","ǔ","ŭ","Ū","Ữ","ū","Ũ","ũ","Ů","ů","Ų","Ừ","ų","ủ","Ủ","ụ","ứ","ừ","ữ","ử","Ụ","Ự","Ử","ự","Ỳ","ý","ý","ỳ","ÿ","Ỵ","Ỹ","Ÿ","ỹ","ỷ","Ý","Ỷ","Ź","ź","ż","Ž","ž","ž","à","á","â","Ä","ä","ǎ","ă","Ā","ā","Ã","Á","Ằ","ã","ã","Ẩ","Å","Ầ","Ẳ","Â","å","å","Å","Ả","Ą","Ă","ą","ấ","ầ","Ấ","ắ","Ắ","Ẫ","Ẵ","ằ","ẫ","ả","ẩ","ẳ","ậ","À","Ặ","Ậ","€","£","×",">","<","”","“","’","´","\"\"","\"","/","—","–","-","\xC2","\xa0","&#363;","\"\"","\"","&quot;","quot;",": ","æ","œ","Œ","ß"," !"," ?"," /","$","\\","&nbsp;","&","'","¨",",","!","+","?","¿","§","@"," ");

$aReplace1= array("","", "",  "",  "", "",  "", "_","", "", "", "", "", "", "", "", "", "",  "", "", "_", "_", "", "", "", "", "","", "", "", "", "", "", "","c","c","c","c","c","c","c","c","c","d","d","d","d","o","o","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","e","g","g","g","g","g","g","g","h","h","h","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","i","j","j","k","k","l","l","l","l","l","l","l","l","n","n","n","n","n","n","n","n","n","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","r","r","r","r","s","s","s","s","s","s","s","s","t","t","t","y","t","t","t","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","y","y","y","y","y","y","y","z","z","z","z","z","z","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","E","L","x","_","_","_","_","_","_","_"   ,"_", "_","_","_","_",""    ,""    ,"u"     ,""    ,""  ,""      ,""     ,""  ,"ae","oe","oe","ss",""  ,""  ,""  ,"USD",""  ,""      ,"" ,"_","" ,"" ,"" ,"" ,"" ,"" ,"" ,"" ,"_");

$string=str_replace($aSearch, $aReplace1, $string);

if($all_replace)
{
	$string = preg_replace('#([^.a-z0-9]+)#i', '_', $string);
}

return strtolower($string);
}

/**
 * Génère un header pour les tableaux BO
 *
 * @param string $lib
 * @param string $color
 */
function paragraphTitle($lib, $color = '#EFEFEF', $bDirectOuput = false) {
	$sRetour = "<tr><td height='18' colspan='2' bgcolor='".$color."' align='center'><b><i>".strtoupper($lib)."</i></b></td></tr>";
	if ($bDirectOuput) {
		echo $sRetour;
	} else {
		return $sRetour;
	}
}

/**
 *  Gestion encodage utf8_encode prise en compte caractere cyrilique
 */

function utf8_encode_without_cyrilique ( $data, $lan=''){
	//return $data;
    if( preg_match( '/[\p{Cyrillic}]/u', $data) || mb_detect_encoding($data, 'UTF-8', true) == 'UTF-8'){
        return $data;
    }else{
        return utf8_encode( $data );
    }
}
