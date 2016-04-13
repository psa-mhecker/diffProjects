<?php
class Layout_Citroen_1Colonne_Controller extends Pelican_Controller_Front
{
    public function indexAction()
       {
           $data = $this->getParams();

           $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
               $data["MEDIA_ID"]
           ));

           $this->assign('ZONE_TITRE', $data["ZONE_TITRE"]);
           $this->assign('ZONE_TITRE2', $data["ZONE_TITRE2"]);
           $this->assign('ZONE_TEXTE', $data["ZONE_TEXTE"]);
           $this->assign('ZONE_TEXTE2', $data["ZONE_TEXTE2"]);
           $this->assign('MEDIA_PATH', $mediaDetail["MEDIA_PATH"]);
           $this->fetch();
       }
}
