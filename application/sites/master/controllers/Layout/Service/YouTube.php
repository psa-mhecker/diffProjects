<?php
class Layout_Service_YouTube_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		
		if ($this->getParam ( 'ZONE_TITRE2' )) {
			$yt = new Zend_Gdata_YouTube ();
			$videoFeed = $yt->getUserUploads ( $this->getParam ( 'ZONE_TITRE2' ) );
			foreach ( $videoFeed as $videoEntry ) {
				$t = $videoEntry->getVideoThumbnails ();
				$video [] = array ('title' => $videoEntry->getVideoTitle (), 'description' => $videoEntry->getVideoDescription (), 'viewCount' => $videoEntry->getVideoViewCount (), 'url' => $videoEntry->getFlashPlayerUrl (), 'thumbnail' => $t [0] ['url'], 'time' => $t [0] ['time'], 'width' => $t [0] ['width'], 'height' => $t [0] ['height'], 'id' => $videoEntry->getVideoId () );
			}
		}
		$this->assign ( 'video', $video );
		$this->assign ( 'data', $this->getParams () );
		$this->assign ( 'id', $this->getParam ( 'ZONE_TEMPLATE_ID' ) );
		$this->fetch ();
	}
}