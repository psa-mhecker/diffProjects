<?php
class Layout_Citroen_MonProjet_SimulateurFinancement_Controller extends Pelican_Controller_Front{
	
	public function step2AjaxAction(){
		Pelican_Request::call('/_/Layout_Citroen_SimulateurFinancement/step2Ajax');
		$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/SimulateurFinancement/step2Ajax.tpl';
		$this->fetch();
	}

}

