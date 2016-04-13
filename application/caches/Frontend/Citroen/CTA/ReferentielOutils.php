<?php
/**
 * Retourne le type de l'outil
 * param 0 : SITE_ID
 * param 1 : LANGUE_ID
 * param 2 : isMobile
 * param 3 : OUTIL_ID
 *
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_CTA_ReferentielOutils extends Pelican_Cache
{
    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $referentielOutil =array();
         $connection = Pelican_Db::getInstance();
            $bind = array(
                ':OUTIL_ID' => $this->params[3],
                ':DEVICE' =>$connection->strToBind($this->params[2]),
                ':SITE_ID' => $this->params[0],
                ':LANGUE_ID' => $this->params[1],
            );
            $sql = 'SELECT * '
                .'FROM #pref#_referentiel_outils pro'
                .' LEFT JOIN #pref#_referentiel_outils_assoc proa '
                .' ON (pro.ID = proa.TYPO_ID)'
                .' WHERE OUTIL_ID = :OUTIL_ID'
                .' AND DEVICE = :DEVICE'
                .' AND SITE_ID = :SITE_ID'
                .' AND LANGUE_ID = :LANGUE_ID';

            $referentielOutil = $connection->queryRow($sql, $bind);
        $this->value = $referentielOutil['TYPE'];
    }
}
