<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Résultat de requête sur #pref#_STATE.
     *
     * retour : id, lib
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 20/06/2004
     */
    class Backend_State_Publication extends Pelican_Cache
    {
        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $query = "SELECT
				STATE_PUBLICATION
				FROM
				#pref#_state
				WHERE
				STATE_ID=".$this->params[0];
            $this->value = $oConnection->queryItem($query);
        }
    }
