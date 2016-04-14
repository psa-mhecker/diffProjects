<?php
/**
 * Fichier de Pelican_Caches_Citroen : Gamme.
 *
 * Cache remontant les informations sur les finitions des véhicules
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
 *                                      'lcdv6'     le tableau remonté est modifié pour que les
 *                                                  clés correspondent au code LCDV6
 *                                      'vehicule'  le tableau renvoyé remonte les Prix/Finitions/version
 *                                                  par ordre de prix croissant. Cela permet de remonter
 *                                                  le prix "A partir de"
 */
class Citroen_GammePrixFinitionVersion extends Pelican_Cache
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
                SELECT *
                FROM
                    #pref#_ws_prix_finition_version
SQL;

        /* Création de la requête principale */
        $sSqlOrder = <<<SQL
                ORDER BY
                    LCDV6
SQL;
        /* Surcharge du order pour l'affichage dans le formulaire car on souhaite
         * afficher dans le formulaire le prix comptant pour un véhicule, mais
         * le prix dépend du choix de la motorisation et de la finition. On prend
         * donc ici le prix le moins cher
         */
        if ($sDisplayMode === 'form') {
            $sSqlOrder = <<<SQL
                ORDER BY
                    PRICE_NUMERIC
SQL;
        }

        /* Ajout automatique des binds */
        if (is_array($aBindAuto) && !empty($aBindAuto)) {
            $iBindAuto = count($aBindAuto);
            $i = 0;
            $sSqlWhere = '';
            /* Pour chaque élément du tableau de bind automatique
             * on utilise la clé du bind pour formé le champ
             */
            foreach ($aBindAuto as $sBindKey => $aOneBind) {
                $sSqlWhere .= substr($sBindKey, 1).' = '.$sBindKey;
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

        if ($sDisplayMode === 'vehicule') {
            $aResults = $oConnection->queryRow($sSqlQuery, $aBindAuto);
        } else {
            $aResults = $oConnection->queryTab($sSqlQuery, $aBindAuto);
        }

        /* Si un mode d'affichage est indiqué, des traitements sont effectués sur
         * le tableau
         */
        if (is_array($aResults) && !empty($aResults) && !empty($sDisplayMode)) {
            switch ($sDisplayMode) {
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
