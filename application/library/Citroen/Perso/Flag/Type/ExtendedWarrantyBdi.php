<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class ExtendedWarrantyBdi extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array());
        $this->call();
    }
}