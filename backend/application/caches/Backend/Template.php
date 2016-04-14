<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Tableau des chemins des fichiers templates.
 *
 * retour : *, id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 10/01/2006
 */
class Backend_Template extends Pelican_Cache
{
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $result = Pelican_Cache::fetch("Template");

        foreach ($result as $ligne) {
            $root = ($ligne["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] : Pelican::$config["CONTROLLERS_ROOT"]);
            $template[$ligne["TEMPLATE_ID"]] = array("path" => $root.$ligne["TEMPLATE_PATH"],"path_fo" => $root.'/'.$ligne["TEMPLATE_PATH_FO"], "name" => $ligne["TEMPLATE_LABEL"]);
        }
        $this->value = $template;
    }
}
