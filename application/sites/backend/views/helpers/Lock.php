<?php

/**
 * Classe de gestion du Lock
 *
 *
 * @package Pelican_BackOffice
 * @subpackage Lib
 * @author Nizar LAOUINI <nizar.laouini@businessdecision.com>
 * @since 19/09/2007 V 0.1
 * @update 30/09/2008 Raphael Carles <raphael.carles@businessdecision.com>
 */

class Backoffice_Lock_Helper
{

    public $bIsLocked = false; //flag du lock

    
    public $sessionLocker; //session du proprietaire du lock

    
    public $nameLocker; //proprietaire du lock

    
    public $iDuree = 60; //durée du lock en secondes

    
    public $iDureeObselete = 600; //durée en secondes à partir de laquelle le lock est obselete (à supprimer)

    
    public $htmlOutput; //sortie Pelican_Html resultante du lock

    
    /**
     * Constructeur
     *
     * @return Pelican_Index_Backoffice_Lock
     */
    public function __construct()
    {
        if (!$this->id) {
            if (valueExists($_GET, "id")) {
                $this->id = $_GET["id"];
            }
        }
        if (!$this->iTemplateId) {
            if (valueExists($_GET, "tid")) {
                $this->iTemplateId = $_GET["tid"];
            }
        }
        if ($this->id && $this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
            $this->getLocked();
        }
    }

    /**
     * Enter description here...
     *
     * @param boolean $bFlag
     */
    protected function setLocked($bFlag = false)
    {
        $this->bIsLocked = $bFlag;
        $html = "";
        $ajaxJsCall = Pelican_Factory::staticCall(Pelican::getAjaxEngine(), 'getJsCall');
        
        if ($this->bIsLocked) {
            //cas du lock
            $this->bReadO = true;
            //affichage du message
            $this->htmlOutput = Pelican_Html::div(array(
                "class" => "erreur"
            ), Pelican_Html::b(Pelican_Html::br() . t('LOCKED_RECORD') . $this->nameLocker . Pelican_Html::br() . Pelican_Html::br())) . Pelican_Html::br();
            //cacher le bouton supprimer
            $this->htmlOutput .= Pelican_Html::script("
					function releaseLock() {
					" . $ajaxJsCall . "(\"release_lock\",\"" . $this->iTemplateId . "\",\"" . $this->id . "\");
					}
					/* setInterval('releaseLock()'," . ($this->iDuree * 1000) . "); */");
            $this->bNoDelete = true;
        } elseif ($this->bReadO) {
            //cas de non lock et supression
            $this->bNoDelete = false;
            $this->bReadO = true; //
        } else {
            //cas de non lock et edition $this->bReadO = false;
            //script de maj automatique du lock
            $this->htmlOutput .= Pelican_Html::script("
					function updateLock() {
					" . $ajaxJsCall . "(\"update_lock\",\"" . $this->iDuree . "\",\"" . $this->iTemplateId . "\",\"" . $this->id . "\");
					}
					setInterval('updateLock()'," . ($this->iDuree * 1000) . ");");
        }
    }

    /**
     * fonction qui vérifie s'il y a un lock sur la template en cours...
     *
     * @return unknown
     */
    protected function getLocked()
    {
        $oConnection = Pelican_Db::getInstance();
        
        if ($this->iTemplateId) {
            $aBind = array(
                ":TIME_LIMIT" => time(), 
                ":TEMPLATE_ID" => $this->iTemplateId, 
                ":SUBJECT_ID" => $oConnection->strToBind($this->id)
            );
            
            $result = $oConnection->queryRow("SELECT SESSION_ID, USER_NAME
					FROM " . Pelican::$config['FW_PREFIXE_TABLE'] . "lock
					WHERE TIME_LIMIT > :TIME_LIMIT
					AND TEMPLATE_ID = :TEMPLATE_ID
					AND SUBJECT_ID = :SUBJECT_ID", $aBind);
            
            $Locker = $result['SESSION_ID'];
            
            if ($Locker) { //template déja lockée
                $this->nameLocker = ($result['USER_NAME'] ? $result['USER_NAME'] : $result['SESSION_ID']);
                if ($Locker == session_id()) { //cas ou il s'agit du l'utilisateur ayant le lock
                    $this->updateLock($this->iDuree, $this->iTemplateId, $this->id); //maj duree du lock
                    $this->setLocked(false);
                } else {
                    $this->setLocked(true); //autre utilisateur
                }
            } else { //template non lockée
                $this->addLock();
                $this->setLocked(false);
            }
        }
    }

    /**
     * fonction qui établie le lock
     *
     */
    protected function addLock()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        if ($this->iTemplateId) {
            Pelican_Db::$values["SESSION_ID"] = session_id();
            Pelican_Db::$values["USER_ID"] = $_SESSION[APP]["user"]["id"];
            Pelican_Db::$values["USER_NAME"] = $_SESSION[APP]["user"]["name"];
            Pelican_Db::$values["TIME_LIMIT"] = time() + $this->iDuree;
            Pelican_Db::$values["TEMPLATE_ID"] = $this->iTemplateId;
            Pelican_Db::$values["SUBJECT_ID"] = $this->id;
            
            $aBind[":TEMPLATE_ID"] = $this->iTemplateId;
            $aBind[":SUBJECT_ID"] = $oConnection->strToBind($this->id);
            
            $this->purgeLock($this->iDureeObselete, $this->iTemplateId, $this->id);
            
            $oConnection->insertquery(Pelican::$config['FW_PREFIXE_TABLE'] . "lock");
        }
    }

    /**
     * fonction qui met à jour le lock
     *
     */
    public static function updateLock($iDuree, $iTemplateId, $id)
    {
        $oConnection = Pelican_Db::getInstance();
        
        if ($iTemplateId) {
            $aBind = array(
                ":SESSION_ID" => $oConnection->strToBind(session_id()), 
                ":USER_ID" => $_SESSION[APP]["user"]["id"], 
                ":USER_NAME" => $oConnection->strToBind($_SESSION[APP]["user"]["name"]), 
                ":TIME_LIMIT" => $oConnection->strToBind(time() + $iDuree), 
                ":TIME_LIMIT_OLD" => $oConnection->strToBind(time()), 
                ":TEMPLATE_ID" => $iTemplateId, 
                ":SUBJECT_ID" => $oConnection->strToBind($id)
            );
            
            $oConnection->query("UPDATE " . Pelican::$config['FW_PREFIXE_TABLE'] . "lock
					SET TIME_LIMIT=:TIME_LIMIT
					WHERE  SESSION_ID = :SESSION_ID
					AND    TEMPLATE_ID = :TEMPLATE_ID
					AND    SUBJECT_ID = :SUBJECT_ID", $aBind);
        
     //       AND    TIME_LIMIT >= :TIME_LIMIT_OLD
        }
    }

    public static function isLocked($iTemplateId, $id)
    {
        $oConnection = Pelican_Db::getInstance();
        
        if ($iTemplateId) {
            $aBind = array(
                ":SESSION_ID" => $oConnection->strToBind(session_id()), 
                ":USER_ID" => $_SESSION[APP]["user"]["id"], 
                ":USER_NAME" => $oConnection->strToBind($_SESSION[APP]["user"]["name"]), 
                ":TIME_LIMIT" => $oConnection->strToBind(time() + $iDuree), 
                ":TIME_LIMIT_OLD" => $oConnection->strToBind(time()), 
                ":TEMPLATE_ID" => $iTemplateId
            );
            
            $oConnection->query("select count(1) from " . Pelican::$config['FW_PREFIXE_TABLE'] . "lock
					WHERE TEMPLATE_ID=:TEMPLATE_ID
					and SUBJECT_ID=:SUBJECT_ID
					and TIME_LIMIT < :TIME_LIMIT)", $aBind);
        }
    }

    /**
     * fonction qui purge les lock obseletes
     *
     */
    protected function purgeLock($iDureeObselete, $iTemplateId, $id)
    {
        $oConnection = Pelican_Db::getInstance();
        
        if ($iTemplateId) {
            $aBind = array(
                ":TIME_LIMIT" => time() - $iDureeObselete, 
                ":TEMPLATE_ID" => $iTemplateId, 
                ":SUBJECT_ID" => $oConnection->strToBind($id)
            );
            
            $oConnection->query("DELETE from " . Pelican::$config['FW_PREFIXE_TABLE'] . "lock WHERE
					(TEMPLATE_ID=:TEMPLATE_ID and SUBJECT_ID=:SUBJECT_ID)
					OR
					(TIME_LIMIT < :TIME_LIMIT)", $aBind);
        }
    }

    /**
     * Surcharge de la fonction du parent
     *
     * @param string $title Titre principal
     * @param string $subtitle Sous-titre
     * @param string $class Classe CSS du titre
     * @return Code Pelican_Html pour afficher le titre (javascript) + code Pelican_Html lock
     */
    protected function render()
    {
        
        $return = $this->htmlOutput; //construit dans setLocked
        return $return;
    }

    /**
     * surcharge de la fonction de la classe mere
     * S'il ya pas de lock : Affiche un message interdisant la suppresion du contenu affich� s'il est associ� à d'autres contenus
     *
     * @return string Code Pelican_Html du message d'interdiction
     */
    protected function getUsage()
    {
        $return = "";
        /**
         * * Contrôle d'utilisation du CONTENT_ID pour empêcher la supression en cas du non lock
         */
        if (!$this->bIsLocked) {
            return parent::getUsage();
        } else {
            return $return;
        }
    
    }

}

/*
	CREATE TABLE `men_lock` (
	`SESSION_ID` varchar(100) collate latin1_bin NOT NULL,
	`TIME_LIMIT` varchar(100) collate latin1_bin NOT NULL,
	`TEMPLATE_ID` int(11) NOT NULL,
	`SUBJECT_ID` varchar(100) collate latin1_bin NOT NULL,
	`USER_ID` varchar(100) collate latin1_bin NOT NULL,
	`USER_NAME` varchar(255) collate latin1_bin NOT NULL,
	PRIMARY KEY  (`SESSION_ID`,`TIME_LIMIT`,`TEMPLATE_ID`,`SUBJECT_ID`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;
	*/
?>