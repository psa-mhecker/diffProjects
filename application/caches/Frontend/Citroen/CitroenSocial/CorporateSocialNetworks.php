<?php

class Frontend_Citroen_CitroenSocial_CountrySocialNetworks extends Pelican_Cache {

    var $duration = HOUR;

    function getValue() {
        $this->value = $aNetworks;
    }

}
