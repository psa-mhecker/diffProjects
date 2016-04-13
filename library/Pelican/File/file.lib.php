<?php
/**
	* Librairie de gestion des fichiers
	*
	* @package Pelican
	* @subpackage File
	*/

$iconPath = Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FILE']."/images/";

/**
	* Tableau associatif des types mimes avec les extensions de fichier
	*/
$type_mime = array("bin" => array("application/octet-stream", "application", "Fichier binaire non interprété"),
"bmp" => array("image/bmp", "image", "Image BMP"),
"bz2" => array("application/x-bzip2", "archive", ""),
"c" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"cc" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"ccad" => array("application/clariscad", "document", "Fichier ClarisCAD"),
"cdf" => array("application/x-netcdf", "document", "Fichier netCDF"),
"cgi" => array("text/plain", "script", "Script CGI"),
"chrt" => array("application/x-kchart", "document", ""),
"class" => array("application/octet-stream", "document", "Fichier de classe JAVA"),
"cmu" => array("image/x-cmu-raster", "image", "Raster cmu"),
"cpio" => array("application/x-cpio", "application", "CPIO Posix"),
"cpt" => array("application/mac-compactpro", "archive", "Archive Compact Pro"),
"csh" => array("application/x-csh", "document", ""),
"css" => array("text/css", "text", "Feuille de style CSS"),
"csv" => array("text/comma-separated-value", "text", "Fichier texte avec séparation des valeurs"),
"dcr" => array("application/x-director", "document", ""),
"dir" => array("application/x-director", "document", ""),
"dms" => array("application/octet-stream", "application", ""),
"doc" => array("application/msword", "document", "Fichier Word"),
"docx" => array("application/msword", "document", "Fichier Word"),
"drw" => array("application/drafting", "document", "Fichier MATRA Prelude drafting"),
"dvi" => array("application/x-dvi", "document", "Fichier texte dvi"),
"dwg" => array("application/pdf", "document", "Fichier PDF"),
"dxf" => array("application/dxf", "document", "Fichier AutoCAD"),
"dxr" => array("application/x-director", "document", ""),
"eps" => array("application/postscript", "document", "Fichier PostScript"),
"etx" => array("text/x-setext", "text", "Fichier texte Struct"),
"exe" => array("application/octet-stream", "application", ""),
"ez" => array("application/andrew-inset", "document", "--"),
"f90" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"g" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"gif" => array("image/gif", "image", "Image GIF"),
"gtar" => array("application/x-gtar", "archive", "Tar GNU"),
"gz" => array("application/x-gzip", "archive", "Archive GNU zip"),
"gzip" => array("application/x-gzip", "archive", "Archive GNU zip"),
"h" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"hdf" => array("application/x-hdf", "document", "Fichier de données"),
"hh" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"hqx" => array("application/mac-binhex40", "archive", "Archive BinHex"),
"htacces" => array("text/plain", "config", "Fichier de configuration HTACCES"),
"htm" => array("text/html", "web", "Fichier HTML"),
"html" => array("text/html", "web", "Fichier HTML"),
"ief" => array("image/ief", "image", "Image exchange format"),
"iges" => array("model/iges", "document", "Format d'échange CAO IGES"),
"igs" => array("model/iges", "document", "Format d'échange CAO IGES"),
"ini" => array("text/plain", "config", "Fichier de configuration INI"),
"jpe" => array("image/jpeg", "image", "Image JPEG"),
"jpeg" => array("image/jpeg", "image", "Image JPEG"),
"jpg" => array("image/jpeg", "image", "Image JPEG"),
"js" => array("application/x-javascript", "script", "Fichier Javascript"),
"kar" => array("audio/midi", "audio", "Fichier audio MIDI"),
"kil" => array("application/x-killustrator", "document", ""),
"kpr" => array("application/x-kpresenter", "document", ""),
"kpt" => array("application/x-kpresenter", "document", ""),
"ksp" => array("application/x-kspread", "document", ""),
"kwd" => array("application/x-kword", "document", ""),
"kwt" => array("application/x-kword", "document", ""),
"latex" => array("application/x-latex", "document", "Fichier LaTEX"),
"lha" => array("application/octet-stream", "archive", "Archive LHA"),
"lzh" => array("application/octet-stream", "archive", "Archive LZH"),
"m" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"man" => array("application/x-troff-man", "document", "Fichier Troff/macro MAN"),
"mdb" => array("application/msaccess", "document", "Base Access"),
"me" => array("application/x-troff-me", "document", "Fichier Troff/macro ME"),
"mesh" => array("model/mesh", "document", ""),
"mid" => array("audio/midi", "audio", "Fichier audio MIDI"),
"midi" => array("audio/midi", "audio", "Fichier audio MIDI"),
"mif" => array("application/vnd.mif", "document", "Fichier Framemaker"),
"moov" => array("video/quicktime", "video", "Vidéo QuickTime"),
"mov" => array("video/quicktime", "video", "Vidéo QuickTime"),
"movie" => array("video/x-sgi-movie", "video", "Vidéo MoviePlayer"),
"mp2" => array("audio/mpeg", "audio", "Fichier audio MPEG"),
"mp3" => array("audio/mpeg", "audio", "Fichier audio MPEG"),
"mp4" => array("audio/mpeg", "audio", "Fichier audio MPEG"),
"mpe" => array("video/mpeg", "video", "Vidéo MPEG"),
"mpeg" => array("video/mpeg", "video", "Vidéo MPEG"),
"mpg" => array("video/mpeg", "video", "Vidéo MPEG"),
"mpga" => array("audio/mpeg", "audio", "Fichier audio MPEG"),
"ms" => array("application/x-troff-ms", "document", "Fichier Troff/macro MS"),
"msh" => array("model/mesh", "document", ""),
"nc" => array("application/x-netcdf", "document", "Fichier netCDF"),
"oda" => array("application/oda", "document", "Fichier ODA"),
"pbm" => array("image/x-portable-bitmap", "image", "Image Bitmap PBM"),
"pdf" => array("application/pdf", "document", "Fichier PDF"),
"pgm" => array("image/x-portable-graymap", "image", "Image Graymap PBM"),
"pgn" => array("application/x-chess-pgn", "document", ""),
"php" => array("text/plain", "script", "Script PHP"),
"php2" => array("text/plain", "script", "Script PHP"),
"php3" => array("text/plain", "script", "Script PHP"),
"php4" => array("text/plain", "script", "Script PHP"),
"phps" => array("text/plain", "script", "Script PHP"),
"phtml" => array("text/plain", "script", "Script PHP"),
"pl" => array("text/plain", "script", "Script PERL"),
"png" => array("image/png", "image", "Image PNG"),
"pnm" => array("image/x-portable-anymap", "image", "Image Anymap PBM"),
"ppm" => array("image/x-portable-pixmap", "image", "Image Pixmap PBM"),
"ppt" => array("application/vnd.ms-powerpoint", "document", "Fichier PowerPoint"),
"pptx" => array("application/vnd.ms-powerpoint", "document", "Fichier PowerPoint"),
"prt" => array("application/pro_eng", "document", "Fichier ProEngineer"),
"ps" => array("application/postscript", "document", "Fichier PostScript"),
"qt" => array("video/quicktime", "video", "Vidéo QuickTime"),
"ra" => array("audio/x-realaudio", "audio", "Fichier audio REAL AUDIO"),
"ram" => array("audio/x-pn-realaudio", "audio", "Fichier audio REAL AUDIO"),
"rar" => array("application/octet-stream", "archive", "Archive WinRAR"),
"ras" => array("image/x-cmu-raster", "image", "Raster cmu"),
"rgb" => array("image/x-rgb", "image", "Image RGB"),
"rm" => array("audio/x-pn-realaudio", "audio", "Fichier audio REAL AUDIO"),
"roff" => array("application/x-troff", "document", "Fichier Troff"),
"rpm" => array("application/x-rpm", "audio", "Plug-in REAL AUDIO"),
"rtf" => array("application/rtf", "document", "Format de texte enrichi"),
"rtx" => array("text/richtext", "text", "Fichier texte enrichis"),
"sc4crc" => array("application/x-sv4crc", "document", "CPIO SVR4 avec CRC"),
"set" => array("application/set", "document", "Fichier CAO SET"),
"sgm" => array("text/sgml", "web", "Fichier SGML"),
"sgml" => array("text/sgml", "web", "Fichier SGML"),
"sh" => array("text/plain", "script", "Script SHELL"),
"shar" => array("application/x-shar", "archive", "Archives Shell"),
"shtml" => array("text/plain", "web", "Fichier SHTML"),
"si" => array("text/vnd.wap.si", "text", ""),
"sic" => array("application/vnd.wap.sic", "document", ""),
"silo" => array("model/mesh", "document", ""),
"sit" => array("application/x-stuffit", "archive", "Archive Stuffit"),
"skd" => array("application/x-koan", "document", ""),
"skm" => array("application/x-koan", "document", ""),
"skp" => array("application/x-koan", "document", ""),
"skt" => array("application/x-koan", "document", ""),
"sl" => array("text/vnd.wap.sl", "text", ""),
"slc" => array("application/vnd.wap.slc", "document", ""),
"smi" => array("application/smil", "audio", ""),
"smil" => array("application/smil", "audio", ""),
"snd" => array("audio/basic", "audio", "Fichier audio basique"),
"spl" => array("application/x-futuresplash", "document", ""),
"src" => array("application/x-wais-source", "document", "Source Wais"),
"step" => array("application/step", "document", "Fichier de données STEP"),
"stl" => array("application/sla", "document", "Fichier stéréolithographie"),
"sv4cpio" => array("application/x-sv4cpio", "document", "CPIO SVR4n"),
"swf" => array("application/x-shockwave-flash", "video", "Fichier Flash"),
"t" => array("application/x-troff", "document", "Fichier Troff"),
"tar" => array("application/x-tar", "archive", "Fichier compressé tar"),
"tcl" => array("application/x-tcl", "text", "Script Tcl"),
"tex" => array("application/x-tex", "text", "Fichier Tex"),
"texi" => array("application/x-texinfo", "text", "Fichier eMacs"),
"texinfo" => array("application/x-texinfo", "text", "Fichier eMacs"),
"text" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"tgz" => array("application/x-gzip", "archive", "Archive GNU zip"),
"tif" => array("image/tiff", "image", "Image TIFF"),
"tiff" => array("image/tiff", "image", "Image TIFF"),
"tr" => array("application/x-troff", "document", "Fichier Troff"),
"troff" => array("application/x-troff", "document", "Fichier Troff"),
"tsv" => array("text/tab-separated-value", "text", "Fichier texte avec séparation des valeurs"),
"txt" => array("text/plain", "text", "Fichier texte sans mise en forme"),
"unv" => array("application/i-deas", "document", "Fichier SDRC I-deas"),
"ustar" => array("application/x-ustar", "document", ""),
"vcd" => array("application/x-cdlink", "document", ""),
"vda" => array("application/vda", "document", "Fichier de surface"),
"vqf" => array("application/octet-stream", "audio", ""),
"vrml" => array("model/vrml", "document", ""),
"wav" => array("audio/x-wav", "audio", "Fichier audio Wave"),
"wbmp" => array("image/vnd.wap.wbmp", "image", "Image WBMP"),
"wml" => array("text/vnd.wap.wml", "script", "Script WML"),
"wmlc" => array("application/vnd.wap.wmlc", "document", ""),
"wmls" => array("text/vnd.wap.wmlscript", "script", "Script WML"),
"wmlsc" => array("application/vnd.wap.wmlscriptc", "document", ""),
"wrl" => array("model/vrml", "document", ""),
"xbm" => array("image/x-xbitmap", "image", "Image Bitmap X"),
"xls" => array("application/msexcel", "document", "Fichier Excel"),
"xlsx" => array("application/msexcel", "document", "Fichier Excel"),
"xml" => array("text/xml", "script", "Fichier XML"),
"xpm" => array("image/x-xpixmap", "image", "Image Pixmap X"),
"xsl" => array("text/plain", "script", "Fichier XSL"),
"xwd" => array("image/x-xwindowdump", "image", "Image Windows Dump"),
"zip" => array("application/zip", "archive", "Archive Zip")
);

/**
	* Récupération de l'extension d'un fichier
	*
	* @return string
	* @param string $file Chemin physique du fichier
	*/
function getExtension($file) {
	$ext = pathinfo($file);
	return $ext["extension"];
}

/**
	* Récupération du chemin de l'icône associé à l'extension d'un fichier
	*
	* @return string
	* @param string $file Chemin physique du fichier
	*/
function getAssociatedIcon($file) {

	global $type_mime,  $iconPath;

	$path = $iconPath;
	$icon = getExtension($file);
	if (strlen($icon)) {
		$associatedIconPath = $path.$icon.".gif";
		if (!file_exists(Pelican::$config['DOCUMENT_ROOT'].$associatedIconPath)) {
			$associatedIconPath = $path.$type_mime["$icon"][1].".gif";
			if (!file_exists(Pelican::$config['DOCUMENT_ROOT'].$associatedIconPath)) {
				$associatedIconPath = $path."default.gif";
			}
		}
	} else {
		$associatedIconPath = $path."default.gif";
	}

	return $associatedIconPath;
}

/**
	* Récupération et fromattage de la taille d'un fichier
	*
	* @return string
	* @param string $file Chemin physique du fichier
	*/
function displaySize($file) {

	return formatSize(filesize($file));

}

/**
	* Formattage de la taille d'un fichier en Mo, Ko ou o.
	*
	* @return string
	* @param integer $fileSize Taille du fichier
	*/
function formatSize($fileSize) {

	if ($fileSize) {
		$mb = 1024 * 1024;
		if ($fileSize > $mb) {
			return sprintf("%01.2f", $fileSize/$mb)." M".t('FILE_BYTE');
		} elseif ($fileSize >= 1024) {
			return sprintf("%01.2f", $fileSize/1024)." K".t('FILE_BYTE');
		} else {
			return $fileSize." ".t('FILE_FULL_BYTE');
		}
	} else {
		return "";
	}

}

/**
	* Récupération des propriétés d'un fichier
	*
	* name, icon, extension, size, type_mime, type, changeddate, fullpath, httppath, urlpath, permissions
	*
	* @return mixed
	* @param string $basedir unknown
	* @param string $dir unknown
	* @param string $file unknown
	*/
function getFileProperties($basedir, $dir, $file) {

	global $type_mime, $_SERVER;
	$arFile["name"] = $file;
	$arFile["icon"] = getAssociatedIcon($file);
	$arFile["extension"] = getExtension($file);
	$arFile["size"] = displaySize($file);
	$arFile["type_mime"] = $type_mime[$arFile["extension"]][0];
	$arFile["type"] = $type_mime[$arFile["extension"]][2];
	$lastchanged = filectime($file);
	$arFile["changeddate"] = date(t('DATE_FORMAT_PHP')." H:i:s", $lastchanged);
	$arFile["fullpath"] = str_replace("//", "/", $basedir.$dir.$file);
	$arFile["httppath"] = "http://".str_replace(Pelican::$config['DOCUMENT_ROOT'], $_SERVER["HTTP_HOST"], $arFile["fullpath"]);
	$arFile["urlpath"] = rawurlencode(str_replace("//", "/", $dir.$file));
	$arFile["permissions"] = sprintf("%o", (fileperms($file)) & 0777);

	return $arFile;
}

/**
	* Récupération des couples extension <->description du type MIME
	*
	* @return mixed
	*/
function getTypeMime() {

	global $type_mime;

	$arFile["*"] = "* - ".t('TABLE_FILTER_ALL');
	foreach (array_keys($type_mime) as $ligne) {
		if ($type_mime[$ligne][2] != "") {
			$arFile[$ligne] = $ligne." - ".$type_mime[$ligne][2];
		}
	}
	return $arFile;
}

/**
	* Copie récursive d'un répertoire
	*
	* @return void
	* @param string $from_path Répertoire de départ
	* @param string $to_path Répertoire de destination
	*/
function copyDir($from_path, $to_path) {

	verifyDir($to_path, 0755);
	$this_path = getcwd();
	if (is_dir($from_path)) {
		chdir($from_path);
		$handle = opendir('.');
		while (($file = readdir($handle)) !== false) {
			if (($file != ".") && ($file != "..")) {
				if (is_dir($file)) {
					copyDir ($from_path.$file."/", $to_path.$file."/");
					chdir($from_path);
				}
				if (is_file($file)) {
					copy($from_path.$file, $to_path.$file);
				}
			}
		}
		closedir($handle);
	}

}

/**
	* Vérification de l'existence d'un répertoire et création s'il n'existe pas (avec les droits 755)
	*
	* @return void
	* @param string $dir_name Chemin physique du répertoire
	*/
function verifyDir($dir_name, $permission = 755) {

	$tmp = "";
	if (!is_dir($dir_name)) {
		if (isset($_SERVER['WINDIR'])) {
			mkdir($dir_name, $permission, true);
		} else {
			$cmd = "mkdir -p -m ".$permission." ".$dir_name;
			Pelican::runCommand($cmd);
		}
		/*
		$dir = explode("/", $dir_name);
		for ($i = 1; $i < count($dir); $i++) {
		if ($dir[$i] || $dir[$i] === "0") {
		$tmp .= "/".$dir[$i];
		if (!is_dir($tmp)) {
		$oldumask = umask(0);
		mkdir($tmp, $permission);
		umask($oldumask);
		}
		}
		}*/
	}
}

function controlType($file) {
	

	$pathinfo = pathinfo($file);
	$allow = getAllowedExtensions();
	foreach ($allow as $type=>$extensions) {
		if (valueExists($pathinfo,"extension")) {
			if (valueExists($extensions, strtolower($pathinfo["extension"]))) {
				return $type;
			}
		}
	}

	return false;
}
?>