<?php
class Layout_Citroen_Footer_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		$this->setParam ( 'ZONE_TITRE', 'titre' );
		$this->assign ( 'data', $this->getParams () );
		
		$this->assign ( 'nav', Pelican_Request::call ( 'Layout_Citroen_Footer_Navigation' ), false );
		$this->assign ( 'SocialFooter', Pelican_Request::call ( 'Layout_Citroen_Footer_SocialFooter' ) );
		$this->assign ( 'AddFooter', Pelican_Request::call ( 'Layout_Citroen_Footer_AddFooter' ), false );
		
		$this->fetch ();
	}
}