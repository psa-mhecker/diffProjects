<?php
/*
** Class de verification de formulaire
*/
class CheckForm_Controller extends Pelican_Controller_Back
{
    public function checkFormAction()
    {
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm = unserialize($_SESSION["INSTANCE_FORM"]);

        $aErrorMessages = json_encode($oForm->getFormValidation());

        header('Content-type: application/json');
        echo $aErrorMessages;
    }
}
