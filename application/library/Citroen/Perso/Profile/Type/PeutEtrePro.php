<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;

class PeutEtrePro extends Type
{
    public function init()
    {
        return $this->equalTo(Detail::$pro, null);
    }
}