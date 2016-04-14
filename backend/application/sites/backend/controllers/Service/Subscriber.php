<?php

class Service_Subscriber_Controller extends Pelican_Controller_Back
{
    protected $administration = false; //true

    protected $form_name = "subscriber";

    protected $field_id = "SUBSCRIBER_ID";

    protected $defaultOrder = "SUBSCRIBER_LABEL";

    protected function setListModel()
    {
        if ($_GET["recherche_active"]) {
            if ($_GET["filter_SUBSCRIBER_EMAIL"]) {
                $aBind[":SUBSCRIBER_EMAIL"] = $_GET["filter_SUBSCRIBER_EMAIL"];
                $strWhere .= " AND LOWER(subscriber_email) LIKE '%'||LOWER(':SUBSCRIBER_EMAIL')||'%' ";
            }
            if ($_GET["filter_DOMAINE_EMAIL"]) {
                $aBind[":DOMAINE_EMAIL"] = $_GET["filter_DOMAINE_EMAIL"];
                $strWhere .= " AND LOWER(subscriber_email) LIKE '%'||LOWER('@:DOMAINE_EMAIL')||'%' ";
            }
            if ($_GET["filter_DATE_DEBUT"]) {
                $tmpDdeb = explode("/", $_GET["filter_DATE_DEBUT"]);
                $aBind[":DATE_DEBUT"] = $tmpDdeb[2].$tmpDdeb[1].$tmpDdeb[0];
                $strWhere .= " AND DATE_FORMAT(s.subscriber_date,'%Y%m%d')>=:DATE_DEBUT";
            }
            if ($_GET["filter_DATE_FIN"]) {
                $tmpDfin = explode("/", $_GET["filter_DATE_FIN"]);
                $aBind[":DATE_FIN"] = $tmpDfin[2].$tmpDfin[1].$tmpDfin[0];
                $strWhere .= " AND DATE_FORMAT(s.subscriber_date,'%Y%m%d')<=:DATE_FIN";
            }

            if ($_GET["filter_DATE_PREC"]) {
                $aBind[":DATE_PREC"] = $_GET["filter_DATE_PREC"];
                $strWhere .= " AND DATE_FORMAT(s.subscriber_date,'%Y%m')=:DATE_PREC";
            }
            // filtre forcé pour ne rien afficher
            if (! $strWhere) {
                $showFilter = true;
                $strWhere .= " AND 1 = 0 ";
                $msgErr = "<div class=\"erreur\">".t('TABLE_NO_RECORD')."</div>";
                $msgCritere = "<div class=\"erreur\">".t('One criteria')."</div>";
            }
        } elseif ($_GET["service_id"]) {
            $showFilter = false;
            $strWhere .= "AND subscriber_id in (SELECT subscriber_id FROM ".$_CONST["FW_PREFIXE_TABLE"]."subscription WHERE service_id = ".$_GET["service_id"].")";
        } else {
            $showFilter = true;
            $strWhere .= " AND 1 = 0 ";
            $msgErr = "<div class=\"erreur\">".t('TABLE_NO_RECORD')."</div>";
            $msgCritere = "<div class=\"erreur\">".t('One criteria')."</div>";
        }

        $this->listModel = "SELECT SUBSCRIBER_ID, SUBSCRIBER_LASTNAME,SUBSCRIBER_FIRSTNAME, SUBSCRIBER_NICKNAME, CONCAT('<a href=\"mailto:',SUBSCRIBER_EMAIL,'\">',SUBSCRIBER_EMAIL,'</a>') SUBSCRIBER_EMAIL, DATE_FORMAT(s.subscriber_date,'%d/%m/%Y') DATE_INSCR
		             FROM #pref#_subscriber s
		             WHERE s.SITE_ID = ".$_SESSION[$_CONST["APP"]]['SITE_ID']."
		             ".$strWhere."
		             ORDER BY ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = $this->id;
        $this->editModel = "SELECT * from #pref#_subscriber WHERE SUBSCRIBER_ID=:ID";
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');

        // {LIST}


        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        //------------ Begin startStandardForm ----------
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm
            ->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm
            ->beginFormTable();
        //------------ End startStandardForm ----------


        // {FORM}


        //------------ Begin stopStandardForm ----------
        $form .= $this->oForm
            ->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm
            ->close();
        //------------ End stopStandardForm ----------
        $this->setResponse($form);
    }
}
