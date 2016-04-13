<?php
namespace Citroen\Perso\Flag\Type;

use Citroen\Perso\Flag\Type;

class DatePurchaseBdi extends Type
{
    /*
     * Constructeur
     */
    public function __construct()
    {
        $this->setProcesses(array('datePurchase'));
        $this->call();
    }
}