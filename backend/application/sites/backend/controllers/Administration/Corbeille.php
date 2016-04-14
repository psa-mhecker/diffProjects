<?php
require_once Pelican::$config["APPLICATION_CONTROLLERS"]."/Administration/Directory.php";

/**
 * Formulaire de gestion de la corbeille.
 *
 * @author Maillot Sébastien <sebastien.maillot@businessdecision.com>
 *
 * @since 26/06/2013
 */
class Administration_Corbeille_Controller extends Pelican_Controller_Back
{

    protected $administration = false; //true
    protected $form_name = "corbeille";
    protected $field_id = "CORBEILLE_ID";
    protected $defaultOrder = "CORBEILLE_LABEL";

    protected function setListModel()
    {
        
    }

    protected function setEditModel()
    {
        
    }

    /**
     *
     */
    public function listAction()
    {
        $this->editAction();
    }

    /**
     *
     */
    public function editAction()
    {
        $script = "<script type='text/javascript'>
            function confirmeAction(action){

            }
            jQuery(document).ready(function() {
                $('#DELETE_TRASH').click(function() {
                    var libMessage = '".t('CONFIRM_DELETE', 'js')."';
                    $('#form_action').val('delete');
                    if(confirm(libMessage)){
                        $('#fForm').submit();
                    }else{
                        return false;
                    }
                });
                $('#RESTORE_TRASH').click(function() {
                    var libMessage = '".t('CONFIRM_RESTORE', 'js')."';
                    $('#form_action').val('restore');
                    if(confirm(libMessage)){
                         $('#fForm').submit();
                    }else{
                        return false;
                    }
                });
            });
        </script>";
        $_SESSION[APP]["form_profile"] = $_SERVER["REQUEST_URI"];
        parent::editAction();

        //Création du formulaire
        $this->startStandardForm();
        $form = $this->oForm->open("", "post", "fForm");
        $this->form_action = "save";

        $form .= $this->oForm->createHidden("PROFILE_ADMIN", $this->values["PROFILE_ADMIN"]);

        $this->values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);

        $this->values['STATE_ID'] = Pelican::$config["CORBEILLE_STATE"];

        $form .= $this->oForm->createSubFormHmvc("corbeille", t('TRASH_PAGE'), array(
            'path' => Pelican::$config["APPLICATION_CONTROLLERS"].'/Administration/Directory.php',
            'class' => 'Administration_Directory_Controller',
            'method' => 'corbeille',
            ), $this->values, $this->readO, false, "subformjs", "formsub formsub2");

        $form .= $this->oForm->createHidden($this->field_id, $this->id);

        $form .= $this->oForm->createButton("DELETE_TRASH", t('DEL_TRASH'));
        $form .= $this->oForm->createButton("RESTORE_TRASH", t('REST_TRASH'));

        $form .= $this->stopStandardForm();

        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop
        //suppression des boutons PHP factory
        $this->aButton["add"] = "";
        $this->aButton["save"] = "";
        $this->aButton["back"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($script.$form);
    }

    /**
     * Retire une page ou un contenu de la corbeille.
     */
    public function restoreAction()
    {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["PAGE_ID"] && is_array(Pelican_Db::$values["PAGE_ID"])) {


            $realPagesID = [];

            foreach (Pelican_Db::$values["PAGE_ID"] as $page_Id) {
                $isContent = false;

                if (strpos($page_Id, 'cid-') === 0) {
                    $isContent = true;
                    $contentId = str_replace('cid-', '', $page_Id);
                    $this->updateContentStateAction(null, Pelican::$config["DEFAULT_STATE"], $contentId);
                }
                if (!$isContent) {
                    $realPagesID[] = $page_Id;
                }
            }

            //get restaured pages orders

            $sSql = "SELECT PAGE_ID, PAGE_ORDER,PAGE_PARENT_ID
                             FROM #pref#_page
                             WHERE PAGE_ID IN (:PAGE_IDS)
                             AND SITE_ID = :SITE_ID
                             AND LANGUE_ID= :LANGUE_ID
                             ";
            $aBindAllPages = array(
                ':PAGE_IDS' => implode(',', array_map(array($oConnection, 'strToBind'), $realPagesID)),
                ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
                ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
            );

            $aPages = $oConnection->queryTab($sSql, $aBindAllPages);
            foreach ($aPages as $aOnePage) {
                $sReorderNodesSql = 'UPDATE #pref#_page p
                                             LEFT JOIN #pref#_page_version pv
                                             ON (
                                                p.PAGE_ID = pv.PAGE_ID
                                                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                                                AND pv.LANGUE_ID = p.langue_id
                                                )
                                             SET p.PAGE_ORDER = PAGE_ORDER+1
                                             WHERE p.PAGE_ORDER >=:PAGE_ORDER
                                             AND p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                                             AND (pv.STATE_ID <> 5)';

                    $aBindReorder = array(
                        ':PAGE_ID' => $aOnePage['PAGE_ID'],
                        ':PAGE_ORDER' => $aOnePage['PAGE_ORDER'],
                        ':PAGE_PARENT_ID' => $aOnePage['PAGE_PARENT_ID'],
                    );
                    
                    $oConnection->query($sReorderNodesSql, $aBindReorder);
                    
                if (self::isRestorable($aOnePage['PAGE_ID'])) {
                     Ndp_Page::_updateChildPage($aOnePage['PAGE_ID'],Pelican::$config["DEFAULT_STATE"]);
                }
            }
        }
    }

    /**
     * Retire une page de la corbeille à partir d'un parent (de restoreAction).
     *
     * @param int $pageID  id de la page à restaurer
     * @param int $stateId changement d'etat de page(corbeille => à publier)
     */
    public function updateChildPageAction($pageID, $stateId)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":PAGE_ID"] = $pageID;
        $sqlMaxVersion = "select DISTINCT PAGE_ID as PAGE_ID from #pref#_page p
                            where
                                p.PAGE_PARENT_ID =:PAGE_ID";
        $listPage = $oConnection->queryRow($sqlMaxVersion, $aBind);

        // Mise à jour des enfants
        if (is_array($listPage) && count($listPage)>0) {
            $save = Pelican_Db::$values;
            Pelican_Db::$values["PAGE_ID"]=[];
            foreach( $listPage as $pid){
                Pelican_Db::$values["PAGE_ID"][] = $pid["PAGE_ID"];    
            }
            $this->restoreAction();
            
            Pelican_Db::$values = $save;
            
        }
    }

    /**
     * Retire une page de la corbeille à partir d'un parent (de restoreAction).
     *
     * @param int $pageId    id de la page ou des contenus sont à restaurer
     * @param int $stateId   changement d'etat de contenu(corbeille => à publier)
     * @param int $contentId ID du contenu à restaurer
     */
    public function updateContentStateAction($pageId = null, $stateId, $contentId = null)
    {
        $oConnection = Pelican_Db::getInstance();
        if ($pageId !== null) {
            $aBind[":PAGE_ID"] = $pageId;
            $aBind[":STATE_ID"] = $stateId;
            $sqlUpdate = "
				UPDATE #pref#_content_version
                SET STATE_ID = :STATE_ID
				WHERE PAGE_ID = :PAGE_ID";
            $oConnection->query($sqlUpdate, $aBind);
        } elseif ($contentId !== null) {
            $aBind[":CONTENT_ID"] = $contentId;
            $aBind[":STATE_ID"] = $stateId;

            $sqlUpdate = "
				UPDATE #pref#_content_version
                SET STATE_ID = :STATE_ID
				WHERE CONTENT_ID = :CONTENT_ID";
            $oConnection->query($sqlUpdate, $aBind);
        }
    }

    /**
     * Supprime définitivement une page.
     */
    public function deleteAction()
    {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["PAGE_ID"] && is_array(Pelican_Db::$values["PAGE_ID"])) {
            foreach (Pelican_Db::$values["PAGE_ID"] as $pageID) {

                // Les contenus sont préfixés par cid-
                $contentId = str_replace('cid-', '', $pageID);
                // Traitement d'un contenu
                if ($contentId != $pageID) {
                    $this->deleteContent(null, $contentId);
                }
                // Traitement d'une page
                else {
                    //supprime les enfants
                    $this->deleteChild($pageID);
                    //supprime les contenus de la page racine
                    $this->deleteContent($pageID);
                    //suppression de la page racine
                    $aBind[":PAGE_ID"] = $pageID;
                    // suppression de la page racine
                    if (is_array(Pelican::$config["PAGE_TABLE"])) {
                        foreach (Pelican::$config["PAGE_TABLE"] as $table) {
                            $sqlDeletePage1 = "
                                DELETE FROM ".$table."
                                WHERE PAGE_ID = :PAGE_ID";
                            $oConnection->query($sqlDeletePage1, $aBind);
                        }
                    }
                }
            }
        }
        Pelican_Cache::clean("Template/Page", array($_SESSION[APP]['SITE_ID']));
    }

    /**
     * Supprime définitivement une page à partir d'un parent (de deleteAction).
     *
     * @param int $pageID id de la page à supprimer
     */
    public function deleteChild($pageID)
    {
        $oConnection = Pelican_Db::getInstance();
        $noMoreChild = false;
        $aBind[':PAGE_ID'] = $pageID;
        $sqlMaxVersion = "
            select DISTINCT PAGE_ID as PAGE_ID
            from #pref#_page p
            where CONCAT('#', p.PAGE_PATH, '#') LIKE '%#:PAGE_ID#%'";
        $listPage = $oConnection->queryTab($sqlMaxVersion, $aBind);
        // suppression des enfants
        if (is_array($listPage)) {
            foreach ($listPage as $page) {
                $aBind[':PAGE_ID'] = $page['PAGE_ID'];
                if (is_array(Pelican::$config['PAGE_TABLE'])) {
                    foreach (Pelican::$config['PAGE_TABLE'] as $table) {
                        $sqlDeletePage1 = "delete from ".$table." where PAGE_ID = :PAGE_ID";
                        $oConnection->query($sqlDeletePage1, $aBind);
                    }
                }
                $this->deleteContent($page['PAGE_ID']);
            }
            $noMoreChild = true;
        }

        return $noMoreChild;
    }

    /**
     * Supprime definitivement un contenu.
     *
     * @param int $pageId    id de la page où des contenus sont à supprimer
     * @param int $contentId id duc contenu à supprimer
     */
    public function deleteContent($pageId = null, $contentId = null)
    {
        $oConnection = Pelican_Db::getInstance();
        //suppression des contenus
        if ($pageId != null) {
            if (is_array(Pelican::$config["CONTENT_TABLE"])) {
                foreach (Pelican::$config["CONTENT_TABLE"] as $table) {
                    $aBind[":PAGE_ID"] = $pageId;
                    $sqlDeleteContent1 = "
						DELETE FROM ".$table."
						WHERE CONTENT_ID IN (
							SELECT DISTINCT (CONTENT_ID)
							FROM (
								SELECT CONTENT_ID
								FROM #pref#_content_version
								WHERE PAGE_ID = :PAGE_ID
							) AS tmp
						)";
                    $oConnection->query($sqlDeleteContent1, $aBind);
                }
            }
        } elseif ($contentId != null) {
            if (is_array(Pelican::$config["CONTENT_TABLE"])) {
                foreach (Pelican::$config["CONTENT_TABLE"] as $table) {
                    $aBind[":CONTENT_ID"] = $contentId;
                    $sqlDeleteContent1 = "
						DELETE FROM ".$table."
						WHERE CONTENT_ID = :CONTENT_ID";
                    $oConnection->query($sqlDeleteContent1, $aBind);
                }
            }
        }
    }

    /**
     * Test si la page est récuperable
     * càd, si elle n'utilise pas un gabarit unique deja utilisé.
     */
    private function isRestorable($pageId)
    {
        $oConnection = Pelican_Db::getInstance();
        // Recuperation
        $aBind[":PAGE_ID"] = $pageId;
        $sSQL = "
			SELECT pv.TEMPLATE_PAGE_ID
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
			ON (p.PAGE_ID = pv.PAGE_ID
				AND p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
				AND p.LANGUE_ID = pv.LANGUE_ID)
			WHERE p.PAGE_ID = :PAGE_ID";
        $iTemplatePageId = $oConnection->queryItem($sSQL, $aBind);
        if ($iTemplatePageId) {
            $aBind[":TEMPLATE_PAGE_ID"] = $iTemplatePageId;
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
            $aBind[":CORBEILLE_STATE"] = Pelican::$config["CORBEILLE_STATE"];
            $sSQL = "
				SELECT 1
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv
					ON (p.PAGE_ID = pv.PAGE_ID
						AND p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
						AND p.LANGUE_ID=pv.LANGUE_ID)
				INNER JOIN #pref#_template_page tp
					ON (pv.TEMPLATE_PAGE_ID = tp.TEMPLATE_PAGE_ID)
				INNER JOIN #pref#_page_type pt
					ON (tp.PAGE_TYPE_ID = pt.PAGE_TYPE_ID)
				WHERE p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND PAGE_TYPE_ONE_USE = 1
				AND pv.STATE_ID <> :CORBEILLE_STATE
				AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID";
            $bTemplatePageUsed = $oConnection->queryItem($sSQL, $aBind);
            if ($bTemplatePageUsed) {
                return false;
            }
        }

        return true;
    }
}
