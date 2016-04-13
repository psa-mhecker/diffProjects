<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class PreferredProduct extends Type
{
    /*
      * Constructeur
      */
    public function __construct()
    {
        $this->setProcesses(array('preferedProduct'));
        $this->call();
    }
}