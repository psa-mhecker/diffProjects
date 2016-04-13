<?php
class Mobapp_Config_Controller extends Pelican_Controller_Back {
	protected $administration = false;
	
	protected $form_name = "mobapp_site";
	
	protected $field_id = 'SITE_ID';
	
	protected $defaultOrder = "MOBAPP_SITE_TITLE";
	
	protected function init() {
		$_GET ['id'] = $_SESSION[APP]['SITE_ID'];
	}
	
	protected function setEditModel() {
		$this->aBind [':ID'] = $this->id;
		$this->editModel = "SELECT * from #pref#_mobapp_site WHERE SITE_ID=:ID";
	}
	
	public function editAction() {
		parent::editAction ();
		//------------ Begin startStandardForm ----------  
		$this->oForm = Pelican_Factory::getInstance ( 'Form', true );
		$this->oForm->bDirectOutput = false;
		$form = $this->oForm->open ( Pelican::$config ['DB_PATH'] );
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		//------------ End startStandardForm ----------  
		
		$this->setFormRetour($_SERVER["REQUEST_URI"] );

		//------------ Begin Input ----------  
		$form .= $this->oForm->createHidden ( 'SITE_ID', $this->values ['SITE_ID'], $this->readO, 100 );
		$form .= $this->oForm->createInput ( "MOBAPP_SITE_TITLE", t ( 'title' ), 50, "", true, $this->values ["MOBAPP_SITE_TITLE"], $this->readO, 50 );
		$form .= $this->oForm->createEditor ( "MOBAPP_SITE_TEXT", t ( 'Content' ), false, $this->values ["MOBAPP_SITE_TEXT"], $this->readO, true, "", 500, 150 );
		//$form .= $this->oForm->createEditor ( "MOBAPP_SITE_SHORTTEXT", t ( 'Short Content' ), false, $this->values ["MOBAPP_SITE_SHORTTEXT"], $this->readO, true, "", 500, 50 );
		//$form .= $this->oForm->createInput ( "MOBAPP_SITE_URL", t ( 'Link' ), 255, "link", false, $this->values ["MOBAPP_SITE_URL"], $this->readO, 50, false );
		$form .= $this->oForm->createMedia ( "MEDIA_LOGO_ID", t ( 'Logo' ), false, "image", "", $this->values ["MEDIA_LOGO_ID"], $this->readO );
		$form .= $this->oForm->createMedia ( "MEDIA_BANNER_ID", t ( 'Banner' ), false, "image", "", $this->values ["MEDIA_BANNER_ID"], $this->readO );
		$form .= $this->oForm->createMedia ( "MEDIA_BACKGROUND_ID", t ( 'BackgroundImage' ), false, "image", "", $this->values ["MEDIA_BACKGROUND_ID"], $this->readO );
		$form .= $this->oForm->createInput ( "MOBAPP_SITE_BACKGROUND_COLOR", t ( 'BackgroundColor' ), 10, "color", false, $this->values ["MOBAPP_SITE_BACKGROUND_COLOR"], $this->readO, 10, false );
		//------------ End Input ----------  
		

		//------------ Begin stopStandardForm ----------  
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();
		//------------ End stopStandardForm ----------  
		$this->setResponse ( $form );
	}
} 