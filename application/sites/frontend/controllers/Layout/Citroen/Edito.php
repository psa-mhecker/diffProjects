<?php  
class Layout_Citroen_Edito_Controller extends Pelican_Controller_Front  
{  
  
    public function indexAction()  
    {
    	$aData = $this->getParams();
        $this->assign('aData', $aData);
        $this->fetch();  
    }  
}  
?>