<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class RecentClientBdi extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('isRecentClientBdi','isNotRecentClientBdi'));
        $this->call();
    }
}