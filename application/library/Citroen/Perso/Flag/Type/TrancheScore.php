<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class TrancheScore extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('trancheScoreCalcul'));
        $this->call();
    }
}