<?php
class Ndp_GestionGamme_Controller extends Pelican_Controller_Back
{
    protected $administration = false; //true

    protected $form_name = "googleanalytics";

    public function listAction()
    {
        $con = Pelican_Db::getInstance();
        $sql = 'SELECT  SITE_RANGE_MANAGER FROM #pref#_sites_et_webservices_psa WHERE SITE_ID=:SITE_ID ';
        $url = $con->queryItem($sql, [':SITE_ID'=>$_SESSION[APP]['SITE_ID']]);
        $redirect = t('NDP_MSG_RANGE_MANAGER_LINK_MISSING');
        if (!empty($url)) {
            $redirect = '
                <script type="text/javascript">
                    window.open(\'' . $url.'\', \'_blank\');
                </script>

                <p style="text-align:center">
                    <br/><br/>
                    ' . t('NDP_MSG_RANGE_MANAGER_LINK').'
                    <br/><br/>
                    <a href="' . $url.'" target="_blank">'.$url.'</a>
                </p>
            ';
        }
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $this->setResponse($redirect);
    }

}
