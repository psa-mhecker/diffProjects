<?php
	/**
	* @package Cache
	* @subpackage General
	*/
	 
	/**
	* Fichier de Pelican_Cache : Résultat de requête sur state
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Backend_State extends Pelican_Cache {
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		public function getValue() {
			 
			
			$oConnection = Pelican_Db::getInstance();
			 
			$query = "SELECT
				STATE_ID as \"id\",
				'A' as \"pid\",
				STATE_LABEL as \"lib\",
				STATE_LABEL2 as \"lib2\",
				".$oConnection->getConcatClause(array("'javascript:state('", Pelican::$config["TPL_CONTENT"], "','", "STATE_ID", "','''", "STATE_LABEL", "''''", "')'"))." as \"url\",
				'".Pelican::$config["SKIN_PATH"]."/images/tree_workflow_detail.gif' as \"icon\",
				'".Pelican::$config["SKIN_PATH"]."/images/tree_workflow_detail.gif' as \"iconOpen\",
				STATE_REPORT_ORDER as \"order\"
				FROM
				#pref#_state ";
			if ($this->params) {
				$query .= " WHERE STATE_ID IN (".implode(",", $this->params).") ";
			}
                        
			$query .= "ORDER BY
				STATE_REPORT_ORDER";
                        $aState = $oConnection->queryTab($query);
                        
                        //traduction des etats
                        foreach ($aState as $key => $state) {
                            $aState[$key]["lib2"] = Pelican::$config["STATE_TRANSLATE"][$state["id"]];
                        }
                        
			$this->value = $aState;
		}
	}
?>