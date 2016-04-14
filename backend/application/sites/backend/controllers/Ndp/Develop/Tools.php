<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_Develop_Tools_Controller  extends Ndp_Controller
{
    protected $form_name = "devtools";

    protected $buttons;

    protected function addControllerButton($name, $action)
    {

        $this->buttons[$name] =
            [
                'controller' => '/_/Index/child?tid=' . $this->tid,
                'action' => $action,
                'label' => $name,
            ]
        ;

    }

    protected function addMenu()
    {
        $this->addControllerButton('TRANSLATIONS','translation');
        $this->addControllerButton('PAGES','page');
        $this->assign('buttons',$this->buttons);
    }

    protected function hideButtons()
    {
        // Hide default list button
        $this->aButton["add"] = "";
        $this->aButton["save"] = "";
        $this->aButton["back"] = "";
        Backoffice_Button_Helper::init($this->aButton);
    }
    /**
     * Index action, override done to forward to another action
     *
     */
    public function indexAction()
    {
        $this->addMenu();
        $this->hideButtons();
        parent::indexAction();

        if (!empty($_POST['stepAction'])) {
            $this->_forward($_POST['stepAction']);
        }

    }

    /**
     * Default list action used to display migration input IHM
     */
    public function listAction()
    {

        $this->fetch();
    }

    /**
     * Default list action used to display migration input IHM
     */
    public function translationAction()
    {
        $con= Pelican_Db::getInstance();

        // traductions BO  incomplete
        $sql = 'SELECT l.LABEL_ID FROM #pref#_label l LEFT JOIN #pref#_label_langue_site ls ON l.LABEL_ID=ls.LABEL_ID WHERE l.LABEL_BO=1 AND ls.LABEL_ID IS NULL';
        $this->assign('missingTranslationBo', $this->autoTable($con->queryTab($sql,[])), false);

        // traductions BO  orpheline
        $sql = 'SELECT ls.* FROM #pref#_label_langue_site ls LEFT JOIN #pref#_label l ON l.LABEL_ID=ls.LABEL_ID WHERE l.LABEL_ID IS NULL';
        $this->assign('orphanTranslationBo', $this->autoTable($con->queryTab($sql,[])), false);

        // traductions FO  incomplete
        $sql = 'SELECT l.LABEL_ID FROM #pref#_label l LEFT JOIN #pref#_label_langue ll ON l.LABEL_ID=ll.LABEL_ID WHERE l.LABEL_FO=1 AND ll.LABEL_ID IS NULL';
        $this->assign('missingTranslationFo', $this->autoTable($con->queryTab($sql,[])), false);

        // traductions FO  orpheline
        $sql = 'SELECT ll.* FROM #pref#_label_langue ll LEFT JOIN #pref#_label l ON l.LABEL_ID=ll.LABEL_ID WHERE l.LABEL_ID IS NULL';
        $this->assign('orphanTranslationFo', $this->autoTable($con->queryTab($sql,[])), false);

        $this->fetch();
    }

    /**
     * Default list action used to display migration input IHM
     */
    public function pageAction()
    {
        $con= Pelican_Db::getInstance();

        // pages sans version
        $sql = 'SELECT p.PAGE_ID, p.LANGUE_ID, p.PAGE_PARENT_ID, p.SITE_ID, p.PAGE_TYPE_ID, p.PAGE_CREATION_DATE, p.PAGE_PATH, p.PAGE_LIBPATH FROM #pref#_page p LEFT JOIN #pref#_page_version pv ON pv.PAGE_ID=p.PAGE_ID AND pv.LANGUE_ID=p.LANGUE_ID WHERE pv.PAGE_ID IS NULL';
        $this->assign('pageWhitoutVersion', $this->autoTable($con->queryTab($sql,[])), false);

        // versions sans page
        $sql = 'SELECT pv.PAGE_ID,pv.PAGE_CLEAR_URL FROM #pref#_page_version pv LEFT JOIN #pref#_page p ON pv.PAGE_ID=p.PAGE_ID AND pv.LANGUE_ID=p.LANGUE_ID WHERE p.PAGE_ID IS NULL';
        $this->assign('versionWithoutPage', $this->autoTable($con->queryTab($sql,[])), false);

        // rewrite sans page
        $sql = 'SELECT rw.* FROM #pref#_rewrite rw LEFT JOIN #pref#_page p ON rw.PAGE_ID=p.PAGE_ID WHERE p.PAGE_ID IS NULL';
        $this->assign('rewriteWithoutPage', $this->autoTable($con->queryTab($sql,[])), false);


        $this->fetch();
    }

    protected function autoTable($data)
    {
        reset($data);
        $columns = array_keys(current($data));
        /** @var Ndp_List $table */
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->navLimitRows = count($data)+1;
        $attributes = ['data-page-size'=>20,'data-page-navigation="#page-without-version"'];
        $table->setTableAttributes($attributes);
        $table->bTablePages = false;
        $table->setValues($data);
        foreach ($columns as $column) {
            $table->addColumn($column, $column);
        }
        $table = $table->getTable();
        $footer = $this->getFooter($data);
        $table = str_replace('</thead>','</thead>'.$footer,$table);
        return $table;
    }

    /**
     * @param $data
     * @return string
     */
    protected function getFooter($data)
    {
        $nbColumns = count(array_keys(current($data)));
        $tpl = '<tfoot>
		<tr>
			<td colspan="%d">
				<div class="pagination pagination-centered hide-if-no-paging"></div>
			</td>
		</tr>
	</tfoot>';

        return sprintf($tpl, $nbColumns);
    }
}
