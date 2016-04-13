<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Event
 * @author __AUTHOR__
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Event
 * @author __AUTHOR__
 */
class Pelican_Event_Queue
{

    const PELICAN_QUEUE_NAME_ERROR = 'Name queue is not valid';

    /**
     * @access private
     * @var __TYPE__ __DESC__
     */
    private $name;

    /**
     * @access private
     * @var __TYPE__ __DESC__
     */
    private $events;

    /**
     * @access private
     * @var __TYPE__ __DESC__
     */
    private $listeners;

    /**
     * @access private
     * @var __TYPE__ __DESC__
     */
    private $is_lock;

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $name __DESC__
     * @return __TYPE__
     */
    public function __construct($name)
    {
        if ($name == null || strcmp($name, "") == 0)
            throw new Pelican_Exception_Error(self::PELICAN_QUEUE_NAME_ERROR);
        $this->name = $name;
        $this->events = array();
        $this->listeners = array();
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function popEvent()
    {
        return array_shift($this->events);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $listener __DESC__
     * @return __TYPE__
     */
    public function removeListener(Pelican_Event_Listener & $listener)
    {
        $max_index = count($this->listeners);
        $find = null;
        for ($i = 0; $i < $max_index; $i++) {
            if ($this->listeners[$i] === $listener) {
                $find = $i;
                break;
            }
        }
        
        if ($find != null) {
            if ($max_index == 1)
                $this->listeners = array();
            else {
                $tmp = $this->listeners[$find];
                $this->listeners[$find] = $this->listeners[$max_index - 1];
                $this->listeners[$max_index - 1] = $tmp;
                array_pop($this->listeners);
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $listener __DESC__
     * @return __TYPE__
     */
    public function addListener(Pelican_Event_Listener $listener)
    {
        $this->listeners[count($this->listeners)] = $listener;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $event __DESC__
     * @return __TYPE__
     */
    public function send(Pelican_Event & $event)
    {
        
        foreach ($this->getListeners() as $listener) {
            if ($listener != null)
                $listener->onEvent($event);
        }
        /*
		array_push($this->events,$event);		
		if($this->is_lock==false)
		{
			$this->is_lock = true;
			while(count($this->events)>0)		
				$this->_send();
			
			$this->is_lock =false;			
		}	-*/
    
    }

    /**
     * __DESC__
     *
     * @access private
     * @return __TYPE__
     */
    private function _send()
    {
        $event = & array_shift($this->events);
        foreach ($this->getListeners() as $listener) {
            if ($listener != null)
                $listener->onEvent($event);
        }
    }
}