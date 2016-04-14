<?php
interface Pelican_Event_Loader
{
    // array ( 'queue_name', array(class_name,class_name)))
    public function getListeners();
    public function _require();
}
