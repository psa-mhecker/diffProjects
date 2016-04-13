<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class ClientRecent extends Type
{
    public function init()
    {
        return $this->equalTo(Detail::$recentClient, 'Oui');
    }
}