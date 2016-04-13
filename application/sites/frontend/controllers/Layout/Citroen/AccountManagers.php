<?php
class Layout_Citroen_AccountManagers_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();

        $aManager =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'MANAGER',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        if(is_array($aManager) && !empty($aManager)){
            $i = 0;
            foreach($aManager as $multi){
                if($aData['ZONE_ATTRIBUT'] == 1){                    
                    $tmp_mail = explode("@", $aManager[$i]['PAGE_ZONE_MULTI_LABEL5']);
                    $aManager[$i]['MAIL'] = $tmp_mail[0];
                }                
                $aManager[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($multi['MEDIA_ID']),Pelican::$config['MEDIA_FORMAT_ID']['WEB_KEY_ACCOUNT_MANGER']);
                      
                switch ($multi["PAGE_ZONE_MULTI_LABEL7"]) {
                    case 'Mademoiselle':
                        $aManager[$i]["PAGE_ZONE_MULTI_LABEL7"] = t ( 'MS_FO' );
                        break;
                    case 'Madame':
                        $aManager[$i]["PAGE_ZONE_MULTI_LABEL7"] = t ( 'MRS_FO' );
                        break;
                    case 'Monsieur':
                        $aManager[$i]["PAGE_ZONE_MULTI_LABEL7"] = t ( 'MR_FO' );
                        break;
                }
                $i++;
            }
        }
        $this->assign('aData', $aData);
        $this->assign('aManager', $aManager);
        $this->fetch();
    }
}
?>
