<?php
class Layout_Citroen_OutilChoixFinancement_Controller extends Pelican_Controller_Front
{
	public function indexAction()
	{
		$aParams = $this->getParams();
		if ($aParams['ZONE_WEB'] == 1 || $aParams['ZONE_MOBILE'] == 1) {
			$bTrancheVisible = true;
			// Dans le gabarit Mon Projet / Mes sélections, la tranche ne s'affiche que si l'utilsateur est connecté.
			if ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
				if (!isset($_GET['FINANCER'])) {
					$bTrancheVisible = false;
				} else {
					$oUser = \Citroen\UserProvider::getUser();
					if (!$oUser || !$oUser->isLogged()) {
						$bTrancheVisible = false;
					}
				}
			}
			if ($bTrancheVisible) {
				if (isset($aParams['qid']) && !empty($aParams['qid'])) {
					$iId = $aParams['qid'];
									$bDisplayReload = true;
				}
				if (isset($aParams['qpid']) && !empty($aParams['qpid'])) {
					$iPid = $aParams['qpid'];
									 $bDisplayReload = false;
				}

				if (isset($aParams["ZONE_TITRE7"]) && $aParams["ZONE_TITRE7"] != "") {
					$pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
							$aParams["ZONE_TITRE7"],
							$aParams['SITE_ID'],
							$_SESSION[APP]['LANGUE_ID']
					));
				}

				$mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array($aParams["MEDIA_ID4"]));
				$aQuestion = $this->getQuestionAndAnswers($iId, $iPid);
				if(isset($aQuestion['responses'])){
					$this->assign('ofclass', 'of'.count($aQuestion['responses']));
				}

				$this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
				$this->assign('aQuestion', $aQuestion);
				$this->assign('bDisplayReload',$bDisplayReload);
				$this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
				$this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
			}
			$this->assign('bTrancheVisible', $bTrancheVisible);
			$this->assign('aParams', $aParams);
			$this->fetch();
		}
	}

	public function getQuestionAndAnswers($iId = null, $iPid = null)
	{
		$aQuestion = $this->getQuestion($iId, $iPid);
		if (count($aQuestion)) {
			$aQuestion['responses'] = $this->getReponses($aQuestion['ARBRE_DECISIONNEL_ID']);
		}
		return $aQuestion;
	}

	protected function getQuestion($iId = null, $iPid = null)
	{
		return Pelican_Cache::fetch('Frontend/Citroen/OutilAideChoixFinancement/Question', array($iId, $iPid));
	}

	protected function getReponses($iPid = null)
	{
		return Pelican_Cache::fetch('Frontend/Citroen/OutilAideChoixFinancement/Reponses', array($iPid));
	}

	public function _cleanCacheAction()
	{
		Pelican_Cache::clean("Frontend/Citroen/OutilAideChoixFinancement");
	}

	public function getQuestionAjaxAction()
	{
		$aParams = $this->getParams();

		if(isset($aParams['id'])&&!is_null($aParams['id'])){
			$qid = $aParams['id'];
			$bDisplayReload = true;
		}else{
			$qid = null;
			$bDisplayReload = false;
		}

		if(isset($aParams['qpid'])&&!is_null($aParams['qpid'])){
			$qpid = $aParams['qpid'];

		}else{
			$qpid = null;
		}

		$aQuestion = $this->getQuestionAndAnswers($qid,$qpid);

		// Test si il s'agit d'une question de niveau 1 (première question, pas de parent), niveau 2 ou supérieur
		$niveauQuestion = null;
                
		if( empty($aQuestion['ARBRE_DECISIONNEL_PARENT_ID']) ){
			$niveauQuestion = '1';
		} elseif( empty($aQuestion['adp2_id']) ) {
			$niveauQuestion = '2';
		} else {
			$niveauQuestion = '3etplus';
		}
                
                $classes = 'row field';
		if(isset($aQuestion['responses'])){
				$class='of'.count($aQuestion['responses']);
                                $classes = sprintf('row %s field',$class);
			}

		$this->assign('aParams',$aParams);
		$this->assign('aQuestion',$aQuestion);
		$this->assign('bDisplayReload',$bDisplayReload);
		$this->assign('niveauQuestion',$niveauQuestion);
		$this->fetch();
                
                
                
		$this->getRequest()->addResponseCommand("assign", array(
			'id' => 'choix_financement',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
		$this->getRequest()->addResponseCommand("script", array(
			'value' =>'$("#choix_financement").removeClass(); $("#choix_financement").addClass("'.$classes.'");'
		));
	}

	public function getProduitFinancierAjaxAction()
	{
		// Chargement helpers (lors d'un appel AJAX on ne passe pas par le bootstrap, donc les Helpers ne sont pas chargés automatiquement)
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Video.php');
		
        $aParams = $this->getParams();
        
        // Récupération des données de la tranche produit financier à afficher (à partir de son pid et de son ordre)
        $aData = Pelican_Cache::fetch('Frontend/Citroen/OutilAideChoixFinancement/ProduitFinancier', array($aParams['qpid'],$aParams['zo']));
        
        // Récupération vidéo
        if (!empty($aData["MEDIA_ID"]) && !empty($aData["MEDIA_ID2"])) {
            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array($aData["MEDIA_ID"]));
            $this->assign('MEDIA_PATH', $mediaDetail["MEDIA_PATH"]);
            $this->assign('MEDIA_TITLE', $mediaDetail["MEDIA_TITLE"]);
            $mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array($aData["MEDIA_ID2"]));

            if ($mediaDetail2['MEDIA_TYPE_ID'] == 'video') {
                $MEDIA_VIDEO = Pelican::$config['MEDIA_HTTP'] . $mediaDetail2["MEDIA_PATH"];

                if ($mediaDetail2['MEDIA_ID_REFERENT'] != "") {
                    $OMP_mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array($mediaDetail2['MEDIA_ID_REFERENT']));
                    $MEDIA_VIDEO .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail2["MEDIA_PATH"];
                }
            } elseif ($mediaDetail2['MEDIA_TYPE_ID'] == 'youtube') {
                $MEDIA_VIDEO = Frontoffice_Video_Helper::setYoutube($mediaDetail2["YOUTUBE_ID"]);
            }
        } else {
            $MEDIA_VIDEO = '';
        }
        $this->assign('MEDIA_VIDEO', $MEDIA_VIDEO);
        
        // Récupération multi visuel
        $aMultiVisuel = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
            $aData["PAGE_ID"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'VISUEL',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
        if (is_array($aMultiVisuel) && !empty($aMultiVisuel)) {
            $i = 0;
            foreach ($aMultiVisuel as $multi) {
                $aMultiVisuel[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($multi['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_2_COLONNE_ENRICHI']);
                $i++;
            }
        }
        $this->assign('aMultiVisuel', $aMultiVisuel);
        if( !empty($aData['MEDIA_ID6']) ){
			$this->assign('VIGN_GALLERY_TOP', Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID6']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_2_COLONNE_ENRICHI']));
		}
        
		$this->assign('aParams',$aParams);
        $this->assign('aData', $aData);
		$this->fetch();
        
		$this->getRequest()->addResponseCommand("assign", array(
			'id' => 'choix_financement',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
        
        $this->getRequest()->addResponseCommand('script', array(
			'value' => "lazy.load($('#choix_financement img.lazy'));"
		));
        
        $this->getRequest()->addResponseCommand('script', array(
			'value' => "popInit();"
		));
	}
}
?>