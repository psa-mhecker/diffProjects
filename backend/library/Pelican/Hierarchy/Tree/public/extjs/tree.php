<?php

/** XML généré pour xloadtree à partir de l'id du noeud parent
 * Ce XML utilise les fonctions XLoadTree de http://webfx.eae.net.
 *
 * @link http://webfx.eae.net
 * @since 20/10/2008
 */
include_once 'config.php';
set_include_path(get_include_path.':'.Pelican::$config['LIB_ROOT']);
include Pelican::$config['LIB_ROOT'].'/Zend/Json.php';

$attrs = array();

if (!isset($_GET['type'])) {
    $aNodes = $_SESSION['extjs'];
    $minDragLevel = 2;
} else {
    $aNodes = $_SESSION['nodes'];
    $minDragLevel = 1;
}

if ($aNodes && isset($_POST["node"])) {
    $node = $aNodes['params'][$_POST["node"]];
    if ($node["child"]) {
        foreach ($node["child"] as $id) {
            $list[$aNodes['params'][$id]['record']] = $aNodes['nodes'][$aNodes['params'][$id]['record']];
        }
        ksort($list);
    }
    if ($list) {
        foreach ($list as $node) {
            $hasChild = $aNodes['params'][$node->id]["child"];
            //print_r($hasChild);
            $hasChild = is_array($hasChild) && array_count_values($hasChild)>0;
            $disabled = preg_match('#tree_table_red#', $node->icon);
            $global = preg_match('#tree_base#', $node->icon) || isset($_GET['type']);
            $treedata[] = array(
            'id' => $node->id,
            'action' => $node->url,
            'src' => ($hasChild ? $_SERVER["PHP_SELF"]."?node=".$node->id : ''),
            'children' => $hasChild ? null : array(),
            'disabled' => ($disabled) ? true : false,
            'expanded' => (!$hasChild || ($hasChild && !$node->pid)) ? true : false,
            'allowDrag' => (($node->level>$minDragLevel && !$disabled)) ? true : false,
            'allowDrop' => !$global,
            'icon' => $node->icon,
            'name' => 'DIRECTORY_ID[]',
            'value' => $node->id,
            'iconAction' => '',
            'openIcon' => ($node->iconOpen ? $node->iconOpen : $node->icon),
            'text' => $node->lib,
            'toolTip' => $node->lib,
            'editable' => false,
            'test' => $node,
            );

            if (isset($_GET['type'])) {
                if (isset($node->used)) {
                    $treedata[count($treedata)-1]['checked'] = false;
                    if ($node->used) {
                        $treedata[count($treedata)-1]['checked'] = true;
                    }
                }
                $treedata[count($treedata)-1]['icon'] = '/library/Pelican/Index/Backoffice/public/skins/outlook/images/folder.gif';
            }
        }
    } else {
        $attrs['nohead'] = true;
        //nodata
    }
}

$result = Zend_Json::encode($treedata);
echo $result;
