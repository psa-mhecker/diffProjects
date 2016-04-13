<?php
namespace Citroen\Batch;

use Itkg\Batch;

/**
 * Classe Wurfl
 *
 * Batch Wurfl : Permet de mettre à jour le fichier xml de Wurfl
 *
 * @author Raphael Carles <raphael.carles@businessdecision.com>
 */
class Wurfl extends Batch
{

    public function execute ()
    {
        include_once (\Pelican::$config['LIB_ROOT'] . '/Pelican/Http/UserAgent/Features/Adapter/WurflApi.php');
        \Pelican_Http_UserAgent_Features_Adapter_WurflApi::updateDatabase();
    }
}