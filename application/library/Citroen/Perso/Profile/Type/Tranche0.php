<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class Tranche0 extends Type
{
    public function init()
    {
        return $this->compareTwoFlags(Detail::$recentClient, 'Non', null, Detail::$trancheScore[Detail::$preferredProduct], 0);
    }
}