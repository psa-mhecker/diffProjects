<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class PasDeProjetOuvert extends Type
{
    public function init()
    {
        return $this->compareTwoFlags(Detail::$recentClient, 'Non', null, Detail::$projectOpen, 'Non');
    }
}