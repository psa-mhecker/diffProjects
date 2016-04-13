<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class Reconsultation extends Type
{
    public function __construct()
    {
        $this->setProcesses(array('reconsultation'));
        $this->call();
    }
}