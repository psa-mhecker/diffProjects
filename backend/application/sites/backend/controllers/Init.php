<?php
include 'Administration/Template.php';

class Init_Controller extends Pelican_Controller
{
    protected $administration = true;

    protected $form_name = "init";

    protected $subDir = 'Teletoon';

    protected $dir = array(
        'int' => '/projects/dev/phpfactory/var',
        'root' => '/application/sites',
        'front_module' => '/frontend/controllers',
        'back_module' => '/backend/controllers',
    );

    protected $skeletonType = array(
        'back_admin' => 1,
        'back_module' => 10,
        'back_content' => 3,
        'front_module' => 20,
    );

    public function indexAction()
    {
        $zone[] = array(
            'label' => 'Texte riche',
            'type' => 'P',
            'path' => 'Teletoon_RichText',
            'action' => 'index,insert,update',
        );

        if (isset($zone)) {
            foreach ($zone as $value) {
                $return['data'] = $this->_buildData($value, 'back_module');
                $return['code'] = $this->_buildCode($value, 'back_module');
                $return['file'] = $this->_buildFile($value, 'back_module');
            }
        }
        var_dump($return);
    }

    protected function _buildData($value, $type)
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values = array();

        $zone_type = array(
            'P' => 1,
            'A' => 2,
            'H' => 3,
        );

        switch ($type) {
            case 'back_module':
                {
                    //Pelican_Db::$values['ZONE_ID'] = '';
                    Pelican_Db::$values['ZONE_TYPE_ID'] = $zone_type[$value['type']];
                    Pelican_Db::$values['ZONE_LABEL'] = $value['label'];
                    if ($value['type'] == 'P' or $value['type'] == 'H') {
                        Pelican_Db::$values['ZONE_BO_PATH'] = 'Cms_Page_'.$value['path'];
                    }
                    Pelican_Db::$values['ZONE_FO_PATH'] = 'Layout_'.$value['path'];
                    Pelican_Db::$values['ZONE_IFRAME'] = '';
                    Pelican_Db::$values['ZONE_AJAX'] = '';
                    Pelican_Db::$values['ZONE_CATEGORY_ID'] = '';
                    $oConnection->replaceQuery('#pref#_zone', "ZONE_LABEL='".str_replace("'", "''", $value['label'])."' and ZONE_TYPE_ID=".$zone_type[$value['type']]);

                    break;
                }
        }

        return Pelican_Db::$values;
    }

    protected function _buildCode($value, $type)
    {
        $return = Administration_Template_Controller::getSkeleton($this->skeletonType[$type], $value['path']);

        return $return;
    }

    protected function _buildFile($value, $type)
    {
        $return = $this->dir['init'].$this->dir['root'].$this->dir[$type].'/'.str_replace('_', '/', $value['path']).'.php';

        return $return;
    }
}
