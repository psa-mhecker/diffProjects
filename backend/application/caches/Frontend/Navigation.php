<?php
    /**
     */
    pelican_import('Hierarchy');

    /**
     * Fichier de Pelican_Cache : Chemin de fer d'un epage.
     *
     * @param string $this->params[0] id de page
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 02/09/2006
     */
    class Frontend_Navigation extends Pelican_Cache
    {
        public $duration = DAY;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $aBind[":SITE_ID"] = $this->params[0];
            $aBind[":PAGE_ID"] = $this->params[1];
            $aBind[":LANGUE_ID"] = $this->params[2];

            $strSqlPage = "
				SELECT
				PAGE_PATH,
				PAGE_LIBPATH
				FROM
				#pref#_page p
				WHERE PAGE_ID=:PAGE_ID
				AND LANGUE_ID=:LANGUE_ID
				AND SITE_ID=:SITE_ID";

            $this->value = $oConnection->queryRow($strSqlPage, $aBind);
        }
    }
