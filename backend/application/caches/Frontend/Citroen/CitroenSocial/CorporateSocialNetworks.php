<?php

class Frontend_Citroen_CitroenSocial_CountrySocialNetworks extends Pelican_Cache
{
    public $duration = HOUR;

    public function getValue()
    {
        $this->value = $aNetworks;
    }
}
