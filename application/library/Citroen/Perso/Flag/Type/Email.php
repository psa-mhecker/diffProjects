<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class Email extends Type
{
    /*
     * Constructeur
     */
    public function __construct()
    {
        $this->setProcesses(array('signInNewsletter','signInMyProject','emailForm'));
        $this->call();
    }
}