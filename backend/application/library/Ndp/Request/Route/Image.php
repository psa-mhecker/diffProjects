<?php

class Ndp_Request_Route_Image extends Pelican_Request_Route_Image
{
    public function match()
    {
        $return = parent::match();

        $return['params']['root']       = '';
        $return['params']['directory']  = '';
        $return['params']['controller'] = 'Image';
        $return['params']['action']     = 'format';

        return $return;
    }
}
