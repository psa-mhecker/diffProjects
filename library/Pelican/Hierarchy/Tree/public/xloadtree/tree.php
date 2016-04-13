<?php

/** XML généré pour ExtJS à partir de l'id du noeud parent
	*
	*
	* @package Pelican
	* @subpackage Hierarchy
	* @since 14/04/2010
*/

session_start();

$attrs = array();

if ($_SESSION['xloadtree'] && isset($_GET["node"])) {
	$node = $_SESSION['xloadtree']['params'][$_GET["node"]];
	if ($node["child"]) {
		foreach ($node["child"] as $id) {
			$list[$_SESSION['xloadtree']['params'][$id]['record']]=$_SESSION['xloadtree']['nodes'][$_SESSION['xloadtree']['params'][$id]['record']];
		}
		ksort($list);
	}
	if ($list) {
		foreach ($list as $node) {

			$hasChild = $_SESSION['xloadtree']['params'][$node->id]["child"];
			$treedata[] = array(
			'id' => $node->id,
			'action' => $node->url,
			'src' => ($hasChild?$_SERVER["PHP_SELF"]."?node=".$node->id:''),
			//'icon' => $node->icon,
			'iconAction' => '',
			'openIcon' => ($node->iconOpen?$node->iconOpen:$node->icon),
			'text' => $node->lib,
			'toolTip' => $node->id
			);
		}
	} else {
		$attrs['nohead'] = true;
		//nodata
	}
}
$xml = treeXML($treedata, $attrs);

echo(utf8_encode($xml));
?>