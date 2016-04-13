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
require_once('Pelican/Media.php');

class Citroen_Media extends Pelican_Media
{
    /**
     * Fonction de contrôle d'utilisation du média + récupération des contenus/rubriques
     *
     * @access public
     * @param  __TYPE__ $id Integer Identifiant du Pelican_Media
     * @return array
     */
    public static function checkMediaUsageDetail($id, $disabled = false)
    {

        // annulation temporaire
        if ($disabled) {
             return false;
        }

        if (Pelican::$config["FW_MEDIA_TABLE_NAME"] && Pelican::$config["FW_MEDIA_USAGE"] && !empty($id)) {
            $oConnection = Pelican_Db::getInstance();
            $aContent = array();
            $aRubrique = array();
            $aAdmin = array();

            //boucle sur les contenus
            if(is_array(Pelican::$config["FW_MEDIA_USAGE_CONTENT"])){

                foreach(Pelican::$config["FW_MEDIA_USAGE_CONTENT"] as $table) {
					
                    $content = array();
                    if(isset($table["principale"])){
                        $jointure = "";
                        $key = "";
                        //liste table de jointure
                        for($i=0; $i <= 100;$i++){
                            if($table[$i] == null){
                                break;
                            }else{
                                if($table[$i]["key"] != "" && $table[$i]["table"] != ""){
                                    $jointure .= ", " . $table[$i]["table"] . " ";
                                    $key .= $table["principale"]. "." . $table[$i]["key"]. " = " . $table[$i]["table"] . "." . $table[$i]["key"] . " and ";
                                }
                            }
                        }

                        //début de la requete
                        $query = "SELECT content_type_id, content_title,content_current_version as current_version,
                                    " . $table["principale"] . ".content_id,
                                    group_concat(DISTINCT  " . $table["principale"] . ".content_version SEPARATOR ', ') as versions, site_id ";
                        $query .= " from ".$table["principale"];
                        $query .= $jointure;
                        $query .= "where ";
                        $query .= $key;
                        $query .= "" . $table["principale"] . ".media_id = :ID ";
                        $query .= " group by " . $table["principale"] . ".content_id";
						
                        $content = $oConnection->queryTab($query, array(":ID" => $id));
                    }
                    //merge des résultats contenus
                    $aContent = array_merge($aContent, $content); 
                }
                     $query = "

                    SELECT #pref#_content.content_type_id, #pref#_content_version.content_title, #pref#_content_version.content_id, MAX( #pref#_content_version.content_version ) AS versions, #pref#_content.content_current_version as current_version ,site_id
                    FROM #pref#_content_version, #pref#_content
                    WHERE #pref#_content_version.content_id = #pref#_content.content_id
                    AND (
                    #pref#_content_version.MEDIA_ID8 =:ID
                    OR #pref#_content_version.MEDIA_ID7 = :ID
                    OR #pref#_content_version.MEDIA_ID6 = :ID
                    OR #pref#_content_version.MEDIA_ID5 = :ID
                    OR #pref#_content_version.MEDIA_ID4 = :ID
                    OR #pref#_content_version.MEDIA_ID3 = :ID
                    OR #pref#_content_version.MEDIA_ID2 = :ID
                    OR #pref#_content_version.MEDIA_ID = :ID
                    )
                    GROUP BY #pref#_content_version.content_id

                    ";
					
                    $content = $oConnection->queryTab($query, array(":ID" => $id));
                    $aContent = array_merge($aContent, $content);
            }

            //boucle sur les rubriques
            if(is_array(Pelican::$config["FW_MEDIA_USAGE_RUBRIQUE"])){
                foreach(Pelican::$config["FW_MEDIA_USAGE_RUBRIQUE"] as $table) {
                    $rubrique = array();
                    if(isset($table["principale"])){
                        $jointure = "";
                        $key = "";
                        //liste table de jointure
                        for($i=0; $i <= 100;$i++){
                            if($table[$i] == null){
                                break;
                            }else{
                                if($table[$i]["key"] != "" && $table[$i]["table"] != ""){
                                    $jointure .= ", " . $table[$i]["table"] . " ";
                                    $key .= $table["principale"]. "." . $table[$i]["key"]. " = " . $table[$i]["table"] . "." . $table[$i]["key"] . " and ";
                                }
                            }
                        }

                        $media = 'media_id';
                        if ($table['media']) {
                            $media = $table['media'];
                        }

                        //début de la requete
                        $query = "SELECT site_id, page_title,page_current_version as current_version ,
                                    " . $table["principale"] . ".page_id,
                                    group_concat(DISTINCT  " . $table["principale"] . ".page_version SEPARATOR ', ') as versions ";
                        $query .= " from ".$table["principale"];
                        $query .= $jointure;
                        $query .= " where ";
                        $query .= $key;
                        $query .= "" . $table["principale"] . ".".$media." = :ID ";
                        $query .= " group by " . $table["principale"] . ".page_id";
						
                        $rubrique = $oConnection->queryTab($query, array(":ID" => $id));
					
						
                    }
                    //merge des résultats rubrique
					
                    $aRubrique = array_merge($aRubrique, $rubrique);
                }
				
            }
			
			

            if(is_array(Pelican::$config["FW_MEDIA_USAGE_ADMIN"])){
                foreach(Pelican::$config["FW_MEDIA_USAGE_ADMIN"] as $table) {
                    $admin = array();
                    if(isset($table["table"])){

                        //début de la requete
                        $query = "SELECT ";
                        if (is_array($table['field']) && !empty($table['field'])) {
                            $aSelect = array("'" . $table["table"] . "' as tableName" );
                            foreach ($table['field'] as $alias => $field) {
                                $aSelect[] = $field . ' as ' . $alias;
                            }
                            $query .= implode($aSelect, ', ');
                        }
                        $query .= " from ".$table["table"];
                        $query .= " where ";
                        if (is_array($table['media']) && !empty($table['media'])) {
                            $where = array();
                            foreach ($table['media'] as $media) {
                                $where[] = $media . ' = :ID';
                            }
                            $query .= implode($where, ' OR ');
                        }

                        $admin = $oConnection->queryTab($query, array(":ID" => $id));
                    }
                    //merge des résultats rubrique
                    $aAdmin = array_merge($aAdmin, $admin);
                }
				
            }
			
			$aUsedMedia = array_merge($aRubrique, $aContent);
			
			/****solution tempôraire pour la suppresion du media***/
			if(is_array($aUsedMedia) && sizeof($aUsedMedia)>0){
				$bUseMedia = Citroen_Media::getMediaUseDetailVersionRubrique($aUsedMedia);
				if($bUseMedia === false && sizeof($aAdmin) == 0 ){
					return false;
				}
			}
			/**************/

            return array($aContent, $aRubrique, $aAdmin);
        } else {
            return false;
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
	public static function getAllowedExtensions()
	{
		return Pelican::$config['ALLOWED_EXTENSTION_MEDIA'];
	}

    /**
     * Retourne un chemin complet incluent l'id de format à partir d'un
     * path d'image et d'un id de format
     *
     * @access public
     * @param  string $path      Chemin d'origine du fichier
     * @param  string $format    id du format
     * @param  string $extension (option) String extension du type de fichier résultat
     *                           (par défaut vide => même type que l'original)
     * @return string
     */
    public static function getFileNameMediaFormat($path, $format, $extension = "")
    {
        $file = pathinfo($path);
        //Si version mobile prend le format mobile si il existe
        if(Pelican_Controller::isMobile()){
            $mediatFormatFlip = array_flip(Pelican::$config['MEDIA_FORMAT_ID']);
            if( strpos($mediatFormatFlip[$format], "WEB") !== false){
                $preFormat = str_replace("WEB", "MOBILE", $mediatFormatFlip[$format]);
                if(Pelican::$config['MEDIA_FORMAT_ID'][$preFormat] != ""){
                    $format = Pelican::$config['MEDIA_FORMAT_ID'][$preFormat];
                }
            }
        }

        if (empty($file["extension"])) {
            $file["extension"] = '';
        }
        if (empty($extension)) {
            $extension = $file["extension"];
        }
        
        if (isset($file['dirname']) && isset($format) && $file['dirname'].$file['filename'].$format) {
            $preFormat = $file['dirname'].$file['filename'].$format;
        }

        return str_replace("." . $file["extension"], "." . $format . "." . $extension, $path);
    }

    /**
    * Méthode permettant de construire chaine séparé par des pipes
    *
    * @param array $media_id Id du média
    * @return array $strImploded retourne une chaine de caractère
    */
    public static function childMediaImplodeToString($media_id, $path = "")
    {
        $strImploded = $path;
        if($media_id != ""){
            $aToExplode = Pelican_Cache::fetch("Frontend/MediaChild", array(
                                                    $media_id
                                                ));
        }
        if(is_array($aToExplode) && count($aToExplode) > 0){
            foreach($aToExplode["MEDIA_ENFANTS"] as $childMedia){
                if($strImploded != ""){
                    $strImploded .= "|" . Pelican::$config['MEDIA_HTTP'] . $childMedia["MEDIA_PATH"];
                }else{
                    $strImploded .= Pelican::$config['MEDIA_HTTP'] . $childMedia["MEDIA_PATH"];
                }
            }
        }

        return $strImploded;
    }

    /**
     * Nomenclature de fichier
     *
     * @access public
     * @param  __TYPE__ $fileName  String Nom du fichier
     * @param  __TYPE__ $ID        Integer Id du fichier (in out)
     * @param  string   $subfolder (option) __DESC__
     * @return string
     */
    public static function fileName($fileName, &$ID, $subfolder = "")
    {
		
		
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
                $oConnection->query("INSERT INTO ".Pelican::$config["FW_MEDIA_TABLE_NAME"]."( MEDIA_TYPE_ID) VALUES ('image')");
                $ID = $oConnection->queryItem('SELECT LAST_INSERT_ID()');
                $oConnection->query("DELETE FROM ".Pelican::$config["FW_MEDIA_TABLE_NAME"]." WHERE ".Pelican::$config["FW_MEDIA_FIELD_ID"]."=".$ID);
                $oConnection->commit();
            }
            $FULLID = str_pad($ID, 3, "0", STR_PAD_LEFT);
            $name = str_replace("." . $extension, "", Pelican_Media::cleanName($fileName)) . ".";
			$sNameFile = str_replace("." . $extension, "", Pelican_Media::cleanName($fileName));
			
             if( $extension == 'pdf'){
                $return = Pelican::$config["MEDIA_VAR"] . "/" . $type . "/" . substr($FULLID, -3, 2) . "/" . substr($FULLID, -1) . "/" . $name  . $extension;
			 }elseif(substr_count($sNameFile,$ID) >= 1){// 20150112 CPW-3585 bug plusieurs id dans l'url de l'image
				 
				 $sRandomString = Citroen_Media::RandomString();
				 if(!empty(Pelican_Db::$values["MEDIA_TITLE"])){
					 $sNewFilename = Pelican_Db::$values["MEDIA_TITLE"];
					 $return = Pelican::$config["MEDIA_VAR"] . "/" . $type . "/" . substr($FULLID, -3, 2) . "/" . substr($FULLID, -1) . "/" . $sNewFilename.".". $ID .".". $sRandomString. "." . $extension;
				 }
				//FIN  CPW-3585
		     } else{
				 $return = Pelican::$config["MEDIA_VAR"] . "/" . $type . "/" . substr($FULLID, -3, 2) . "/" . substr($FULLID, -1) . "/" . $name . $ID . "." . $extension;
			 }
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
     * Retourne le champ MEDIA_ALT du fichier (à partir de l'id si la gestion
     * se fait en base de données)
     *
     * @access public
     * @param string $value (option) __DESC__
     * @return string
     */
    public static function getMediaAlt($value = "") {
        if (!$value && valueExists($_REQUEST, "id")) {
            $value = $_REQUEST["id"];
        }
        if (Pelican::$config["FW_MEDIA_TABLE_NAME"]) {
            $file = Pelican_Cache::fetch("Media/Detail", $value);
            return $file['MEDIA_ALT'];
        } else {
            return Pelican::$config["MEDIA_ALT"];
        }
    }
	public static function RandomString()
	{
		$characters = '123456789';
		$randstring = '';
		for ($i = 1; $i < 10; $i++) {
			$randstring = $characters[rand(1, strlen($characters))];
		}
		return $randstring;
	}
	
	//fonction temporaire todo: a supprimer et  genraliser  dans controllers media
	public static function getMediaUseDetailVersionRubrique($aData =null)
	{
		$bUseMedia = false;
		
		if(is_array($aData) && sizeOf($aData)>0){
			foreach($aData as $iKey=>$aValue){
						if(strpos($aValue["versions"],$aValue["current_version"]) || $aValue["versions"] == $aValue["current_version"]){
							$bUseMedia = true;
							break;
						}
			}
		}
		
		return $bUseMedia;
	}
	
	
	function clearFileName($sString){
		
		$sNameFileFromBo   = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $sString);	
		$sTitleFromBo      = str_replace(array(' ','?'), array('-',''), $sNameFileFromBo);
		$sTitle            = preg_replace ('#[^.0-9a-z]\-+#i', '', $sTitleFromBo);
		
		return $sTitle;
		
	}

	

}
