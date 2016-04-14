<?php
/**
 * Retourne la liste des outils de chaque référentiel (typologie), pour le web et pour le mobile
 * param 0 : SITE_ID
 * param 1 : LANGUE_ID
 * param 2 : isMobile.
 */
class Frontend_Citroen_ReferentielOutils extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $device = isset($this->params[2]) && $this->params[2] === true ? 'mobile' : 'web';
        $bind[':SITE_ID']   = $this->params[0];
        $bind[':LANGUE_ID'] = $this->params[1];
        $bind[':DEVICE']    = $oConnection->strToBind($device);
        $stmt = "
        SELECT oa.*, o.TYPE
        FROM #pref#_referentiel_outils_assoc oa
        INNER JOIN #pref#_referentiel_outils o ON o.ID = oa.TYPO_ID AND o.SITE_ID = :SITE_ID AND o.LANGUE_ID = :LANGUE_ID AND oa.DEVICE = :DEVICE;";
        $result = $oConnection->queryTab($stmt, $bind);

        $referentiel = array();
        foreach ($result as $key => $val) {
            $referentiel[$val['TYPE']][] = $val['OUTIL_ID'];
        }

        $this->value = $referentiel;
    }
}
