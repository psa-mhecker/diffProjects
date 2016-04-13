<?php
class Layout_Citroen_ContenuGrandVisuel_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        /*
         * Infos de la page pour le titre
         */
        $aParams = $this->getParams();
		

	

       
        // Titre du Forfait entretien
        if(isset($_GET['Forfait']))
        {
            $CONTENT_ID = $_GET['Forfait'];
            $aRsListeForfait = Pelican_Cache::fetch("Frontend/Citroen/ListeForfait", array(
                $_SESSION[APP]['LANGUE_ID'],
                'VISUELFORFAIT',
                $CONTENT_ID,
                $aParams['pid']
            ));
            if($aRsListeForfait){
                $aParams['PAGE_TITLE'] = $aRsListeForfait[0]['TITRE'];
            }
        }
        
        /*
         * Infos de la zone pour le visuel
         */
        if ($aParams['ZONE_TITRE11']) {
            $temp = Pelican_Cache::fetch("Frontend/Citroen/HeritageGrandVisuel", array(
                $aParams['PAGE_PARENT_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                "CURRENT"
            ));
            if($temp['MEDIA_PATH']) {
                $aParams['MEDIA_PATH'] = $temp['MEDIA_PATH'];
            }
        }
        if ($aParams['MEDIA_PATH']) {
            if ($this->isMobile()) {
                $aParams['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aParams['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_GRAND_VISUEL']);
            }
            else {
                $aParams['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aParams['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_GRAND_VISUEL']);
            }

        }
		if(!empty($aParams['PAGE_PRIMARY_COLOR']) && !empty($aParams['PAGE_SECOND_COLOR'])){//temporaire pour la livraiuson 2.10
				$aParams['PRIMARY_COLOR'] = $aParams['PAGE_PRIMARY_COLOR'];
				$aParams['SECOND_COLOR']  = $aParams['PAGE_SECOND_COLOR'];
		}
        $this->assign("aParams", $aParams);

        /*
         * Test de présence d'une StickyBar dans la page
         */
        $aStickyBar = Pelican_Cache::fetch("Frontend/Citroen/StickyBar", array(
            $aParams['pid'],
            $aParams['PAGE_PARENT_ID'],
            $aParams['TEMPLATE_PAGE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "CURRENT"
        ));

        if (sizeof($aStickyBar) >= 2   && !$this->isMobile()) {
            $this->assign("bSticky", 1);
        }

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'],$aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
        $this->assign("sSharer", $sSharer);
        
        // Pour la page plan du site, on affiche à la fois le visuel et le titre (CPW-2423)
        if($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['PLAN_DU_SITE']){
            $this->assign("show_both_title_visuel", true);
        }

        $this->fetch();
    }

}