<?php
require_once 'Pelican/Exception/Error.php';
require_once 'Pelican/Event/Queue.php';

class Pelican_Event_Helper
{
    const ERROR_00 =  'Loader is NULL';
    private static $instance = null;

    private function __construct()
    {
    }

    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new Pelican_Event_Helper();
        }

        return self::$instance;
    }

    //TODO work in progress
    public function load(Event_Loader $loader)
    {
        if ($loader != null) {
            $queue = $loader->getListeners();
            $loader->_require();
            if (is_array($queue)) {
                $keys = array_keys($queue);
                if (is_array($keys)) {
                    foreach ($keys as $key) {
                        $listeners = $queue[$key];
                        foreach ($listeners as $name) {
                            $class = (new ReflecClass($name));
                            $listener = & $class->newInstance();
                            Pelican_Queue::instance()->listen($listener, $key);
                        }
                    }
                }
            }
        } else {
            throw Pelican_Exception_Error(Pelican_Event_Helper::ERROR_00);
        }
    }

    public function send(Event $event, $queue_name = null)
    {
        Pelican_Queue_Manager::instance()->send($event);
    }
}
