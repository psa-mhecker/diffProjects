<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class RecentProduct extends Type
{
    /*
     * Constructeur
     */
    public function __construct()
    {
        $this->setProcesses(array('getRecentProduct'));
        $this->call();
    }
}