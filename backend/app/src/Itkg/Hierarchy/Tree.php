<?php

namespace Itkg\Hierarchy;

use DateTime;

class Tree
{
    const DTREE_LIMIT = 20000
    ;
    /**
     * @var TreeModule
     */
    public $tree;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $iIncrCorrection;

    public $type;

    /**
     * @var string
     */
    public $xmlScript = 'tree.php';

    /**
     * @var string
     */
    public $rootParams = 'Accueil';

    /**
     * @var string
     */
    public $defaultIcon = '/library/Pelican/Hierarchy/Tree/public/images/base.gif';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Nom de la propriété servant d'ID.
     *
     *
     * @var string
     */
    public $idName = '';

    /**
     * Nom de la propriété servant d'ID PARENT (PIVOT).
     *
     *
     * @var string
     */
    public $pidName = '';

    /**
     * Tableau résultant de description de chaque noeud de l'objet hiérarchique : se
     * tableau est créée par la méthode setOrder.
     *
     *
     * @var array
     */
    public $aNodes = array();

    /**
     * Tableau de travail des noeud de l'objet hiérarchique.
     *
     *
     * @var mixed
     */
    public $aParams = array();

    /**
     * Tableau associatif entre les id de noeud dans $aParams  ("id") et leur rang au
     * sein du tableau $aNodes ("indice") => array("id"=>"indice").
     *
     *
     * @var mixed
     */
    public $aPosition = array();

    /**
     * Indique si la hiérarchie a été créée.
     *
     *
     * @var bool
     */
    protected $_hierarchyGenerated = false;

    /**
     * Nombre de noeuds de l'objet hiérarchique.
     *
     *
     * @var int
     */
    protected $_countNodes;

    /**
     * Niveau maximal atteind par l'objet hiérarchique.
     *
     *
     * @var int
     */
    protected $_countLevels = 0;

    /**
     * Tableau Récapitulatif des ordres des ID de noeud après le tri effectué dans
     * setOrder.
     *
     *
     * @var mixed
     */
    protected $_aOrder = array();

    /**
     * @var array
     */
    protected $sauve;
    /**
     * @var mixed
     */
    protected $selected;
    /**
     * @var array
     */
    protected $aLevels;
    /**
     * @var mixed
     */
    protected $selectedNode;

    /**
     * Constructeur.
     *
     *
     * @param int    $id      Identifiant de l'objet hiérarchique
     * @param string $idName  Nom de la propriété ou du champ jouant le rôle d'ID
     * @param string $pidName Nom de la propriété ou du champ jouant le rôle d'ID PERE
     */
    public function __construct($id, $idName, $pidName)
    {
        $this->id = $id;
        $this->idName = $idName;
        $this->pidName = $pidName;
        $this->aPosition[0] = 0;
        $this->iIncrCorrection = 0;
        $this->now = new \DateTime();
        $this->readOnly = false;
        $this->startName = 'start';
        $this->endName = 'end';
        $this->prevStartName = 'prev_start';
        $this->prevEndName = 'prev_end';
    }

    /**
     * @param bool $readOnly
     *
     * @return Tree
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Ajout de plusieurs entrées dans l'objet hiérarchique.
     *
     * Tableau du type
     * array("0"=>array("champ1"=>"valeur1"),"1"=>array("champ2"=>"valeur2"),...)
     * Le format attendu est le même que celui retourné par
     * $oConnection->queryTab($sql);
     * <code>
     * $sql = "select TABLE_ID \"id\",TABLE_LIB \"lib\", TABLE_PÄRENT_ID \"pid\" FROM
     * TABLE";
     * $directory = $oConnection->queryTab($sql);;
     * $oTree = Pelican_Factory::getInstance('Hierarchy',"dtree", "id", "pid");
     * $oTree->addTabNode($directory);
     * </code>
     *
     *
     * @param array $aTab Tableau de données
     *
     * @return $this
     */
    public function addTabNode(array $aTab)
    {
        if (!empty($aTab)) {
            foreach ($aTab as $ligne) {
                $this->addNode($ligne);
            }
        }

        return $this;
    }

    /**
     * Création récursive des propriétés "path" (id dans l'ordre hiérarchique des
     * parents) et "child" (id des enfants direct) du noeud.
     *
     *
     * @param string $id    Identifiant du noeud
     * @param int    $level (option) Niveau du noeud
     */
    public function putPath($id, $level = 1)
    {
        /**premiers niveaux */
        $this->aParams[$id]['level'] = $level;
        if (isset($this->aParams[$id]['record'])) {
            if ($this->aNodes[$this->aParams[$id]['record']]) {
                $this->aNodes[$this->aParams[$id]['record']]->level = $level;
            }
        }
        $this->aParams[$id]['path'][] = $id;
        ++$level;
        if (isset($this->aParams[$id]['child'])) {
            foreach ($this->aParams[$id]['child'] as $child) {
                $this->aParams[$child]['path'] = $this->aParams[$id]['path'];
                $this->putPath($child, $level);
            }
        }
        $this->_countLevels = ($this->_countLevels > $level ? $this->_countLevels : $level);
    }

    /**
     * Création de la hiérarchie : initialisation de la propriété $hierarchy qui
     * indique que la hiérarchie a été "calculée".
     */
    public function getHierarchy()
    {
        $this->putPath('0');
        $this->_hierarchyGenerated = true;
    }

    /**
     * .
     *
     *
     * @param int $id
     *
     * @return array
     */
    public function getNodeTab($id)
    {
        $aReturn = [];

        $node = $this->aNodes[$this->aParams[$id]['record']];
        if ($node) {
            while (list($key, $val) = each($node)) {
                $aReturn[$key] = $val;
            }
        }

        return $aReturn;
    }

    /**
     * Retourne la valeur de la propriété demandée pour un ID donné.
     *
     *
     * @param string $id       ID de l'élément
     * @param string $property Nom de la propriété
     *
     * @return mixed
     */
    public function getNodeProperty($id, $property)
    {
        return $this->aNodes[$this->aParams[$id]['record']]->$property;
    }

    /**
     * Attribution manuelle d'une propriété à un noeud.
     *
     *
     * @param string $id       ID de l'élément
     * @param string $property Nom de la propriété
     * @param mixed  $value    (option) Valeur de la propriété
     */
    public function setNodeProperty($id, $property, $value = '')
    {
        if ($this->aNodes[$this->aParams[$id]['record']]) {
            $this->aNodes[$this->aParams[$id]['record']]->addProperty($property, $value);
        }
    }

    /**
     * .
     *
     *
     * @param string $id        Unknown
     * @param string $property  Unknown
     * @param string $separator (option) Unknown
     * @param string $class     (option) Unknown
     * @param bool   $bUseUrl   (option) Unknown
     *
     * @return string
     */
    public function getNodePath($id, $property, $separator = ' > ', $class = '', $bUseUrl = true)
    {
        $path = '';
        if ($this->aParams[$id]['path']) {
            $url = '';
            foreach ($this->aParams[$id]['path'] as $path_id) {
                if ($path_id != '0') {
                    if ($bUseUrl) {
                        $url = $this->getNodeProperty($path_id, 'url');
                    }
                    $path .= ($path ? ' '.trim($separator).' ' : '');
                    if ($url && $path_id != $id) {
                        $path .= '<a href="'.$url.'" '.($class ? 'class="'.$class.'"' : '').'>';
                    }
                    $path .= $this->getNodeProperty($path_id, $property);
                    if ($url && $path_id != $id) {
                        $path .= '</a>';
                    }
                }
            }
        } else {
            $path .= $this->getNodeProperty($id, $property);
        }

        return $path;
    }

    /**
     * .
     *
     *
     * @param string     $property   Unknown
     * @param string     $type       (option) Unknown
     * @param string     $limitedId  (option) Unknown
     * @param int|string $sort_flags (option) Constante de tri exploitée dans   orderChild : SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     */
    public function setOrder($property, $type = 'ASC', $limitedId = '', $sort_flags = null)
    {
        $this->orderChild($property, $type, '0', $sort_flags);
        $this->getOrder();
        if ($limitedId) {
            $this->limitedPath($limitedId);
        }
    }

    /**
     * .
     *
     * @param int $id
     */
    public function limitedPath($id)
    {
        $level1 = $this->aParams[$this->aNodes[1]->id]['child'];
        $rootId = $this->aParams[$id]['path'][2];
        $this->aNodes[1]->limit = true;
        foreach ($level1 as $child) {
            $this->aNodes[$this->aParams[$child]['record']]->limit = true;
        }
        $this->markLimitedChildNodes($rootId);
        $i = 0;
        foreach ($this->aParams as $value) {
            if (!$this->aNodes[$this->aParams[$value['id']]['record']]->limit) {
                unset($this->aNodes[$this->aParams[$value['id']]['record']]);
                unset($this->aParams[$value['id']]);
                unset($this->aPosition[$value['id']]);
            } else {
                if ($this->aParams[$value['id']]['level'] == 3) {
                    ++$i;
                    if ($value['id'] != $rootId && $this->aParams[$value['id']]['child']) {
                        $this->aNodes[$this->aParams[$value['id']]['record']]->url = 'javascript:goLevel('.$value['id'].')';
                        $this->aNodes[$this->aParams[$value['id']]['record']]->icon = $this->options['SKIN_PATH'].'/images/folder_child.gif';
                    }
                }
            }
        }
    }

    /**
     * .
     *
     *
     * @param int $id
     */
    public function markLimitedChildNodes($id)
    {
        $this->aNodes[$this->aParams[$id]['record']]->limit = true;
        if ($this->aParams[$id]['child']) {
            foreach ($this->aParams[$id]['child'] as $child) {
                $this->markLimitedChildNodes($child);
            }
        }
    }

    /**
     * .
     *
     *
     * @param string $property   Unknown
     * @param string $type       (option) Unknown
     * @param string $pid        (option) Unknown
     * @param int    $sort_flags (option) Constante de tri exploitée dans      orderChild : SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     */
    public function orderChild($property, $type = 'ASC', $pid = '0', $sort_flags = null)
    {
        $this->verifyHierarchy();
        // liste des enfants
        if (isset($this->aParams[$pid]['child'])) {
            foreach ($this->aParams[$pid]['child'] as $child) {
                $temp[$this->getNodeProperty($child, $property) ] = $child;
                $this->orderChild($property, $type, $child, $sort_flags);
            }
        }
        if (isset($temp)) {
            switch ($type) {
                case 'DESC':
                        rsort($temp, $sort_flags);
                    break;
                case 'ASC':
                        ksort($temp, $sort_flags);
                    break;
            }
            foreach ($temp as $child) {
                $new[] = $child;
            }
            $this->aParams[$pid]['child'] = $new;
        }
    }

    /**
     * .
     *
     *
     * @param int $id (option) Unknown
     */
    public function getOrder($id = 0)
    {
        $this->verifyHierarchy();
        if ($id == 0) {
            $this->sauve = $this->aNodes;
            $this->aNodes = array();
        } else {
            $this->_aOrder[] = $id;
            if (!empty($this->sauve)) {
                $indice = count($this->aNodes) + 1;
                $this->aNodes[$indice] = $this->sauve[$this->aParams[$id]['record']];
                $this->aParams[$id]['record'] = $indice;
                $this->aPosition[$id] = $this->aParams[$id]['record'];
                $this->aLevels[$this->aParams[$id]['level']][$indice] = $id;
            }
        }
        if (isset($this->aParams[$id]['child'])) {
            foreach ($this->aParams[$id]['child'] as $child) {
                $this->getOrder($child);
            }
        }
    }

    /**
     * .
     */
    public function verifyHierarchy()
    {
        if (!$this->_hierarchyGenerated) {
            $this->getHierarchy();
        }
    }

    /**
     * .
     *
     *
     * @param string $property
     * @param mixed  $value
     */
    public function addProperty($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * L'utilisateur a t il le droit de mofifier les pages.
     *
     * @var bool
     */
    protected $readOnly;

    /**
     * la date courante.
     *
     * @var DateTime
     */
    protected $now;

    /**
     * field name used for start publication date.
     *
     * @var string
     */
    protected $startName;

    /**
     * field name used for end publication date.
     *
     * @var string
     */
    protected $endName;

    /**
     * field name used for start publication date of the previous published verion.
     *
     * @var string
     */
    protected $prevStartName;

    /**
     * field name used for end publication date of the previous published verion.
     *
     * @var string
     */
    protected $prevEndName;

    /**
     * @param array $aNodes
     * @param int   $parentId
     *
     * @return array
     */
    public function buildJsonTree(array $aNodes, $parentId = 0)
    {
        $branch = array();
        foreach ($aNodes as $oNode) {
            $rel = 'page';
            if ($oNode->page_general == '1' && (is_null($oNode->pid) || $oNode->pid == 0)) {
                $rel = 'global_drive';
            } elseif ($oNode->pid == 0) {
                $rel = 'drive';
            }
            if ($rel == 'drive') {
                $state = 'open';
            } else {
                $state = '';
            }

            $oNode->data = array(
                'title' => $this->html_entity_decode($oNode->order.'_'.$oNode->lib),
                'attr' => array(
                    'href' => 'javascript:void(0)',
                ),
            );
            $oNode->state = $state;
            $oNode->attr = array(
                'id' => sprintf('node_%s', $oNode->id),
                'rel' => $rel,
                'type' => $rel,
                'class' => $oNode->class,
                'data_n' => sprintf('%s', json_encode(array(
                    'id' => $oNode->id,
                    'pid' => $oNode->pid,
                    'langue_id' => $oNode->langue_id,
                    'order' => $oNode->order,
                    'path' => $oNode->path,
                    'on_select' => str_replace('javascript:', '', $oNode->url),
                    'is_published' => (isset($oNode->is_published)) ? $oNode->is_published : false,
                    'is_never_published' => (isset($oNode->is_never_published)) ? $oNode->is_never_published : false,
                ))),
            );
            // @todo try to find something more efficient
            if ($oNode->pid == $parentId) {
                $children = $this->buildJsonTree($aNodes, $oNode->id);
                if (!empty($children)) {
                    $oNode->children = $children;
                }
                $branch[] = $oNode;
            }
        }

        return $branch;
    }

    protected function getPublicationClass($node, $startName, $endName)
    {
        $class = 'green';
        // before
        if (!empty($node->{$startName})) {
            $startTime = DateTime::createFromFormat('d/m/Y H:i', $node->{$startName});
            //var_dump($startName.'='.$this->now->format('d/m/Y').'  <'.$startTime->format('d/m/y').' result ='.($this->now < $startTime));
            if ($this->now < $startTime) {
                $class = 'green_oh';
            }
        }
        //after
        if (!empty($node->{$endName})) {
            $endTime = DateTime::createFromFormat('d/m/Y H:i', $node->{$endName});
            if ($this->now > $endTime) {
                $class = 'orange_oh';
            }
        }

        return $class;
    }

    /**
     * @param $node
     *
     * @return string
     */
    protected function getClass($node)
    {

        // version par defaut
        $class = 'grey';
        // par defaut on utilise les date de la version courante
        $startName = $this->prevStartName;
        $endName = $this->prevEndName;

        // la page a été publié une fois donc on un des 3 cas en fonction des dates de publication
        if (!empty($node->current_version)) {
            $class = $this->getPublicationClass($node, $startName, $endName);
        }

        // si la page a été publié une fois mais qu'elle est hors ligne
        if (!empty($node->current_version) && !$node->status) {
            // state offline
            $class = 'red';
        }

        // check if user can write or not
        // change color of the text link
        if ($this->readOnly) {
            $class .= ' user-readonly';
        }

        return $class;
    }

    /**
     * Ajout d'une entrée dans l'objet hiérarchique.
     *
     * Tableau du type array("champ1"=>"valeur1","champ2"=>"valeur2",...)
     * Le format attendu est le même que celui retourné par
     * $oConnection->queryRom($sql);
     * <code>
     * $directory = array("id" => "999", "pid" => "A", "lib" => "Contenus
     * archivés");
     * $oTree = Pelican_Factory::getInstance('Hierarchy',"dtree", "id", "pid");
     * $oTree->addNode($directory);
     * </code>
     *
     *
     * @param array $aTab Tableau de données
     *
     * @return bool|mixed
     */
    public function addNode(array $aTab)
    {
        if (!empty($aTab)) {
            $id = $aTab[$this->idName];
            if (!isset($aTab[$this->pidName])) {
                $aTab[$this->pidName] = 0;
            }
            $aTab['lib'] = strip_tags(html_entity_decode($aTab['lib']));
            $pid = $aTab[$this->pidName];
            // création du Node
            $indice = count($this->aNodes) + 1;
            $node = (object) $aTab;
            $node->class = $this->getClass($node);
            $this->aNodes[$indice] = $node;
            // définition de paramètres de positionnement
            $this->aParams[$id]['record'] = $indice;
            $this->aParams[$id]['id'] = $id;
            $this->aParams[$id]['pid'] = $pid;
            $this->aParams[$pid]['child'][$id] = $id;
            $this->aPosition[$id] = $this->aParams[$id]['record'];
            ++$this->_countNodes;

            return $this->aNodes[$indice];
        }

        return false;
    }

    public function html_entity_decode($value)
    {
        $return = $value;
        $return = str_replace(array(
            '&amp;',
            '&gt;',
            '&lt;',
            '&quot;',
        ), array(
            '&',
            '>',
            '<',
            '"',
        ), $return);

        return $return;
    }

    /**
     * .
     *
     *
     * @param string $type
     */
    public function setTreeType($type)
    {
        $this->type = $type;
        $tid = $this->options['tid'];

        /*
         *
         * @access public
         * @var __TYPE__ 
         */
        if ((count($this->aNodes) > self::DTREE_LIMIT) && $type == 'dtree') {
            $type = 'xloadtree';
        }
        switch ($type) {
            case 'jstree':
                // organiser les enfants
                // reorganisation de noeuds pour le format json jstree
                // on pourra penser à créer une classe pour chaque type d'arborescence
                // cette méthode sera du coup une surcharge de la méthode addNode

                if (isset($_SESSION[APP]['PAGE_ID'])) {
                    $pageid = $_SESSION[APP]['PAGE_ID'];
                } else {
                    $pageid = 1;
                }

                $this->buildJsonTree($this->aNodes);
                $this->tree = new TreeModule($this->id);
                $this->tree->setJS($this->options['LIB_PATH'].'/External/jquery/jsTree/jquery.jstree.js');

                /*
                 *
                 * @author : Ayoub Hidri
                 *
                 */

                // Hack pour avoir le nom de la méthode à appeler
                $jstree_id = '#jstree_'.$this->id;

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
                    return {
                        id : n.attr ? n.attr("id") : 0,
                        tid : $tid
                    };
                }
            }
       }
        ,
        "themes" : {
            "theme" : "default"
        },

        "ui":{
            "initially_select" : [ "node_$pageid" ]
        },
        "progressive_render" : true,
        "plugins": ["themes", "json_data", "ui","crrm","dnd","types"],
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
            case 'dtree':
                // paramètre à utiliser : "id", "pid", "lib", "url", "title", "target", "icon", "iconOpen", "open"
                $this->tree = new TreeModule($this->id);
                $this->tree->setCSS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/Tree/public/dtree/dtree.css');
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/Tree/public/dtree/dtree.js');
                $this->tree->setStart("<div class=\"dtree\">\n<script type=\"text/javascript\">\n".$this->id." = new dTree('".$this->id."');\n");
                $this->tree->setEnd('document.write('.$this->id.");\n</script>\n</div>\n");
                $this->tree->setAdd($this->id.'.add(', ");\n", array(
                    'id',
                    'pid',
                    'lib',
                    'url',
                    'title',
                    'target',
                    'icon',
                    'iconOpen',
                    'open',
                ));
                $this->tree->setIncrement($this->idName, $this->pidName, $this->aPosition);
                $this->tree->iIncrCorrection = -1;
                break;
            case 'extjs':
                $this->tree = new TreeModule($this->id);
                $this->xmlScript = 'tree.php';
                $this->xmlScript = $this->options['LIB_PATH'].'/Pelican/Hierarchy/Tree/public/extjs/'.$this->xmlScript;
                $this->tree->setStart('');
                // Ouverture d'un noeud par défaut
                if (is_array($this->options) && isset($this->options['defaultnode']) && $this->options['defaultnode'] != '') {
                    $def = $this->options['defaultnode'];
                } elseif ($this->aNodes[1]) {
                    $def = $this->aNodes[1]->id;
                } else {
                    $def = '';
                }
                $defaultOptions = array(
                    'target' => 'divRubrique0',
                    'dragDrop' => true,
                );

                $this->options = array_merge($defaultOptions, $this->options);

                $this->tree->setEnd('
						<script type="text/javascript">

						Ext.onReady(function(){

							Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

							Ext.QuickTips.init();

							var '.$this->id." = new Ext.Pelican.Tree({
								root: {
									nodeType: 'async',
									text: 'Racine',
									draggable: false,
									allowDrop: false,
									id: '0'
								},
								id: '".$this->id."',
								stateId: '".$this->id."',
								api: {'list': '".$this->xmlScript."'},
								defaultnode: '".$def."',
								rootVisible : false,
								enableDD: '".$this->options['dragDrop']."',
								listeners: {
									'click': {fn:function(a,b,c){eval(a.attributes.action);}},
									'movenode': {fn:function(tree,node,oldParent,newParent,index){ajaxDragnDropFolder(node.id,oldParent.id, newParent.id,index);}}
								}
							});
							".$this->id.".render('".$this->options['target']."');
						});
						</script>
					");
                $_SESSION['extjs']['nodes'] = (array) $this->aNodes;
                $_SESSION['extjs']['params'] = (array) $this->aParams;
                break;
            case 'menu':
                // paramètre à utiliser : "id", "pid", "lib", "url", "icon"
                // config à utiliser : orientation (0-horizontal 1-vertical)
                $this->tree = new TreeModule($this->id);
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/menu/lw_layers.js');
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/menu/lw_menu.js');
                $this->tree->setStart("<script type=\"text/javascript\">\n");
                $this->tree->setEnd("DrawMenu();\n</script>\n");
                $this->tree->setAdd('AddMenuItem(', ");\n", array(
                    'id',
                    'pid',
                    'url',
                    'lib',
                    'icon',
                ));
                $this->tree->setIncrement($this->idName, $this->pidName, $this->aPosition);
                break;
            case 'xloadtree':
                // paramètre à utiliser : "id", "pid", "lib", "url", "title", "target", "icon", "iconOpen", "open"
                $this->tree = new TreeModule($this->id);
                $this->tree->setCSS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/xloadtree/xloadtree.css');
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/xloadtree/xtree2.js');
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/xloadtree/xloadtree2.js');
                $this->tree->setJS($this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/xloadtree/xloadtree.addon.js');
                $this->tree->setStart('');
                $this->xmlScript = $this->options['LIB_PATH'].$this->options['LIB_HIERARCHY'].'/xloadtree/'.$this->xmlScript;
                if ($this->aLevels[2]) {
                    $nodes = array_values($this->aLevels[2]);
                    $script = '<div class="webfx-tree-container"><script type="text/javascript">';
                    $script .= 'var '.$this->id.' = new WebFXTree("'.$this->rootParams.'");';
                    $count = count($nodes);
                    for ($i = 0; $i < $count; ++$i) {
                        $record = $this->aParams[$nodes[$i]]['record'];
                        $node = $this->aNodes[$record];
                        $hasChild = $this->aParams[$node->id]['child'];
                        $src = ($hasChild ? $this->xmlScript.'?node='.$node->id : '');
                        $script .= ''.$this->id.'.add(new WebFXLoadTreeItem("'.$node->lib.'", "'.$src.'", "'.$node->url.'","","'.($node->icon ? $node->icon : $this->defaultIcon).'","'.($node->iconOpen ? $node->iconOpen : ($node->icon ? $node->icon : $this->defaultIcon)).'"));';
                    }
                    $script .= '
					'.$this->id.'.setObjName("'.$this->id.'");
					'.$this->id.'.write();
					'.$this->id.'.setExpanded(true);
					</script>
					</div>';
                    // $script = str_replace('dtreedtree','dtree',$script);
                    $this->tree->setEnd($script);
                }
                $_SESSION['xloadtree']['nodes'] = (array) $this->aNodes;
                $_SESSION['xloadtree']['params'] = (array) $this->aParams;
                break;

        }
    }

    /**
     * .
     *
     *
     * @param array $aConfig
     *                       Unknown
     */
    public function setConfig($aConfig)
    {
        $this->tree->aConfig = $aConfig;
    }

    /**
     * .
     *
     *
     * @return array
     */
    public function getTree()
    {
        $return = '';
        $i = 0;
        $return .= $this->tree->getCSS();
        $return .= $this->tree->getJS();
        $return .= $this->tree->getStart();
        if ($this->tree->fAddStart || $this->tree->fEndStart) {
            $bFlagOnce = false;
            foreach ($this->aNodes as $node) {
                ++$i;
                $required = $this->tree->requiredParam;
                if (!$required || ($required && $node->$required)) {
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
                if (isset($_GET['tid']) && !$bFlagOnce && ($node->TEMPLATE_ID == $_GET['tid'] || $node->id == $_GET['tid'])) {
                    setcookie('cs'.$this->id.'_'.$_GET['idbo'], ($i - 1), time() + 86400);
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
     * .
     *
     *
     * @return int
     */
    public function getIncre()
    {
        $aIncrement = $this->tree->aPosition;

        $return = '';
        if ($this->type == 'dtree') {
            foreach ($aIncrement as $key => $value) {
                $return .= $this->id.".increment('".$key."','".($value - 1)."');\n";
            }
        }

        return $return;
    }
}
