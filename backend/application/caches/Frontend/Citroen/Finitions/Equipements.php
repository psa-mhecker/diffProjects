<?php
/**
 * Fichier de Pelican_Cache : Equipements.
 */
class Frontend_Citroen_Finitions_Equipements extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aLcdv6Gamme = $this->params[0];
        $aBind[':LCDV6'] = $oConnection->strToBind($aLcdv6Gamme['LCDV6']);
        $aBind[':GAMME'] = $oConnection->strToBind($aLcdv6Gamme['GAMME']);
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $sSQL = "
            SELECT
				*
			FROM
				#pref#_ws_equipement_disponible
			WHERE
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID
			AND LCDV6 = :LCDV6
			AND GAMME = :GAMME

        ";
        if (empty($aLcdv6Gamme['GAMME'])) {
            $sSQL = "
				SELECT
					*
				FROM
					#pref#_ws_equipement_disponible
				WHERE
					SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID
				AND LCDV6 = :LCDV6
			";
        } else {
            $sSQL = "
				SELECT
					*
				FROM
					#pref#_ws_equipement_disponible
				WHERE
					SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID
				AND LCDV6 = :LCDV6
				AND GAMME = :GAMME

			";
        }
        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $aEquipements = array();
        if (is_array($aResult) && count($aResult)>0) {
            foreach ($aResult as $key => $res) {
                $aEquipements[$res['GR_COMMERCIAL_NAME_CODE']][$res['CATEGORY_NAME']]['LABEL'] = $res['CATEGORY_NAME'];
                $aEquipements[$res['GR_COMMERCIAL_NAME_CODE']][$res['CATEGORY_NAME']]['EQUIPEMENTS'][] = array(
                'LABEL' => $res['EQUIPEMENT_NAME'], 'DISPONIBILITY' => $res['DISPONIBILITY'], );
            }
        }
        $this->value = $aEquipements;
    }
}
