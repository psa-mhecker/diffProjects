<?php
/**
* Retourne la liste des profils de personnsalisation, indexé par le PROFILE_ID.
* Si le paramètre $sCodeLangue est renseigné, le cache récupère aussi la traduction pour la langue courante,
* en utilisant la clé de traduction PROFILE_I18N_KEY pour chaque profil.
* Le paramètre $sCodeLangue n'est pas directement utilisé par ce cache template, il l'est indirectement à travers la fonction de traduction (t).
* Ce paramète donc nécessaire pour avoir un fichier de cache propre à chaque langue.
* 
* @package Cache
* @subpackage Pelican
* @author Vincent Paré <vincent.pare@businessdecision.com>
* @since 04/02/2014
* @param 0 string $sCodeLangue      Code de la langue courante (accessible dans $_SESSION[APP]['LANGUE_CODE'])
*/
class Citroen_PersoProfile extends Pelican_Cache {
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        // Collecte paramètres
        $sCodeLangue = isset($this->params[0]) ? $this->params[0] : null;

        // Récupération contenu de la table profile
        $oConnection = Pelican_Db::getInstance();
        $sSqlQuery = "SELECT * FROM #pref#_perso_profile ORDER BY PROFILE_ID ASC";
        $result = $oConnection->queryTab($sSqlQuery);

        // Assemblage du tableau de résultat
        $return = array();
        foreach ($result as $key => $val) {
            $return[$val['PROFILE_ID']] = $val;
            
            // Ajout de la traduction pour la langue courante si le paramètre $sCodeLangue est renseigné
            if ($sCodeLangue && !empty($val['PROFILE_I18N_KEY'])) {
                $return[$val['PROFILE_ID']]['locallabel'] = Citroen_Translate::tForceBo($val['PROFILE_I18N_KEY'], "forceBO");
            }
        }

        $this->value = $return;
    }
}