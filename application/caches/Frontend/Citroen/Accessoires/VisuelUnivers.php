<?php
/**
 * Fichier de Pelican_Cache : Visuel des accessoires définis en backoffice dans "Univers Accessoires"
 * @package Cache
 * @subpackage Pelican
 */
 
use Citroen\Accessoires;
 
class Frontend_Citroen_Accessoires_VisuelUnivers extends Pelican_Cache
{
    public $duration = DAY;
    
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        
        // Récupération données
        $stmt = "SELECT ua.ID, ua.MEDIA_ID, ua.CODE, m.MEDIA_PATH
        FROM #pref#_univers_accessoires ua
        INNER JOIN #pref#_media m ON m.MEDIA_ID = ua.MEDIA_ID
        WHERE ua.SITE_ID = :SITE_ID AND ua.LANGUE_ID = :LANGUE_ID;";
        $bind[':SITE_ID']   = $this->params[0];
        $bind[':LANGUE_ID'] = $this->params[1];
        $result = $oConnection->queryTab($stmt, $bind);
        
        // Indexation par le champ code
        $visuels = array();
        foreach ($result as $key => $val) {
            $visuels[$val['CODE']] = $val;
        }
        
        $this->value = $visuels;
    }
}
