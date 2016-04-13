<?php
/** Popup de sélection de contenus internes simplifié
	*
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/10/2004
	* @package Pelican
	* @subpackage Pelican_Form
	*/

/** Fichier de configuration */
include_once('config.php');
include_once(pelican_path('Form'));
/**Fichier avec des focntion js utilisée dans la popup navigation */
?>
<?php
pelican_import('Index');
Pelican::$frontController = new Pelican_Index ( false );
Pelican::$frontController->setTitle ( t('EDITOR_INTERNAL') );
	pelican_import ( 'Controller.Back' );
	include_once (Pelican::$config ['APPLICATION_VIEW_HELPERS'] . '/Div.php');
	Pelican_Controller_Back::_setSkin ( Pelican::$frontController );
Pelican::$frontController->setCss ( Pelican::$frontController->skinPath . "/css/popup.css.php" );
echo Pelican::$frontController->getHeader ();
?>
   <body id="body_popup" leftmargin="3" topmargin="3">
<script type="text/javascript">
function submitMe() {
	update();
	if (document.fForm.NAVIGATION_URL.value) {
		if (opener) {
			if (opener.DialogArguments) {
				opener.returnPopupInternalLink(opener.DialogArguments, document.fForm.NAVIGATION_URL.value);
			}
		} else {
			window.returnValue = document.fForm.NAVIGATION_URL.value;
		}
	}
	closePopup();
}
function update() {
	updateURL(<?=(Pelican::$config["CLEAR_URL"]?"true":"false")?>);
}
function closePopup() {
	window.close();
}
</script>
<script type="text/javascript" src="/js/navigation.js"></script>
<script type="text/javascript" src="/library/Pelican/Form/public/js/xt_text_controls.js"></script>
<? if ($tiny) {?>
	<script type="text/javascript" src="<?=Pelican::$config["LIB_PATH"]?>/External/tiny_mce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="<?=Pelican::$config["LIB_PATH"]?>/External/tiny_mce/plugins/betd_internallink/js/betd_internallink.js"></script>
<?}?>
<style type="text/css">
 input.c1 {width:300px}
</style>
<?php
$oForm = Pelican_Factory::getInstance('Form',true);
$oForm->open("");
?>
      <fieldset>
         <legend><b><?=t('MSG_AIDE_SELECT')?></b></legend>
	<?php
	require_once(Pelican::$config["CONTROLLERS_ROOT"]."/index/include_internallink.php");
	?>
      </fieldset>
	<?php
	$oForm->close();
	?>
      <p class="bottom">
         <button onclick="submitMe()"><?=t('POPUP_BUTTON_OK')?></button>
         <button onclick="closePopup();"><?=t('POPUP_BUTTON_CANCEL')?></button>
      </p>
   </body>
</html>