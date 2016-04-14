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
class Backend_Layout extends Pelican_Cache
{
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $category = $this->params[0];
        $aBind[":CATEGORY"]  = $category;

        $query = "SELECT
				t.*,
				tg.TEMPLATE_GROUP_ID,
				t.TEMPLATE_ID as \"id\",
				TEMPLATE_LABEL as \"lib\",
				TEMPLATE_GROUP_LABEL as \"optgroup\"
				FROM
				#pref#_template t
				left join #pref#_template_group tg on
				(t.TEMPLATE_GROUP_ID = tg.TEMPLATE_GROUP_ID) ";
        if ($category) {
            $query .= " inner join #pref#_content_template cct on (t.TEMPLATE_ID=cct.TEMPLATE_ID AND CONTENT_CATEGORY_ID=:CATEGORY)";
        }
        $query .= " ORDER BY TEMPLATE_LABEL";
        $result = $oConnection->queryTab($query, $aBind);

        foreach ($result as $ligne) {
            $template[] = array("id" => $ligne["TEMPLATE_ID"], "lib" => $ligne["TEMPLATE_LABEL"]);
        }
        $this->value = $template;
    }
}
