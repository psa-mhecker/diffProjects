<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class ClientBdi extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('isClientBdi', 'isNotClientBdi'));
        $this->call();
    }

}