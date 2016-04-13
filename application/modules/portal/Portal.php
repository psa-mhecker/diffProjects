<?php

/**
 * - load
 * - install
 * - uninstall
 * Appel au Pelican_Cache avec id du plugin
 *
 */
class Module_Portal extends Pelican_Plugin
{

    /**
     * Défintion de constantes ou traitements d'initialisation du plugin au chargement
     *
     */
    function load ()
    {}

    /**
     * à lancer lors de l'installation du plugin :
     * - insertion de données
     * - création d'une table
     * - création de répertoires etc...
     */
    function install ()
    {}

    /**
     * à lancer lors de la désinstatllation
     * - suppression de tables
     * - suppression de données
     *
     */
    function uninstall ()
    {}

}