<?php
interface Pelican_Event_Listener
{
    public function onEvent(Pelican_Event & $message);
}
