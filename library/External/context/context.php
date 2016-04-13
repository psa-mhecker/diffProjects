<?php
/**
	* Génération de l'appel à un menu contextuel géré par la librairie ContextMenu.js de webfx.eae.net
	*
	* @usage :
	* <code>
	* $menu["onload"]=array(
	*                   array("Refresh","window.location.reload()"),
	*                   array("Disabled Item","",true),
	*                   array(),
	*                   array("Print","window.print()",false),
	*                   array("Kill Jared","",true),
	*                   array("View Source","location='view-source:'+location"),
	*                   array()
	*                );
	* ?>
	* <head>
	* <title>Untitled</title>
	* <?=getContextMenu($menu)?>
	* </head>
	* <body oncontextmenu="contextonload(this)">
	* <?=$loadContext?>
	* </body>
	* </code>
	*
	* @author Raphaël Carles <rcarles@businessdecision.fr>
	* @since 01/05/2004
	* @package Pelican
	* @subpackage External
	*/

/**
	* Création de l'appel javascript au menu contectuel
	* @return string
	* @param string $menu Identifiant de menu
	*/


function getContextMenu($menu) {

	

	/** création des menus */
	$context = "";
	if (Pelican::$config['ENV']['LOCAL']['NAVIGATOR']['ie']) {
		if ($menu) {
			$context = "<link href=\"".Pelican::$config["LIB_PATH"].Pelican::$config['LIB_OTHER']."/context/css/WebFX-ContextMenu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
			$context .= "<script src=\"".Pelican::$config["LIB_PATH"].Pelican::$config['LIB_OTHER']."/context/js/ContextMenu.js\" type=\"text/javascript\"></script>\n";
			$context .= "<script src=\"".Pelican::$config["LIB_PATH"].Pelican::$config['LIB_OTHER']."/context/js/ieemu.js\" type=\"text/javascript\"></script>\n";
			$context .= "<script type=\"text/javascript\">\n";
			foreach ($menu as $key => $value) {
				$context .= "function context".$key."(obj)\n";
				$context .= "{\n";
				$context .= "   var eobj,popupoptions;\n";
				$context .= "   popupoptions = [\n";
				$options = array();
				foreach($value as $item) {
					if (count($item)==0) {
						$options[] .= "      new ContextSeperator()";
					} else {
						$options[] .= "      new ContextItem(\"".$item[0]."\",".($item[1]?"function(){".$item[1]."}":"null").",".(isset($item[2])?$item[2]:"false").")";
					}
				}
				$context .= implode(",\n", $options)."\n";
				$context .= "   ];\n";
				$context .= "   ContextMenu.display(popupoptions);\n";
				$context .= "}\n\n";
			}
			$context .= "</script>\n";
		}
	}
	return $context;

}

$loadContext = "";
if (!empty(Pelican::$config['ENV']['LOCAL']['NAVIGATOR']['ie'])) {
	$loadContext = "<script>ContextMenu.initializeContextMenu();\n</script>";
}
?>