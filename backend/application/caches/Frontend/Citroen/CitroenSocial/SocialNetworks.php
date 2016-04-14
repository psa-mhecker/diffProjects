<?php
class Frontend_Citroen_CitroenSocial_SocialNetworks extends Pelican_Cache
{
    public $duration = HOUR;

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aNetworks = array();

        if (count($this->params)) {
            $i = 0;

            foreach ($this->params[2] as $iSocialNetworkId) {
                $aBind[':NETWORK_ID_'.$i] = $iSocialNetworkId;
                $aSqlNetworkIds[] = 'rs.RESEAU_SOCIAL_ID=:NETWORK_ID_'.$i;
                $i++;
            }

            $sSqlSocialNetworks = "SELECT
						rs.*, m.MEDIA_PATH, m.MEDIA_ALT
					FROM #pref#_reseau_social rs
                        LEFT JOIN #pref#_media m on (rs.media_id = m.media_id)
					WHERE
						rs.SITE_ID = :SITE_ID
					AND rs.LANGUE_ID = :LANGUE_ID
					";

            if (!empty($aSqlNetworkIds)) {
                $sSqlSocialNetworks .=  ' AND ('.implode(' OR ', $aSqlNetworkIds).')';
            }

            $aNetworks = $oConnection->queryTab($sSqlSocialNetworks, $aBind);
        }

        $this->value = $aNetworks;
    }
}
