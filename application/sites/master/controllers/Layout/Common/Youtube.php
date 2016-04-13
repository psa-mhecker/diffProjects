<?php
require_once (pelican_path ( 'Media' ));

class Layout_Common_Youtube_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		$data = $this->getParams ();
		$tmp = explode ( 'x', $data ['ZONE_PARAMETERS'] );

		$width = $tmp [0];
		$height = $tmp [1];

		$this->assign('width',$width);
		$this->assign('height',$height);
		$this->fetch ();
	}
}