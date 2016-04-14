<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';
use Itkg\Mapper\RedirectUrlImportMapper;
use Itkg\Reporter\RedirectImportReporter;
use Itkg\Writer\PelicanWriter;
use XtoY\Reader\XLSReader;
use XtoY\XtoY;

class Ndp_Administration_Redirect_Controller extends Ndp_Controller
{
    protected $administration = false;

    protected $form_name = "rewrite";

    protected $field_id = "REWRITE_URL";

    protected $defaultOrder = "REWRITE_URL ASC";

    protected $multiLangue = true;

    protected $defaultValues = ['REWRITE_RESPONSE'=>401,'REWRITE_TYPE'=>'PAGE'];

    /**
     * Index action, override done to forward to another action
     *
     */
    public function indexAction()
    {
        parent::indexAction();

        // Migration step 2
        if (!empty($_POST['stepAction'])) {
            $this->_forward($_POST['stepAction']);
        }
    }

    protected function setListModel()
    {

        $this->aBind[':SITE_ID']   = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $order = $this->getParam('order', $this->defaultOrder );
        $sqlList= '
            SELECT
                rw.REWRITE_URL, pv.PAGE_CLEAR_URL, rw.REWRITE_RESPONSE, rw.CREATED_AT
            FROM
                #pref#_'.$this->form_name.' rw
            INNER JOIN #pref#_page p ON rw.REWRITE_ID = p.PAGE_ID AND rw.LANGUE_ID=p.LANGUE_ID AND rw.SITE_ID=p.SITE_ID
            INNER JOIN #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID AND p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION AND p.LANGUE_ID=pv.LANGUE_ID
            WHERE
                (rw.REWRITE_TYPE="PAGE" OR rw.REWRITE_TYPE="EXTERNAL")
                AND p.SITE_ID = :SITE_ID
                AND p.LANGUE_ID= :LANGUE_ID
            ORDER BY '.$order.''
            ;

        $this->listModel = $sqlList;
    }

    public function listAction()
    {
        parent::listAction();
        $this->getImportHTML();
        /** @var Pelican_List $table */
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'REWRITE_URL','', $this->aBind);
        $table->addColumn(t('REDIRECTIONS'), 'REWRITE_URL', '45', 'left', '', 'tblheader', 'REWRITE_URL');
        $table->addColumn(t('CLEAR_URL'), 'PAGE_CLEAR_URL', '45', 'left', '', 'tblheader', 'PAGE_CLEAR_URL');
        $table->addColumn(t('CODE'), 'REWRITE_RESPONSE', '10', 'left', '', 'tblheader', 'REWRITE_RESPONSE');
        $table->addColumn(t('DATE'), 'CREATED_AT', '10', 'left', 'datetime|d/m/Y', 'tblheader', 'CREATED_AT');
        $table->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => 'REWRITE_URL'), 'center');
        $table->addInput(t('POPUP_LABEL_DEL'),  'button', array('id' => 'REWRITE_URL', '' => 'readO=true'), 'center');
        $this->assign("table", $table->getTable(), false);
        $this->assign(
            'import',
            [
                'controller' => '/_/Index/child?tid=' . $this->tid,
                'action' => 'importFile',
            ]
        );
        $this->fetch();
    }

    protected function setEditModel()
    {

        $con = Pelican_Db::getInstance();
        $this->aBind[':' . $this->field_id] = $con->strToBind($this->id);
        $this->aBind[':SITE_ID']            = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID']          = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->editModel                    = 'SELECT
                                    *
                            FROM
                                    #pref#_'.$this->form_name.'
                            WHERE
                                SITE_ID = :SITE_ID
                            AND LANGUE_ID = :LANGUE_ID
                            AND ' . $this->field_id . ' = :' . $this->field_id;
    }


    public function editAction()
    {
        $this->multiLangue = false;
        parent::editAction();
        if (empty($this->values['CREATED_AT'])){
            $now =new \DateTime();
            $this->values['CREATED_AT'] = $now->format('Y-m-d');
        }

        $form     = $this->startStandardForm();
        $form .= $this->oForm->createInput('REWRITE_URL',t('REWRITE_URL'),255,'',true,$this->values['REWRITE_URL'],$this->readO,100,false,'','text');
        $pages = getComboValuesFromCache("Backend/Page", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            null,
            null
        ));
        $form .= $this->oForm->createComboFromList("REWRITE_ID", t('PAGE')." :", $pages, $this->values['REWRITE_ID'], true, $this->readO, "1", false, '',false, false);
        $codes = array(301=>301, 410=>410);
        $form .= $this->oForm->createComboFromList("REWRITE_RESPONSE", t('CODE')." :", $codes, $this->values['REWRITE_RESPONSE'], true, $this->readO, "1", false, '', false, false);
        $form .= $this->oForm->createHidden('SITE_ID',$_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('LANGUE_ID',$_SESSION[APP]['LANGUE_ID']);
        $form .= $this->oForm->createHidden('REWRITE_ORDER', $this->values['REWRITE_ORDER']);
        $form .= $this->oForm->createHidden('REWRITE_TYPE',$this->values['REWRITE_TYPE']);
        $form .= $this->oForm->createHidden('CREATED_AT',$this->values['CREATED_AT']);

        $form .= $this->stopStandardForm();

        $this->assign("form", $form, false);
        $this->fetch();
    }


    public function saveAction()
    {
        $backup = $values =  Pelican_Db::$values;

        if (empty( $values['REWRITE_ORDER'])) {
           $values['REWRITE_ORDER'] =  $this->getRewriteOrder($values['REWRITE_ID']);
        }
        if (empty($values['PAGE_ID'])) {$values['PAGE_ID'] = $values['REWRITE_ID'];}
        $values['REWRITE_TYPE'] = 'PAGE';
        $regex = '#^https?://#';
        if (preg_match($regex, $values['REWRITE_URL'])) {
            $values['REWRITE_TYPE'] = 'EXTERNAL';
            $values['REWRITE_RESPONSE'] = 301;
        }
        Pelican_Db::$values = $values;
        parent::saveAction();
        Pelican_Db::$values= $backup;
    }

    public function ajaxVerifUrlAction()
    {

    }

    public function getRewriteOrder($pageId)
    {
        $connection = Pelican_Db::getInstance();
        $bind=[':PAGE_ID'=>$pageId];
        $sql = 'SELECT MAX(REWRITE_ORDER) FROM #pref#_rewrite WHERE REWRITE_ID= :PAGE_ID';
        $max = $connection->getItem($sql, $bind);

        return  (empty($max)) ? 1 : $max+1;
    }


    public function getImportHTML($site_id, $tc)
    {

        $this->assign('site_id',$site_id);
        $this->assign('tc',$tc);

    }


    /**
     * Methode permettant de lancer l'import de nouvelles traductions
     * a partir d'un fichier et d'une langue selectionnee recuperes en POST
     * Si l'import s'est deroule correctement on redirige avec un bool a true
     */
    public function importFileAction()
    {
        $this->before();
        $this->assign('success', false);

        if (isset($_FILES['FILE_REDIRECT_IMPORT']['tmp_name']) && $_FILES['FILE_REDIRECT_IMPORT']['error'] == UPLOAD_ERR_OK) {
            $pathfilename = $_FILES['FILE_REDIRECT_IMPORT']['tmp_name'];

            $reporter = new RedirectImportReporter();
            $readerConfig = array('skip'=>1);
            $reader = new XLSReader($readerConfig);
            $reader->setDSN($pathfilename);

            $writerConfig = ['table'=>'#pref#_rewrite','transaction'=>true];
            $writer = new PelicanWriter($writerConfig);
            $writer->setDDN(Pelican_Db::getInstance());
            $rules = array();
            $rules['REWRITE_URL'] = array('src'=>2);
            $rules['DEST_URL']    = array('src'=>3);
            $rules['SITE_ID']     = array('value'=> $_SESSION[APP]['SITE_ID']);
            $rules['LANGUE_ID']   = array('value'=> $_SESSION[APP]['LANGUE_ID']);
            $rules['CREATED_AT']  = array('value'=> date('Y-m-d'));
            /** @var \PsaNdp\MappingBundle\Services\PageFinder $pageFinder */
            $pageFinder = Pelican::getContainer()->get('psa_ndp.services.page_finder');
            $mapper = new RedirectUrlImportMapper();

            $mapper->setSiteId($_SESSION[APP]['SITE_ID'])
                    ->setCon(Pelican_Db::getInstance())
                    ->setLangueId($_SESSION[APP]['LANGUE_ID'])
                    ->setRootPage($pageFinder->getHomePage($rules['SITE_ID'], $_SESSION[APP]['LANGUE_CODE']))
                    ->setReporter($reporter)
                    ->setRules($rules);

            $uc = new XtoY();
            $uc->setMapper($mapper)
                ->setReader($reader)
                ->setWriter($writer)
                ->setMode(XtoY::MODE_FULL);
            $uc->run();
            $this->assign('reporter', $reporter);
            $this->assign('success', true);
            $this->addFlashMessage(t('NDP_IMPORT_REDIRECT_SUCCESS'),'success');
        } else {
            $this->addFlashMessage(t('ERROR_UPLOAD_FILE'),'error');
        }

        $this->fetch();
        $this->after();
    }
}
