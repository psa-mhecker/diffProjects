<?php
class Layout_Citroen_ConceptCars_Galerie_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        /*
         * Infos de la zone pour le visuel
         */
        $aData = $this->getParams();
        $galerieConceptCars = Pelican_Cache::fetch("Frontend/Citroen/ConceptCars/Galerie", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['pid']
        ));
        $this->assign("galerieConceptCars", $galerieConceptCars);

        $this->fetch();
    }

}