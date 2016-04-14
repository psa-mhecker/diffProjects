<?php
/**
 * Fichier de Citroen_ServicePDV:.
 *
 *
 *
 * @author Joseph Franclin <Joseph.Franclin@businessdecision.com>
 *
 * @since 20/08/2014
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php';

class Citroen_Administration_ServicePDV_Controller extends Citroen_Controller
{
    protected $administration = true;

    protected $multiLangue = true;
    protected $form_name = "";
    protected $defaultOrder = "";
    protected $field_id = "CODE_ID";

    protected function setListModel()
    {
    }

    public function listAction()
    {
    }

    public function saveAction()
    {
    }
}
