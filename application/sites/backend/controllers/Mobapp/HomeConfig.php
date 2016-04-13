<?php

class Mobapp_HomeConfig_Controller extends Pelican_Controller_Back {
	
	protected $administration = false;
	
	protected $form_name = "mobapp_home";
	
	protected $field_id = "MOBAPP_SITE_HOME_ID";
	
	protected $defaultOrder = "MOBAPP_SITE_HOME_ORDER";
	
	protected function setListModel() {
		$this->listModel = 'SELECT 
        MOBAPP_SITE_HOME_ID as "id", 
        MOBAPP_CONTENT_TYPE_CODE as "type", 
        MOBAPP_SITE_HOME_LABEL as "label",
        MOBAPP_CONTENT_TYPE_ICON as "img",
        MEDIA_PATH as "img2" from #pref#_mobapp_site_home h
        inner join #pref#_mobapp_content_type  ct on (ct.MOBAPP_CONTENT_TYPE_ID=h.MOBAPP_CONTENT_TYPE_ID)
			left join #pref#_media m on (h.MEDIA_ID=m.MEDIA_ID)
            where SITE_ID = ' . $_SESSION [APP] ['SITE_ID'] . '
            order by ' . $this->listOrder;
	}
	
	protected function setEditModel() {
		$this->aBind [':ID'] = $this->id;
		$this->editModel = "SELECT * from #pref#_mobapp_site_home WHERE MOBAPP_SITE_HOME_ID=:ID";
	}
	
	public function listAction() {
		$this->sAddUrl = '';
		
		$head = $this->getView ()->getHead ();
		$head->endJQuery ( 'ui' );
		$head->endJs ( '/js/mobapp/jquery.mobile-interface.js' );
		$head->endJs ( '/js/mobapp/jquery.dragsort-0.4.3.min.js' );
		$head->endJs ( '/js/mobapp/jquery.colorbox.min.js' );
		$head->endJs ( '/js/mobapp/jquery-ui-1.8.16.custom.min.js' );
		$head->setJs ( 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' );
		$head->setJs ( '/library/Pelican/Form/public/js/hmvc.js' );
		$head->setJs ( '/library/Pelican/Form/public/js/ajax.js' );
		$head->setJs ( '/library/Pelican/Form/public/js/xt_text_controls.js' );
		$head->setJs ( '/library/Pelican/Form/public/js/xt_popup_fonctions.js' );
		
		$head->setcss ( '/css/mobapp/style.css' );
		$head->setcss ( '/css/mobapp/colorbox.css' );
		$head->setcss ( '/css/mobapp/bootstrap.css' );
		
		parent::listAction ();
		
		$oConnection = Pelican_Db::getInstance ();
		
		$Data = $oConnection->queryTab ( $this->getListModel () );
		
		$App = $oConnection->queryTab ( 'select -2 as "id", 
        MOBAPP_CONTENT_TYPE_CODE as "type", 
        MOBAPP_CONTENT_TYPE_LABEL as "label", 
        MOBAPP_CONTENT_TYPE_ICON as "img" from #pref#_mobapp_content_type order by MOBAPP_CONTENT_TYPE_LABEL' );
		
		foreach ( $App as $key => $app ) {
			$App [$key] ['html'] = '<li class="' . ($key % 2 ? "odd" : "even") . '">' . $this->buildButton ( $app ['id'], $app ['type'], $app ['label'], $app ['img'] ) . '</li>';
		}
		
		$aPage = array_chunk ( $Data, 9 );
		foreach ( $aPage as $id => $values ) {
			$aPage [$id] ['html'] = $this->buildPage ( $id, $values );
		}
		
		$this->assign ( 'App', $App );
		$this->assign ( 'aPage', $aPage );
		$this->assign ( 'form', $this->getForm (), false );
		$this->fetch ();
	}
	
	public function getForm() {
		// ------------ Begin startStandardForm ----------
		$this->oForm = Pelican_Factory::getInstance ( 'Form', true );
		$this->oForm->bDirectOutput = false;
		$form = $this->oForm->open ( Pelican::$config ['DB_PATH'], "post", "fForm", false, true, "CheckForm", "", true, false );
		// $form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		// ------------ End startStandardForm ----------
		
		// ------------ Begin Input ----------
		$form .= $this->oForm->createHidden ( "MOBAPP_SITE_HOME_ID", '' );
		$form .= $this->oForm->createHidden ( "MOBAPP_CONTENT_TYPE_CODE", '' );
		$form .= $this->oForm->createInput ( "MOBAPP_SITE_HOME_LABEL", t ( 'title' ), 50, "", true, $this->values ["MOBAPP_SITE_HOME_LABEL"], $this->readO, 50 );
		$form .= $this->oForm->createLabel ( "Image originale", '<img src="file" height="72" width="72" id="ICON" />' );
		$form .= $this->oForm->createMedia ( "MEDIA_ID2", t ( 'Icon' ), false, "image", "", '', $this->readO );
		// ------------ End Input ----------
		
		// ------------ Begin stopStandardForm ----------
		$form .= $this->oForm->endFormTable ();
		$form .= $this->oForm->createButton ( "validate", t ( 'Valider' ) );
		// $form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();
		// ------------ End stopStandardForm ----------
		return $form;
	}
	
	function buildButton($id, $type, $label, $img, $img2 = '') {
		$button = '<div class="appli" id="' . $type . '">
   <img src="' . ($img2 ? $img2 : $img) . '" height="72" width="72" alt="Appli 1" />
   <h4>' . $label . '</h4>
   	<div class="more"><a href="#"><img src="/images/mobapp/bt_edit.png" alt="Edit" /></a></div>
    <div class="delete"><a href="#"><img src="/images/mobapp/bt_delete.png" alt="Delete" /></a></div>
    <input type="hidden" name="button[]" value="' . $id . '#' . $type . '#' . $img . '#' . $img2 . '#' . $label . '" />
   </div>';
		
		return $button;
	}
	
	function buildPage($id, $values) {
		if ($id < 1) {
			$page = '<ul>
        <li><a href="#Inner' . $id . '"><img src="/images/mobapp/bullet.png" /></a></li>
    </ul>';
		}
		$page .= '<div id="Inner' . $id . '" class="inner">
    <div class="message"><h3>Ecran complet</h3><div class=""><p>Cet écran est complet, passez à l\'écran suivant.</p> <a href="#" class="button">Ok</a></div></div>
    <div class="delete"><a href="#"><img src="/images/mobapp/bt_delete.png" alt="Delete" /></a></div>
    <ul>';
		if (! empty ( $values )) {
			foreach ( $values as $key => $app ) {
				$page .= '<li>';
				$page .= $this->buildButton ( $app ['id'], $app ['type'], $app ['label'], $app ['img'], ($app ['img2'] ? Pelican::$config ['MEDIA_HTTP'] . $app ['img2'] : '') );
				$page .= '</li>';
			}
		}
		$page .= '</ul>
    </div>';
		return $page;
	}
}  