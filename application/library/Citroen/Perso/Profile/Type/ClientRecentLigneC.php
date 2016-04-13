<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile;
use Citroen\Perso\Profile\Type;

class ClientRecentLigneC extends Type
{
    public function init()
    {
        return $this->equalToAandB(Detail::$recentClient, 'Oui', Profile::$profil[\Pelican::$config['PERSO_PROFILES']['LIGNE_C_PREFEREE']], true);
    }
}