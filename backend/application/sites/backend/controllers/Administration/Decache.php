<?php
/**
 * Formulaire de demande de decache pays.
 *
 * @since 21/11/2014
 */
class Administration_Decache_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "decache";

    // Sélection des données de la liste de cache
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $stmt = "SELECT * FROM #pref#_decache_manager;";
        $this->listModel = $oConnection->queryTab($stmt);
    }

    // Page de lancement de clean cache pays
    public function listAction()
    {
        parent::listAction();
        $form = $this->startStandardForm();

        // Définition du form_action pour déclencher l'appel de saveAction au submit du formulaire
        $this->form_action = 'decache';

        // Titre de l'écran
        $this->title = $this->getTemplateTitle($this->getView()->getHead()->sTitle, t("DECACHE_BUTTON"));
        $this->assign('title', $this->title, false);

        // Affichage message d'information
        if (!empty($_SESSION[APP]['tmp_flash_message'])) {
            $messageClass = isset($_SESSION[APP]['tmp_flash_message']['type']) ? 'alert-'.$_SESSION[APP]['tmp_flash_message']['type'] : '';
            $form .= '<div class="alert '.$messageClass.'">'.htmlspecialchars($_SESSION[APP]['tmp_flash_message']['message']).'</div>';
            unset($_SESSION[APP]['tmp_flash_message']);
            unset($messageClass);
        }

        // Récupération de la liste des sites pays
        $oConnection = Pelican_Db::getInstance();
        $sitesStmt = "SELECT s.SITE_ID, s.SITE_LABEL, s.SITE_URL, s.SITE_TITLE FROM #pref#_site s";
        $sites = $oConnection->queryTab($sitesStmt);

        // Affichage formulaire de décache complet
        $sitesValues = array();
        foreach ($sites as $key => $val) {
            // Exclusion du site d'administration et du site master
            if (in_array($val['SITE_ID'], array(Pelican::$config['SITE_BO']))) {
                continue;
            }
            $sitesValues[$val['SITE_ID']] = $val['SITE_LABEL'];
        }
        $sitesComboHtml = $this->oForm->createComboFromList("DECACHE_SITE_ID", t("SITE"), $sitesValues, null, false, $this->readO, null, false, null, true, true);
        $buttonHtml = $this->oForm->createSubmit("FULL_DECACHE", t("DECACHE_BUTTON"));
        $form .= '<div>'.htmlspecialchars(t('SITE')).' : '.$sitesComboHtml.' '.$buttonHtml.'</div>';

        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    // Traitement d'une demande de decache pays
    public function saveAction()
    {
        // Vérification paramètres
        $decacheSiteId = isset(Pelican_Db::$values['DECACHE_SITE_ID']) ? Pelican_Db::$values['DECACHE_SITE_ID'] : null;
        if (empty($decacheSiteId)) {
            $_SESSION[APP]['tmp_flash_message'] = array('message' => t("DECACHE_MUST_SELECT_A_WEBSITE"), 'type' => 'danger');

            return;
        }

        // Vidage des caches
        $this->setListModel();
        $decacheMatrix = array();
        foreach ($this->listModel as $key => $val) {
            switch ($val['cache_type']) {
                // Cache spécifique à un pays (SITE_ID en paramètre)
                case 'par site':
                    $decacheMatrix[] = array(
                        'object' => $val['cache_object'],
                        'param'  => $decacheSiteId,
                        'order'  => empty($val['siteid_order']) ? 1 : $val['siteid_order'],
                    );
                    break;
                // Cache global (pas de SITE_ID en paramètre)
                case 'global':
                default:
                    Pelican_Cache::clean($val['cache_object']);
                    break;
            }
        }

        // Vidage des caches via extendedDelete
        if (!empty($decacheMatrix)) {
            Pelican_Cache::extendedDelete($decacheMatrix, Pelican::$config["CACHE_FW_ROOT"]);
        }

        // Mémorisation du message à afficher après le nettoyage du cache
        $_SESSION[APP]['tmp_flash_message'] = array('message' => t("DECACHE_CACHE_CLEANED"), 'type' => 'success');
    }
}
