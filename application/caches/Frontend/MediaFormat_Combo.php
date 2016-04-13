<?php
	/**
	* @package Cache
	* @subpackage Media
	*/

	/**
	* Fichier de Pelican_Cache : Combo sur media_format
	*
	* retour : id, lib, appel de changeMediaFormat() sur onchange
	*
	* @package Cache
	* @subpackage Media
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Frontend_MediaFormat_Combo extends Pelican_Cache {

		
		var $duration = UNLIMITED;

		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			$query = "
				SELECT ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_ID"]." as \"id\", ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_LABEL"]." as \"lib\",
				".Pelican::$config["FW_MEDIA_FORMAT_FIELD_WIDTH"].", ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_HEIGHT"]."
				FROM ".Pelican::$config["FW_MEDIA_FORMAT_TABLE_NAME"]."
				WHERE ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_LABEL"]." IS NOT NULL
				AND MEDIA_FORMAT_COLORS=0
				ORDER BY  ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_LABEL"];
				//ORDER BY ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_WIDTH"].", ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_HEIGHT"];
			$result = $oConnection->queryTab($query);

			$aJs[] = "var aFormatSize=new Array();";
			$return = "<select id=\"cboMediaFormat\" onchange=\"changeMediaFormat()\">";
			$return .= "<option value=\"\">- ".t('Image originale')." -</option>";
			foreach($result as $mf) {
				$return .= "<option value=\"".$mf["id"]."\">".$mf["lib"]."</option>";
				$aJs[] = "aFormatSize[".$mf["id"]."] = '".$mf[Pelican::$config["FW_MEDIA_FORMAT_FIELD_WIDTH"]].".".$mf[Pelican::$config["FW_MEDIA_FORMAT_FIELD_HEIGHT"]]."';";
			}
			$return .= "</select>";
			$this->value = $return.Pelican_Html::script(implode("\n",$aJs));
		}
	}

?>