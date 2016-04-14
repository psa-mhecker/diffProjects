<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : langues.
     *
     * @author Lenormand Gilles <glenormand@businessdecision.com>
     *
     * @since 23/04/2007
     */
    class Frontend_Rss extends Pelican_Cache
    {
        public $duration = DAY;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $sqlQuery = "select rss.RSS_FEED_ID ID,rss.RSS_FEED_LABEL LIB
						from #pref#_rss_feed rss
						";

            $oConnection->query($sqlQuery, $aBind);

            $results = array();
            if ($oConnection->data) {
                foreach ($oConnection->data["ID"] as $key => $data) {
                    $results[$data] = $oConnection->data["LIB"][$key];
                }
            }

            $this->value = $results;
        }
    }
