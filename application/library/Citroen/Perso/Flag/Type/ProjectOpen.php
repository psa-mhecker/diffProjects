<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class ProjectOpen extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('isProjectOpen'));
        $this->call();
    }

}