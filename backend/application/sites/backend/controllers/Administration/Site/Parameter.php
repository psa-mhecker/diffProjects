<?php
require_once Pelican::$config ["APPLICATION_CONTROLLERS"]."/Administration/Directory.php";

/**
 * Formulaire de gestion d'un parametre du site.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 27/05/2015
 */
class Administration_Site_Parameter_Controller extends Ndp_Controller
{

    protected $administration = true;
    protected $form_name = "site_parameter";
    protected $field_id = 'SITE_ID';

    protected function init()
    {
        parent::init();
        $params = $this->getParams();
        $this->id = $params['SITE_ID'];
    }

    protected function setEditModel()
    {
        $params = $this->getParams();

        $this->editModel = "SELECT * FROM #pref#_".$this->form_name."
                WHERE SITE_ID = ".(int) $params['SITE_ID'].
            " AND SITE_PARAMETER_ID = '".$params['SITE_PARAMETER_ID']."'";
    }

    public function listAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        self::init();
        parent::editAction();
        $params = $this->getParams();


        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;

        $this->setDefaultValueTo($params['SITE_PARAMETER_ID'], $params['DEFAULT_VALUE']);

        $params['SIZE'] = !empty($params['SIZE']) ? $params['SIZE'] : 100;
        $params['MAX_SIZE'] = !empty($params['MAX_SIZE']) ? $params['MAX_SIZE'] : 255;
        $params['LABEL'] = !empty($params['LABEL']) ? $params['LABEL'] : t($params['SITE_PARAMETER_ID']);
        $params['TYPE'] = !empty($params['TYPE']) ? $params['TYPE'] : '';

        $form = $oForm->createInput(
            $params['SITE_PARAMETER_ID'], $params['LABEL'], $params['MAX_SIZE'], $params['TYPE'], false, $this->values['SITE_PARAMETER_VALUE'], $this->readO, $params['SIZE']
        );

        $this->setResponse($form);
    }

    public function saveAction()
    {
        self::init();

        $params = $this->getParams();
        $connection = Pelican_Db::getInstance();
        $save = Pelican_Db::$values;
        if(isset($params['HMVC']) && $params['HMVC']) {
            foreach ($params as $name => $value) {
                Pelican_Db::$values[$name] = $value;
            }
        }

        $connection->query('delete from #pref#_'.$this->form_name.
            ' WHERE SITE_ID = '.$params['SITE_ID'].
            ' AND SITE_PARAMETER_ID = "'.$params['SITE_PARAMETER_ID'].'"');
        if (!empty(Pelican_Db::$values[$params['SITE_PARAMETER_ID']])) {
            Pelican_Db::$values['SITE_ID'] = $params['SITE_ID'];
            Pelican_Db::$values['SITE_PARAMETER_ID'] = $params['SITE_PARAMETER_ID'];
            Pelican_Db::$values['SITE_PARAMETER_VALUE'] = Pelican_Db::$values[$params['SITE_PARAMETER_ID']];
            $connection->insertQuery('#pref#_'.$this->form_name);
        }

        Pelican_Db::$values = $save;
    }
}
