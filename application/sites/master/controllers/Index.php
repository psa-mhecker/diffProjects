<?php
pelican_import('Profiler');
require_once (pelican_path('Layout'));
require_once (pelican_path('Translate'));

// temp
Pelican::$config['ARTISTEER_VERSION'] = 'artisteer/2.4';

class Index_Controller extends Pelican_Controller_Front
{

    protected $_layout;

    /**
     * @return the $_layout
     */
    public function getLayout ()
    {
        if (empty($this->_layout)) {
            $this->_layout = new Pelican_Layout();
        }
        return $this->_layout;
    }

    /**
     * @param field_type $_layout
     */
    public function setLayout ($_layout)
    {
        $this->_layout = $_layout;
    }

    public function previewAction ()
    {
        if(!isset($_SESSION["APP"]["PREVIEW"]["LOGGED"])){
            //head
            $head = $this->getView()
                ->getHead();
            $head->setCss("/css/preview-connect.css");
            $head->setJs("/js/jquery-1.8.0.min.js");
            $head->setJs("/js/jquery.tools.min.meta.js");
            $head->setJs("/js/jquery.jcarousel.min.js");
            $head->setJs("/js/jquery.functions.js");
            $head->setTitle(t("Authentification"));
            //$head->setTitle(t("PREVIEW_TITLE"));


            //login
            $inputLogin = Pelican_Html::input(array(
                    'name' => "preview_login", 'id' => "preview_login", 'value' => $this->getParam('login')
                ));
            $labelLogin = Pelican_Html::div(array(
                    'name' => "label_mdp", 'id' => "label_mdp", 'class' => "label_preview"
                ), t("PREVIEW_LOGIN"));
            $inputLoginDiv = Pelican_Html::div(array(
                    'class' => "loginMdpDiv",
                ), $labelLogin . $inputLogin);


            //mot de passe
            $inputMdp = Pelican_Html::input(array(
                    'name' => "preview_mdp", 'id' => "preview_mdp", 'type' => "password"
                ));
            $labelMdp = Pelican_Html::div(array(
                    'name' => "label_mdp", 'id' => "label_mdp", 'class' => "label_preview"
                ), t("PREVIEW_MDP"));
            $inputMdpDiv = Pelican_Html::div(array(
                    'class' => "loginMdpDiv",
                ), $labelMdp . $inputMdp);

             $title = Pelican_Html::div(array(
                    'id' => "title_preview"
                //), t("PREVIEW_TITLE"));
                ), t("Authentification"));

             $inputButtonPreview = Pelican_Html::input(array(
                    'type' => "submit",  'id' => "btnPreviewValid"
                ));

            $inputDivPreview = Pelican_Html::div(array(
                    'id' => "previewLoginBox",
                ), $title . $inputLoginDiv . $inputMdpDiv . $inputButtonPreview);


            $formPreview = Pelican_Html::form(array(
                    'id' => "previewForm",
                    'method' => "previewForm",
                    'action' => "/_/Index/connectPreview",
                ), $inputDivPreview);

            $this->setResponse($head->getHeader(false) . $formPreview);

            $pid = $this->getParam('pid');
            if(!empty($pid)){
                $_SESSION["APP"]["PREVIEW"]["PID"] = $this->getParam('pid');
            }
        }else{
            $_GET["preview"] = 1;
            $cachetimeout = - 1;
            $this->_forward('index');
        }
    }

    public function indexAction ()
    {
        // profiling
        Pelican_Profiler::start('header', 'page');

        //head
        $head = $this->getView()
            ->getHead();

        //chargement jquery pour le choix du device
        if(isset($_SESSION["APP"]["PREVIEW"]["LOGGED"]) && strripos($_SERVER["REQUEST_URI"], "preview")){
            $head->setCss("/css/preview-connect.css");

            if(is_array(Pelican:: $config['USER_AGENT_LIST'])){
                $option = "<option value=''>---".t("PREVIEW_CHOICE")."---</option>";
                foreach(Pelican:: $config['USER_AGENT_LIST'] as $lib=>$userAgent){
                    $selected = false;
                    if($userAgent == $this->getParam("useragent")){
                        $selected = "selected=selected";
                    }
                    $option .= "<option value='".$userAgent."' ".$selected.">" .$lib. "</option>";
                }
            }
            $pid = $this->getParam('pid');
            $pidHidden = Pelican_Html::input(array(
                    'id' => "pid_preview_hidden",
                    'type' => "hidden",
                    'value' => $pid
                ));

            $listeDevice = Pelican_Html::select(array(
                    'id' => "select_device"
                ), $option);
            $listeDeviceDiv = Pelican_Html::div(array(
                ), $listeDevice);
            $previewDeviceChoice = Pelican_Html::div(array(
                    'id' => "previewUserAgent"
                ), $pidHidden . $listeDeviceDiv);
        }

        //layout
        $layout = $this->getLayout();
        // Site initialisation
        $layout->getInfos();
        if (! $layout->isValid() || strcmp($layout->aPage['PAGE_TITLE'], "") == 0) {
            $this->sendError(404, '');
        }

        //---------> Build Page
        // metaTags
        $layout->getMetaTag();

        $head->setTitle($layout->getPageTitle());
        Pelican_Profiler::stop('header', 'page');

        Pelican_Profiler::start('zones', 'page');
        $body = $layout->getZones();
        Pelican_Profiler::stop('zones', 'page');

        Pelican_Profiler::start('fetch', 'page');
        $body .= $layout->getCybertag();

        $this->assign('header', $previewDeviceChoice . $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        $this->assign('body', $body, false);

        /** affichage de la vue */
        $this->fetch();
        Pelican_Profiler::stop('fetch', 'page');
    }

    public function connectPreviewAction(){
        $error = false;
        $errorBack = "";

        //controle login
        if($this->getParam('preview_login') != Pelican::$config["SITE"]["INFOS"]["SITE_LOGIN_PREVISU"]){
            $errorBack = "?error=login";
            $error = true;
        }

        //controle mdp
        if(md5($this->getParam('preview_mdp')) != Pelican::$config["SITE"]["INFOS"]["SITE_PWD_PREVISU"]){
            if($errorBack != ""){
                $errorBack .= "&error=mdp";
            }else{
                $errorBack .= "?error=mdp&login=" . $this->getParam('preview_login');
            }
            $error = true;
        }

        if($error == true){
            $this->redirect('/_/Index/preview' . $errorBack);
        }else{
           $_SESSION["APP"]["PREVIEW"]["LOGGED"] = 1;
           $this->redirect('/_/Index/preview?pid=' . $_SESSION["APP"]["PREVIEW"]["PID"]);
        }
    }


}