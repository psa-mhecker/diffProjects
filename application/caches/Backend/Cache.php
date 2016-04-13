<?php
	/**
	* @package Pelican_Cache
	* @subpackage General
	*/
	 
	/**
	* Fichier de Pelican_Cache : Liste des modules de Pelican_Cache disponibles
	*
	* retour : *
	*
	* @package Pelican_Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 04/02/2005
	*/
	class Backend_Cache extends Pelican_Cache {
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		public function getValue() {
			$this->value = $this->_getCacheHierarchy($this->params[0]);
		}
		 
		protected function _getCacheHierarchy($parentPath) {
			 
			$read = "";
			$cmd = "find ".$parentPath."/ -type f -name \"*.php\" -ls | awk '{print $11\"#\";}'";
			$handle = popen($cmd, 'r');
			while (!feof($handle)) {
				$read .= fread($handle, 2096);
			}
			pclose($handle);
			$list = explode("#", $read);
			if ($list) {
				ksort($list);
				foreach($list as $fileDir) {
					$fileDir = trim(str_replace($parentPath."/", "", $fileDir));
					if ($fileDir) {
						$detail = pathinfo($parentPath.$fileDir);
					$aList[$fileDir] = array("id" => (count($aList)+1), "lib" => $fileDir, "url" => "javascript:menu(34,'".$fileDir."');", "pid" => "X1", "order" => $fileDir);
					}
				}
				$aList["mediabuild"] = array("id" => (count($aList)+1), "lib" => "mediabuild", "url" => "javascript:menu(34,'mediabuild');", "pid" => "X1", "order" => "mediabuild");
				$aList["text"] = array("id" => (count($aList)+1), "lib" => "text", "url" => "javascript:menu(34,'text');", "pid" => "X1", "order" => "text");
				@ksort($aList);
			}
			return $aList;
		}
		 
	}
	 
?>