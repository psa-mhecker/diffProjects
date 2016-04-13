<?php
/** Fonctions javascript du backoffice
	*
	* @package Pelican_BackOffice
	* @subpackage Pelican_Index
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 13/01/2006
	*/
?>
<script type="text/javascript">
<?php
/** Gestion des boutons
	* les boutons sont déclarés dans pelican.ini.php dans Pelican::$config["MASTER_BUTTON"]
	* un bouton doit être créé avec "Pelican::$frontController->button->display"
	* 2 modes d'actions possibles (exemple dans template_include.php et initialisaiton de $BUTTON) :
	* - une url défini l'action => document.location.href
	* - un nom de formulaire de l'iframe de droite est défini => exécution de la propriété onsubmit
	*/
if ($_SERVER['SCRIPT_FILENAME'] == '/projects/pelican/public/backend/index_front.php' || $_GET["is_edit_front"] == "1") {
	$this->button->init("", true);
} else {
	$this->button->init();
}

?>

var blnPreview = false;
var blocSubmit = false;

function clickButton(command) {
	var vSubmit=true;
	var page=getIFrameDocument("iframeRight");
	var state=null;
	/*	if (!blocSubmit) { */
	if (buttonDef[command]) {
		switch (command) {
			case "mutualisation" : {
				wManageRef = popupSimpleNoScroll(libDir + "<?=Pelican::$config['LIB_FORM']?>/script/popup_content.php?zone=single&contenttype=" + decodeURIComponent(buttonDef[command]) + "&mutualisation=true", "", 914, 470);
				break;
			}
			case "moderation" : {
				//alert('popup de modération : ' + decodeURIComponent(buttonDef[command]));
				wManageRef = popupSimpleNoScroll("/index_moderation.php?moderate=" + decodeURIComponent(buttonDef[command]), "", 630, 220);
				break;
			}
			case "add" :
			case "back" : {
				page.location.href = decodeURIComponent(buttonDef[command]);
				break;
			}
			<?php
			echo $this->button->javascriptAction("state");
			?>
			case "delete" :
			case "save" : {
				var oForm=page.forms[decodeURIComponent(buttonDef[command])];
				oForm.form_button.value = command;

				if (state) {
					oForm.STATE_ID.value=state;
					/* envois d'alerte de maj */

					if(state == 4 && oForm.form_alerte) {
						var agree = confirm("envoyer un email de mise a jour?");
						if(agree) {
							oForm.form_alerte.value = "1";

						}
					}

				}
				if (blnPreview) {
					oForm.form_preview.value = 1;
					oForm.form_retour.value = page.location.href;
					blnPreview = false;
				}
				if (oForm.onsubmit) {
					vSubmit=oForm.onsubmit();
				}
				if (vSubmit) {
					oForm.submit();
				}
				break;
			}
			case "preview" : {
				if (confirm("La page doit s'enregistrer avant d'être vue")) {
					blnPreview = true;
					clickButton("save");
				}
				break;
			}
		}
	}
	blocSubmit = true;
	/*	} else {
	alert('vous avez déjà cliqué, le traitement est en cours');
	} */
}
</script>