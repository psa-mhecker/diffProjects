<?php
namespace Citroen\Perso\Flag;

use Citroen\Perso\Flag\Detail;

/**
 * Classe Type
 *
 * Cette classe permet le lancement des tests calculant les indicateurs
 *
 * @package Flag/Type
 * @author Khadidja MESSAOUDI <khadidja.messaoudi@businessdecision.com>
 */

class Type
{
    public $processes = array();
    /*
     * Lancement des processus
     */
    public function call()
    {
        if(is_array($this->processes) && !empty($this->processes)){
            $detail = new Detail();
            $detail->init();
            foreach($this->processes as $process){
                if($detail->$process()){
                    break;
                }
            }
        }
    }
    public function setProcesses($processes)
    {
        $this->processes = $processes;
    }
}