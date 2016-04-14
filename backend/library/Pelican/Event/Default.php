<?php
/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Event_Default implements Pelican_Event_Interface
{
    const ERROR_00 = 'null is not valid value for message';

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    private $time;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    private $message;
    private $source;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    private $consume;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    private $type;

    /**
     * __DESC__.
     *
     * @access private
     *
     * @var __TYPE__
     */
    private $user;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $type    __DESC__
     * @param __TYPE__ $user    __DESC__
     * @param __TYPE__ $message __DESC__
     * @param string   $source  (option) __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($type, Pelican_User $user, $message, $source = null)
    {
        if ($message == null) {
            throw new Pelican_Exception_Error(self::ERROR_00);
        }
        $this->message = $message;
        $this->source  = & $source;
        $this->consume = false;
        $this->time = time();
        $this->type = $type;
        $this->user = $user;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getId()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function consume()
    {
        $this->consume = true;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function isConsumed()
    {
        return $this->consume;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __toString()
    {
        $s = "Peclian_Default_Event {";
        $s .= 'message:'.$this->message.'::';
        $s .= 'source:'.$this->source.'::';
        $s .= 'is consume:';
        $this->consume ? $s .= "true" : $s .= "false";
        $s .= '}';

        return $s;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getUser()
    {
        return $this->user;
    }
}

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Event_Sql extends Pelican_Event_Default implements Pelican_Event
{
    /**
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    public function __construct($message, &$source = null)
    {
        parent::__construct($message, $source);
    }
}
