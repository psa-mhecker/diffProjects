<?php
/**
 * Fichier de Citroen_BarreOutils :
 *
 * Classe Back-Office de contribution des éléments de la Barre d'Outils
 *
 * @package Citroen
 * @subpackage Administration
 * @author Patrice Chégard <patrice.chegard@businessdecision.com>
 * @update Mathieu Raiffé <mathieu.raiffe@businessdecision.com> Ajout de la notion de langue
 * @since 17/07/2013
 */
class Administration_Personnalisation_Controller extends Pelican_Controller_Back
{
    protected $administration = true;
    protected $form_name = "site_personnalisation";
    protected $field_id = "SITE_ID";
    protected $defaultOrder = "SITE_ID";

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $sql = "
                SELECT
                    s.SITE_ID,
                    s.SITE_LABEL,
                    z.ZONE_ID,
                    z.ZONE_LABEL
                FROM
                    #pref#_site s
                LEFT JOIN #pref#_site_personnalisation sp ON (s.SITE_ID = sp.SITE_ID)
                LEFT JOIN #pref#_zone z ON (z.ZONE_ID = sp.ZONE_ID) ";

        if ($_GET['filter_search_keyword'] != '') {
            $sql.= " WHERE (
            SITE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            OR ZONE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
        }
               $sql.= " GROUP BY s.{$this->listOrder}
                ORDER BY ZONE_LABEL

        ";
        $this->listModel = $oConnection->queryTab($sql);

    }

    protected function setEditModel()
    {
        $this->aBind[':' . $this->field_id] = (int)$this->id;

        $sql = "
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                   {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
";

        $this->editModel = $sql;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "SITE_ID");
        $table->addColumn(t('ID'), "SITE_ID", "20", "left", "", "tblheader", "SITE_ID");
        $table->addColumn(t('LABEL'), "SITE_LABEL", "20", "left", "", "tblheader", "SITE_LABEL");
        $sqlZone = "select distinct sp.ZONE_ID as \"id\",
        ZONE_LABEL as \"lib\"
        from #pref#_site_personnalisation sp
        inner join #pref#_zone z on (sp.ZONE_ID = z.ZONE_ID)";
        $table->addMulti ( t ( 'ZONES' ), 'SITE_ID', "25", "left", "<br>", "tblheader", "", $sqlZone );
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "SITE_ID"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();

        $bind[':SITE_ID'] = Pelican::$config["SITE_MASTER"];
        $bind[':TPL_PAGE'] = Pelican::$config['TPL_GLOBAL'];
        $bind[':ZONE_TYPE_ID'] = 2;
        $oConnection = Pelican_Db::getInstance();
        $sql = "
            SELECT
                distinct z.ZONE_ID,
                z.ZONE_LABEL
            FROM
                #pref#_zone z
            INNER JOIN
                #pref#_zone_template zt ON (zt.ZONE_ID = z.ZONE_ID)
            INNER JOIN
                #pref#_template_page t ON (t.TEMPLATE_PAGE_ID  = zt.TEMPLATE_PAGE_ID)
            WHERE
                t.SITE_ID = :SITE_ID
            AND
                t.TEMPLATE_PAGE_ID <> :TPL_PAGE
            AND
                z.ZONE_TYPE_ID <> :ZONE_TYPE_ID
        ";
        $results = $oConnection->queryTab($sql,$bind);
        $data = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $result){
                $data[$result['ZONE_ID']] = $result['ZONE_LABEL'];
            }
        }
        $bind[':SITE_ID'] = $this->id;
        $sql = "select distinct sp.ZONE_ID as \"id\",
        ZONE_LABEL as \"lib\"
        from #pref#_site_personnalisation sp
        inner join #pref#_zone z on (sp.ZONE_ID = z.ZONE_ID) where sp.site_ID = :SITE_ID";
        $results = $oConnection->queryTab($sql,$bind);
        $selected = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $result){
                $selected[] = $result['id'];
            }
        }
        $form = $this->startStandardForm();
        $form .= $this->oForm->createAssocFromList($oConnection, "ZONE_PERSONNALISATION", t('Zones'), $data, $selected, false, true, $this->readO, 8, 350);
        $form .= $this->stopStandardForm();
        $form = formToString($this->oForm, $form);
        $this->setResponse ($form);
    }


    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = Pelican_Db::$values['SITE_ID'];
        $sqlDelete = "
            DELETE
                FROM
            #pref#_site_personnalisation
                WHERE
            SITE_ID = :SITE_ID
        ";
        $oConnection->query($sqlDelete,$bind);
        if(is_array(Pelican_Db::$values['ZONE_PERSONNALISATION']) && count(Pelican_Db::$values['ZONE_PERSONNALISATION'])>0){
            foreach(Pelican_Db::$values['ZONE_PERSONNALISATION'] as $zone){
                Pelican_Db::$values['ZONE_ID'] = $zone;
                $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_site_personnalisation');
            }
        }
        Pelican_Cache::clean("Frontend/Citroen/Perso/Activation");
    }

}