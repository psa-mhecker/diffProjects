<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile;
use Citroen\Perso\Profile\Type;

class ClientRecentLigneDS extends Type
{
    public function init()
    {
        return $this->equalToAandB(Detail::$recentClient, 'Oui', Profile::$profil[\Pelican::$config['PERSO_PROFILES']['LIGNE_DS_PREFEREE']], true);
        debug(Profile::$profil);
    }
}