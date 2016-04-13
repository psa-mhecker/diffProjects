<?php  
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');
class Layout_Citroen_AnimationPointsForts_Controller extends Pelican_Controller_Front  
{  
  
  
    public function indexAction()  
    {
			
		$aData = $this->getParams();
		
		
		$sHtml5Display ='';		
		$sHtml5 = $aData['ZONE_TEXTE'];
		$bIsUk =false;
		$bIsPT =false;
		$bIsGeneral = false;
		
		
		$sIsWebMobile = $this->isMobile()?'mobile':'desktop';
		$bVehiculeIsC4Cactus = false;
		switch ($aData['select_vehicule_lcdv6']) {
			case Pelican::$config['LCDV_C1']:
				$sFolderImg = Pelican::$config['FOLDER_C1'];
				break;
			case Pelican::$config['LCDV_GRAND_C4_PICASSO']:
				$sFolderImg = Pelican::$config['FOLDER_GRAND_C4_PICASSO'];
				break;
			case Pelican::$config['LCDV_C4_PICASSO']:
				$sFolderImg = Pelican::$config['FOLDER_C4_PICASSO'];
				break;
				
			case Pelican::$config['LCDV_C4_CACTUS']:
				$bVehiculeIsC4Cactus = true;
				$sFolderImg = Pelican::$config['FOLDER_C4_CACTUS'];
				break;
			case Pelican::$config['LCDV_C4']:
				$sFolderImg = Pelican::$config['FOLDER_C4'];
				break;
			case Pelican::$config['LCDV_C3']:
				$sFolderImg = Pelican::$config['FOLDER_C3'];
				break;
			case Pelican::$config['LCDV_C3_PICASSO']:
				$sFolderImg = Pelican::$config['FOLDER_C3_PICASSO'];
				break;
			case Pelican::$config['LCDV_C5_TOURER']:
				$sFolderImg = Pelican::$config['FOLDER_C5_TOURER'];
				break;
		}
			

		if($_SESSION[APP]['CODE_PAYS']=='GB' && $bVehiculeIsC4Cactus==true){
			$bIsUk =true;
		}elseif($_SESSION[APP]['CODE_PAYS']=='PT' && $bVehiculeIsC4Cactus==true){
			$bIsPT =true;
		}else{
			$bIsGeneral = true;
		}

		
		$sHtml5Display = $this->getValidImage($sHtml5,$sFolderImg,$sIsWebMobile,$bVehiculeIsC4Cactus);
		
		if($sHtml5Display !== false){
			$sHtml5Display = $this->getTraduction($sHtml5Display);
		}

		
		
		$this->assign('sIsWebMobile', $sIsWebMobile);
		$this->assign('sFolderImg', $sFolderImg);
		$this->assign('bIsUk', $bIsUk);
		$this->assign('bIsPT', $bIsPT);
		$this->assign('bIsGeneral', $bIsGeneral);
		$this->assign('sHtml5Display', $sHtml5Display);
        $this->assign('aData', $aData);
        $this->fetch();  
    } 
	
	
	public function getValidImage($sHtml5,$sFolderImg,$sIsWebMobile,$bVehiculeIsC4Cactus){
		
		$aUrl = $aNewUrl = array();
		
		
		if(!empty($sHtml5)){
		
			$sHtml5   = str_replace('&','|and|',$sHtml5);
			
			libxml_use_internal_errors(true);
			$dom = new domDocument;
			$dom->loadHTML($sHtml5);
			$aError = libxml_get_errors();
			
			$xpath = new DOMXPath($dom);
			$src = $xpath->evaluate("string(//source/@src)");
			if(!empty($src)){
				$sSrcUpdate = Pelican::$config["DESIGN_HTTP"].'/animation/'.$sFolderImg.'/'.$sIsWebMobile.'/'.$src;	
			}
			
			
				
			$oImages = $dom->getElementsByTagName('img');
			
			 if(is_object($oImages)){
				
				foreach($oImages as $img)
				{
					 $aUrl[]    = $img->getAttribute('src');	
					 if(($_SESSION[APP]['CODE_PAYS']=='GB' || $_SESSION[APP]['CODE_PAYS']=='PT') && $bVehiculeIsC4Cactus == true ){
						 $aNewUrl[] = Pelican::$config['DESIGN_HTTP']. '/animation/'.$sFolderImg.'/'.$sIsWebMobile.'/'.$_SESSION[APP]['CODE_PAYS'].'/'.$img->getAttribute('src');	
					 }else{
						 $aNewUrl[] = Pelican::$config['DESIGN_HTTP']. '/animation/'.$sFolderImg.'/'.$sIsWebMobile.'/'.$img->getAttribute('src');	
					 }
					 
					
				}
			 }	
			 
			$sHtml5 = str_replace(array_unique($aUrl),array_unique($aNewUrl),$sHtml5);
			$sHtml5 = str_replace('|and|','&',$sHtml5);
			$sHtml5 = str_replace($src,$sSrcUpdate,$sHtml5);
			
			return $sHtml5;
			
			
		}		
		 
		
	
	}
	
	public function getTraduction($sHtml5Display){
		
		$sHtmlTradStart = '{';
		$sHtmlTradEnd   = '}';
		
		$pattern = sprintf('/%s(.+?)%s/ims',preg_quote($sHtmlTradStart, '/'), preg_quote($sHtmlTradEnd, '/'));
		if (preg_match_all($pattern, $sHtml5Display, $matches)) {
			list(, $aMatchs) = $matches;
		}
		
		 if(is_array($aMatchs) && sizeof($aMatchs)>0){
			 
			
			foreach($aMatchs as $key=>$aValue){
				 $aValuesToHtml[] = t($aValue) ;
			}
			
			$sHtml5Display = str_replace(array($sHtmlTradStart,$sHtmlTradEnd),'',$sHtml5Display);
			$sHtml5Display = str_replace($aMatchs,$aValuesToHtml,$sHtml5Display);	
		 }
		 
		 return $sHtml5Display;
	}
	
} 
?>