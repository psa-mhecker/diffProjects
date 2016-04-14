    <?php
    class Ndp_GoogleAnalytics_Controller extends Pelican_Controller_Back
    {
        protected $administration = false; //true

        protected $form_name = "googleanalytics";

        public function listAction()
        {
            $url = 'https://accounts.google.com/ServiceLogin?service=analytics&passive=true&nui=1&hl=fr';

            $redirect = '
                <script type="text/javascript">
                    window.open(\''.$url.'\', \'_blank\');
                </script>

                <p style="text-align:center">
                    <br/><br/>
                    '.t('NDP_MSG_GOOGLEANALYTICS_LINK').'
                    <br/><br/>
                    <a href="'.$url.'" target="_blank">'.$url.'</a>
                </p>
            ';

            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
            $this->setResponse($redirect);
        }

    }
