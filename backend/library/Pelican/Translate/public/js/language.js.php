<?php
/**
 */

/**
 * Page de génération d'un tableau javascript aLabel à partir du tabelau Pelican::$lang PHP.
 *
 * les fonctions getLabel("param") et getWindowTitle("param") permettent l'utilsation des ces
 * variables de traduction dans le javascript des pages et évite de maintenir plusieurs formats de fichiers traduction
 */

/** Fichier de configuration */
header("Content-type: text/javascript");

include_once 'config.php';
?>
var aLabel = new Object();
<?php
Pelican::$lang = Pelican_Translate::getTranslations();
while (list($name, $value) = each(Pelican::$lang)) {
    //  echo("aLabel[\"".$name."\"]=\"".stripslashes(Pelican_Text::htmlentities($value))."\";\n");
    echo("aLabel[\"".$name."\"]=\"".str_replace("'", "\\'", $value)."\";\n");
}
?>

function getLabel(label) {
	document.write(aLabel[label]);
}

function getWindowTitle(label) {
	document.write('<title>' + aLabel[label] + '</title>');
}
