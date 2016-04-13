<?php
/**
 * Liste des scores referrer pour un site
 *
 * @package Cache
 * @subpackage Pelican
 */

class Frontend_Citroen_Perso_ReferrerScore extends Pelican_Cache
{
    public $duration = DAY;
    
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        
        // Collecte paramètres
        $siteId = isset($this->params[0]) ? $this->params[0] : null;
        
        // Requête
        $stmt = "SELECT rs.* FROM #pref#_perso_referrer_score rs WHERE rs.SITE_ID = :SITE_ID";
        $bind[':SITE_ID'] = $siteId;
        $result = $oConnection->queryTab($stmt, $bind);
        $result = is_array($result) ? $result : array();
        
        // Assemblage
        $scores = array(
            'default' => null,
            'keywords' => null,
        );
        foreach ($result as $key => $val) {
            if ($val['IS_DEFAULT_SCORE'] == 1) {
                if (isset($scores['default']['UPDATED']) && $val['UPDATED'] < $scores['default']['UPDATED']) {
                    continue;
                }
                $scores['default'] = $val;
            } else {
                $scores['keywords'][$val['KEYWORD']] = $val;
            }
        }
        
        $this->value = $scores;
    }
}
