<?php
/**
 * Fichier de Pelican_Caches_Citroen : Gamme.
 *
 * Cache remontant les informations des véhicules. Seules les informations
 * concernant vehicule_gamme sont remontées
 *
 * @author  Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since   17/07/2013
 *
 * @param 0 SITE_ID                 Identifiant du site
 * @param 1 LANGUE_ID               Identifiant de la langue
 * @param 2 LCDV6                   Identifiant du véhicule
 * @param 3 sDisplayMode            Valeur de retour différente en fonction du type
 *                                      vide ou ''  retour sous forme du queryTab classique,
 *                                                  les clés du tableau sont générées à la
 *                                                  volée
 *                                      'combo'     le tableau de retour prend en clé le
 *                                                  LCDV6 et le tableau n'a qu'une dimension
 *                                                  pour son utilisation dans une combo de
 *                                                  formulaire
 *                                      'lcdv6'     le tableau remonté est modifié pour que les
 *                                                  clés correspondent au code LCDV6
 *                                      'row'       le tableau remonté ne comprend que le premier
 *                                                  enregistrement
 */
class Citroen_GammeVehiculeGamme extends Pelican_Cache
{
    public $duration = HOUR;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        $sSqlWhere = '';
        $sSqlOrder = '';
        $sDisplayMode = '';

        /* Mise en Bind des paramètres */
        $aBindAuto = array();

        if (!is_null($this->params[0])) {
            $aBindAuto[':SITE_ID'] = (int) $this->params[0];
        }
        if (!is_null($this->params[1])) {
            $aBindAuto[':LANGUE_ID'] = (int) $this->params[1];
        }
        if (!is_null($this->params[2])) {
            $aBindAuto[':LCDV6'] = (string) $oConnection->strToBind($this->params[2]);
        }
        if (!empty($this->params[3])) {
            $sDisplayMode = (string) $this->params[3];
        }

        /* Création de la requête principale */

        $sSqlQuery = <<<SQL
                SELECT
                    v.VEHICULE_ID,
                    v.VEHICULE_LCDV6_CONFIG,
                    v.VEHICULE_LABEL,
                    v.VEHICULE_CASH_PRICE,
                    v.VEHICULE_CASH_PRICE_TYPE,
                    wvg.*
                FROM
                    #pref#_ws_vehicule_gamme wvg
                        INNER JOIN #pref#_vehicule v
				ON (v.VEHICULE_LCDV6_CONFIG = wvg.LCDV6
                                    AND v.LANGUE_ID = wvg.LANGUE_ID
                                    AND v.SITE_ID = wvg.SITE_ID
                                    )

                        LEFT JOIN #pref#_media m
                            ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)

SQL;
        if ($sDisplayMode === 'combo') {
            $sSqlQuery = <<<SQL
                SELECT
                    wvg.*
                FROM
                    #pref#_ws_vehicule_gamme wvg
SQL;
        }

        /* Création de la requête principale */
        $sSqlOrder = <<<SQL
                ORDER BY
                    wvg.GAMME, wvg.MODEL_LABEL , wvg.BODY_LABEL
SQL;
        /* Ajout automatique des binds */
        if (is_array($aBindAuto) && !empty($aBindAuto)) {
            $iBindAuto = count($aBindAuto);
            $i = 0;
            $sSqlWhere = '';
            /* Pour chaque élément du tableau de bind automatique
             * on utilise la clé du bind pour formé le champ
             */
            foreach ($aBindAuto as $sBindKey => $aOneBind) {
                $sSqlWhere .= 'wvg.'.substr($sBindKey, 1).' = '.$sBindKey;
                if ($i !== $iBindAuto-1) {
                    $sSqlWhere .= ' AND ';
                }
                $i++;
            }
        }

        /* Ajout des contraintes à la requete principale */
        if (!empty($sSqlWhere)) {
            $sSqlQuery = "{$sSqlQuery} WHERE {$sSqlWhere} {$sSqlOrder}";
        }
        if ($sDisplayMode === 'row') {
            $aResults = $oConnection->queryRow($sSqlQuery, $aBindAuto);
        } else {
            $aResults = $oConnection->queryTab($sSqlQuery, $aBindAuto);
        }

        /* Si un mode d'affichage est indiqué, des traitements sont effectués sur
         * le tableau
         */
        if (is_array($aResults) && !empty($aResults) && !empty($sDisplayMode)) {
            switch ($sDisplayMode) {
                case 'combo':
                    foreach ($aResults as $aOneResult) {
                        $aComboView[$aOneResult['GAMME'].'_'.$aOneResult['LCDV6']] = "({$aOneResult['GAMME']}) ({$aOneResult['LCDV6']}) {$aOneResult['MODEL_LABEL']} - {$aOneResult['BODY_LABEL']}";
                    }
                    $aResults = $aComboView;
                    break;
                case 'lcdv6':
                    foreach ($aResults as $aOneResult) {
                        $aLcdv6View[$aOneResult['LCDV6']] = $aOneResult;
                    }
                    $aResults = $aLcdv6View;
                    break;
            }
        }
        $this->value = $aResults;
    }
}
