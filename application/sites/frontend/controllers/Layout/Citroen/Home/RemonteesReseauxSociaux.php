<?php
class Layout_Citroen_Home_RemonteesReseauxSociaux_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);
        $this->assign("session", $_SESSION[APP]);
        
        $reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));       
        
		foreach($reseauxSociaux as $key => $reseauSocial){
			if($reseauSocial['RESEAU_SOCIAL_TYPE'] == Pelican::$config['YOUTUBE']){
				$reseauxSociaux[$key]['RESEAU_SOCIAL_URL_WEB'] = $reseauSocial['RESEAU_SOCIAL_URL_WEB'] . '?id=' . $reseauSocial['RESEAU_SOCIAL_ID'];
				$reseauxSociaux[$key]['RESEAU_SOCIAL_URL_MOBILE'] = $reseauSocial['RESEAU_SOCIAL_URL_MOBILE'] . '?id=' . $reseauSocial['RESEAU_SOCIAL_ID'];
			}
		}
		$this->assign("reseauxSociaux", $reseauxSociaux);

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$this->assign("sLangue", $sLangue);
        $this->fetch();
    }
	
	public function instagramAction()
    {
        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);

        $reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
						date("Ymdh")
        ));
        $this->assign("reseauxSociaux", $reseauxSociaux);

        $this->fetch();
		$this->getRequest()->addResponseCommand("append", array(
			'id' => 'instagramFeed',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
    }
	
    public function youtubeFeedsAction(){
		header('Content-type: application/json');
		if(!empty($_GET['id'])){
			$idYoutube = $_GET['id'];
		}else{
			return false;
		}		
		$reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
		if(empty($reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_ID_COMPTE'])|| empty($reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_KEY_API'])){
			return false;
		}		
		$aDatas = array(
						'part'			=> 'snippet,contentDetails', 
						'channelId' 	=> $reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_ID_COMPTE'],
						'maxResults' 	=> (empty($reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_NB_FLUX'])) ? 10 : $reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_NB_FLUX']
					);		
		$urlActivities	=	'/youtube/v3/activities?key=' . $reseauxSociaux[$idYoutube]['RESEAU_SOCIAL_KEY_API'];
		Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = $urlActivities;
        Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider'] = '';		
        $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
        $oResponse = $oService->call('activitiesList', $aDatas);
        echo  json_encode($oResponse->getItems());		
    }		
}
