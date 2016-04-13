<?php  
class Layout_Citroen_EquipementCaracteristiqueTech_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams();
		
		
		$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($aData["PAGE_ID"],$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);

		if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
				$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
				$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
		}
        
		
		$this->assign('aData', $aData);
        $this->fetch();
    }  
}  
?>
