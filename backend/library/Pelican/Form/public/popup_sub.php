<?php
/** Popup de création des sous formulaires utilisés par createSubForm
 * @version 1.0
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/01/2003
 */
/** Fichier de configuration */
include_once 'config.php';

include_once pelican_path('Form');
require_once pelican_path('Text.Utf8');

// connection à la base de données
$oConnection = Pelican_Db::getInstance();
?>
<html>
<head>
<!--<meta http-equiv="content-type" content="text/html<?=(Pelican::$config["CHARSET"] ? "; charset=".Pelican::$config["CHARSET"] : "")?>" />-->
</head>
<body>
<?php
$values = $_GET;
$oForm = Pelican_Factory::getInstance('Form');
$oForm->open("");
beginFormTable();

ob_start();
if (isset($_GET["subfile"])) {
    include_once $_GET["subfile"];
} elseif (isset($_GET["hmvc"])) {
    pelican_import('Controller.Back');

    foreach ($_GET as $key => $value) {
        $tabValues[$key] = $value;
    }

    $params = explode(',', $_GET["hmvc"]);
    if (file_exists($params[0])) {
        include_once $params[0];
    }
    echo call_user_func_array(array(
        $params[1],
        $params[2],
    ), array(
        &$oForm,
        $tabValues,
        false,
    ));
}
$oForm->putHidden();
$form_html .= ob_get_contents();
ob_end_clean();
endFormTable();
$oForm->close();

if ($_GET["subjs"]) {
    $js = $$_GET["subjs"];
}

if (Pelican::$config["CHARSET"] == "UTF-8") {
    $form_html = Pelican_Text_Utf8::utf8_to_unicode($form_html);
}
$form_html = rawurlencode($form_html);
?>
<script type="text/javascript">
	parent.fillSub('<?=$_GET["divname"]?>', '<?=$form_html?>');
	<?php
if ($js) {
    echo($js);
}
?>
</script>
</body>
</html>
