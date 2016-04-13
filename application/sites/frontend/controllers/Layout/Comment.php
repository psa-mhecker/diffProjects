<?php

pelican_import ( 'Comment' );
pelican_import ( 'Pagination' );

class Layout_Comment_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		$this->assign ( "objectId", Pelican_Security::escapeXSS ( $_GET ['cid'] ) );
		$this->assign ( "objectTypeId", "1" );
		$this->assign ( "retour", Pelican_Security::escapeXSS ( $_SERVER ["REQUEST_URI"] ) );
		$this->assign ( "siteId", $_SESSION [APP] ['SITE_ID'] );
		
		$comment = new Pelican_Comment (Pelican::$config ["SITE"] ["INFOS"]['SITE_COMMENT_MANAGEMENT']);
		$count = $comment->getCountForObject ( $_GET ['cid'], '1', true );
		
		$security = Pelican_Factory::getInstance ( 'Security' );
		$options ['lang'] = 'fr';
		$options ['theme'] = 'white';
		$captcha = $security->inputCaptcha ( 'test', 'RECAPTCHA', $options );
		$head = $this->getView ()->getHead ();
		$head->setJquery ( 'jrating' );

		$this->assign ( "recaptcha", $captcha, false );
		$this->assign ( "countComment", $count ['count'] );
		$this->fetch ();
	}
	
	public function getAction() {
		
		$id = $this->getParam ( 0 );
		$typeId = $this->getParam ( 1 );
		$div = $this->getParam ( 2 );
		$skin = $this->getParam ( 3 );
		// $page = $this->getParam(4);
		

		$comment = new Pelican_Comment (Pelican::$config ["SITE"] ["INFOS"]['SITE_COMMENT_MANAGEMENT']);
		if (! $page) {
			$pageCount = 1;
		}
		
		$aResult = $comment->getCommentForObject ( $id, $typeId, 10, $pageCount, true );
		
		$count = $comment->getCountForObject ( $id, $typeId, true );
		$Pagination = new Pelican_Pagination ( array (total_rows => $count ["count"], base_url => $_SERVER ["SCRIPT_URI"], query_string_segment => "p" ) ); // Affichera 10 news par page
		$pagination = $Pagination->create_links ();
		
		$output = '';
		
		$this->assign ( 'comment', $aResult );
		$this->assign ( 'pagination', $pagination );
		
		$this->fetch ();
		
		$this->getRequest ()->addResponseCommand ( 'assign', array ('id' => $div, 'attr' => "innerHTML", 'value' => $this->getResponse () ) );
		
		$this->getRequest ()->addResponseCommand ( 'script', array('value' => 'updateRatings();'));
	
	}
	
	public function addAction() {
		
		include ('Pelican/Comment.php');
		
		$temp = $this->getParams ();
		Pelican_Db::$values = $temp;
		
		$_POST ['recaptcha_challenge_field'] = Pelican_Db::$values ['recaptcha_challenge_field'];
		$_POST ['recaptcha_response_field'] = Pelican_Db::$values ['recaptcha_response_field'];
		
		//if (!empty($_POST['recaptcha_challenge_field']) && !empty($_POST['recaptcha_response_field'])) {
		/** controle du rythme de commentaire */
		/*$now = mktime();
            if (isset($_SESSION[APP]['LAST_COMMENT'])) {
                $duration = $now - $_SESSION[APP]['LAST_COMMENT'];
                if ($duration < Pelican_Comment::$frequency) {
                    $this->getRequest()->addResponseCommand('alert', array(
                        'value' => "Veuillez attendre quelques instants avant de saisir un nouveau commentaire.\n\nMerci"
                    ));
                }
            }
            $_SESSION[APP]['LAST_COMMENT'] = $now;*/
		
		if (true) { //Pelican_Security::checkCaptcha('RECAPTCHA')) {
			$comment = new Pelican_Comment (Pelican::$config ["SITE"] ["INFOS"]['SITE_COMMENT_MANAGEMENT']);
			$comment->saveComment ();
			
			$this->getRequest ()->addResponseCommand ( 'script', array ('value' => 'loadComments()' ) );
			$this->getRequest()->addResponseCommand('alert', array(
                'value' => 'Votre commentaire a bien été pris en compte.'
            ));
		} else {
			$this->getRequest ()->addResponseCommand ( 'alert', array ('value' => 'Le texte de contrôle est faux, veuillez le resaisir.' ) );
		}
		/*} else {
            $this->getRequest()->addResponseCommand('alert', array(
                'value' => 'Veuillez saisir le texte de contrôle.'
            ));
        }*/
	}
	
	public function ratingAction() {
		
		$temp = $this->getParams ();
		
		$aResponse ['server'] = $temp ['rate'];
		
		echo json_encode ( $aResponse );
	
	}
}
