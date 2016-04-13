<?php
require_once('Pelican/Event/Event.php');
require_once('Pelican/Event/Listener.php');
require_once('Pelican/Event/Queue.php');
require_once('Pelican/Service/Service.php');

class Pelican_Event_Service extends Pelican_Service
{
	const DEFAULT_QUEUE = 'DEFAULT';
	
	private $queues;
	private static $instance = null;
    
	private function __construct() {
		$this->queues = array();
	}
    
	public static function getInstance() {
        if (self::$instance == null) 
        	self::$instance = new Pelican_Event_Service();           
        return self::$instance;
    }
    
	public function send(Pelican_Event & $event, $queue_name = null)
    {    	        	
    	if($queue_name==null)
    		$queue_name = self::DEFAULT_QUEUE;
    		
    	$queue = $this->getQueue( $queue_name );
    	if($queue != null && $queue instanceof Pelican_Event_Queue){	
    		$queue->send($event);
    	}
    }
    
    public function sendEvent($type,$user,$message,$source=null, $queue_name = null){
    	$this->send(new Pelican_Event_Default($type,$user,$message,source),$queue_name);
    }
  
    
    private function getQueue($queue_name)
    {
    	return $this->queues[$queue_name];
    }
    
    public function listen(Pelican_Event_Listener & $listener, $queue_name = null)
    {
    	if($queue_name==null)
    		$queue_name =  self::DEFAULT_QUEUE;
    	    	
    	$queue = & $this->getQueue( $queue_name ); 
    	if(!($queue instanceof Pelican_Event_Queue))
    	{    		    		
    		$queue = new Pelican_Event_Queue($queue_name);    		
    		$this->queues[$queue->getName()] =   $queue;    		    
    	}    	
    
    	$queue->addListener($listener);
    }

    public function closeListener(Pelican_Event_Listener & $listener, $queue_name = null)
    {
    	if($queue_name==null)
    		$queue_name =  self::DEFAULT_QUEUE;
    		
    	$queue = & $this->getQueue( $queue_name );
    	if($queue instanceof Pelican_Queue)   	
    		$queue->removeListener($listener);    	
    }
}

?>