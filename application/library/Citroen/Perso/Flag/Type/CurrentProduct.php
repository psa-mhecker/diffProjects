<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class CurrentProduct extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('notExistCurrentProduct','existCurrentProduct','myProjectCurrentProduct' ));
        $this->call();
    }
}