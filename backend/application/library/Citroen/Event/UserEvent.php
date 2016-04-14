<?php
namespace Citroen\Event;

use Symfony\Component\EventDispatcher\Event;

/*class USER_EVENTS {



}*/

class UserEvent extends Event
{
    const LOGIN = 'user.login';

    public function __construct($args = null)
    {
        if (is_array($args)) {
            if (isset($args['user'])) {
                $this->user = $args['user'];
            }
            if (isset($args['mode'])) {
                $this->mode = $args['mode'];
            }
        }
    }
}
