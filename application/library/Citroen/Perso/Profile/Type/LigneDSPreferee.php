<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class LigneDSPreferee extends Type
{
    public function init()
    {
        $ligne = \Citroen_View_Helper_Vehicule::getProductLigne($_SESSION[APP]['SITE_ID'], Detail::$preferredProduct);
        return $this->equalTo($ligne, 'GAMME_LIGNE_DS');
    }
}