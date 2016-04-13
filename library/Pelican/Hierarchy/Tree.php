<?php
/**
 * Gestion des couches de présentation des objets hiérarchiques
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 15/12/2003
 * @link http://www.interakting.com
 */
ini_set('max_execution_time', 90);
define('DTREE_LIMIT', 20000);

/**
 * Classe de gestion des objets hiérarchiques
 */
pelican_import('Hierarchy');

/**
 * Cette classe permet d'appliquer des couches de présentation à un objet issu de
 * la classe hiérarchique
 * Cela peut s'appliquer à une présentation indentés au seins de listes
 * déroulantes, à l'afiichage d'une arborescence
 *
 * à l'affichage d'un menu de navigation ou d'un chemin de fer.
 * la couche d'abstraction de l'objet hiérarchique permet de passer d'une couche
 * de présentation à une autre ou
 * de faire varier les données utilisées par la couche de présentation
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/12/2003
 *        @update 16/10/2008 Ajout de xloadtree
 */
class Pelican_Hierarchy_Tree extends Pelican_Hierarchy
{

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $tree;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $id;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $iIncrCorrection;

    public $type;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $xmlScript = "tree.php";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $rootParams = "Accueil";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $defaultIcon = "/library/Pelican/Hierarchy/Tree/public/images/base.gif";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $options = '';

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id
     *            Unknown
     * @param __TYPE__ $idName
     *            (option) Unknown
     * @param __TYPE__ $pidName
     *            (option) Unknown
     * @return Pelican_Hierarchy_Tree
     */
    public function Pelican_Hierarchy_Tree ($id, $idName = "id", $pidName = "pid")
    {
        Pelican_Hierarchy::Pelican_Hierarchy($id, $idName, $pidName);
        $this->iIncrCorrection = 0;
    }

    public function buildJsonTree (array $aNodes, $parentId = 0)
    {
        $branch = array();
        foreach ($aNodes as $oNode) {
            $classes = array();
            
            $rel = 'page';
            // ((boolean)$oNode->pid)? $rel='file':$rel='folder';
            if ($oNode->page_general == '1' && (is_null($oNode->pid) || $oNode->pid == 0)) {
                $rel = 'global_drive';
                // $type ='drive'
            } elseif ($oNode->pid == 0) {
                $rel = 'drive';
            }
            if ($rel == 'drive') {
                $state = 'open';
            } else {
                $state = '';
            }
            if (is_null($oNode->current_version) || ! isset($oNode->current_version) || (isset($oNode->langue_id) && $_SESSION[APP]['LANGUE_ID'] != $oNode->langue_id)) {
                $class = 'grey';
            } else {
                if ($oNode->status == 0) {
                    $class = 'red';
                } else {
                    // optimisation : DateTime use a lot of CPU
                    $class = 'green';
                    if (! empty($oNode->start)) {
                        if (new DateTime($oNode->start) > new DateTime()) {
                            $class = 'green_oh';
                        }
                    }
                    if (! empty($oNode->end)) {
                        if (new DateTime($oNode->end) < new DateTime()) {
                            $class = 'orange_oh';
                        }
                    }
                }
            }
            $oNode->data = array(
                'title' => $this->html_entity_decode($oNode->order . '_' . $oNode->lib),
                'attr' => array(
                    // 'onclick'=>sprintf('%s;return false;',$oNode->url),
                    
                    'href' => 'javascript:void(0)'
                )
            );
            $oNode->state = $state;
            $oNode->attr = array(
                'id' => sprintf('node_%s', $oNode->id),
                'rel' => $rel,
                'type' => $rel,
                'class' => $class,
                'data_n' => sprintf('%s', json_encode(array(
                    'id' => $oNode->id,
                    'pid' => $oNode->pid,
                    'langue_id' => $oNode->langue_id,
                    'order' => $oNode->order,
                    'path' => $oNode->path,
                    'on_select' => str_replace('javascript:', '', $oNode->url),
                    'is_published' => $oNode->is_published,
                    'is_never_published' => $oNode->is_never_published
                )))
            );
            
            if ($oNode->pid == $parentId) {
                $children = $this->buildJsonTree($aNodes, $oNode->id);
                if ($children) {
                    $oNode->children = $children;
                }
                $branch[] = $oNode;
            }
        }
        
        return $branch;
    }

    public function html_entity_decode ($value)
    {
        $return = $value;
        $return = str_replace(array(
            '&amp;',
            '&gt;',
            '&lt;',
            '&quot;'
        ), array(
            '&',
            '>',
            '<',
            '"'
        ), $return);
        // $return = html_entity_decode($return, ENT_QUOTES, 'UTF-8');
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $type
     *            Unknown
     * @param string $complement
     *            (option) Unknown
     * @param __TYPE__ $options
     *            (option) __DESC__
     * @return void
     */
    public function setTreeType ($type, $complement = "", $options = array())
    {
        $this->type = $type;
        $this->options = $options;
        /**
         *
         * @access public
         * @var __TYPE__ __DESC__
         */
        if ((count($this->aNodes) > DTREE_LIMIT) && $type == 'dtree') {
            $type = 'xloadtree';
        }
        switch ($type) {
            case "jstree":
                {
                    // organiser les enfants
                    // reorganisation de noeuds pour le format json jstree
                    // on pourra penser à créer une classe pour chaque type d'arborescence
                    // cette méthode sera du coup une surcharge de la méthode addNode
                    
                    if (isset($_SESSION[APP]['PAGE_ID'])) {
                        $pageid = $_SESSION[APP]['PAGE_ID'];
                    } else {
                        $pageid = 1;
                    }
                    
                    if (isset($_SESSION[APP]['LANGUE_ID'])) {
                        $current_langue_id = $_SESSION[APP]['LANGUE_ID'];
                    } else {
                        $current_langue_id = 1;
                    }
                    $jsTreeNodes = $this->buildJsonTree($this->aNodes);
                    $json_data = json_encode($jsTreeNodes);
                    $this->tree = new TreeModule($this->id);
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . "/External/jquery/jsTree/jquery.jstree.js");
                    
                    /*
                     * $html = "<div id=\"jstree_".$this->id."\">"; $startLevel = $this->aNodes[1]->level; $prevLevel = $startLevel; // ouverture du premier niveau $html .= "\n<ul>\n"; foreach($this->aNodes as $aNode) { if($aNode->level > $prevLevel) { // on monte d'un niveau $html .= "\n<ul>\n"; } elseif($aNode->level == $prevLevel) { // on reste dans le niveau, on ferme l'item avant de passer à une soeur $html .= "</li>\n"; } elseif($aNode->level < $prevLevel) { // on descend de n niveau, on ferme l'item et les niveaux parents $html .= "</li>\n"; for($i=1;$i <= $prevLevel-$aNode->level;$i++) { $html .= "</ul>\n</li>\n"; } } $html .= "<li id=\"node_".$aNode->id."\"><a href=\"".$aNode->url."\">".$aNode->lib."</a>"; $prevLevel = $aNode->level; } // fermeture jusqu'au niveau initial $html .= "</li>\n"; if($startLevel < $prevLevel) { for($i=1;$i <= $prevLevel-$startLevel;$i++) { $html .= "</ul>\n</li>\n"; } } // fermeture du premier niveau $html .= "</ul>\n"; /* ne pas utilser "ui" plugin qui desactive le href sauf en modifiant le code avec un .bind(\"select_node.jstree\", function (event, data) {} pour y mettre directement l'action menu() actuellement dans le href $htmlSetJs = "<script type=\"text/javascript\"> $(function () { $(\"#jstree_".$this->id."\") .jstree({ \"plugins\" : [\"themes\",\"html_data\"] }) }); </script> "; $html .= "\n</div>\n".$htmlSetJs;
                     */
                    
                    /**
                     *
                     * @author : Ayoub Hidri
                     *        
                     */
                    
                    // Hack pour avoir le nom de la méthode à appeler
                    // $js_function = str_replace('javascript: ', '', $)
                    $jstree_id = '#jstree_' . $this->id;
                    
                    $html = <<<EOD
<div id="jstree_$this->id">
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    /*$("$jstree_id").block({ css: {
			border: 'none',
			padding: '25px',
			backgroundColor: '#000',
			opacity: '.7',
			color: '#fff',
			cursor:'wait'
		},
		overlayCSS:  {
			backgroundColor:'#fff',
			opacity:        '0'
		},
		message: '<img src=\"/images/ajax-loader.gif\" alt=\"\"/><h1>Traitement en cours...</h1>',
		fadeIn:  200,
        fadeOut:  200,
        timeout: 8000 });*/
        
    jQuery("$jstree_id").jstree({
       "json_data":{
           "ajax" : {
                "url" : '/_/Cms_Navigation/getTreeJson',
                "data" : function (n) {
                    return { id : n.attr ? n.attr("id") : 0 };
                }
            }
       }
        
        ,
        "themes" : {
            "theme" : "default"
            //"dots" : false,
            //"icons" : false

        },

        "ui":{
            "initially_select" : [ "node_$pageid" ]
        },
        "progressive_render" : true,
        "plugins": ["themes", "json_data", "ui","crrm","dnd","types"],
        //"dnd":{       },
        "types" : {
            "valid_children" : [ "global_drive","drive" ],
            types:{
                "global_drive":{
                  "valid_children" : "none",
                  "draggable" : false,
                  "start_drag" : false,
                  "move_node" : false,
                  "delete_node" : false,
                  "remove" : false
                },
                "drive":{
                    "valid_children" : [ "page"],
                    "draggable" : false,
                    "draggable" : false,
                    "start_drag" : false,
                    "move_node" : false,
                    "delete_node" : false,
                    "remove" : false

                },
                "page":{
                    "valid_children" : [ "page"],
                    "start_drag" : true,
                    "move_node" : true
                    
                }
            }    
        }
    }).bind("select_node.jstree", function (event, data) {
         jsonData = jQuery.parseJSON(jQuery(data.rslt.obj).attr('data_n'));
         
         eval(jsonData.on_select);
         jQuery('#recherchePage').val(jsonData.id);

    }).bind("move_node.jstree", function (e, data) {
            //console.log(jQuery(data.rslt.obj).attr('data_n')); 
            draggedJsonData = jQuery.parseJSON(jQuery(data.rslt.o).attr('data_n'));
            console.log(data.rslt);
            cr = data.rslt.cr;
            op = data.rslt.op;
            dragged_id = parseInt(data.rslt.o.attr('id').replace('node_',''));
            if(cr.attr('id').replace('node_','') == op.attr('id').replace('node_','')){
                target = op;
            }else{
                target = cr;
            }
            targetJsonData = jQuery.parseJSON(jQuery(target).attr('data_n'));
            //data.o - the foreign object being dragged
            //data.r - the target node
            movePage({
                'dragged':{
                    id:parseInt(data.rslt.o.attr('id').replace('node_','')),
                    text:data.rslt.o.text().trim(),
                    path:draggedJsonData.path,
                    pid:draggedJsonData.pid,
                    order: draggedJsonData.order
                },
                'target':{
                    id: parseInt(target.attr('id').replace('node_','')),
                    path:targetJsonData.path
                },
                'order':parseInt(data.rslt.cp),
                'tree_id':'$jstree_id'
            });
       
        })
        .bind('refresh.jstree',function(e, data) {
                
               $('div#frame_left_middle').unblock();
                
            
        })
            
        

});
</script>
EOD;
                    
                    $this->tree->setStart($html);
                    
                    break;
                }
            case "dtree":
                {
                    // paramètre à utiliser : "id", "pid", "lib", "url", "title", "target", "icon", "iconOpen", "open"
                    $this->tree = new TreeModule($this->id);
                    $this->tree->setCSS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/Tree/public/dtree/dtree.css");
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/Tree/public/dtree/dtree.js");
                    $this->tree->setStart("<div class=\"dtree\">\n<script type=\"text/javascript\">\n" . $this->id . " = new dTree('" . $this->id . "');\n");
                    $this->tree->setEnd("document.write(" . $this->id . ");\n</script>\n</div>\n");
                    $this->tree->setAdd($this->id . ".add(", ");\n", array(
                        "id",
                        "pid",
                        "lib",
                        "url",
                        "title",
                        "target",
                        "icon",
                        "iconOpen",
                        "open"
                    ));
                    $this->tree->setIncrement($this->idName, $this->pidName, $this->aPosition);
                    $this->tree->iIncrCorrection = - 1;
                    break;
                }
            case "extjs":
                {
                    $this->tree = new TreeModule($this->id);
                    $this->xmlScript = 'tree.php';
                    $this->xmlScript = Pelican::$config["LIB_PATH"] . "/Pelican/Hierarchy/Tree/public/extjs/" . $this->xmlScript;
                    $this->tree->setStart('');
                    // Ouverture d'un noeud par défaut
                    // if(is_array($options) && isset($options['defaultnode'])){
                    // $def = $options['defaultnode'];
                    // }else
                    if (is_array($options) && isset($options['defaultnode']) && $options['defaultnode'] != '') {
                        $def = $options['defaultnode'];
                    } else 
                        if ($this->aNodes[1]) {
                            $def = $this->aNodes[1]->id;
                        } else {
                            $def = '';
                        }
                    $defaultOptions = array(
                        'target' => 'divRubrique0',
                        'dragDrop' => true
                    );
                    if (is_array($options)) {
                        $this->options = array_merge($defaultOptions, $options);
                    } else {
                        $this->options = $defaultOptions;
                    }
                    $this->tree->setEnd("
						<script type=\"text/javascript\">
						
						Ext.onReady(function(){
							
							Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
							
							Ext.QuickTips.init();

							var " . $this->id . " = new Ext.Pelican.Tree({
								root: {
									nodeType: 'async',
									text: 'Racine',
									draggable: false,
									allowDrop: false,
									id: '0'
								},
								id: '" . $this->id . "',
								stateId: '" . $this->id . "',
								api: {'list': '" . $this->xmlScript . "'},
								defaultnode: '" . $def . "',
								rootVisible : false,
								enableDD: '" . $this->options['dragDrop'] . "',
								listeners: {
									'click': {fn:function(a,b,c){eval(a.attributes.action);}},
									'movenode': {fn:function(tree,node,oldParent,newParent,index){ajaxDragnDropFolder(node.id,oldParent.id, newParent.id,index);}}
								}
							});
							" . $this->id . ".render('" . $this->options['target'] . "');
						});
						</script>
					");
                    $_SESSION['extjs']['nodes'] = (array) $this->aNodes;
                    $_SESSION['extjs']['params'] = (array) $this->aParams;
                    break;
                }
            case "menu":
                {
                    // paramètre à utiliser : "id", "pid", "lib", "url", "icon"
                    // config à utiliser : orientation (0-horizontal 1-vertical)
                    $this->tree = new TreeModule($this->id);
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/menu/lw_layers.js");
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/menu/lw_menu.js");
                    $this->tree->setStart("<script type=\"text/javascript\">\n");
                    $this->tree->setEnd("DrawMenu();\n</script>\n");
                    $this->tree->setAdd("AddMenuItem(", ");\n", array(
                        "id",
                        "pid",
                        "url",
                        "lib",
                        "icon"
                    ));
                    $this->tree->setIncrement($this->idName, $this->pidName, $this->aPosition);
                    break;
                }
            case "xloadtree":
                {
                    // paramètre à utiliser : "id", "pid", "lib", "url", "title", "target", "icon", "iconOpen", "open"
                    $this->tree = new TreeModule($this->id);
                    $this->tree->setCSS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/xloadtree/xloadtree.css");
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/xloadtree/xtree2.js");
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/xloadtree/xloadtree2.js");
                    $this->tree->setJS(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/xloadtree/xloadtree.addon.js");
                    $this->tree->setStart('');
                    $this->xmlScript = Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_HIERARCHY'] . "/xloadtree/" . $this->xmlScript;
                    if ($this->aLevels[2]) {
                        $nodes = array_values($this->aLevels[2]);
                        $script = '<div class="webfx-tree-container"><script type="text/javascript">';
                        $script .= 'var ' . $this->id . ' = new WebFXTree("' . $this->rootParams . '");';
                        $count = count($nodes);
                        for ($i = 0; $i < $count; $i ++) {
                            $record = $this->aParams[$nodes[$i]]['record'];
                            $node = $this->aNodes[$record];
                            $hasChild = $this->aParams[$node->id]["child"];
                            $src = ($hasChild ? $this->xmlScript . '?node=' . $node->id : "");
                            $script .= '' . $this->id . '.add(new WebFXLoadTreeItem("' . $node->lib . '", "' . $src . '", "' . $node->url . '","","' . ($node->icon ? $node->icon : $this->defaultIcon) . '","' . ($node->iconOpen ? $node->iconOpen : ($node->icon ? $node->icon : $this->defaultIcon)) . '"));';
                        }
                        $script .= '
					' . $this->id . '.setObjName("' . $this->id . '");
					' . $this->id . '.write();
					' . $this->id . '.setExpanded(true);
					</script>
					</div>';
                        // $script = str_replace('dtreedtree','dtree',$script);
                        $this->tree->setEnd($script);
                    }
                    $this->tree->xmlType = $complement;
                    $_SESSION['xloadtree']['nodes'] = (array) $this->aNodes;
                    $_SESSION['xloadtree']['params'] = (array) $this->aParams;
                    break;
                }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aParams
     *            Unknown
     * @return void
     */
    public function setParams ($aParams)
    {
        // obsolete $this->tree->setParams($aParams);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aConfig
     *            Unknown
     * @return void
     */
    public function setConfig ($aConfig)
    {
        $this->tree->aConfig = $aConfig;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sParam
     *            Unknown
     * @return void
     */
    public function requiredParam ($sParam)
    {
        $this->tree->requiredParam = $sParam;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getTree ()
    {
        $return = "";
        $i = 0;
        $return .= $this->tree->getCSS();
        $return .= $this->tree->getJS();
        $return .= $this->tree->getStart();
        if ($this->tree->fAddStart || $this->tree->fEndStart) {
            $bFlagOnce = false;
            foreach ($this->aNodes as $node) {
                $i ++;
                $required = $this->tree->requiredParam;
                if (! $required || ($required && $node->$required)) {
                    $values = array();
                    foreach ($this->tree->aAddParams as $param) {
                        /*
                         * if ($node) { $node = $this->finalizeValues($node); }
                         */
                        if (isset($node->$param)) {
                            $values[$param] = $node->$param;
                        }
                    }
                    $return .= $this->tree->getAdd($values);
                }
                if (isset($this->selected)) {
                    if ($this->selected == $node->id) {
                        $this->selectedNode = $i;
                    }
                }
                /* Fonctionnement pour le routard */
                if ($_GET['tid'] && ! $bFlagOnce && ($node->TEMPLATE_ID == $_GET['tid'] || $node->id == $_GET['tid'])) {
                    setcookie("cs" . $this->id . "_" . $_GET["idbo"], ($i - 1), time() + 86400);
                    $bFlagOnce = true;
                }
            }
        }
        $return .= $this->tree->getConfig();
        $return .= $this->getIncre();
        $return .= $this->tree->getEnd();
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getIncre ()
    {
        $aIncrement = $this->tree->aPosition;
        
        $return = "";
        if ($this->type == "dtree") {
            foreach ($aIncrement as $key => $value) {
                $return .= $this->id . ".increment('" . $key . "','" . ($value - 1) . "');\n";
            }
        }
        return $return;
    }
}

/**
 * Cette classe permet de définir les méthodes d'utilisation des couches de
 * présentation utilisées par la classe Pelican_Hierarchy_Tree
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/12/2003
 */
class TreeModule
{

    public $id;

    public $aCSS = array();

    public $aJS = array();

    public $aAdd = array();

    public $aAddParams = array();

    public $fStart = "";

    public $fEnd = "";

    public $fAddStart = "";

    public $fEndStart = '';

    public $fAddEnd = "";

    public $idName = "id";

    public $pidName = "pid";

    public $requiredParam = "";

    public $aPosition = array();

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id
     *            Unknown
     * @return TreeModule
     */
    public function TreeModule ($id)
    {
        $this->id = $id;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sStart
     *            Unknown
     * @return void
     */
    public function setStart ($sStart)
    {
        $this->fStart = $sStart;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getStart ()
    {
        return $this->fStart;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sEnd
     *            Unknown
     * @return void
     */
    public function setEnd ($sEnd)
    {
        $this->fEnd = $sEnd;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getEnd ()
    {
        return $this->fEnd;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sAddStart
     *            Unknown
     * @param __TYPE__ $sAddEnd
     *            Unknown
     * @param __TYPE__ $aAddParams
     *            Unknown
     * @return void
     */
    public function setAdd ($sAddStart, $sAddEnd, $aAddParams)
    {
        $this->fAddStart = $sAddStart;
        $this->fAddEnd = $sAddEnd;
        $this->aAddParams = $aAddParams;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aAddParams
     *            Unknown
     * @return void
     */
    public function setParams ($aAddParams)
    {
        $this->aAddParams = $aAddParams;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sId
     *            Unknown
     * @return void
     */
    public function setParamId ($sId)
    {
        $this->idName = $sId;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $sPid
     *            Unknown
     * @return void
     */
    public function setParamPid ($sPid)
    {
        $this->pidName = $sPid;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $idName
     *            Unknown
     * @param __TYPE__ $pidName
     *            Unknown
     * @param __TYPE__ $aPosition
     *            Unknown
     * @return void
     */
    public function setIncrement ($idName, $pidName, $aPosition)
    {
        $this->setParamId($idName);
        $this->setParamPid($pidName);
        $this->aPosition = $aPosition;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aValues
     *            Unknown
     * @return __TYPE__
     */
    public function getAdd ($aValues)
    {
        $return = $this->fAddStart;
        $vall = array();
        $values = array();
        foreach ($this->aAddParams as $param) {
            if ($param == $this->idName || $param == $this->pidName) {
                $temp = ($this->aPosition[$aValues[$param]] ? $this->aPosition[$aValues[$param]] : "0") + $this->iIncrCorrection;
                if ($param == $this->idName) {
                    $vall[$aValues[$param]] = ($this->aPosition[$aValues[$param]] ? $this->aPosition[$aValues[$param]] : "0") + $this->iIncrCorrection;
                }
                $values[] = $temp;
            } else {
                if (isset($aValues[$param])) {
                    $values[] = "'" . str_replace("'", "\\'", $aValues[$param]) . "'";
                } else {
                    $values[] = "''";
                }
            }
        }
        $return .= implode(",", $values);
        $return .= $this->fAddEnd;
        $return = str_replace("'false'", "false", $return);
        $return = str_replace("'true'", "true", $return);
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aValues
     *            Unknown
     * @return void
     */
    public function add ($aValues)
    {
        $this->aAdd[] = $this->getAdd($aValues);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aCSS
     *            Unknown
     * @return void
     */
    public function setCSS ($aCSS)
    {
        $this->aCSS[] = $aCSS;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getCSS ()
    {
        $return = "";
        if ($this->aCSS) {
            foreach ($this->aCSS as $css) {
                $return .= "<link type=\"text/css\" href=\"" . $css . "\" rel=\"stylesheet\" />\n";
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aJS
     *            Unknown
     * @return void
     */
    public function setJS ($aJS)
    {
        $this->aJS[] = $aJS;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getJS ()
    {
        $return = "";
        if ($this->aJS) {
            foreach ($this->aJS as $JS) {
                $return .= Pelican_Html::script(array(
                    src => $JS
                )) . "\n";
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $aConfig
     *            Unknown
     * @return void
     */
    public function setConfig ($aConfig)
    {
        $this->aConfig[] = $aConfig;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getConfig ()
    {
        $return = "";
        if (isset($this->aConfig)) {
            foreach ($this->aConfig as $key => $value) {
                if (! is_numeric($value)) {
                    $value = "\"" . $value . "\"";
                }
                $return .= $key . "=" . $value . ";\n";
            }
        }
        return $return;
    }
}