<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class Client extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('isClient','isNotClient'));
        $this->call();
    }
}