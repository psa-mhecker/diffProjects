<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class Tranche3Particulier extends Type
{
    public function init()
    {
        $productType = \Citroen_View_Helper_Vehicule::getProductGamme($_SESSION[APP]['SITE_ID'], Detail::$preferredProduct);
        return $this->compareThreeFlags(Detail::$recentClient, 'Non', null, Detail::$trancheScore[Detail::$preferredProduct], 3, $productType, 'VP');
    }
}