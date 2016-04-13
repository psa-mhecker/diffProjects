<?php
class Layout_Citroen_PushGammePdv_Controller extends Pelican_Controller_Front  
{  
  
    public function indexAction()  
    {  
        if($this->isMobile()){
            
            $aData = $this->getParams();
            $this->assign('aData', $aData);
            $this->fetch();  
        }        
    } 
} 
?>
