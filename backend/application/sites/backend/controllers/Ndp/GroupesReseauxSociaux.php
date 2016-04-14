<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_GroupesReseauxSociaux_Controller extends Ndp_Controller
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "groupe_reseaux_sociaux";
    protected $field_id = "SITE_ID";

    /**
     *
     */
    public function listAction()
    {
        parent::_initBack();
        $this->_forward('edit');
    }

    /**
     *
     */
    public function editAction()
    {
        parent::editAction();

        $this->aButton['add'] = '';

        $form = $form = $this->startStandardForm();

        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);

        $sSqlData = 'SELECT RESEAU_SOCIAL_ID as id, RESEAU_SOCIAL_LABEL as lib
                      FROM #pref#_reseau_social
                      WHERE SITE_ID = '.$_SESSION[APP]['SITE_ID'].'
                        AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
                      ORDER BY RESEAU_SOCIAL_ORDER asc';

        $sSqlSelected = 'SELECT DISTINCT grs.RESEAU_SOCIAL_ID
                         FROM #pref#_groupe_reseaux_sociaux grs, #pref#_reseau_social rs
                         WHERE grs.SITE_ID = '.$_SESSION[APP]['SITE_ID'].'
                           AND grs.LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
                           AND grs.RESEAU_SOCIAL_ID = grs.RESEAU_SOCIAL_ID
                         ORDER BY grs.GROUPE_ORDER ASC';

        $form .= $this->oForm->createAssocFromSql(
            '',
            'RESEAU_SOCIAUX_IDS',
            t('GROUPES_DE_RESEAUX_SOCIAUX'),
            $sSqlData,
            $sSqlSelected,
            false,
            true,
            $this->readO,
            5,
            200,
            false,
            '',
            '',
            array(),
            true, // not real field, just for access to order function
            false,
            true,
            3
        );

        $form .= $this->stopStandardForm();
        $form = formToString($this->oForm, $form);

        $this->setResponse($form);
    }

    /**
     *
     */
    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();

        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            $connection->query('DELETE FROM #pref#_groupe_reseaux_sociaux
                                WHERE SITE_ID = '.Pelican_Db::$values['SITE_ID'].'
                                AND LANGUE_ID = '.Pelican_Db::$values['LANGUE_ID']);

            if (count(Pelican_Db::$values['RESEAU_SOCIAUX_IDS']) > 0) {
                $count = 0;
                foreach (Pelican_Db::$values['RESEAU_SOCIAUX_IDS'] as $socialNetwork) {
                    $count++;
                    Pelican_Db::$values['RESEAU_SOCIAL_ID'] = $socialNetwork;
                    Pelican_Db::$values['GROUPE_ORDER'] = $count;
                    $connection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_groupe_reseaux_sociaux');
                }
            }
        } else {
            $connection->query('DELETE FROM #pref#_groupe_reseaux_sociaux
                                WHERE SITE_ID = '.Pelican_Db::$values['SITE_ID'].'
                                  AND LANGUE_ID = '.Pelican_Db::$values['LANGUE_ID']);
        }
    }
}
