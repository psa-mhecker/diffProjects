<?php

class Layout_Model_WebScrapping_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		if ($this->getParam ( 'ZONE_PARAMETERS' )) {
			$this->setParam ( 'ZONE_TITRE', '' );
			
			$protocole = 'http';
			if (strpos ( $this->getParam ( 'ZONE_PARAMETERS' ), 'https://' ) || substr_count ( $_SERVER ['REQUEST_URI'], '?/https/' )) {
				$protocole = 'https';
			}
			
			if ($this->getParam ( 'alias' )) {
				$alias = trim ( $this->getParam ( 'alias' ), '/' );
				$url = '/_/' . $protocole . '/' . str_replace ( $protocole . '://', '', $this->getParam ( 'ZONE_PARAMETERS' ) );
				
				if (! $this->getParam ( 'external' )) {
					$content = Pelican_Request::call ( $url );
				} else {
					$parse = parse_url ( $this->getParam ( 'ZONE_PARAMETERS' ) );
					$host = $parse ['host'];
					$content = Pelican_Request::call ( '/_/' . $protocole . '/' . $host . $this->getParam ( 'external' ) );
				}
				
				if ($this->getParam ( 'ZONE_TEXTE' )) {
					$content = $this->_remove ( $content, explode ( "\r\n", $this->getParam ( 'ZONE_TEXTE' ) ) );
				}
				
				$content = str_replace ( '"/' . $alias . '/', '"/' . $alias . '/_/', $content );
				$content = str_replace ( '\'/' . $alias . '/', '\'/' . $alias . '/_/', $content );
			} else {
				if (substr_count ( $_SERVER ['REQUEST_URI'], '?/' . $protocole . '/' )) {
					$content = Pelican_Request::call ( '/_' . $_SERVER ['QUERY_STRING'] );
				} else {
					$content = Pelican_Request::call ( '/_/' . $protocole . '/' . str_replace ( $protocole . '://', '', $this->getParam ( 'ZONE_PARAMETERS' ) ) );
				}
				
				if ($this->getParam ( 'ZONE_TEXTE' )) {
					$content = $this->_remove ( $content, explode ( "\r\n", $this->getParam ( 'ZONE_TEXTE' ) ) );
				}
				$content = str_replace ( '/_/' . $protocole . '/', $_SERVER ['REDIRECT_URL'] . '?/' . $protocole . '/', $content );
			
			}
			
			$this->assign ( 'zone_title', '' );
			$this->assign ( 'content', $content, false );
			$this->assign ( 'css', $this->getParam ( 'ZONE_TEXTE2' ), false );
			$this->model ();
			$this->fetch ();
		}
	}
	
	public function indexAction1() {
		
		$title = $this->getParam ( 'ZONE_TITRE' );
		
		$protocole = 'http';
		if (strpos ( $this->getParam ( 'ZONE_PARAMETERS' ), 'https://' ) || substr_count ( $_SERVER ['REQUEST_URI'], '?/https/' )) {
			$protocole = 'https';
		}
		
		if (substr_count ( $_SERVER ['REQUEST_URI'], '?/' . $protocole . '/' )) {
			$content = Pelican_Request::call ( '/_' . $_SERVER ['QUERY_STRING'] );
		} else {
			$content = Pelican_Request::call ( '/_/' . $protocole . '/' . str_replace ( $protocole . '://', '', $this->getParam ( 'ZONE_PARAMETERS' ) ) );
		}
		
		if ($this->getParam ( 'ZONE_TEXTE' )) {
			$content = $this->_remove ( $content, explode ( "\r\n", $this->getParam ( 'ZONE_TEXTE' ) ) );
		}
		$content = str_replace ( '/_/' . $protocole . '/', $_SERVER ['REDIRECT_URL'] . '?/' . $protocole . '/', $content );
		/* if ($title) {
            $remote = str_replace('/http/', 'http://', $_SERVER['QUERY_STRING']);
            $parse = parse_url($remote);
            $host = $parse['host'];
            

            directdebug( str_replace($_SERVER['REDIRECT_URL'] . '?/http/'.$host, 'http://' . $_SERVER['HTTP_HOST'] . '/' . $title . '/_', $content));
            die();
        }*/
		$this->assign ( 'content', $content, false );
		$this->assign ( 'css', $this->getParam ( 'ZONE_TEXTE2' ), false );
		$this->model ();
		$this->fetch ();
	}
	
	public function indexAction0() {
		
		if (substr_count ( $_SERVER ['REQUEST_URI'], '?/http/' )) {
			$content = Pelican_Request::call ( '/_' . $_SERVER ['QUERY_STRING'] );
		} else {
			$content = Pelican_Request::call ( '/_/http/' . str_replace ( 'http://', '', $this->getParam ( 'ZONE_PARAMETERS' ) ) );
		
		}
		
		if ($this->getParam ( 'ZONE_TEXTE' )) {
			$content = $this->_remove ( $content, explode ( "\r\n", $this->getParam ( 'ZONE_TEXTE' ) ) );
		}
		$content = str_replace ( '/_/http/', $_SERVER ['REDIRECT_URL'] . '?/http/', $content );
		$this->assign ( 'content', $content, false );
		$this->assign ( 'css', $this->getParam ( 'ZONE_TEXTE2' ), false );
		$this->model ();
		$this->fetch ();
	}
	
	public function _remove($text, $aId) {
		$html = $text;
		if ($html) {
			if (! is_array ( $aId )) {
				$aId = array ($aId );
			}
			
			$html = str_get_html ( $text );
			$id = implode ( ',', $aId );
			$ret = $html->find ( $id );
			foreach ( $ret as $el ) {
				$el->outertext = '';
			}
		}
		
		return $html;
	}
}