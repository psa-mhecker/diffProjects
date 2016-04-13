<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class ProductBestScore extends Type
{
    /*
    * Constructeur
    */
    public function __construct()
    {
        $this->setProcesses(array('bestScore'));
        $this->call();
    }
}