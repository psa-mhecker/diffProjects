<?php
require_once 'Pelican/Event/QueueManager.php';

class Pelican_Event_Loader_Default implements Pelican_Event_Loader
{
    public function __construct()
    {
    }

    public function & getListeners()
    {
        $tab =  array();

        $tab[Pelican_Queue_Manager::DEFAULT_QUEUE] = array('Pelican_Console_Listener','Pelican_Default_Listener');

        return  $tab;
    }

    public function _require()
    {
        require_once 'Pelican/Event/Listener.php';
    }
}
