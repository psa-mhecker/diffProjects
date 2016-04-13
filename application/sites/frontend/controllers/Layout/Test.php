<?php
class Layout_Test_Controller extends Pelican_Controller_Front {
	
	public function indexAction() {
		$path	=	'/projects/dev/cppv2/application/i18n/'.$_GET['lang'].'.php';
		$commun 	=	include($path);
		foreach(Pelican::$lang as $key => $lang){
			$mystring = 'abc';
			if(strpos($lang, " ")){
				echo $key . ';"' . $lang . '"<br/>';
			}else{
				echo $key . ';' . $lang . '<br/>';
			}
		}
	}
}