<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class ProductHasBdi extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('productOwned'));
        $this->call();
    }
}