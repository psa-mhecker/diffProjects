<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Media
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Media
 * @author __AUTHOR__
 */
class Pelican_Media {
    
    /**
     * Retourne le chemin d'accès au fichier (à partir de l'id si la gestion
     * se fait en base de données)
     *
     * @access public
     * @param string $value (option) __DESC__
     * @return string
     */
    public static function getMediaPath($value = "") {
        if (!$value) {
            $value = Pelican_Security::execSafeCommandArg($_REQUEST["id"]);
        }
        if ($value) {
            if (Pelican::$config["FW_MEDIA_TABLE_NAME"]) {
                $media = Pelican_Cache::fetch("Media/Detail", $value);
                $file = $media[Pelican::$config["FW_MEDIA_FIELD_PATH"]];
                /*
                * $file = $oConnection->queryItem("select " .
                * Pelican::$config["FW_MEDIA_FIELD_PATH"] . " from " .
                * Pelican::$config["FW_MEDIA_TABLE_NAME"] . " where " .
                * Pelican::$config["FW_MEDIA_FIELD_ID"] . "=:ID", array( ":ID"
                * => $value ));
                */
                return $file;
            } else {
                return str_replace(Pelican::$config["MEDIA_ROOT"], "", $_REQUEST["id"]);
            }
        }
    }
    
    /**
     * Retourne le chemin d'accès au fichier (à partir de l'id si la gestion
     * se fait en base de données)
     *
     * @access public
     * @param string $value (option) __DESC__
     * @return string
     */
    public static function getMediaInfo($value = "") {
        if (!$value && valueExists($_REQUEST, "id")) {
            $value = $_REQUEST["id"];
        }
        if (Pelican::$config["FW_MEDIA_TABLE_NAME"]) {
            $file = Pelican_Cache::fetch("Media/Detail", $value);
            return $file;
        } else {
            return str_replace(Pelican::$config["MEDIA_ROOT"], "", $_REQUEST["id"]);
        }
    }
    
    /**
     * PROPRIETES
     */
    
    /**
     * Récupération de la taille d'un flash : utilisation de getimagesize pour
     * flash 5- et décompression LZW puis extraction de la ptaille pour un
     *
     * Flash 6+
     *
     * @access public
     * @param __TYPE__ $filename String Chemin d'accès au fichier
     * @return mixed
     */
    public static function getFlashSize($filename) {
        $image_info = @getimagesize($filename);
        // Pelican::$config["FW_MEDIA_FLASH_TMP_ROOT"]="/tmp";
        if (!$image_info) {
            $zd = gzopen($filename, "r");
            $contents = gzread($zd, filesize($filename));
            gzclose($zd);
            $image_string = Pelican_Media::swfDecompress($contents); // Decompress
                                                                     // the file
            $tmpfname = tempnam(Pelican_Media::cleanDirectory(Pelican::$config["FW_MEDIA_FLASH_TMP_ROOT"]), "SWF");
            $tempHandle = fopen($tmpfname, "w");
            fwrite($tempHandle, $image_string);
            fclose($tempHandle);
            $image_info = getimagesize($tmpfname);
            unlink($tmpfname);
        }
        return $image_info;
    }
    
    /**
     * Décompression d'un falsh version 6+
     *
     * @access public
     * @param __TYPE__ $buffer String Buffer
     * @return string
     */
    public static function swfDecompress($buffer) {
        if (function_exists('gzuncompress') && substr($buffer, 0, 3) == "CWS" && ord(substr($buffer, 3, 1)) >= 6) {
            $output = 'F';
            $output.= substr($buffer, 1, 7);
            $output.= gzuncompress(substr($buffer, 8));
            return ($output);
        } else {
            return ($buffer);
        }
    }
    
    /**
     * MISE EN FORME
     */
    
    /**
     * Mise en forme de la recherche sur le nom de fichier
     *
     * @access public
     * @param __TYPE__ $searchFile String Nom ou chemin du fichier
     * @param bool $returnId (option) Boolean Retourne l'id du fichier
     * @return string
     */
    public static function getSearchFile($searchFile, $returnId = false) {
        $pathinfo = pathinfo(str_replace(Pelican::$config["MEDIA_HTTP"], "", $searchFile));
        
        /**
         * S'il n'y a pas d'extension, ce n'est pas un fichier
         */
        if (!$pathinfo["extension"]) {
            $return = $searchFile;
        } else {
            $return = str_replace("'", "''", $searchFile);
            $folder = $pathinfo["dirname"];
            
            /**
             * Nom sans l'extension
             */
            $file = str_replace("." . $pathinfo["extension"], "", $pathinfo["basename"]);
            $aFile = explode(".", $file);
            
            /**
             * on retire tout format inclu dans le nom
             */
            $file = $aFile[0];
            if (!$returnId) {
                $return = $folder . "/" . $aFile[0] . "." . $pathinfo["extension"];
            } else {
                $return = $aFile[0];
            }
        }
        return $return;
    }
    
    /**
     * Nomenclature de fichier
     *
     * @access public
     * @param __TYPE__ $fileName String Nom du fichier
     * @param __TYPE__ $ID Integer Id du fichier (in out)
     * @param string $subfolder (option) __DESC__
     * @return string
     */
    public static function fileName($fileName, &$ID, $subfolder = "") {
        
        /**
         * type de fichier
         */
        
        /**
         * si c'est "fichier" alors le type est "autre"
         */
        if (!$subfolder) {
            $subfolder = $_REQUEST["view"];
        }
        $type = $subfolder;
        
        /**
         * on garde l'extension
         */
        $pathinfo = pathinfo($fileName);
        $pathinfo["extension"] = strtolower($pathinfo["extension"]);
        $extension = $pathinfo["extension"];
        
        /**
         * si la taille existe en base, on la récupère
         */
        if (Pelican::$config["FW_MEDIA_TABLE_NAME"]) {
            if ($ID == Pelican::$config["DATABASE_INSERT_ID"]) {
                $oConnection = Pelican_Db::getInstance();
                $ID = $oConnection->getNextId(Pelican::$config["FW_MEDIA_TABLE_NAME"], Pelican::$config["FW_MEDIA_FIELD_ID"]);
            }
            $FULLID = str_pad($ID, 3, "0", STR_PAD_LEFT);
            $name = str_replace("." . $extension, "", Pelican_Media::cleanName($fileName)) . ".";
            $return = Pelican::$config["MEDIA_VAR"] . "/" . $type . "/" . substr($FULLID, -3, 2) . "/" . substr($FULLID, -1) . "/" . $name . $ID . "." . $extension;
            // $return =
            // Pelican::$config["MEDIA_VAR"]."/".$type."/".substr($FULLID, -3,
            // 2)."/".substr($FULLID, -1)."/".$ID.".".$extension;
            
        } else {
            $return = Pelican::$config["MEDIA_VAR"] . "/" . $fileName;
        }
        $return = Pelican_Media::cleanDirectory($return);
        
        /**
         * Création du répertoire
         */
        verifyDir(getUploadRoot(dirname($return)));
        return $return;
    }
    
    /**
     * Retourne un chemin complet incluent l'id de format à partir d'un
     * path d'image et d'un id de format
     *
     * @access public
     * @param string $path Chemin d'origine du fichier
     * @param string $format id du format
     * @param string $extension (option) String extension du type de fichier résultat
     * (par défaut vide => même type que l'original)
     * @return string
     */
    public static function getFileNameMediaFormat($path, $format, $extension = "") {
        $file = pathinfo($path);
        if (empty($file["extension"])) {
            $file["extension"] = '';
        }
        if (empty($extension)) {
            $extension = $file["extension"];
        }
        return str_replace("." . $file["extension"], "." . $format . "." . $extension, $path);
    }
    
    /**
     * Remplacement des "//" par "/" dans les chaines
     *
     * @access public
     * @param __TYPE__ $dir String Chemin d'accès
     * @return string
     */
    public static function cleanDirectory($dir) {
        return str_replace("//", "/", $dir);
    }
    
    /**
     * Fonction de contrôle d'existence du média (sur la taille et le md5)
     *
     * @access public
     * @param string $md5 (option) Integer md5 du fichier
     * @param string $size (option) Integer taille du fichier
     * @return string
     */
    public static function fileDbExists($md5 = "", $size = "") {
        $return = false;
        if (Pelican::$config["FW_MEDIA_TABLE_NAME"]) {
            if (Pelican::$config["FW_MEDIA_FIELD_WEIGHT"] && $size) {
                $where[] = Pelican::$config["FW_MEDIA_FIELD_WEIGHT"] . " = " . $size;
            }
            if (Pelican::$config["FW_MEDIA_FIELD_MD5"] && $md5) {
                $where[] = Pelican::$config["FW_MEDIA_FIELD_MD5"] . " = '" . $md5 . "'";
            }
            if ($where) {
                $strSQL = "select " . Pelican::$config["FW_MEDIA_FIELD_PATH"] . " as \"file\", " . Pelican::$config["FW_MEDIA_FIELD_ID"] . " as \"id\", " . Pelican::$config["FW_MEDIA_FIELD_FOLDER_PATH"] . " as \"path\" from " . Pelican::$config["FW_MEDIA_TABLE_NAME"] . ", " . Pelican::$config["FW_MEDIA_FOLDER_TABLE_NAME"] . " where " . Pelican::$config["FW_MEDIA_TABLE_NAME"] . "." . Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . Pelican::$config["FW_MEDIA_FOLDER_TABLE_NAME"] . "." . Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"] . " AND " . implode(" AND ", $where);
                $oConnection = Pelican_Db::getInstance();
                $return = $oConnection->queryRow($strSQL);
            }
        }
        return $return;
    }
    
    /**
     * Fonction de contrôle d'utilisation du média
     *
     * @access public
     * @param __TYPE__ $id Integer Identifiant du Pelican_Media
     * @return bool
     */
    public static function checkMediaUsage($id) {
        if (Pelican::$config["FW_MEDIA_TABLE_NAME"] && Pelican::$config["FW_MEDIA_USAGE"] && !empty($id)) {
            $oConnection = Pelican_Db::getInstance();
            foreach(Pelican::$config["FW_MEDIA_USAGE"] as $table) {
                if ($table == "view_content_version") {
                    $count = $oConnection->queryItem("SELECT count(1) FROM #pref#_content_version WHERE MEDIA_ID=:ID", array(":ID" => $id));
                } else {
                    $count = $oConnection->queryItem("SELECT count(1) FROM " . $table . " WHERE " . Pelican::$config["FW_MEDIA_FIELD_ID"] . "=:ID", array(":ID" => $id));
                }
                if ($count) {
                    $usage[$table] = $count;
                }
            }
            return $usage;
        } else {
            return false;
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $texte __DESC__
     * @param string $size (option) __DESC__
     * @return __TYPE__
     */
    public static function reduceText($texte, $size = "") {
        $return = $texte;
        if ($size) {
            $return = substr($texte, 0, $size) . "...";
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $path __DESC__
     * @return __TYPE__
     */
    public static function getMediaId($path) {
        return Pelican_Media::getSearchFile($path, true);
    }
    
    /**
     * Supprime tous les formats d'un fichier, ses sous-répertoire et le
     * fichier
     *
     * @access public
     * @param __TYPE__ $file Unknown_type
     * @return __TYPE__
     */
    public static function deleteFullFile($file) {
        if ($file) {
			$file = Pelican_Security::execSafeCommandArg($file);
            $info = pathinfo($file);
            $cmd = "rm -rf " . str_replace($info["extension"], "", $file) . "*";
            @passthru($cmd);
            $cmd = "rm -rf " . str_replace("." . $info["extension"], "/", $file) . "*";
            @passthru($cmd);
            @rmdir(str_replace("." . $info["extension"], "", $file));
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @param __TYPE__ $media_id __DESC__
     * @param __TYPE__ $size (option) __DESC__
     * @param bool $front (option) __DESC__
     * @param bool $autoplay (option) __DESC__
     * @param bool $subtitle (option) __DESC__
     * @return __TYPE__
     */
    function getFlashPlayer($id, $media_id, $size = "1", $front = false, $autoplay = false, $subtitle = false, $vignetteVideo = '') {
        $swf 		= self::getMediaInfo($media_id);
		
		$fileThumb 	= $vignetteVideo;
        if ($swf) {
            $swf["MEDIA_PATH"] = str_replace("video/", "videos/", $swf["MEDIA_PATH"]);
            $comment = $swf["MEDIA_COMMENT"];
            $info = pathinfo($swf["MEDIA_PATH"]);
            switch ($info["extension"]) {
                case "zip": {
                            $file = "/flashplayer/player_externe.swf";
                            $params["file"] = str_replace("." . $info["extension"], "", str_replace("/videos", "", $swf["MEDIA_PATH"]));
                            $params["debit"] = 1;
                            $params["vidPlay"] = 'true';
                        break;
                    }
                case "xml": {
                        $file = "/flashplayer/player_interne.swf";
                        $params["ref_xml"] = str_replace("." . $info["extension"], "", $swf["MEDIA_PATH"]);
                        break;
                    }
                case "flv":
                case "mov":
                case "rm":
                case "mp3":
                case "mp4":
                case "webm":
                case "wmv": {
                        $file = $swf["MEDIA_PATH"];
						if(!isset($fileThumb) || empty($fileThumb)){
							$fileThumb = $swf['MEDIA_WTV_THUMBNAIL_PATH'];
						}
                        if (preg_match('/^http:\/\//', $file) || preg_match('/^ftp:\/\//', $file) || preg_match('/^mms:\/\//', $file)) {
                            // Le m�dia est h�berg� sur une source externe
                            $file = $swf["MEDIA_PATH"];
                        } else {
                            $file = str_replace("videos/", "video/", $swf["MEDIA_PATH"]);
                            $file = Pelican::$config['MEDIA_HTTP'] . $file;
                        }
                        break;
                    }
                }
				if(is_array($size)){
					$width 	= $size['WIDTH'];
					$height	= $size['HEIGHT'];
				}else{					
					switch ($size) {
						case 1: {
									if ($_SESSION[Pelican::$config["APP"]]['SITE_ID'] == Pelican::$config["SITE"]["EDUS"]) {
										$width = 165;
										$height = 245;
										$width_wmv = 165;
										$height_wmv = 187;
										$width_rm = 165;
										$height_rm = 143;
										$width_flv = 165;
										$height_flv = 153;
									} elseif ($_SESSION["IS_WEB_ACAD"]) {
										$width = 182;
										$height = 252;
										$width_wmv = 182;
										$height_wmv = 195;
										$width_rm = 182;
										$height_rm = 150;
										$width_flv = 182;
										$height_flv = 160;
									} else {
										$width = 200;
										$height = 252;
										$width_wmv = 200;
										$height_wmv = 195;
										$width_rm = 200;
										$height_rm = 150;
										$width_flv = 200;
										$height_flv = 160;
									}
								break;
							}
						case 2: {
								if ($_SESSION["IS_WEB_ACAD"]) {
									$width = 498;
									$height = 637;
									$width_wmv = 498;
									$height_wmv = 439;
									$width_rm = 498;
									$height_rm = 373;
									$width_flv = 498;
									$height_flv = 426;
									break;
								} else {
									$width = 340;
									$height = 435;
									$width_wmv = 340;
									$height_wmv = 300;
									$width_rm = 340;
									$height_rm = 255;
									$width_flv = 340;
									$height_flv = 265;
									break;
								}
							}
						case 3: {
								$width = 420;
								$height = 537;
								$width_wmv = 420;
								$height_wmv = 370;
								$width_rm = 420;
								$height_rm = 315;
								$width_flv = 420;
								$height_flv = 327;
								break;
							}
						}
					}
                    switch ($info["extension"]) {
                        case "mov":
                            $param = Pelican_Html::param(array('name' => 'src', 'value' => $file));
                            $param.= Pelican_Html::param(array('name' => 'controller', 'value' => 'true'));
                            $param.= Pelican_Html::param(array('name' => 'scale', 'value' => 'aspect'));
                            //$param .= empty($autoplay) ? Pelican_Html::param(array('name' => 'autoStart', 'value' => '0')) : Pelican_Html::param(array('name' => 'autoStart', 'value' => '1')) ;
                            $param.= empty($autoplay) ? Pelican_Html::param(array('name' => 'autoplay', 'value' => 'false')) : Pelican_Html::param(array('name' => 'autoplay', 'value' => 'true'));
                            $auto = empty($autoplay) ? array('autoplay', 'false') : array('autoplay', 'true');
                            $param.= $subtitle ? Pelican_Html::param(array('name' => 'captions', 'value' => '')) : Pelican_Html::param(array('name' => 'captions', 'value' => $subtitle));
                            $subtitle_param = $subtitle ? array('captions', '') : array('captions', $subtitle);
                            //$param .= Pelican_Html::embed(array('TYPE' => 'video/quicktime', 'SRC' => $file, 'width' => $width, 'height' => $height, $auto[0] => $auto[1]));
                            $param2 = Pelican_Html::param(array('name' => 'scale', 'value' => 'aspect'));
                            $param2.= Pelican_Html::param(array('name' => 'controller', 'value' => 'true'));
                            $param2.= empty($autoplay) ? Pelican_Html::param(array('name' => 'autoplay', 'value' => 'false')) : Pelican_Html::param(array('name' => 'autoplay', 'value' => 'true'));
                            $param2.= $subtitle ? Pelican_Html::param(array('name' => 'captions', 'value' => '')) : Pelican_Html::param(array('name' => 'captions', 'value' => $subtitle));
                            $param2 = str_replace('></param>', '/>', $param2);
                            $param.= Pelican_Html::object(array('type' => 'video/quicktime', 'width' => $width, 'height' => $height, 'data' => $file), $param2);
                            $param = str_replace('></param>', '/>', $param);
                            switch ($front) {
                                case false:
                                    $return = Pelican_Html::object(array('CLASSID' => 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B', 'width' => $width, 'height' => $height, 'CODEBASE' => 'http://www.apple.com/qtactivex/qtplugin.cab'), $param);
                                break;
                                case true:
                                    $return = Pelican_Html::object(array('CLASSID' => 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B', 'width' => $width, 'height' => $height, 'CODEBASE' => 'http://www.apple.com/qtactivex/qtplugin.cab', "class" => 'bloc_video', 'id' => 'bloc_video_' . $id), $param);
                                break;
                                default:
                                    $return = Pelican_Html::object(array('CLASSID' => 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B', 'width' => $width, 'height' => $height, 'CODEBASE' => 'http://www.apple.com/qtactivex/qtplugin.cab'), $param);
                                break;
                            }
                        break;
                        case "rm":
                            $param = Pelican_Html::param(array('name' => 'console', 'value' => 'video'));
                            $param = Pelican_Html::param(array('name' => 'controls', 'value' => 'ImageWindow'));
                            $param.= empty($autoplay) ? Pelican_Html::param(array('name' => 'autoStart', 'value' => '0')) : Pelican_Html::param(array('name' => 'autoStart', 'value' => '1'));
                            $param.= $subtitle ? Pelican_Html::param(array('name' => 'captions', 'value' => '')) : Pelican_Html::param(array('name' => 'captions', 'value' => $subtitle));
                            $param.= Pelican_Html::param(array('name' => 'src', 'value' => $file));
                            $auto = empty($autoplay) ? array('autoStart', 'false') : array('autoStart', 'true');
                            $subtitle_param = $subtitle ? array('captions', '') : array('captions', $subtitle);
                            $param.= Pelican_Html::embed(array('type' => 'audio/x-pn-realaudio-plugin', 'src' => $file, 'width' => $width, 'height' => $height, 'controls' => 'ImageWindow', 'console' => 'video', $auto[0] => $auto[1], $subtitle_param[0] => $subtitle_param[1]));
                            switch ($front) {
                                case false:
                                    $return = Pelican_Html::object(array('width' => $width, 'height' => $height, 'classid' => 'clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA'), $param);
                                break;
                                case true:
                                    $return = Pelican_Html::object(array('width' => $width, 'height' => $height, 'classid' => 'clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA', "class" => 'bloc_video', 'id' => 'bloc_video_' . $id), $param);
                                break;
                                default:
                                    $return = Pelican_Html::object(array('width' => $width, 'height' => $height, 'classid' => 'clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA'), $param);
                                break;
                            }
                        break;
                        case "wmv":
                            $param = Pelican_Html::param(array('name' => 'FileName', 'value' => $file));
                            $param.= empty($autoplay) ? Pelican_Html::param(array('name' => 'autoStart', 'value' => '0')) : Pelican_Html::param(array('name' => 'autoStart', 'value' => '1'));
                            $param.= Pelican_Html::param(array('name' => 'width', 'value' => $width));
                            $auto = empty($autoplay) ? array('autoStart', 'false') : array('autoStart', 'true');
                            $subtitle_param = $subtitle ? array('captions', '') : array('captions', $subtitle);
                            $param.= Pelican_Html::embed(array('class' => 'video', 'type' => 'application/x-mplayer2', 'pluginspage' => 'http://www.microsoft.com/Windows/MediaPlayer/', 'src' => $file, 'name' => 'video' . $swf["MEDIA_ID"], 'width' => $width, 'height' => $height, $auto[0] => $auto[1], $subtitle_param[0] => $subtitle_param[1]));
                            switch ($front) {
                                case false:
                                    $return = Pelican_Html::object(array('id' => 'video' . $swf["MEDIA_ID"], 'width' => $width, 'height' => $height, 'classid' => 'CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95', 'codebase' => 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715', 'standby' => 'Chargement...', 'type' => 'application/x-oleobject'), $param);
                                break;
                                case true:
                                    $return = Pelican_Html::object(array('id' => 'bloc_video_' . $id, 'width' => $width, 'height' => $height, 'classid' => 'CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95', 'codebase' => 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715', 'standby' => 'Chargement...', 'type' => 'application/x-oleobject', 'class' => 'bloc_video'), $param);
                                break;
                                default:
                                    $return = Pelican_Html::object(array('id' => 'video' . $swf["MEDIA_ID"], 'width' => $width, 'height' => $height, 'classid' => 'CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95', 'codebase' => 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715', 'standby' => 'Chargement...', 'type' => 'application/x-oleobject'), $param);
                                break;
                            }
                        break;
                        case "flv":					
                            $autoplay = empty($autoplay) ? 'false' : 'true';
                            $subtitle_param = $subtitle ? '&captions=' . $subtitle : '';
                            switch ($front) {
                                case false:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",bgcolor:"#FFFFFF",allowfullscreen:"true", wmode:"transparent",
                            flashvars:"file=' . $file . '&image=' . $fileThumb . '&autostart=' . $autoplay . $subtitle_param . '"};
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "player' . $swf["MEDIA_ID"] . '_' . $size . '");';
                                    $return = Pelican_Html::div(array(id => "player" . $swf["MEDIA_ID"] . "_" . $size, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;"));
                                break;
                                case true:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",bgcolor:"#FFFFFF",allowfullscreen:"true", wmode:"transparent",
                            flashvars:"file=' . $file . '&image=' . $fileThumb . '&autostart=' . $autoplay . $subtitle_param . '"};
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "bloc_video_' . $id . '");';
                                    $return = Pelican_Html::div(array(id => 'bloc_video_' . $id, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;", "class" => 'bloc_video'));
                                break;
                                default:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",bgcolor:"#FFFFFF",allowfullscreen:"true", wmode:"transparent",
                            flashvars:"file=' . $file . '&image=' . $fileThumb . '&autostart=' . $autoplay . $subtitle_param . '"};
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "player' . $swf["MEDIA_ID"] . '_' . $size . '");';
                                    $return = Pelican_Html::div(array(id => "player" . $swf["MEDIA_ID"] . "_" . $size, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;"));
                                break;
                            }
                            $return.= Pelican_Html::script(array(type => "text/javascript"), $script);
                        break;
                        case "mp3":
                            $width = $width_rm;
                            $height = 20;
                            $autoplay = empty($autoplay) ? 'false' : 'true';
                            switch ($front) {
                                case false:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",
                            flashvars:"file=' . $file . '&autostart=' . $autoplay . '" };
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "player' . $swf["MEDIA_ID"] . '_' . $size . '");';
                                    $return = Pelican_Html::div(array(id => "player" . $swf["MEDIA_ID"] . "_" . $size, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;"));
                                break;
                                case true:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",
                            flashvars:"file=' . $file . '&autostart=' . $autoplay . '" };
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "bloc_video_' . $id . '");';
                                    $return = Pelican_Html::div(array(id => 'bloc_video_' . $id, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;", "class" => 'bloc_video'));
                                break;
                                default:
                                    $script = 'var FO' . $swf["MEDIA_ID"] . ' = { movie:"' . Pelican::$config['MEDIA_HTTP'] . '/flashplayer/flvplayer.swf",width:"' . $width . '",height:"' . $height . '",majorversion:"7",build:"0",
                            flashvars:"file=' . $file . '&autostart=' . $autoplay . '" };
                            UFO.create( FO' . $swf["MEDIA_ID"] . ', "player' . $swf["MEDIA_ID"] . '_' . $size . '");';
                                    $return = Pelican_Html::div(array(id => "player" . $swf["MEDIA_ID"] . "_" . $size, style => "height:" . $height . "px;width:" . $width . "px;text-align:center;"));
                                break;
                            }
                            $return.= Pelican_Html::script(array(type => "text/javascript"), $script);
                        break;
                        case "mp4":
                        case "webm":
                            if ($info["extension"] == 'webm') $type="video/webm";
                            elseif ($info["extension"] == 'mp4') $type="video/mp4";
                            
                            $return = Pelican_Html::_tag2('source', array(src => $file, type=>$type));
                            $return = Pelican_Html::_tag1('video', array(array(controls => 'controls', width=>$width, height => $height), $return));
                            $return .= Pelican_Html::br();
                        break;
                        default:
                            switch ($front) {
                                case false:
                                    $return = Pelican_Html::swfObject($id, $file, $width, $height, $params, $comment);
                                break;
                                case true:
                                    $return = Pelican_Html::swfObject('bloc_video_' . $id, $file, $width, $height, $params, $comment);
                                break;
                                default:
                                    $return = Pelican_Html::swfObject($id, $file, $width, $height, $params, $comment);
                                break;
                            }
                        break;
                    }
                    return $return;
                }
            }
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $name __DESC__
             * @return __TYPE__
             */
            public static function cleanName($name) {
                if ($name) {
                    $replace = array("\\'" => "_", "'" => "_", "/" => "_", "\\" => "_", "\"" => "_", "+" => "_", ".." => ".", "*" => "_", "\$" => "", "%" => "");
                    $return = strtr($name, $replace);
                    $return = str_replace("__", "_", $return);
                    $return = str_replace("__", "_", $return);
                    $return = strtolower(Pelican_Text::dropAccent($return));
                    return $return;
                } else {
                    return "";
                }
            }
            
            /**
             * Diminution du tag d'affichage d'une image pour une dimension maximale
             *
             * @access public
             * @param __TYPE__ $size Mixed Tableau contenant les tailles d'affichage de
             * l'image (issue de getimagesize())
             * @param __TYPE__ $max (option) Integer Taille maximale : "0" par défaut
             * @return void
             */
            public static function reduceSize(&$size, $max = "0") {
                $origine = $size;
                if ($max) {
                    if (!isset($origine[0]) || $origine[0] > (int)$max) {
                        $size[0] = $max;
                        $size[1] = "";
                        $size[3] = "width=\"" . $max . "\"";
                    }
                    if ($origine[1] > (int)$max && $origine[1] > $origine[0]) {
                        $size[0] = "";
                        $size[1] = $max;
                        $size[3] = "height=\"" . $max . "\"";
                    }
                }
            }
            
            /**
             * Types de Pelican_Media autorisés (filtrage par extension de fichier) : pour
             * tout autoriser utiliser "all"
             *
             * @static
             * @access public
             * @return mixed
             */
            public static function getAllowedExtensions() {
                /*$return = array("image" => 
                    array("libelle" => t('IMAGES'), 
                        "png" => "Image PNG", 
                        "gif" => "Image GIF", 
                        "jpg" => "Image JPEG", 
                        "jpeg" => "Image JPEG", 
                        "bmp" => "Image Bitmap"), 
                    "file" => array("libelle" => t('FICHIERS'), 
                        "txt" => "Fichier Texte", 
                        "ai" => "Adobe Illustrator", 
                        "eps" => "Postcript", 
                        "pdf" => "Fichier Adobe Acrobat", 
                        "rtf" => "Fichier RTF", 
                        "doc" => "Fichier Microsoft Word", 
                        "docx" => "Fichier Microsoft Word 2007", 
                        "xls" => "Fichier Microsoft Excel", 
                        "xlsx" => "Fichier Microsoft Excel 2007", 
                        "zip" => "Fichier ZIP", 
                        "ppt" => "Fichier Microsoft PowerPoint", 
                        "pptx" => "Fichier Microsoft PowerPoint 2007"), 
                    "flash" => array("libelle" => t('FLASH'), 
                        "swf" => "Animation Flash"), 
                    "video" => array("libelle" => t('VIDEOS'), 
                        "zip" => "Vidéo Flash externe", 
                        "flv" => "Vidéo Flash", 
                        //"xml" => "Vidéo Flash interne", 
                        "mp4" => "MP4", 
                        "f4v" => "Vidéo F4v"), 
                    "youtube" => array("libelle" => "YouTube", 
                        "flv" => "Vidéo Flash")
                    );
                
                return $return;*/
                return Pelican::$config['ALLOWED_EXTENSTION_MEDIA'];
            }
            
            /**
             * public static function getThumbnailPath ($path)
             * {
             *
             * if (Pelican::$config["FW_MEDIA_USE_THUMBNAIL"] &&
             * Pelican::$config["THUMBNAIL_PATH"]) {
             * return str_replace(".", "_",
             * str_replace(Pelican::$config["THUMBNAIL_ORIGINAL_PATH"],
             * Pelican::$config["THUMBNAIL_PATH"], $path)) . "." .
             * Pelican::$config["IM_EXT"];
             * } else {
             * return $path;
             * }
             * }
             */
        }
?>
