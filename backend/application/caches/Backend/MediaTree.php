<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Arborescence de la mediathèque.
     *
     * retour :
     * - résultat de la requête
     * - objet hiérarchique
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 21/12/2004
     */
    class Backend_MediaTree extends Pelican_Cache
    {
        // Plus nécéssaire... on ne charge qu'un niveau ou deux en raison de l'utilisation de l'arborescence ExtJS
        public $isCached = false;
        public $duration = DAY;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            global $rubrique, $rootImage;

            $oConnection = Pelican_Db::getInstance();

            $site = $this->params[0];
            if ($this->params[1] == 1) {
                /* Initialisation de la requête pour remplir la variable globale $rubrique */
                $path = $this->params[2];
                $image = $this->params[3];
                $allowAdd = $this->params[4];
                $allowDel = $this->params[5];

                $strSQL = "select
					".Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"]." as \"id\",
					".Pelican::$config["FW_MEDIA_FIELD_FOLDER_PARENT"]." as \"pid\",
					".Pelican::$config["FW_MEDIA_FIELD_FOLDER_NAME"]." as \"lib\",
					".$oConnection->getConcatClause(array("LOWER(".Pelican::$config["FW_MEDIA_FIELD_FOLDER_NAME"].")", "'_'", Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"]))." as \"order\",
					".$oConnection->getConcatClause(array("'javascript:parent.goMedia('", Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"], "', '", $oConnection->getNVLClause(Pelican::$config["FW_MEDIA_FIELD_FOLDER_PARENT"], 1), "', ".$allowAdd.", ".$allowDel.", '", "''''", Pelican::$config["FW_MEDIA_FIELD_FOLDER_PATH"], "''''", "')'"))." as \"url\",
					".$oConnection->getCaseClause(Pelican::$config["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'".$image."'"), "'".Pelican::$config["SKIN_PATH"]."/images/folder.gif'")."  as \"icon\",
					".$oConnection->getCaseClause(Pelican::$config["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'".$image."'"), "'".Pelican::$config["SKIN_PATH"]."/images/folderOpen.gif'")."  as \"iconOpen\"
					from
					".Pelican::$config["FW_MEDIA_FOLDER_TABLE_NAME"];
                $strSQL .= " where SITE_ID = ".$site;
                if ($path) {
                    $strSQL .= "where ".Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"]." = ".$path;
                }

                $return = $oConnection->queryTab($strSQL);
            } else {
                $id = $this->params[2];
                $rootImage = $this->params[3];
                $complement = $this->params[4];
                $type = $this->params[5];
                $options = array();
                if ($this->params[6]) {
                    $options = $this->params[6];
                }

                $oTree = Pelican_Factory::getInstance('Hierarchy.Tree', "dtree".$id, "id", "pid");
                $oTree->addTabNode($rubrique);
                $oTree->setOrder("order", "ASC");
                if ($type == "xmltree") {
                    $oTree->rootParams = implode("\",\"", array("Media", "javascript:parent.resetPath(document);", "explorer", $rootImage, $rootImage));
                }
                $oTree->setTreeType($type, $complement, $options);
                $return = $oTree->getTree();
            }

            $this->value = $return;
        }
    }
