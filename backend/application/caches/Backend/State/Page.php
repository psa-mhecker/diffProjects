<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Etat de publication d'une page.
     *
     * retour : id, lib
     *
     * @author Pierre Pottié <pierre.pottie@businessdecision.com>
     *
     * @since 2/7/2015
     */
    class Backend_State_Page extends Pelican_Cache
    {
        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
        $bind = [
            ":SITE_ID" => $this->params[0],
            ":LANGUE_ID" => $this->params[1],
            ":TEMPLATE_PAGE_ID" => $this->params[2]
            ];
        
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
         if ($type_version == "CURRENT") {
            $status = " AND PAGE_STATUS=1";
        }
        $connection = Pelican_Db::getInstance();

            $query = "SELECT
                        pv.STATE_ID
        			FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				WHERE p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                                $status
				";
            
            $this->value = $connection->queryItem($query,$bind);

        }
    }
