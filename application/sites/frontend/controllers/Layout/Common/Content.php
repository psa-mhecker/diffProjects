<?php

class Layout_Common_Content_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
		$this->setParam ( 'ZONE_TITRE', 'titre' );
		
		$val = array ();
		$ids = $this->getParam ( "ZONE_PARAMETERS" );
		
		if ($ids) {
			$oConnection = Pelican_Db::getInstance ();
			$content = $oConnection->queryTab ( 'select c.CONTENT_ID, CONTENT_TITLE, CONTENT_CLEAR_URL, MEDIA_PATH from #pref#_content c inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID and c.CONTENT_CURRENT_VERSION=cv.CONTENT_VERSION and c.LANGUE_ID=cv.LANGUE_ID)
			left join #pref#_media m on (cv.MEDIA_ID=m.MEDIA_ID)
			where c.CONTENT_ID in (' . $ids . ')' );
			if ($content) {
				foreach ( $content as $values ) {
					$val [$values ['CONTENT_ID']] = array ('id' => $values ['CONTENT_ID'], 'title' => $values ['CONTENT_TITLE'], 'media' => $values ['MEDIA_PATH'], 'url' => $values ['CONTENT_CLEAR_URL'] );
				}
			}
			$ordre = explode ( ',', $ids );
			foreach ( $ordre as $o ) {
				$value [] = $val [$o];
			}
		
		}

		$this->assign ( 'list', $value );
		$this->assign ( 'data', $this->getParams () );
		$this->fetch ();
    }
}
