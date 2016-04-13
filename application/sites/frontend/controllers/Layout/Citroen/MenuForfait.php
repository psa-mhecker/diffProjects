<?php

/**
 * Classe d'affichage Front de la tranche MenuForfait
 *
 * @package Layout
 * @subpackage Citroen
 * @author Joseph Franclin <joseph.franclin@businessdecision.com>
 * @since 23/10/2013
 */
class Layout_Citroen_MenuForfait_Controller extends Pelican_Controller_Front {

    
    public function indexAction() {
     
        /* Initialisation des variables */
        /* Tableau des menu sélectionnées pour la page*/
        $aMenuForfait = array();
        $aRsMenuForfait = array();
        $aParams = $this->getParams();
    
        $aRsMenuForfait = Pelican_Cache::fetch("Frontend/Citroen/MenuForfait", array(
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['pid'],
        ));

        $aRsMenuForfait = self::dateForfait($aRsMenuForfait);

        
        if($aRsMenuForfait)
        {
            $i = 0;
            foreach($aRsMenuForfait as $OptionMenu)
            {
                if($OptionMenu["CONTENT_DIRECT_PAGE"])
                {
                   $aMenuForfait[$i]['TITRE'] = $OptionMenu['CONTENT_TITLE_BO'];
                   $aMenuForfait[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($OptionMenu['PICTO']),Pelican::$config['MEDIA_FORMAT_ID']['PETIT_CARRE']);
                   $aMenuForfait[$i]['CONTENT_ID'] = $OptionMenu['CONTENT_ID'];  
                   $aMenuForfait[$i]['CONTENT_CLEAR_URL'] = $OptionMenu['CONTENT_CLEAR_URL'];
                   $i++;
               }
            }
        }
        if(!isset($_GET['Forfait']))
        {
             $this->assign('forfaitSelected', false);

        }
        else
        {
            $this->assign('forfaitSelected', true);  
            $this->assign('idForfaitSelected', $_GET['Forfait']);
          
        }
        
        $aTries = Pelican_Cache::fetch("Frontend/Page/ChildContent", array(
                    $aParams["pid"] ,
                    $aParams['SITE_ID'] ,
                    $_SESSION[APP]['LANGUE_ID'] ,
                    "CURRENT" ,
                    Pelican::$config['ASSISTANT']['CONTENT'][3] ,
                    20 ,
                    "" ,
                    "" 
                ));
        
        // Récupération de tous les forfaits correction ticket CPW-2999
        if(is_array($aTries)){
            $aTriesContenu  =   array();
            foreach ( $aTries as $aTrie){
                $aTriesContenu  = array_merge($aTriesContenu, $aTrie);
            }
        }
        
        $aMenuForfaitTrie = array();               
        // tri par ordre affichage
        for($it=0; $it < count($aTriesContenu); $it++)
        {
            for ($yt=0; $yt < count($aTriesContenu); $yt++) 
            { 
                if($aTriesContenu[$it]["ID"] == $aMenuForfait[$yt]["CONTENT_ID"] )
                {
                    $aMenuForfaitTrie[] = $aMenuForfait[$yt];
                }
            }
        }
        $aMenuForfait = $aMenuForfaitTrie;
       
        $this->assign('aMenuForfait', $aMenuForfait);
        $this->assign('lastElement', count($aMenuForfait));
        $this->fetch();
     
    }

    static private function dateForfait($aResultsForfait)
    {

        $aTemp = array();
        $n = 0;
        // test des dates de publications et fin
        if (is_array($aResultsForfait) && !empty($aResultsForfait)) {
            foreach($aResultsForfait as $aOneHistoire) {
                if($aOneHistoire['CONTENT_START_DATE']) {
                    //si date de debut
                    if($aOneHistoire['CONTENT_END_DATE']) {
                        //si date debut + fin
                        if($aOneHistoire['CONTENT_END_DATE'] >= date("Y-m-d G:i:s") && date("Y-m-d G:i:s") >= $aOneHistoire['CONTENT_START_DATE']) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    } else {
                        //juste debut
                        if(date("Y-m-d G:i:s") >= $aOneHistoire['CONTENT_START_DATE']) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    }
                } else {
                    if($aOneHistoire['CONTENT_END_DATE']) {
                        //si date de fin sans debut
                        if($aOneHistoire['CONTENT_END_DATE'] >= date("Y-m-d G:i:s")) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    } else {
                      //si ni fin ni debut
                        $aTemp[$n] = $aOneHistoire;
                    }
                }
                $n++;
            }
        }
        return $aTemp;
    }
    
}

?>
