<?php
/**
 * Gestion des couches de présentation des objets hiérarchiques.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @since 15/12/2003
 * @link http://www.interakting.com
 */
define('DTREE_LIMIT', 20000);

/*
 * Classe de gestion des objets hiérarchiques
 */
pelican_import('Hierarchy');

/**
 * Cette classe permet d'appliquer des couches de présentation à un objet issu de
 * la classe hiérarchique
 * Cela peut s'appliquer à une présentation indentés au seins de listes
 * déroulantes, à l'afiichage d'une arborescence.
 *
 * à l'affichage d'un menu de navigation ou d'un chemin de fer.
 * la couche d'abstraction de l'objet hiérarchique permet de passer d'une couche
 * de présentation à une autre ou
 * de faire varier les données utilisées par la couche de présentation
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/12/2003
 *        @update 16/10/2008 Ajout de xloadtree
 */
class Ndp_Hierarchy_Tree extends Pelican_Hierarchy_Tree
{

    /**
     * L'utilisateur a t il le droit de mofifier les pages
     *
     * @var bool
     */
    protected $readOnly;

    /**
     * la date courante
     *
     * @var DateTime
     */
    protected $now;

    /**
     * field name used for start publication date
     * @var string
     */
    protected $startName;

    /**
     * field name used for end publication date
     * @var string
     */
    protected $endName;

    /**
     * field name used for start publication date of the previous published verion
     * @var string
     */
    protected $prevStartName;

    /**
     * field name used for end publication date of the previous published verion
     * @var string
     */
    protected $prevEndName;


    /**
     * Constructeur.
     *
     * @access public
     *
     * @param string $id      Identifiant de l'objet hiérarchique
     * @param string $idName  Nom de la propriété ou du champ jouant le rôle d'ID
     * @param string $pidName Nom de la propriété ou du champ jouant le rôle d'ID PERE
     *
     */
    public function __construct($id, $idName, $pidName)
    {
        $this->id = $id;
        $this->idName = $idName;
        $this->pidName = $pidName;
        $this->aPosition[-0] = 0;
        $this->now = new \DateTime();
        $this->readOnly = false; // @todo find witch type of account has readonly access to pages
        $this->startName = sprintf('start_%s', $_SESSION[APP]['LANGUE_ID']);
        $this->endName = sprintf('end_%s', $_SESSION[APP]['LANGUE_ID']);
        $this->stateIdName = sprintf('state_%s', $_SESSION[APP]['LANGUE_ID']);
        $this->prevStartName = sprintf('start_prev_%s', $_SESSION[APP]['LANGUE_ID']);
        $this->prevEndName = sprintf('end_prev_%s', $_SESSION[APP]['LANGUE_ID']);
    }

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
                if ($children) {
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
            $startTime = DateTime::createFromFormat('d/m/Y H:i', $node->$startName);
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
     * @return string
     *
     *
     */
    protected function getClass($node)
    {
        // case current version = 1
        $class = 'grey';
        $startName = $this->startName;
        $endName = $this->endName;
        // Draft style grey
        // to publish style grey
        // published
        if ($node->{$this->stateIdName} == Pelican::$config["PUBLISH_STATE"]) {
            // state offline
            $class = 'red';
        }
        // case version > 1
        if ($node->current_version > 1) {
            $class = 'red';
            //Draft Offline
            if ($node->{$this->stateIdName} != Pelican::$config["PUBLISH_STATE"]) {
                $startName = $this->prevStartName;
                $endName = $this->prevEndName;
            }

        }
        if ($node->status == 1) {
            // online
            $class =  $this->getPublicationClass($node, $startName, $endName);
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
     * @access public
     *
     * @param array $aTab Tableau de données
     * @return
     */
    public function addNode($aTab)
    {
        if ($aTab) {
            $id = $aTab[$this->idName];
            if (!isset($aTab[$this->pidName])) {
                $aTab[$this->pidName] = "0";
            }
            $aTab['lib'] = strip_tags(html_entity_decode($aTab['lib']));
            $pid = $aTab[$this->pidName];
            // création du Node
            $indice = count($this->aNodes) + 1;
            $node = (object) $aTab;
            $node->class = $this->getClass($node);
            $this->aNodes[$indice] = $node;
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

}
