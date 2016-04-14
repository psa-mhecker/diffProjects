<?php

/**
 * Fichier de Pelican_Cache : Récupération des terms pour un objet.
 *
 * @author Patrick.deroubaix@businessdecision.fr
 *
 * @since 07/09/2009
 */
class Taxonomy_Term extends Pelican_Cache
{
    /** Valeur ou objet é mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        if ($this->params[1] == Pelican::$config['TAXONOMY_BUNDLE_ID']) {
            $aBind[":TERMS_GROUPS_ID"] = $this->params[0];
            $query = "select t.TERMS_ID,t.TERMS_NAME from #pref#_terms t,#pref#_terms_groups_rel gr,
                          #pref#_terms_groups tg where t.TERMS_ID=gr.TERMS_ID
                          and gr.terms_groups_id=tg.terms_groups_id
                          and tg.TERMS_GROUP_ID=:TERMS_GROUP_ID";

            $aResult = $oConnection->queryTab($query, $aBind);
        } else {
            $aBind[":OBJECT_ID"] = $this->params[0];
            $aBind[":OBJECT_TYPE_ID"] = $this->params[1];
            $aBind[":TERMS_GROUP_ID"] = $this->params[2] ? $this->params[2] : 0;
            $query = "select t.TERMS_ID,t.TERMS_NAME from #pref#_terms t,#pref#_terms_relationships tr where tr.TERMS_ID=t.TERMS_ID
                          and tr.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND tr.TERMS_GROUP_ID=:TERMS_GROUP_ID ";

            $aResult = $oConnection->queryTab($query, $aBind);
        }
        $this->value = $aResult;
    }
}
