<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class LigneUtilitairePreferee extends Type
{
    public function init()
    {
        $ligne = \Citroen_View_Helper_Vehicule::getProductLigne($_SESSION[APP]['SITE_ID'], Detail::$preferredProduct);
        return $this->equalTo($ligne, 'GAMME_VEHICULE_UTILITAIRE');
    }
}