<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

use PSA\MigrationBundle\Entity\Cta\PsaCta;

class Ndp_Cta_Controller extends Ndp_Controller
{

    protected $multiLangue    = true;
    protected $administration = true;
    protected $form_name      = 'cta';
    protected $field_id       = 'ID';
    protected $defaultOrder   = 'ID';
    protected $con;
    protected $listDecacheOrchestra = array(
        'strategy' => array(
            'strategy' => array(
                'cta',
                'siteId',
                'locale',
            ),
        ),
    );

    const PAGE_PUBLISHED = 4;
    const DISABLE  = 0;
    const ENABLE   = 1;

    protected function setListModel()
    {
        $con                = Pelican_Db::getInstance();
        $bind               = array();
        $bind[':SITE_ID']   = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlList            = 'SELECT
                            *
                    FROM
                            #pref#_cta c
                    WHERE
                        c.IS_REF = 1
                        AND SITE_ID = :SITE_ID
                        AND LANGUE_ID = :LANGUE_ID
                    ORDER BY
                        ' . $this->listOrder;

        $aRx             = $con->queryTab($sqlList, $bind);
        $this->listModel = $aRx;
    }

    protected function setEditModel()
    {
        $this->aBind[':' . $this->field_id] = (int) $this->id;
        $this->aBind[':SITE_ID']            = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID']          = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->editModel                    = 'SELECT
                                    *
                            FROM
                                    #pref#_cta
                            WHERE
                                SITE_ID = :SITE_ID
                            AND LANGUE_ID = :LANGUE_ID
                            AND ' . $this->field_id . ' = :' . $this->field_id;
    }

    /**
     * 
     */
    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'ID');
        $this->updateCounts($table->aTableValues);
        $table->addColumn(t('ID'), 'ID', '10', 'left', '', 'tblheader', 'ID');
        $table->addColumn(t('LIBELLE'), 'TITLE_BO', '45', 'left', '', 'tblheader', 'TITLE_BO');
        $table->addColumn(t('TYPE'), 'TYPE', '45', 'left', '', 'tblheader', 'TYPE');
        $table->addColumn(t('USED_COUNT'), 'USED_COUNT', '45', 'left', 'number', 'tblheader', 'USED_COUNT');
        $table->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => 'ID'), 'center');
        $table->addInput(t('POPUP_LABEL_DEL'),  'button', array('id' => 'ID', '' => 'readO=true'), 'center','USED_COUNT=0' );
        $notice = '* '. t('NDP_MSG_CTA_USED').'<br /><br />';
        $this->setResponse($notice.$table->getTable());
    }
    
    /**
     * 
     * @return array
     */
    protected function loadLinkedCta($linkedCta) {
        $bind = array();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $bind[':ID'] = (int) $linkedCta;
        $sqlList = 'SELECT
                        c.*
                    FROM
                            #pref#_cta c
                    WHERE
                            c.LINKED_CTA = :ID
                            AND c.SITE_ID = :SITE_ID
                            AND c.LANGUE_ID = :LANGUE_ID
                       ';

        $cta = $this->con->queryRow($sqlList, $bind);
        // si on a pas trouvé de fils correspond il faut cherche l'ancetre lui même
        if(!$cta) {
            $sqlList = 'SELECT
                        c.*
                    FROM
                            #pref#_cta c
                    WHERE
                            c.ID = :ID
                            AND c.SITE_ID = :SITE_ID
                            AND c.LANGUE_ID = :LANGUE_ID
                       ';

            $cta = $this->con->queryRow($sqlList, $bind);
        }
        
        return $cta;
    }

    /**
     *
     * @return array
     */
    protected function findAncestor() {
        $return = null;
        $bind = array();
        $bind[':ID'] = (int) $this->id;
        $sqlList = 'SELECT
                        *
                    FROM
                            #pref#_cta c
                    WHERE
                       c.ID = :ID';

        $cta = $this->con->queryRow($sqlList, $bind);
        // celui qu'on a chargé est un parent possible
        if( !empty($cta['ID'])) {
            $return = $cta['ID'];
        }
        // sauf si il a lui même un parent
        if( !empty($cta['LINKED_CTA'])) {
            $return = $cta['LINKED_CTA'];
        }

        return $return;
    }


    public function editAction()
    {
        $this->con = Pelican_Db::getInstance();
        $linkedCta =  $this->findAncestor();
        parent::editAction();

        if (empty($this->values) && $this->id != Pelican_Db::DATABASE_INSERT_ID && $linkedCta) {
            //on a un donc un id de cta mais qui ne correspond pas a la langue courante
            //on a probablement changé de langue
            // on va donc chercher un cta qui aurait comme parent celui d'ou on vient
            $this->values = $this->loadLinkedCta($linkedCta);
            $this->id = Pelican_Db::DATABASE_INSERT_ID;
            if(!empty($this->values)) {
                $this->id = $this->values['ID'];
                if(Pelican_Db::DATABASE_INSERT == $this->form_action && !$this->readO) {
                    $this->form_action = Pelican_Db::DATABASE_UPDATE;
                }
                if(Pelican_Db::DATABASE_INSERT == $this->form_action && $this->readO) {
                    $this->form_action = Pelican_Db::DATABASE_DELETE;
                }
            }
        }
        $this->values['LINKED_CTA'] = $linkedCta;
        if( $this->form_action != Pelican_Db::DATABASE_INSERT
            && ($this->id == $this->values['LINKED_CTA'])) {
            $this->values['LINKED_CTA'] = null;
        }
        // impossible de supprimer un cta qui n'existe pas

        $form     = $this->startStandardForm();
        $form    .= $this->createEditHidden();        
        $usedLink = '0 '.t('PAGES').'/'.t('CONTENUS');
        if ($this->values['USED_COUNT'] > 0) {
            $usedLink = '<a id="open_cta"  href="javascript:void(0)" >' . $this->values['USED_COUNT'] . ' '.t('PAGES').'/'.t('CONTENUS').'</a>';
        }

        $this->setDefaultValueTo('TARGET', '_self');
        $this->setDefaultValueTo('TYPE', PsaCta::TYPE_STANDARD);
        $form .= '<tr><td class="formlib">' . t('CTA_USED_ON') . '*</td><td class="formval">' . $usedLink . '</td></tr>';
        $form .= $this->oForm->createInput('TITLE_BO', t('NDP_LABEL_CTA_BO'), Ndp_Cta_Interface::MAX_TITLE_LENGTH, 'text', true, $this->values['TITLE_BO'], $this->readO, 75);
        $form .= $this->oForm->createInput('TITLE', t('NDP_LABEL_CTA_FO'), Ndp_Cta_Interface::MAX_TITLE_LENGTH, 'text', true, $this->values['TITLE'], $this->readO, 75);
        $form .= $this->oForm->createInput('TITLE_MOBILE', t('NDP_LABEL_CTA_MOBILE_FO'), Ndp_Cta_Interface::MAX_TITLE_LENGTH, 'text', false, $this->values['TITLE_MOBILE'], $this->readO, 75);
        $form .= $this->oForm->createMedia('MEDIA_WEB_ID', t('MEDIA_WEB'), false, 'image', '', $this->values['MEDIA_WEB_ID'], $this->readO, true, false,'');
        $form .= $this->oForm->createMedia('MEDIA_MOBILE_ID', t('MEDIA_MOBILE'), false, 'image', '', $this->values['MEDIA_MOBILE_ID'], $this->readO, true, false,'');
        $form .= $this->getCtaTypesForm();
//        $form .= $this->oForm->createRadioFromList('TARGET', t('MODE_OUVERTURE'), array('_self'  => t('SELF'),'_blank' => t('BLANK')), $this->values['TARGET'], true, $this->readO);
//        $form .= $this->getPopinTransition();
        if ($this->values['USED_COUNT'] > 0) {
            $form .= $this->oForm->createJs(sprintf('return validateEditCta("%s")', t('NDP_MSG_CONFIRM_CTA_EDIT')));
        }
        $form .= $this->stopStandardForm();

        if ($this->values['USED_COUNT'] > 0) {
            $form .= $this->renderPopin();
        }
        // on est en effacement mais avec un id vide
        if( $this->id == Pelican_Db::DATABASE_INSERT_ID && $this->readO ) {
            $this->aButton["delete"] = "";
        }
        Backoffice_Button_Helper::init($this->aButton);

        $this->assign("form", $form, false);
        $this->fetch();
    }
    /**
     * 
     * @return string
     */
    protected function createEditHidden()
    {
        $form  = $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden('IS_REF', 1);
        $linkedCta = $this->values['LINKED_CTA'];
        $form .= $this->oForm->createHidden('LINKED_CTA', $linkedCta);
        $form .= $this->oForm->createHidden('TRUE_ID', $this->values['ID']);
        
        return $form;
    }
    
    /**
     * 
     * @return string
     */
    private function renderPopin()
    {
        $pages = $this->findPages($this->id);
        if (!empty($pages)) {
            $list = '<h2>'.t('PAGES').'</h2><ul>';
            foreach ($pages as $page) {
                $list .= '<li><a target="_blank" href="'.$this->getBaseUrl(). $page['PAGE_CLEAR_URL'] . '">' . $page['PAGE_TITLE_BO'] . '</a></li>';
            }
            $list .= '</ul>';
        }
        $contents = $this->findContents($this->id);
        if (!empty($contents)) {
            $list .= '<h2>'.t('CONTENUS').'</h2><ul>';
            foreach ($contents as $content) {
                $list .= '<li>' . $content['CONTENT_TITLE_BO'] . '</li>';
            }
            $list .= '</ul>';
        }
        $javascript = '<script type="text/javascript">
             $( "#popin_cta" ).dialog({autoOpen: false});
            jQuery("#open_cta").click(function() {
                       $( "#popin_cta" ).dialog("open");
             });
            </script>';


        return '<div style="display:none" id="popin_cta">' . $list . ' <div>' . $javascript;
    }

    /**
     *
     * @return string
     */
    public function getCtaTypesForm()
    {
        $containerName = 'type';
        $callback = $this->addJsContainerRadio($containerName);
        $form  = $this->oForm->createRadioFromList('TYPE', t('TYPE'), $this->getTypeCtas(), $this->values['TYPE'], true, $this->readO, 'h', false, $callback);

        if (!$this->getParam('readO')) {
            $msgStandard = t('NDP_MSG_PLEASE_CHOOSE').' '.t('NDP_FOR').' '.(strip_tags(str_replace('"', '\\"',  t('NDP_URL_CTA'))));
            $msgClickToCall = t('NDP_MSG_PLEASE_CHOOSE').' '.t('NDP_FOR').' '.(strip_tags(str_replace('"', '\\"',  t('NDP_LIB_CTA_CLICK_TO_CALL'))));
            $msgOnlyNumbers = t('NDP_CLICK_TO_CALL_ONLY_NUMBER');
            $this->oForm->createJs(sprintf('checkCtaUrl("%s", "%s", "%s");', $msgStandard, $msgClickToCall, $msgOnlyNumbers));
        }
        foreach ($this->getTypeCtas() as $type => $label) {
            $value = '';
            if ($type == $this->values['TYPE']) {
                $value = $this->values['ACTION'];
            }
            $form .=$this->addHeadContainer($type,$this->values['TYPE'],$containerName);
            switch ($type) {
                case PsaCta::TYPE_CLICK_TO_CHAT:
                case PsaCta::TYPE_JS:
                    $length = 60000;
                    $form .= $this->oForm->createTextArea('ACTION_' . $type, t('NDP_LIB_CTA_' . $type), true, $value, $length, $this->readO, 5, 100);
                    break;
                case PsaCta::TYPE_STANDARD:
                    $value = (empty($value))?  'http://' : $value;
                    $form .= $this->oForm->createInput('ACTION_' . $type, t('NDP_URL_CTA'), 255, 'internallink', true, $value, $this->readO, 75, false);
                    break;
                case PsaCta::TYPE_CLICK_TO_CALL:
                    $value = (empty($value))?  '' : $value;
                    $length = 255;
                    $form .= $this->oForm->createInput('ACTION_' . $type, t('NDP_LIB_CTA_' . $type), $length, '', true, $value, $this->readO, 75);
                    break;
                default:
                    $length = 255;
                    $form .= $this->oForm->createInput('ACTION_' . $type, t('NDP_LIB_CTA_' . $type), $length, '', true, $value, $this->readO, 75);
            }
            $form .= $this->addFootContainer();
        }
        return $form;
    }
    
    /**
     * 
     * @return array
     */
    protected function getTypeCtas()
    {
        return array(
            PsaCta::TYPE_STANDARD           => t('NDP_STANDARD'),
            PsaCta::TYPE_CLICK_TO_CALL      => t('CLICK_TO_CALL'),
//            PsaCta::TYPE_CLICK_TO_CHAT      => t('CLICK_TO_CHAT'),
//            PsaCta::TYPE_CLICK_WEB_CALLBACK => t('NDP_WEB_CALLBACK'),
//            PsaCta::TYPE_JS                 => t('JS')
        );
    }

    /**
     * 
     * @return string
     */
    protected function getPopinTransition()
    {
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $containerName = 'popinContainer';
        $callback = $this->addJsContainerRadio($containerName);
        $this->setDefaultValueTo('POPIN_ACTIVE', self::DISABLE);
        $form = $this->oForm->createRadioFromList("POPIN_ACTIVE", t('NDP_POPIN_TRANSITION'), $targetsAffichage, $this->values['POPIN_ACTIVE'], false, $this->readO, 'h', false, $callback);
        
        $form .= $this->addHeadContainer(self::ENABLE, $this->values['POPIN_ACTIVE'], $containerName);
        $containerName = 'popinContainerConfirm';
        $callback = $this->addJsContainerRadio($containerName);
        $this->setDefaultValueTo('POPIN_CONFIRMATION', self::DISABLE);
        $form .= $this->oForm->createRadioFromList("POPIN_CONFIRMATION", t('NDP_CONFIRM_POPIN_TRANSITION'), $targetsAffichage, $this->values['POPIN_CONFIRMATION'], true, $readO, 'h', false, $callback);
        $form .= $this->oForm->createInput('POPIN_TITLE', t('TITLE'), 255, '', true, $this->values['POPIN_TITLE'], $this->readO, 75);
        $form .= $this->oForm->createTextArea("POPIN_DESC", t('NDP_DESCRIPTION'), true, $this->values['POPIN_DESC'], "", $this->readO, 10, 69);    
        $form .= $this->oForm->createMedia('POPIN_MEDIA', t('NDP_VISUEL'), true, 'image', '', $this->values['POPIN_MEDIA'], $this->readO, true, false,'NDP_RATIO_16_9:3328x1872');
        $labelButtonCancel = '';
        $labelButtonManual = $this->values['POPIN_CANCEL'];
        if ($this->values['POPIN_CONFIRMATION']) {
            $labelButtonCancel = $this->values['POPIN_CANCEL'];
            $labelButtonManual = ''; 
        }
        $form .= $this->addHeadContainer(self::DISABLE, $this->values['POPIN_CONFIRMATION'], $containerName);
        $form .= $this->oForm->createInput('POPIN_TIMING', t('NDP_TIMING'), 5, 'numerique', false, $this->values['POPIN_TIMING'], false, 5, false, '', 'text', [],false,'');
        $form .= $this->oForm->createInput('POPIN_CANCEL_MANUAL', t('NDP_MANUAL_LABEL'), 255, '', true, $labelButtonManual, $this->readO, 75);
        $form .= $this->addFootContainer();
        $form .= $this->addHeadContainer(self::ENABLE, $this->values['POPIN_CONFIRMATION'], $containerName);
        $form .= $this->oForm->createComment(t('NDP_MSG_BUTTON_CONFIRM'));
        $form .= $this->oForm->createInput('POPIN_CANCEL', t('NDP_CANCEL_LABEL'), 255, '', true, $labelButtonCancel, $this->readO, 75);
        $form .= $this->addFootContainer();
        $form .= $this->addFootContainer();
        
        return $form;
    }

    /**
     * 
     */
    public function saveAction()
    {
        $this->con =Pelican_Db::getInstance();
        $type                         = Pelican_Db::$values['TYPE'];
        Pelican_Db::$values['ACTION'] = Pelican_Db::$values['ACTION_' . $type];
        Pelican_Db::$values['USED_COUNT'] = 0;
        if (!Pelican_Db::$values['POPIN_CONFIRMATION']) {
            Pelican_Db::$values['POPIN_CANCEL'] = Pelican_Db::$values['POPIN_CANCEL_MANUAL'];
        }

        // si on est dans le cas d'un effacement
        // il faut verifié si ce cta est un cta master ou linked
        // si LINKED_CTA il est peut-etre master
        if (($this->form_action == Pelican_Db::DATABASE_DELETE) && empty(Pelican_Db::$values['LINKED_CTA']))  {
            // recherche des ces fils
            $sql= 'SELECT * FROM #pref#_'.$this->form_name.' WHERE LINKED_CTA ='.Pelican_Db::$values[$this->field_id] ;
            $res = $this->con->queryTab($sql,[]);
            if(!empty($res)) {
                $this->updateAncestors($res);
            }
        }

        parent::saveAction();
    }


    protected function updateAncestors($childs) {

        $oldValues = Pelican_Db::$values;
        $first = array_shift($childs);
        $this->setAsAncestor($first);
        if(!empty($childs)) {
            $this->setAncestor($childs, $first);
        }
        Pelican_Db::$values = $oldValues;

       return $this;
    }

    protected function setAsAncestor($cta)
    {
        $sql = 'UPDATE #pref#_'.$this->form_name.' SET LINKED_CTA = NULL WHERE ID = '.$cta['ID'];
        $this->con->query($sql,[]);

        return $this;
    }

    protected function setAncestor($ctas, $ancestor)
    {
        foreach( $ctas as $cta) {
            $sql = 'UPDATE #pref#_'.$this->form_name.' SET LINKED_CTA = '.$ancestor['ID'].' WHERE ID = '.$cta['ID'];
            $this->con->query($sql,[]);
        }

        return $this;
    }

    private function updateCounts(&$rows)
    {
        $ids = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $ids[] = $row['ID'];
            }
            $counts = $this->countUsed($ids);
            foreach ($counts as $id => $count) {
                $this->updateCta($id, $count);
            }
            foreach ($rows as $k => $row) {
                $rows[$k]['USED_COUNT'] = (isset($counts[$row['ID']])) ? $counts[$row['ID']] : 0;
            }
        }
    }

    private function updateCta($id, $count)
    {

        $oldValues          = Pelican_Db::$values;
        $con                = Pelican_Db::getInstance();
        $sql                = 'UPDATE #pref#_cta set used_count =  ' . intval($count) . ' WHERE ID =' . intval($id);
        $con->query($sql);
        
        Pelican_Db::$values = $oldValues;
    }

    /**
     *  Count how many page use a CTA.
     *
     * @param array $ids
     *
     * @return array
     */
    private function countUsed($ids)
    {
        $result = [];
        if (!empty($ids)) {
            $this->countUsedPageGeneric($ids, 'page_zone_cta', $result);
            $this->countUsedPageGeneric($ids, 'page_zone_cta_cta', $result);
            $this->countUsedPageGeneric($ids, 'page_zone_multi_cta', $result);
            $this->countUsedPageGeneric($ids, 'page_multi_zone_cta', $result);
            $this->countUsedPageGeneric($ids, 'page_multi_zone_cta_cta', $result);
            $this->countUsedContentGeneric($ids, 'content_version_cta', $result);
            $this->countUsedContentGeneric($ids, 'content_version_cta_cta', $result);
        }

        return $result;
    }

    private function sum_array($leftArray, $rightArray)
    {

        foreach ($rightArray as $key => $value) {
            if (!isset($leftArray[$key])) {
                $leftArray[$key] = 0;
            }
            $leftArray[$key] += $value;
        }

        return $leftArray;
    }

    /**
     * count how many pages use a CTA linked to Page
     *
     * @param array   $ids
     * @param string $table
     */
    private function countUsedPageGeneric($ids, $table, &$count)
    {
        $sql = 'SELECT
                    COUNT(DISTINCT gc.PAGE_ID) as count,
                    gc.CTA_ID as ID
                FROM #pref#_page p
                    JOIN #pref#_page_version pv ON pv.PAGE_ID= p.PAGE_ID AND p.PAGE_CURRENT_VERSION= pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID
                    JOIN #pref#_' . $table . ' gc ON p.PAGE_ID=gc.PAGE_ID AND p.PAGE_CURRENT_VERSION=gc.PAGE_VERSION AND p.LANGUE_ID=gc.LANGUE_ID
                WHERE
                   pv.STATE_ID = ' . self::PAGE_PUBLISHED . '
                   AND p.PAGE_STATUS = 1
                   AND gc.CTA_ID IN  (' . implode(',', $ids) . ')
                GROUP BY gc.CTA_ID';
        $con = Pelican_Db::getInstance();
        $temp   = $con->queryTab($sql, array());
        $result = array();
        foreach ($temp as $row) {
            $result[$row['ID']] = $row['count'];
        }
        $count = $this->sum_array($count, $result);
    }

    /**
     * count how many pages use a CTA linked to Page
     *
     * @param array   $ids
     * @param string $table
     */
    private function countUsedContentGeneric($ids, $table, &$count)
    {
        $sql = 'SELECT
                    COUNT(DISTINCT gc.CONTENT_ID) as count,
                    gc.CTA_ID as ID
                FROM #pref#_content c
                    JOIN #pref#_content_version cv ON cv.CONTENT_ID = c.CONTENT_ID AND c.CONTENT_CURRENT_VERSION = cv.CONTENT_VERSION AND c.LANGUE_ID = cv.LANGUE_ID
                    JOIN #pref#_' . $table . '  gc ON gc.CONTENT_ID = c.CONTENT_ID AND c.CONTENT_CURRENT_VERSION = gc.CONTENT_VERSION AND c.LANGUE_ID=gc.LANGUE_ID
                WHERE
                   cv.STATE_ID = ' . self::PAGE_PUBLISHED . '
                   AND c.CONTENT_STATUS = 1
                   AND gc.CTA_ID IN  (' . implode(',', $ids) . ')
                GROUP BY gc.CTA_ID';
        $con = Pelican_Db::getInstance();
        $temp   = $con->queryTab($sql, array());
        $result = array();
        foreach ($temp as $row) {
            $result[$row['ID']] = $row['count'];
        }
        $count = $this->sum_array($count, $result);
    }

    /**
     * Find pages use a CTA linked by  PageZone or PageZoneMulti.
     *
     * @param int  $id
     * @param string $table
     *
     * @return int
     */
    private function findPagesGeneric($id, $table)
    {
        $sql = 'SELECT
                    gc.PAGE_ID, pv.PAGE_CLEAR_URL, pv.PAGE_TITLE_BO
                FROM #pref#_page p
                    JOIN #pref#_page_version pv ON pv.PAGE_ID= p.PAGE_ID AND p.PAGE_CURRENT_VERSION= pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID
                    JOIN #pref#_' . $table . ' gc ON p.PAGE_ID=gc.PAGE_ID AND p.PAGE_CURRENT_VERSION=gc.PAGE_VERSION AND p.LANGUE_ID=gc.LANGUE_ID
                WHERE
                   pv.STATE_ID = ' . self::PAGE_PUBLISHED . '
                   AND p.PAGE_STATUS = 1
                   AND gc.CTA_ID = ' . $id;
        $con = Pelican_Db::getInstance();

        $temp  = $con->queryTab($sql, array());
        $pages = [];
        foreach ($temp as $page) {
            $pages[$page['PAGE_ID']] = $page;
        }

        return $pages;
    }


    /**
     * Find content that use a CTA .
     *
     * @param int  $id
     * @param string $table
     *
     * @return int
     */
    private function findContentsGeneric($id, $table)
    {
        $sql = 'SELECT
                    gc.CONTENT_ID, cv.CONTENT_TITLE_BO
                FROM #pref#_content c
                    JOIN #pref#_content_version cv ON cv.CONTENT_ID = c.CONTENT_ID AND c.CONTENT_CURRENT_VERSION = cv.CONTENT_VERSION AND c.LANGUE_ID = cv.LANGUE_ID
                    JOIN #pref#_' . $table . '  gc ON gc.CONTENT_ID = c.CONTENT_ID AND c.CONTENT_CURRENT_VERSION = gc.CONTENT_VERSION AND c.LANGUE_ID = gc.LANGUE_ID
                WHERE
                   cv.STATE_ID = ' . self::PAGE_PUBLISHED . '
                   AND c.CONTENT_STATUS = 1
                   AND gc.CTA_ID = ' . $id;
        $con = Pelican_Db::getInstance();

        $temp  = $con->queryTab($sql, array());
        $contents = [];
        foreach ($temp as $page) {
            $contents[$page['CONTENT_ID']] = $page;
        }

        return $contents;
    }


    /**
     * 
     * @param int $id
     * @return array
     */
    private function findPages($id)
    {
        $pages = [];
        $pages += $this->findPagesGeneric($id, 'page_zone_cta');
        $pages += $this->findPagesGeneric($id, 'page_zone_cta_cta');
        $pages += $this->findPagesGeneric($id, 'page_zone_multi_cta');
        $pages += $this->findPagesGeneric($id, 'page_multi_zone_cta');
        $pages += $this->findPagesGeneric($id, 'page_multi_zone_cta_cta');

        return $pages;
    }
    /**
     *
     * @param int $id
     * @return array
     */
    private function findContents($id)
    {
        $contents = [];
        $contents += $this->findContentsGeneric($id, 'content_version_cta');
        $contents += $this->findContentsGeneric($id, 'content_version_cta_cta');

        return $contents;
    }
}
