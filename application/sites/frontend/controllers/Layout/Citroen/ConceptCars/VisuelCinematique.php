<?php
class Layout_Citroen_ConceptCars_VisuelCinematique_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        /*
         * Infos de la page pour le titre
         */
        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);

        // TODO : Sharer

        $this->fetch();
    }

}