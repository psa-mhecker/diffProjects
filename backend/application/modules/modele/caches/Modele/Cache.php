<?php

/**
 */
class Modele_Cache extends Pelican_Cache
{
    public function getValue()
    {
        $this->value = $response;
    }
}
