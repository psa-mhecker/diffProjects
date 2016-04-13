<?php
/**
 * Gestion des objets hiérarchiques
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 15/12/2003
 * @link http://www.interakting.com
 */

/**
 * Cette classe permet de gérer une hiérarchie de donnée de provenances
 * variées
 * le principe est d'alimenter l'objet avec des données contenant un id, un
 * parent
 *
 * Et un champ qui servira de tri au sein de chaque noeud.
 * L'alimentation peut se fait par variable de type tableau :
 * - multiligne (du type de celui renvoyé par Pelican_Db::queryTab)
 * - d'une ligne (du type de celui renvoyé par Pelican_Db::queryRow)
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/12/2003
 */
class Pelican_Hierarchy {
    
    /**
     * Identifiant de l'objet hiérarchique
     *
     * @access public
     * @var string
     */
    public $id;
    
    /**
     * Nom de la propriété servant d'ID
     *
     * @access public
     * @var string
     */
    public $idName = "";
    
    /**
     * Nom de la propriété servant d'ID PARENT (PIVOT)
     *
     * @access public
     * @var string
     */
    public $pidName = "";
    
    /**
     * Tableau résultant de description de chaque noeud de l'objet hiérarchique : se
     * tableau est créée par la méthode setOrder
     *
     * @access public
     * @var mixed
     */
    public $aNodes = array();
    
    /**
     * Tableau de travail des noeud de l'objet hiérarchique
     *
     * @access public
     * @var mixed
     */
    public $aParams = array();
    
    /**
     * Tableau associatif entre les id de noeud dans $aParams  ("id") et leur rang au
     * sein du tableau $aNodes ("indice") => array("id"=>"indice")
     *
     * @access public
     * @var mixed
     */
    public $aPosition = array();
    
    /**
     * Indique si la hiérarchie a été créée
     *
     * @access protected
     * @var bool
     */
    protected $_hierarchyGenerated = false;
    
    /**
     * Nombre de noeuds de l'objet hiérarchique
     *
     * @access protected
     * @var int
     */
    protected $_countNodes;
    
    /**
     * Niveau maximal atteind par l'objet hiérarchique
     *
     * @access protected
     * @var int
     */
    protected $_countLevels = 0;
    
    /**
     * Tableau Récapitulatif des ordres des ID de noeud après le tri effectué dans
     * setOrder
     *
     * @access protected
     * @var mixed
     */
    protected $_aOrder = array();
    
    /**
     * Constructeur
     *
     * @access public
     * @param string $id Identifiant de l'objet hiérarchique
     * @param string $idName Nom de la propriété ou du champ jouant le rôle d'ID
     * @param string $pidName Nom de la propriété ou du champ jouant le rôle d'ID
     * PERE
     * @return Hierarchy
     */
    public function Pelican_Hierarchy($id, $idName, $pidName) {
        $this->id = $id;
        $this->idName = $idName;
        $this->pidName = $pidName;
        $this->aPosition[-0] = 0;
    }
    
    /**
     * Ajout d'une entrée dans l'objet hiérarchique
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
     * @access public
     * @param array $aTab Tableau de données
     * @return void
     */
    public function addNode($aTab) {
        if ($aTab) {
            $id = $aTab[$this->idName];
            if (!isset($aTab[$this->pidName])) {
                $aTab[$this->pidName] = "0";
            }

/*
            if($aTab['current_version']==null&&$aTab['status']!=1){
                $aTab['is_never_published'] = true;
                
            }
            if(isset($aTab['start_date'])&&isset($aTab['end_date'])){

                //$today = 
   

                $start = new DateTime($aTab['start_date']);
                $end = new DateTime($aTab['end_date']);
                $today = new DateTime();
                if($today >= $start && $today <= $end){
                    $aTab['valid_publication_date'] = true;
                }else{
                    $aTab['valid_publication_date'] = false;
                }
                
             
            }*/
            $aTab['path'] = $aTab['path'];
            $pid = $aTab[$this->pidName];
            // création du Node
            $indice = count($this->aNodes) + 1;
            $this->aNodes[$indice] = (object)$aTab;
            // définition de paramètres de positionnement
            $this->aParams[$id]["record"] = $indice;
            $this->aParams[$id]["id"] = $id;
            $this->aParams[$id]["pid"] = $pid;
            $this->aParams[$pid]["child"][$id] = $id;
            $this->aPosition[$id] = $this->aParams[$id]["record"];
            $this->_countNodes++;
            return $this->aNodes[$indice];
        }
        return false;
    }
    
    /**
     * Ajout de plusieurs entrées dans l'objet hiérarchique
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
     * @access public
     * @param array $aTab Tableau de données
     * @return void
     */
    public function addTabNode($aTab) {
        if ($aTab) {
            foreach($aTab as $ligne) {
                $this->addNode($ligne);
            }
        }
    }
    
    /**
     * Création récursive des propriétés "path" (id dans l'ordre hiérarchique des
     * parents) et "child" (id des enfants direct) du noeud
     *
     * @access public
     * @param string $id Identifiant du noeud
     * @param int $level (option) Niveau du noeud
     * @return void
     */
    public function putPath($id, $level = 1) {
        /**premiers niveaux */
        $this->aParams[$id]["level"] = $level;
        if (isset($this->aParams[$id]["record"])) {
            if ($this->aNodes[$this->aParams[$id]["record"]]) {
                $this->aNodes[$this->aParams[$id]["record"]]->level = $level;
            }
        }
        $this->aParams[$id]["path"][] = $id;
        $level++;
        if (isset($this->aParams[$id]["child"])) {
            foreach($this->aParams[$id]["child"] as $child) {
                $this->aParams[$child]["path"] = $this->aParams[$id]["path"];
                $this->putPath($child, $level);
            }
        }
        $this->_countLevels = ($this->_countLevels > $level ? $this->_countLevels : $level);
    }
    
    /**
     * Création de la hiérarchie : initialisation de la propriété $hierarchy qui
     * indique que la hiérarchie a été "calculée"
     *
     * @access public
     * @return void
     */
    public function getHierarchy() {
        $this->putPath("0");
        $this->_hierarchyGenerated = true;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @return __TYPE__
     */
    public function getNodeTab($id) {
        $node = $this->aNodes[$this->aParams[$id]["record"]];
        if ($node) {
            while (list($key, $val) = each($node)) {
                $aReturn[$key] = $val;
            }
        }
        return $aReturn;
    }
    
    /**
     * Retourne la valeur de la propriété demandée pour un ID donné
     *
     * @access public
     * @param string $id ID de l'élément
     * @param string $property Nom de la propriété
     * @return mixed
     */
    public function getNodeProperty($id, $property) {
        return $this->aNodes[$this->aParams[$id]["record"]]->$property;
    }
    
    /**
     * Attribution manuelle d'une propriété à un noeud
     *
     * @access public
     * @param string $id ID de l'élément
     * @param string $property Nom de la propriété
     * @param mixed $value (option) Valeur de la propriété
     * @return void
     */
    public function setNodeProperty($id, $property, $value = "") {
        if ($this->aNodes[$this->aParams[$id]["record"]]) {
            $this->aNodes[$this->aParams[$id]["record"]]->addProperty($property, $value);
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $id Unknown
     * @param string $property Unknown
     * @param string $separator (option) Unknown
     * @param string $class (option) Unknown
     * @param bool $bUseUrl (option) Unknown
     * @return string
     */
    public function getNodePath($id, $property, $separator = " > ", $class = "", $bUseUrl = true) {
        if ($this->aParams[$id]["path"]) {
            $path = "";
            $url = "";
            foreach($this->aParams[$id]["path"] as $path_id) {
                if ($path_id != "0") {
                    if ($bUseUrl) {
                        $url = $this->getNodeProperty($path_id, "url");
                    }
                    $path.= ($path ? " " . trim($separator) . " " : "");
                    if ($url && $path_id != $id) $path.= "<a href=\"" . $url . "\" " . ($class ? "class=\"" . $class . "\"" : "") . ">";
                    $path.= $this->getNodeProperty($path_id, $property);
                    if ($url && $path_id != $id) $path.= "</a>";
                }
            }
        } else {
            $path.= $this->getNodeProperty($id, $property);
        }
        return $path;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $property Unknown
     * @param string $type (option) Unknown
     * @param string $limitedId (option) Unknown
     * @param constante $sort_flags (option) Constante de tri exploitée dans
     * orderChild : SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     * @return void
     */
    public function setOrder($property, $type = "ASC", $limitedId = "", $sort_flags = "") {
        $this->orderChild($property, $type, "0", $sort_flags);
        $this->getOrder();
        if ($limitedId) {
            $this->limitedPath($limitedId);
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @return __TYPE__
     */
    public function setSelected($id) {
        $this->selected = $id;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @return __TYPE__
     */
    public function limitedPath($id) {
        $level1 = $this->aParams[$this->aNodes[1]->id]["child"];
        $rootId = $this->aParams[$id]["path"][2];
        $this->aNodes[1]->limit = true;
        foreach($level1 as $child) {
            $this->aNodes[$this->aParams[$child]["record"]]->limit = true;
        }
        $this->markLimitedChildNodes($rootId);
        $i = 0;
        foreach($this->aParams as $value) {
            if (!$this->aNodes[$this->aParams[$value["id"]]["record"]]->limit) {
                unset($this->aNodes[$this->aParams[$value["id"]]["record"]]);
                unset($this->aParams[$value["id"]]);
                unset($this->aPosition[$value["id"]]);
            } else {
                if ($this->aParams[$value["id"]]["level"] == 3) {
                    $i++;
                    if ($value["id"] != $rootId && $this->aParams[$value["id"]]["child"]) {
                        $this->aNodes[$this->aParams[$value["id"]]["record"]]->url = "javascript:goLevel(" . $value["id"] . ")";
                        $this->aNodes[$this->aParams[$value["id"]]["record"]]->icon = Pelican::$config["SKIN_PATH"] . "/images/folder_child.gif";
                    }
                }
            }
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $id __DESC__
     * @return __TYPE__
     */
    public function markLimitedChildNodes($id) {
        $this->aNodes[$this->aParams[$id]["record"]]->limit = true;
        if ($this->aParams[$id]["child"]) {
            foreach($this->aParams[$id]["child"] as $child) {
                $this->markLimitedChildNodes($child);
            }
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $property Unknown
     * @param __TYPE__ $type (option) Unknown
     * @param __TYPE__ $pid (option) Unknown
     * @param constante $sort_flags (option) Constante de tri exploitée dans
     * orderChild : SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     * @return void
     */
    public function orderChild($property, $type = "ASC", $pid = "0", $sort_flags = "") {
        $this->verifyHierarchy();
        // liste des enfants
        if (isset($this->aParams[$pid]["child"])) {
            foreach($this->aParams[$pid]["child"] as $child) {
                $temp[$this->getNodeProperty($child, $property) ] = $child;
                $this->orderChild($property, $type, $child, $sort_flags);
            }
        }
        if (isset($temp)) {
            switch ($type) {
                case "DESC": {
                            if ($sort_flags != "") {
                                rsort($temp, $sort_flags);
                            } else {
                                rsort($temp);
                            }
                        break;
                    }
                case "ASC": {
                        if ($sort_flags != "") {
                            ksort($temp, $sort_flags);
                        } else {
                            ksort($temp);
                        }
                        break;
                    }
                }
                foreach($temp as $child) {
                    $new[] = $child;
                }
                $this->aParams[$pid]["child"] = $new;
            }
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $id (option) Unknown
         * @return void
         */
        public function getOrder($id = "0") {
            $this->verifyHierarchy();
            if ($id == "0") {
                $this->sauve = $this->aNodes;
                $this->aNodes = array();
            } else {
                $this->_aOrder[] = $id;
                if ($this->sauve) {
                    $indice = count($this->aNodes) + 1;
                    $this->aNodes[$indice] = $this->sauve[$this->aParams[$id]["record"]];
                    $this->aParams[$id]["record"] = $indice;
                    $this->aPosition[$id] = $this->aParams[$id]["record"];
                    $this->aLevels[$this->aParams[$id]["level"]][$indice] = $id;
                }
            }
            if (isset($this->aParams[$id]["child"])) {
                foreach($this->aParams[$id]["child"] as $child) {
                    $this->getOrder($child);
                }
            }
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @return void
         */
        public function verifyHierarchy() {
            if (!$this->_hierarchyGenerated) {
                $this->getHierarchy();
            }
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $property Unknown
         * @param __TYPE__ $value Unknown
         * @return void
         */
        public function addProperty($property, $value) {
            $this->$property = $value;
        }
    }
?>