README
======

This directory should be used to place project specfic documentation including
but not limited to project notes, generated API/phpdoc documentation, or
manual files generated or hand written.  Ideally, this directory would remain
in your development environment only and should not be deployed with your
application to it's final production location.

Etape pour le fonctionnement :

1]
Copier le dossier "Cpw" dans le dossier "library" de l'application Zend

2]
Dans le bootStrap de l'application, il faut référencer la Librairie "Cpw" nécéssaire à la connexion ldap. Pour cela, il faut ajouter dans la fonction '_initAutoload' la ligne suivante : 
"Zend_Loader_Autoloader::getInstance()->registerNamespace('Cpw_');"

3]
Dans le dossier "configs" de l'application, il faut ajouter une nouvelle section avec 2 nouvelle propriétés comme ci-dessous
directory.type= "xml"
directory.filepath= APPLICATION_PATH "/configs/ldap.xml"

le 'type' peut valoir au choix : "xml" ou "ldap"
le 'filepath' doit contenir le chemin du fichier de données pour accéder à l'annuaire.

4]
Après avoir procéder à l'installation et à la configuration de cette librairie, il suffit de l'utiliser comme ceci :
$user = new Cpw_User($login,$password);
if ($user->login())
{
	//connexion etablie
	....
}
else
{
	//erreur de connexion
	echo $user->getLastErrorCode(); //permet d'afficher le code de l'erreur
	echo $user->getLastErrorString(); //permet d'afficher le libellé de l'erreur.
}

Pour voir les droits d'accès :
$user->isAdmin();
Administrateur de l'application, accès total à toutes les fonctions de tout les pays

$user->getRights();
Retourne un tableau associatif dont les clés sont les codes pays (ISO2) et les valeurs le droit maximum pour ce pays.
Seul les pays pour lesquels un droit est attribué sont retournés.
Les droits sont : "ADMINISTRATEUR", "CONTRIBUTEUR", "WEBMASTER", "IMPORTATEUR" (définit dans des constantes ROLE_xxx de la classe Cpw_User)
Exemple :
	"FR"=>"CONTRIBUTEUR", "BE"=>"WEBMASTER"
Administrateur : accès a toutes les fonctions, tous les pays (redondant avec isAdmin())
Webmaster : création, modification, suppression et mise en ligne des contenus d'un pays.
Importateur : création, modification, suppression et mise en ligne des contenus d'un pays.
Contributeur : création, modification, des contenus d'un pays.
Les différent droits sont donnés dans les const de Cpw_User.

$user->getBusiness()
Retourne un tableau associatif dont les clés sont les codes pays, et les valeurs sont un tableau
dont avec un role associé à un éventuel métier.
Exemple :
	"BE"=>"CONTRIBUTEUR"=>COMMUNICATION
	    =>"IMPORTATEUR"=>MARKETING
Les différent métiers sont donnés dans les const de Cpw_User.









