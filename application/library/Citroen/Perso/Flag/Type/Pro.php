<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class Pro extends Type
{
    /*
      * Constructeur
      */
    public function __construct()
    {
        $this->setProcesses(array('isPro', 'isNotPro','isProForm', 'isNotProForm', 'isClientAndHasVU','isProFromSearch'));
        $this->call();
    }
}