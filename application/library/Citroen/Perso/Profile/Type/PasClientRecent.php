<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class PasClientRecent extends Type
{
    public function init()
    {
        return $this->equalToAorB(Detail::$recentClient, 'Non', null);
    }
}