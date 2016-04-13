<?php
//require_once Pelican::$config;
require('External/Facebook/facebook.php'); // require your facebook php sdk

class Layout_Citroen_CitroenSocial_Controller extends Pelican_Controller_Front
{
        public static $aSocialNetworksFieldsMap = array(
            'FACEBOOK' => 'ZONE_TEXTE',
            'TWITTER' => 'ZONE_TEXTE2',
            'YOUTUBE' => 'ZONE_TEXTE3',
            'PINTEREST' => 'ZONE_TEXTE4',
            'INSTAGRAM' => 'ZONE_TEXTE5'
        );
        public static $sCountrySocialNetworksField='ZONE_TEXTE6';
        public static $sCorportateSocialNetworksField='ZONE_TEXTE7';
        public static $number = 10;
        
    public function indexAction(){
        $aParams = $this->getParams();
        $aSocialNetworksWall= array();
        foreach (self::$aSocialNetworksFieldsMap as $name=>$field){
            $aSocialNetworksWall[$name]=Pelican_Cache::fetch(
                'Frontend/Citroen/CitroenSocial/SocialNetworks',
                array(
                   $aParams["SITE_ID"], $aParams["LANGUE_ID"], explode(';',$aParams[$field])
                )
            );
            
        }
        $_SESSION[APP]['CitroenSocial'] = $aSocialNetworksWall;
        
        $aCountrySocialNetworks = Pelican_Cache::fetch(
                'Frontend/Citroen/CitroenSocial/SocialNetworks',
                array($aParams["SITE_ID"], $aParams["LANGUE_ID"],explode(';',$aParams[self::$sCountrySocialNetworksField]))
                );

        $aCorporateSocialNetworks = Pelican_Cache::fetch(
                'Frontend/Citroen/CitroenSocial/SocialNetworks',
                array($aParams["SITE_ID"], $aParams["LANGUE_ID"],explode(';',$aParams[self::$sCorportateSocialNetworksField]))
                );
        $this->assign('aParams',$aParams);
        $this->assign('aSocialNetworksWall',$aSocialNetworksWall);
        $this->assign('aSocialNetworksFields',self::$aSocialNetworksFieldsMap);
        $this->assign('aSocialNetworksTypes',array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']));
        $this->assign('aUnusualNetworks',array('facebook','twitter','google+'));
        $this->assign('aCorporateSocialNetworks',$aCorporateSocialNetworks);
        $this->assign('aCountrySocialNetworks',$aCountrySocialNetworks);
        $this->assign('sCodeLangue',$_SESSION[APP]['LANGUE_CODE']);
        $this->assign('sIncludeTplPath',Pelican::$config['APPLICATION_VIEWS'].'/Layout/Citroen/CitroenSocial/');

        $this->fetch();
    }
    
    public function moreSocialAction($start) {
        
        header('Content-type: application/json');
        
        $start = $_GET['start'];
        
        $aSocial = Pelican_Cache::fetch(
            'Frontend/Citroen/CitroenSocial/Items',
            array(
                $_SESSION[APP]['CitroenSocial'],
                self::$number
            )
        );
        
        $social = $aSocial[($start / self::$number)];
        $length = count($social);
        if (isset($aSocial[($start / self::$number) + 1])) {
            $nextStart = $_GET['start'] + self::$number;
        } else {
            $nextStart = '';
        }
        
        $json =  json_encode($social);
        $return = <<<JSON
{
	"length":"{$length}",
	"data":$json,
    "nextstart":"{$nextStart}"
}
JSON;
        
        echo $return;die;
    }
    
    
}
