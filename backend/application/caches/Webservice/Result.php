<?php
/**
 * Fichier de Pelican_Cache : Résultat d'un appel de webservice
 * 0 : url du webservice
 * 1 : paramètres d'appel
 * 2 : plage de durée (pour forcer l'exécution d'un nouvel appel ws tous les x secondes )
 * 3 : adaptateur de stockage.
 *
 * @return mixed
 *
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 *
 * @since 11/08/2011
 **/
class Webservice_Result extends Pelican_Cache
{
    public $duration = DAY;
    public $storage;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        if (isset($this->params[3]) && $this->params[3]) {
            $this->storage = $this->params[3];
        }

        $aData = Pelican_Factory::getInstance('Webservice')->makeDirectCall($this->params[0], $this->params[1]);
        if ($aData && $aData != '') {
            $this->value = $aData;
        } else {
        }
    }

    public function setDuration($duration)
    {
    }
}
