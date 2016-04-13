<?php
namespace Citroen\Perso;

/**
 * Classe Flag
 *
 * Cette classe est déléguée à la gestion des indicateurs
 *
 * @author Khadidja MESSAOUDI <khadidja.messaoudi@businessdecision.com>
 */

class Flag
{
    /*
     * Tableau de processus
     */
    public $processes = array(
        '\Citroen\Perso\Flag\Type\TrancheScore',
        '\Citroen\Perso\Flag\Type\ProductBestScore',
        '\Citroen\Perso\Flag\Type\RecentProduct',
        '\Citroen\Perso\Flag\Type\Client',
        '\Citroen\Perso\Flag\Type\ClientBdi',
        '\Citroen\Perso\Flag\Type\ProductHasBdi',
        '\Citroen\Perso\Flag\Type\Pro',
        '\Citroen\Perso\Flag\Type\RecentClient',
        '\Citroen\Perso\Flag\Type\RecentClientBdi',
        '\Citroen\Perso\Flag\Type\Email',
        '\Citroen\Perso\Flag\Type\DatePurchaseBdi',
        '\Citroen\Perso\Flag\Type\CurrentProduct',
        '\Citroen\Perso\Flag\Type\PreferredProduct',
        '\Citroen\Perso\Flag\Type\ProjectOpen',
        '\Citroen\Perso\Flag\Type\Reconsultation'
    );

    /*
     * Lancement des processus
     */
    public function process()
    {
        if(is_array($this->processes) && !empty($this->processes)){
            foreach($this->processes as $process){
                $reflexion = new \ReflectionClass($process);
                $reflexion->newInstance();
            }
        }
    }
}