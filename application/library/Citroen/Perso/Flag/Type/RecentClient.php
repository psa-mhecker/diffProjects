<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class RecentClient extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('isRecentClient'));
        $this->call();

    }
}