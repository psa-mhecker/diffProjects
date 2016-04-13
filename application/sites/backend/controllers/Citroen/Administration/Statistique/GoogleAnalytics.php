    <?php  
    class Citroen_Administration_Statistique_GoogleAnalytics_Controller extends Pelican_Controller_Back  
    {  
        protected $administration = false; //true  
      
        protected $form_name = "googleanalytics";  
      
        protected $field_id = "GOOGLEANALYTICS_ID";  
      
        protected $defaultOrder = "GOOGLEANALYTICS_LABEL";  
      
      
        public function listAction ()  
        {  
            parent::listAction(); 
            $url = 'https://accounts.google.com/ServiceLogin?service=analytics&passive=true&nui=1&hl=fr';
            
            $redirect = '
                <script type="text/javascript">
                    window.open(\''.$url.'\', \'_blank\');
                </script>
                
                <p style="text-align:center">
                    <br/><br/>
                    Si la page ne s\'ouvre pas, vous pouvez ouvrir la page avec le lien ci-dessous :
                    <br/><br/>
                    <a href="'.$url.'" target="_blank">'.$url.'</a>
                </p>
            ';
            
            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
            $this->setResponse($redirect);
        }  
      
        public function editAction ()  
        {  
            parent::editAction();  
    
            $this->setResponse($form);          
        }  
    }  