<?php
namespace Citroen\Perso;
use Citroen\Perso\Flag\Detail;

/**
 * Classe Profile
 *
 * Cette classe est déléguée à la gestion des profils
 *
 * @author Khadidja MESSAOUDI <khadidja.messaoudi@businessdecision.com>
 */

class Profile
{
    public $processes = array(
        1=> '\Citroen\Perso\Profile\Type\Pro',
        2=> '\Citroen\Perso\Profile\Type\PeutEtrePro',
        3=> '\Citroen\Perso\Profile\Type\PasPro',
        4=> '\Citroen\Perso\Profile\Type\Client',
        5=> '\Citroen\Perso\Profile\Type\PeutEtreClient',
        6=> '\Citroen\Perso\Profile\Type\ClientRecent',
        7=> '\Citroen\Perso\Profile\Type\SansContratService',
        8=>'\Citroen\Perso\Profile\Type\InteresseExtensionGarantie',
        9=>'\Citroen\Perso\Profile\Type\InteressePreControleTech',
        10=>'\Citroen\Perso\Profile\Type\PasClientRecent',
        11=>'\Citroen\Perso\Profile\Type\PasDeProjetOuvert',
        12=>'\Citroen\Perso\Profile\Type\PasDeProduitPrefere',
        13=>'\Citroen\Perso\Profile\Type\Tranche0',
        14=>'\Citroen\Perso\Profile\Type\Tranche1',
        15=>'\Citroen\Perso\Profile\Type\Tranche1Pro',
        16=>'\Citroen\Perso\Profile\Type\Tranche1Particulier',
        17=>'\Citroen\Perso\Profile\Type\Tranche2',
        18=>'\Citroen\Perso\Profile\Type\Tranche2Pro',
        19=>'\Citroen\Perso\Profile\Type\Tranche2Particulier',
        20=>'\Citroen\Perso\Profile\Type\Tranche3',
        21=>'\Citroen\Perso\Profile\Type\Tranche3Pro',
        22=>'\Citroen\Perso\Profile\Type\Tranche3Particulier',
        23=>'\Citroen\Perso\Profile\Type\Tranche4',
        24=>'\Citroen\Perso\Profile\Type\Tranche4Pro',
        25=>'\Citroen\Perso\Profile\Type\Tranche4Particulier',
        26=>'\Citroen\Perso\Profile\Type\LigneCPreferee',
        27=>'\Citroen\Perso\Profile\Type\LigneDSPreferee',
        28=>'\Citroen\Perso\Profile\Type\LigneUtilitairePreferee',
        29=>'\Citroen\Perso\Profile\Type\AutreLigneBusinessPreferee',
        30=>'\Citroen\Perso\Profile\Type\ClientRecentLigneC',
        31=>'\Citroen\Perso\Profile\Type\ClientRecentLigneDS'
    );

    public static $profil = array();

    /*
     * Lancement des processus
     */
    public function process()
    {
        if(is_array($this->processes) && !empty($this->processes)){
            $mappingClassType = array(); // Mapping associant le nom des classes de profil avec le numéro de profil
            foreach($this->processes as $type => $process){
                $reflexion = new \ReflectionClass($process);
                $mappingClassType[$reflexion->getShortName()] = $type;
                $obj = $reflexion->newInstance();
                self::$profil[$type] = $obj->init();
            }
            
            // Switch profil de tranche pro/particulier en fonction de l'indicateur pro, lorsque l'indicateur pro est prioritaire (CPW-3069)
            if (is_array(self::$profil) && !empty(self::$profil) && preg_match('/formulaire/i', Detail::$__proSource)) {
                $profilesTranche = array('Tranche1', 'Tranche2', 'Tranche3', 'Tranche4');
                foreach ($profilesTranche as $val) {
                    // Si le profil de la tranche est attribué, on redéfini les profils particulier et pro de cette tranche
                    if (self::$profil[$mappingClassType[$val]] == true) {
                        if (Detail::$pro == 'Oui') {
                            self::$profil[$mappingClassType[$val.'Particulier']] = false;
                            self::$profil[$mappingClassType[$val.'Pro']] = true;
                        } else {
                            self::$profil[$mappingClassType[$val.'Particulier']] = true;
                            self::$profil[$mappingClassType[$val.'Pro']] = false;
                        }
                    }
                }
            }
        }
    }

    public function getProfile()
    {
        $profilUser = array();
        
        // Mode forcage profil (debug). Exemple : http://example.com/fr/page.html?perso=1&perso_force_profile=10,2
        if (isset($_GET['perso_force_profile'])) {
            $profilUser = array_map('intval',preg_split('/[^0-9]+/', $_GET['perso_force_profile']));
            
            // Si le paramètre perso=1 est défini dans l'URL, affichage de la liste des profil
            if (isset($_GET['perso']) && $_GET['perso'] == 1) {
                debug(\Pelican::$config['PERSO_PROFILES'], 'Liste des profils');
            }
            return $profilUser;
        }
        
        if(is_array(self::$profil) && !empty(self::$profil)){
            foreach(self::$profil as $key=>$profil){
               if($profil == true){
                   $profilUser[] = $key;
               }
            }
        }
        return $profilUser;
    }
}