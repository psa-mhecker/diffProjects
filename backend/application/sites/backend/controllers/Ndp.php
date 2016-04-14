<?php

class Ndp_Controller extends Pelican_Controller_Back
{
    /* Activation de la barre de langue */

    /**
     * @var Ndp_Form;
     */
    public $oForm;
    protected $multiLangue = false;
    protected $isHmvcContext = false;

    protected function init()
    {
        parent::init();
        $isHmvc = $this->getParam('HMVC');
        $this->isHmvcContext = !empty($isHmvc);
    }

    private function getIsHmvcContext()
    {
        return $this->isHmvcContext;
    }
    /* Fonction de lecture des multis */

    public static function myReadMulti($table, $prefixe)
    {
        $aMulti = array();
        if (is_array($table) && !empty($table)) {
            foreach ($table as $key => $value) {
                $iPrefixeLenght = strlen($prefixe);
                if (substr($key, 0, $iPrefixeLenght) === $prefixe) {
                    $aTemp = explode('_', $key);
                    $index = substr($aTemp[0], $iPrefixeLenght);
                    $rest = substr(strstr($key, '_'), 1);
                    if ($rest) {
                        $aMulti[$index][$rest] = $value;
                    }
                }
            }
        }

        return $aMulti;
    }

    /**
     * Création des éléments de gestion du multilinguisme.
     *
     * @access protected
     *
     * @return __TYPE__
     */
    protected function getLanguageViewAdmin()
    {
        if (!$this->bPopup) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
                               FROM #pref#_language l, #pref#_site_language sl
                               WHERE sl.langue_id = l.langue_id
                               AND sl.site_id = :SITE_ID";
            $aOngletLangue = $oConnection->queryTab($sql, $aBind);

            if ($aOngletLangue) {
                if (count($aOngletLangue) > 1) {

                    /*
                     * Recherche juste la langue par default
                     */
                    $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
                               FROM #pref#_language l, #pref#_site_language sl
                               WHERE sl.langue_id = l.langue_id
                               AND sl.site_id = :SITE_ID
                               AND sl.langue_id = ".(!empty($_SESSION[APP]["SITE_ITEM"]["LANGUE_ID_DEFAULT"]) ? $_SESSION[APP]["SITE_ITEM"]["LANGUE_ID_DEFAULT"] : $_SESSION[APP]['LANGUE_ID']);
                    $aOngletLangueDefault = $oConnection->queryTab($sql, $aBind);
                }
                if (!$aOngletLangueDefault) {
                    if ($aOngletLangue[0]['LANGUE_ID']) {
                        $_SESSION[APP]['LANGUE_ID'] = $aOngletLangue[0]['LANGUE_ID'];
                        $aOngletLangueDefault[0]['LANGUE_ID'] = $aOngletLangue[0]['LANGUE_ID'];
                    } else {
                        $_SESSION[APP]['LANGUE_ID'] = 1;
                        $aOngletLangueDefault[0]['LANGUE_ID'] = 1;
                    }
                }
                $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] = $_SESSION[APP]['LANGUE_ID'];
                if (count($aOngletLangue) > 0) {
                    $strOngL = Pelican_Html::script(array('type' => "text/javascript"), "
                       function saveFormBeforeChangeLanguage(onglet) {
                               var idEnCours = new Number(".$_GET["id"].");
                               var strUrlLang = document.location.href.replace('&langue=".($this->lang ? $this->lang : $aOngletLangueDefault[0]['LANGUE_ID'])."','') + '&langue=' + onglet;

                               if (idEnCours == ".Pelican::$config["DATABASE_INSERT_ID"].") {
                                       var bSaveForm = true;
                               /*
                               } else {
                                       var bSaveForm = confirm('Would you like to save before changing language? \\n\\nClick on [OK] to save\\nClick on [Annuler] to continue without saving');
                                       */
                               }
                               if (bSaveForm) {
                                       document.forms['fForm'].form_retour.value = strUrlLang;
                                       document.location.href = strUrlLang;
                               } else {
                                       document.location.href = strUrlLang;
                               }
                       }
                       ");
                    $strOngL .= Pelican_Html::script(array(type => "text/javascript"), "
                                       /**
                                       * Gestion des onglets de langue
                                       *
                                       * @return void
                                       * @param string onglet Identifiant de l'onglet
                                       */
                                       function activeOngletLangue(onglet) {
                                       ".($_GET["id"] ? "saveFormBeforeChangeLanguage(onglet);" : "document.location.href = document.location.href.replace('&langue=".($this->lang ? $this->lang : $aOngletLangueDefault[0]['LANGUE_ID'])."','') + '&langue=' + onglet;")."
                                       }");
                    $oTab = Pelican_Factory::getInstance('Form.Tab', "tabLanguage", $this->skinPath);
                    foreach ($aOngletLangue as $oL) {
                        $image = Pelican_Html::img(array('border' => "0", 'src' => Pelican::$config["LIB_PATH"]."/Pelican/Translate/public/images/flags/".strtolower($oL["LANGUE_CODE"]).".png"));
                        $oTab->addTab($image." ".$oL["LANGUE_LABEL"], "ongletLangue".$oL['LANGUE_ID'], ($_SESSION[APP]['LANGUE_ID'] == $oL['LANGUE_ID']), "", "activeOngletLangue('".$oL['LANGUE_ID']."');", "", "petit", "", false);
                    }
                    $strOngL .= Pelican_Html::div(array("class" => "petit_onglet_bas"), $oTab->getTabs());
                    $strOngL .= '<br/>';
                    $this->assign('languageTabAdmin', $strOngL, false);
                }
            }
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();

        if ($this->multiLangue) {
            $this->getLanguageViewAdmin();
        }
        $this->_initBack();
        if ($this->multiLangue) {
            $sSQL = 'select count(*) from #pref#_'.$this->form_name.' WHERE '.$this->field_id.' = :ID AND LANGUE_ID = :LANGUE_ID';
            $aBind[':ID'] = $this->id;
            if(!is_numeric($this->id)) {
                $aBind[':ID'] =$oConnection->strToBind($this->id);
            }
            $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            if (!$oConnection->queryItem($sSQL, $aBind)) {
                $this->form_action = $this->config['database']['insert'];
            }
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function listAction()
    {
        if ($this->multiLangue) {
            $this->getLanguageViewAdmin();
        }

        $this->_initBack();
    }

    public function updateUrlAction()
    {
        $aData = $this->getParams();
        $oConnection = Pelican_Db::getInstance();

        $aBind[':PAGE_ID'] = $aData['pid'];
        $aBind[':LANGUE_ID'] = $aData['lang'];
        $sSql = "
            SELECT PAGE_CLEAR_URL
            FROM #pref#_page p
                INNER JOIN #pref#_page_version pv
                    on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION)
            WHERE p.PAGE_ID = :PAGE_ID
            AND p.LANGUE_ID = :LANGUE_ID";
        $clearUrl = $oConnection->queryItem($sSql, $aBind);
        $this->getRequest()->addResponseCommand('script', array(
            'value' => "document.fForm.NAVIGATION_URL.value='".$clearUrl."'",
        ));
    }

    public function updateLabelAction()
    {
        $term = array();
        $aData = $this->getParams();

        if (!$aData['host']) {
            echo 'Merci de renseigner un paramètre host=backend ou host=frontend';
            die;
        }

        if ($aData['host'] == 'backend') {
            $aData['BO'] = 1;
        } elseif ($aData['host'] == 'frontend') {
            $aData['FO'] = 1;
        } else {
            echo 'La valeur de host n\'est pas correct,<br/>merci de renseigner un paramètre host=backend ou host=frontend';
            die;
        }

        $host = str_replace('.', '_', $aData['host']);

        $oConnection = Pelican_Factory::getConnection();
        $filename = Pelican::$config["LOG_ROOT"].'/'.$host.'/trad.txt';

        if (!$fp = @fopen($filename, 'r')) {
            echo "File does not exist.";
            die;
        } else {
            while (!feof($fp)) {
                $label = str_replace("\r\n", "", fgets($fp, 4096));
                if (!$term[$label]) {
                    $term[$label] = $oConnection->strtobind($label);
                }
            }
            fclose($fp);

            echo 'Il y a '.sizeof($term).' labels différents dans le fichier.<br/><br/>';

            echo '/********/<br/>';
            echo '/* USED */<br/>';
            echo '/********/<br/>';
            $sql = "update #pref#_label set LABEL_INFO = 'used' WHERE LABEL_ID IN (".implode(',', $term).")";
            $r = $oConnection->query($sql);

            if ($aData['display'] == 1) {
                echo str_replace('#pref#', 'psa', $sql).'<br/>';
                echo '--------------------------------<br/>';
            }

            if ($oConnection->affectedRows < 2) {
                echo $oConnection->affectedRows.' ligne mise à jour.';
            } else {
                echo $oConnection->affectedRows.' lignes misent à jour.';
            }

            if ($aData['BO']) {
                echo '<br/><br/><br/>';
                echo '/*********/<br/>';
                echo '/*  B O  */<br/>';
                echo '/*********/<br/>';
                $sql = "update #pref#_label set LABEL_BO = 1 WHERE LABEL_ID IN (".implode(',', $term).")";
                $r = $oConnection->query($sql);

                if ($aData['display'] == 1) {
                    echo str_replace('#pref#', 'psa', $sql).'<br/>';
                    echo '--------------------------------<br/>';
                }

                if ($oConnection->affectedRows < 2) {
                    echo $oConnection->affectedRows.' ligne mise à jour.<br/>';
                } else {
                    echo $oConnection->affectedRows.' lignes misent à jour.<br/>';
                }
            }

            if ($aData['FO']) {
                echo '<br/><br/><br/>';
                echo '/*********/<br/>';
                echo '/*  F O  */<br/>';
                echo '/*********/<br/>';
                $sql = "update #pref#_label set LABEL_FO = 1 WHERE LABEL_ID IN (".implode(',', $term).")";
                $r = $oConnection->query($sql);

                if ($aData['display'] == 1) {
                    echo str_replace('#pref#', 'psa', $sql).'<br/>';
                    echo '--------------------------------<br/>';
                }

                if ($oConnection->affectedRows < 2) {
                    echo $oConnection->affectedRows.' ligne mise à jour.<br/>';
                } else {
                    echo $oConnection->affectedRows.' lignes misent à jour.<br/>';
                }
            }
        }
        echo '<br/><br/>';

        unlink($filename);
        echo 'fin';
    }

    public function getUpdateLabelAction()
    {
        $aData = $this->getParams();
        $oConnection = Pelican_Factory::getConnection();

        $sql = "SELECT LABEL_ID FROM #pref#_label WHERE LABEL_BO = 1";
        $aLabel = $oConnection->queryTab($sql);

        if (!empty($aLabel)) {
            $sqlUpdate = "UPDATE #pref#_label SET LABEL_INFO = 'used', LABEL_BO = 1 WHERE LABEL_ID = #LABEL#;<br/>";
            foreach ($aLabel as $label) {
                echo str_replace(array('#pref#', '#LABEL#'), array('psa', $oConnection->strtobind($label['LABEL_ID'])), $sqlUpdate);
            }
        }

        $sql = "SELECT LABEL_ID FROM #pref#_label WHERE LABEL_FO = 1";
        $aLabel = $oConnection->queryTab($sql);

        if (!empty($aLabel)) {
            $sqlUpdate = "UPDATE #pref#_label SET LABEL_INFO = 'used', LABEL_FO = 1 WHERE LABEL_ID = #LABEL#;<br/>";
            foreach ($aLabel as $label) {
                echo str_replace(array('#pref#', '#LABEL#'), array('psa', $oConnection->strtobind($label['LABEL_ID'])), $sqlUpdate);
            }
        }
    }

    /**
     * if not isset, set $defaultValue  to $this->values[$valueName] 
     * @param string $valueName
     * @param mixed $defaultValue
     * @param boolean $forceSetValue (option) default false
     */
    public function setDefaultValueTo($valueName, $defaultValue, $forceSetValue = false)
    {
        if (!isset($this->values[$valueName]) || $forceSetValue) {
            $this->values[$valueName] = $defaultValue;
        }
    }

    /**
     * 
     */
    protected function redirectRequest()
    {
        //Active la redirection dans un contexte non HMVC
        if ($this->getIsHmvcContext() !== true) {
            parent::redirectRequest();
        }
    }

    /**
     * @param string $selected
     * @param string $type
     * @param mixed  $value
     *
     * @return string
     */
    public static function addHeadContainer($selected, $value, $type)
    {
        $display = 'display:none;';
        $class = ' isNotRequired';
        if ($selected == $value || (is_array($selected) && in_array($value, $selected))) {
            $display = '';
            $class = '';
        }
        $isArray = false;
        if (is_array($selected) && count($selected) > 0) {
            $isArray = true;
            $return = '<tbody id="'.$type.'_'.$selected[0].'" style="'.$display.'" class="'.$type;
            foreach($selected as $key => $select) {
                $return .= ' '.$type.'_'.$select;
            }
            $return .= ' '.$class.'"><tr><td colspan="2"><table class="form">';
        }
        if(!$isArray) {
            $return = sprintf('<tbody id="%s_%s" style="%s" class="%s %s_%s %s"><tr><td colspan="2"><table class="form">', $type, $selected, $display, $type, $type, $selected, $class);
        }

        return $return;
    }

    /**
     * @param string $multi
     * @param string $type
     * @param mixed  $value
     *
     * @return string
     */
    public static function addFootContainer()
    {
        $footContainer = '</table>';
        $footContainer .= '</td></tr>';
        $footContainer .= '</tbody>';

        return $footContainer;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerRadio($type)
    {
        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();

                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                "';

        return $js;
    }

    /**
     *
     * @param int $idWs
     *
     * @return boolean
     */
    public function getWsState($idWs)
    {
        $wsEnable = false;
        $webservice  = Pelican_Factory::getInstance('Webservice');
        $sitesWsConf = $webservice->getSitesWsConf();

        foreach ($sitesWsConf as $key => $value) {
            if ($value['site_id'] == $_SESSION[APP]['SITE_ID'] && $value['ws_id'] == $idWs) {
                $wsEnable = true;
                break;
            }
        }

        return $wsEnable;
    }

    public function getNextId()
    {
        $con = Pelican_Db::getInstance();
        $sql = 'SELECT MAX('.$this->field_id.') FROM #pref#_'.$this->form_name.' WHERE SITE_ID=:SITE_ID AND LANGUE_ID=:LANGUE_ID ';
        $bind = [
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];

        return ( (int) $con->queryItem($sql, $bind) +1);
    }
}
