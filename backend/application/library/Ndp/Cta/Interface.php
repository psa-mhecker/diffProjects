<?php

// Declaration de l'interface 'Ndp_Cta_Interface'
interface Ndp_Cta_Interface
{
    const MAX_TITLE_LENGTH=50;
    /*
     * Génération du formulaire pour un nouveau CTA
     * @param object $form
     * @param string $multi
     * @param array $values
     * @param bool $readO
     
     */

    public function getInput(Ndp_Form $form, $multi, $values, $readO,$needed = true);
}
