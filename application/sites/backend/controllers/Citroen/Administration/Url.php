<?php
class Citroen_Administration_Url_Controller extends Pelican_Controller_Back
{
    protected $administration = false; //true

    protected $form_name = "url";

    protected $field_id = "URL_ID";

    protected $defaultOrder = "p.page_id";

    protected function setListModel ()
    {
        $this->listModel = "
            SELECT
                'Rubrique' as TYPE,
                CONCAT('pid ', pv.page_id) as ID,
                l.langue_translate as LANG,
                CONCAT(pv.page_id, '_', p.langue_id) as ID_LANG,
                pv.page_clear_url as CLEAR_URL,
                GROUP_CONCAT(r.rewrite_url SEPARATOR \"\n\") as REWRITE_URL
            FROM #pref#_page p
                INNER JOIN #pref#_page_version pv
                    ON (p.page_id = pv.page_id and p.page_draft_version = pv.page_version and p.langue_id = pv.langue_id)
                LEFT JOIN #pref#_rewrite r
                    ON (p.page_id = r.page_id and p.langue_id = r.langue_id)
                INNER JOIN #pref#_language l
                    ON (p.langue_id = l.langue_id)
            WHERE p.SITE_ID = '" . $_SESSION[APP]['SITE_ID'] . "'";

        if ($_GET['filter_search_url'] != '') {
            $this->listModel .= " AND (
                pv.page_clear_url like '%" . $_GET['filter_search_url'] . "%'
                OR r.rewrite_url like '%" . $_GET['filter_search_url'] . "%'
            )";
        }

        $this->listModel .= "
            GROUP BY ID, LANG
            ORDER BY " . $this->listOrder . ", p.langue_id";
    }

    public function listAction ()
    {

        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $oConnection = Pelican_Db::getInstance();

        // Filter
        /*$table->setFilterField("type_url", "<b>" . t('TYPE_URL') . " :</b>", "TYPE", array(
            array(
                "id"  => "1",
                "lib" => "Rubrique"
            ),
            array(
                "id"  => "2",
                "lib" => "Contenu"
            ),
        ));*/
        $table->setFilterField("search_url", "<b>" . t('SEARCH_URL') . " :</b>", "CLEAR_URL");
        $table->getFilter(1);


        // {LIST}
        $table->setCSS(array(
            "tblalt1" ,
            "tblalt2"
        ));

        $cmd = "document.getElementById('checkbox_'+this.id.split('_')[1]+'_1').checked='checked';verifUrl(this);";
        $result = $oConnection->querytab($this->getListModel());
        $table->setValues($result, "p.page_id");
        $table->addInput('', 'checkbox', array('value'=>'ID_LANG', '_javascript_'=>'', '_value_field_' => 'ID_LANG'));
        //$table->addColumn(t('TYPE'), "TYPE", "10", "left", "", "tblheader", "TYPE");
        $table->addColumn(t('ID'), "ID", "5", "left", "", "tblheader", "ID");
        $table->addColumn(t('Languages'), "LANG", "5", "left", "", "tblheader");
        $table->addInput('CLEAR_URL', 'text', array('_target_' => "self", 'value'=>'CLEAR_URL', '_onchange'=>$cmd), "", "", "tblheader", 0, 1, 1, t('CLEAR_URL'));
        $table->addTextarea('REWRITE_URL', array('_onchange'=>$cmd, "class" => "rewrite_box"), "", "", "tblheader", 0, 1, 1, t('CLEAR_URL'));

        $script = "
<script type=\"text/javascript\">
    function verifUrl(obj) {

        var page_langue = document.getElementById('checkbox_'+obj.id.split('_')[1]+'_1').value;
        var iPage = page_langue.split('_')[0];
        var iLang = page_langue.split('_')[1];

    	callAjax({
			type: \"POST\",
			data: {urls : obj.value, id : iPage, field : obj.id, langue : iLang, type: obj.id.split('_')[2]},
			url: '/_/Citroen_Administration_Url/ajaxVerifUrl'
		});
        return false;
    }


</script>

        ";

        $css = '
        <style type="text/css">
            .rewrite_box {
                width:400px;
                height:60px;
            }
            .text {
                width:400px;
            }
        </style>';
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $this->form_action = "save";
        $form .= $this->beginForm($this->oForm);
        $table->bFiltered = false;
        $form .= $table->getTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $form .= $table->getFilterForm();

        $this->aButton["back"] = "";
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->setResponse($form.$css.$script);
    }

    public function saveAction() {

        $oConnection = Pelican_Db::getInstance();

        if(!empty(Pelican_Db::$values)) {
            foreach(Pelican_Db::$values as $key => $value) {
                if (strpos($key, 'checkbox') === 0) {
                    Pelican_Db::$values['save_checkbox'][str_replace('checkbox', '', $key)] = $value;
                }
            }
        }

        if (!empty(Pelican_Db::$values['save_checkbox'])) {
            foreach(Pelican_Db::$values['save_checkbox'] as $key => $value) {
                $aBind = array();

                $aLigne = explode('_', $key);
                $iLigne = $aLigne[1];
                $aValue = explode('_', $value);

                $aBind[':PAGE_ID'] = $aValue[0];
                $aBind[':LANGUE_ID'] = $aValue[1];
                $aBind[':PAGE_CLEAR_URL'] = $oConnection->strtobind(Pelican_Db::$values['_' . $iLigne . "_4"]);
                $aBind[':REWRITE_URL'] = explode("\n", Pelican_Db::$values['_' . $iLigne . "_5"]);

                //Mise � jour de toutes les versions de la page pour la langue donn�e
                $updatePageVersion = "
                    UPDATE #pref#_page_version
                    SET PAGE_CLEAR_URL = :PAGE_CLEAR_URL
                    WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID";
                $oConnection->query($updatePageVersion, $aBind);

                // Suppression des urls alternative d�j� pr�sent
                $oConnection->query("DELETE FROM #pref#_rewrite WHERE PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID", $aBind);

                // Ajout des urls alternatives
                $DBVALUES_INIT = Pelican_Db::$values;
                if (!empty($aBind[':REWRITE_URL'])) {
                    $i=0;
                    foreach ($aBind[':REWRITE_URL'] as $rewrite) {
                        Pelican_Db::$values = array();
                        Pelican_Db::$values['REWRITE_URL'] = $rewrite;
                        Pelican_Db::$values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                        Pelican_Db::$values['LANGUE_ID'] = $aBind[':LANGUE_ID'];
                        Pelican_Db::$values['REWRITE_ORDER'] = $i++;
                        Pelican_Db::$values['PAGE_ID'] = $aBind[':PAGE_ID'];
                        Pelican_Db::$values['CONTENT_ID'] = '';
                        Pelican_Db::$values['REWRITE_TYPE'] = "PAGE";
                        Pelican_Db::$values['REWRITE_ID'] = $aBind[':PAGE_ID'];
                        Pelican_Db::$values['REWRITE_RESPONSE'] = "301";

                        $oConnection->insertQuery("#pref#_rewrite");
                    }
                }
                Pelican_Db::$values = $DBVALUES_INIT;

            }
        }

    }

    public function ajaxVerifUrlAction() {
        $oConnection = Pelican_Db::getInstance();
        $alert = '';
        $urlUsed = array();

        if($this->getParam('urls') && $this->getParam('id')){
            $urls = explode("\n", $this->getParam('urls'));

            if (!empty($urls)) {
                foreach($urls as $url) {
                    $urlClean = str_replace("//", "/", "/" . $url);
                    $aBind[":REWRITE_URL"] = $oConnection->strToBind($urlClean);
                    $aBind[":ID"] = $oConnection->strToBind($this->getParam('id'));
                    $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
                    $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

                    $count1 = $oConnection->queryRow("select * from #pref#_rewrite WHERE REWRITE_URL=:REWRITE_URL AND page_id != :ID and site_id = :SITE_ID", $aBind);
                    $count2 = $oConnection->queryRow("select PAGE_TYPE_CODE,PAGE_TYPE_SHORTCUT from #pref#_page_type WHERE " . $oConnection->getConcatClause(array("PAGE_TYPE_SHORTCUT", "'/'")) . "=:REWRITE_URL", $aBind);

                    $count3 = $oConnection->queryRow("select distinct pv.PAGE_ID, PAGE_TITLE_BO from #pref#_page_version pv INNER JOIN #pref#_page p ON (p.page_id = pv.page_id and p.langue_id = pv.langue_id) WHERE page_clear_url=:REWRITE_URL and pv.page_id != :ID and site_id = :SITE_ID and pv.langue_id=:LANGUE_ID", $aBind);
                    $count4 = $oConnection->queryRow("select distinct cv.CONTENT_ID, CONTENT_TITLE_BO from #pref#_content_version cv INNER JOIN #pref#_content c ON (c.content_id = cv.content_id and c.langue_id = cv.langue_id) WHERE content_clear_url =:REWRITE_URL and site_id = :SITE_ID and cv.langue_id=:LANGUE_ID", $aBind);

                    $count = ($count1 ? 1 : 0) + ($count2 ? 1 : 0) + ($count3 ? 1 : 0) + ($count4 ? 1 : 0);

                    if ($count) {
                        $temp = '';
                        if ($count1["PAGE_ID"]) {
                            $temp = $urlClean . " x" .t("FOR_RUB") . " " . $count1["PAGE_ID"] . "";
                        }
                        if ($count1["CONTENT_ID"]) {
                            $temp = $urlClean . " c" . t("FOR_CONT") . " " .  $count1["CONTENT_ID"] . "";
                        }
                        if ($count2["PAGE_TYPE_CODE"]) {
                            $temp = $urlClean . " w" . t("FOR_GAB") . " " .  $count2["PAGE_TYPE_CODE"] . "";
                        }
                        if ($count3["PAGE_ID"]) {
                            $temp = $urlClean . " <" . t("FOR_RUB") . " " .  $count3["PAGE_TITLE_BO"] . "";
                        }
                        if ($count4["CONTENT_ID"]) {
                            $temp = $urlClean . " c" . t("FOR_GAB") . " " .  $count4["CONTENT_TITLE_BO"] . "";
                        }
                        if ($temp != '') {
                            $urlUsed[] = $temp;
                        }
                    }
                }

                if (!empty($urlUsed)) {
                    $alert = t("USED_URL", 'js');
                    $alert .= "\\n- " . implode("\\n- ", $urlUsed);
                    $alert .= "\\n\\n".t("NEED_EMPTY", 'js');
                }
            }
        }
        if ($alert) {
            $aBind[":ID"] = $oConnection->strToBind($this->getParam('id'));
            $aBind[":LANG"] = $oConnection->strToBind($this->getParam('langue'));

            if ($this->getParam('id') == 4 || $this->getParam('isFromRubrique') == true) {
                $sql = "
                    SELECT PAGE_CLEAR_URL
                    FROM #pref#_page p
                        INNER JOIN #pref#_page_version pv
                            ON (p.PAGE_ID = pv.PAGE_ID and p.page_draft_version = pv.page_version and p.langue_id = pv.langue_id)
                    WHERE p.page_id=:ID
                    and p.langue_id=:LANG
                ";
                $URL = $oConnection->queryItem($sql, $aBind);
            } else {
                $sql = "
                    SELECT GROUP_CONCAT(rewrite_url SEPARATOR \"||\")
                    FROM #pref#_rewrite
                    WHERE PAGE_ID = :ID
                    AND LANGUE_ID = :LANG
                ";
                $URL = $oConnection->queryItem($sql, $aBind);
                $URL = str_replace(array("||", "\r"), array("\\n", ''), $URL);
            }


            $init = "document.getElementById('".$this->getParam('field')."').value='" . $URL . "'";
            $this->addResponseCommand('script', array('value'=>"alert('" . $alert . "');" . $init . ""));
        } /*else {
            $this->addResponseCommand('script', array('value'=>"alert('Done')"));
        }*/
    }
}
