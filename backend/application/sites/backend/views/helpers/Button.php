<?php
/**
 * Classe de gestion des boutons du Pelican_Index_Backoffice.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 10/01/2006
 */

/**
 * Classe de gestion des boutons du Pelican_Index_Backoffice.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 10/01/2006
 */
class Backoffice_Button_Helper
{
    public static $showButtons;

    /**
     * Création des boutons Ajout, Modification, Suppression etc...
     *
     * La fonction javascript displayButtons est créée à partir des éléments fournis dans $aButtons
     *
     * Création d'un nouveau bouton :
     * 1/ définition dans APP.ini.php (Pelican::$config["MASTER_BUTTON"])
     * 2/ création du bouton dans index.php avec "display"
     * 3/ L'initialisation du paramètre $aButtons avec l'indice du bouton détermine son affichage ou non (depuis la page fille vers la page parente)
     *
     * @param mixed $aButtons Tableau des commandes associées aux boutons à utiliser
     */
    public static function init($aButtons = "", $bFront = false)
    {
        $aButtonDef = array();
        /* pour masquer le bouton brouillon */
        if (valueExists($aButtons, "save") && valueExists($aButtons, "state_1")) {
            $aButtons["state_1"] = "";
        }

        // Suppression du bouton delete pour CPPV2
        if (valueExists($aButtons, "deletepage")) {
            $aButtons["deletepage"] = "";
        }

        /* définitions javascript des boutons (valeurs 'null' pour la page parente (identifiée par $aButtons=""), valeurs non nulles définies dans la page fille pour les boutons à faire apparaître */
        $aButtonDef[] = "var buttonDef=new Object;";

        $aStates = Pelican_Cache::fetch("Backend/State");
        if ($aStates) {
            foreach ($aStates as $state) {
                    Pelican::$config["MASTER_BUTTON"][] = "state_".$state["id"];
            }
        }

        $customButton = Pelican::$config["MASTER_BUTTON"];
        if ($customButton) {
            foreach ($customButton as $label) {

                if (!$bFront) {
                    $aButtonDef[] = "if (top.buttonDef) {top.buttonDef['".$label."']=".(!empty($aButtons[$label]) ? "escape('".$aButtons[$label]."')" : "null").";}";
                    $aButtonObj[] = "document.getElementById('button_".$label."').style.display=(buttonDef['".$label."']>''?'':'none');";
                } else {
                    if ($aButtons) {
                        $aButtonDef[] = "if (parent.buttonDef) {parent.buttonDef['".$label."']=".(empty($aButtons[$label]) ? "escape('".$aButtons[$label]."')" : "null").";}";
                    }
                    $aButtonObj[] = "document.getElementById('button_".$label."').style.display=(buttonDef['".$label."']>''?'':'none');";
                }
            }
            $aButtonsDef = implode("\n", $aButtonDef)."\n";
        }

        if ($aButtons) {

            $tempDisplay = $aButtonsDef;
            if (!$bFront) {
                $tempDisplay .= "if (top.displayButtons) {
                top.displayButtons();
						}";
            } else {
                $tempDisplay .= "if (parent.displayButtons) {
                parent.displayButtons();
						}";
            }
            self::$showButtons = Pelican_Html::script(
                array(
                    type => "text/javascript",
                ),
                $tempDisplay
            );

        } else {
            /* javascript de la page parente => déclaration des boutons, masqués par défaut */
            $display = $aButtonsDef;
            $display .= "function displayButtons() {\n";
            if ($customButton) {
                $display .= implode("\n", $aButtonObj)."\n";
            }
            $display .= "}\n";
            /* blockui BEGIN */
            $display .= "function showLoading(id, state){

	//l'affichage du block 'Traitement en cours...' ne fonctionne pas sous IE10

	var myNav = navigator.userAgent.toLowerCase();
	var isIE = ((myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false);
	//

	if(isIE===false || isIE<9)
	{

	if (!id) {
		id = 'body';
	}
	if (!state) {
		$(id).unblock();
	} else {
		$(id).block({ css: {
			border: 'none',
			padding: '25px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			width: '20%',
			opacity: '.7',
			color: '#fff',
			cursor:'wait'
		},
		overlayCSS:  {
			backgroundColor:'#fff',
			opacity:        '0'
		},
		message: '<img src=\"/images/ajax-loader.gif\" alt=\"\"/><h1>Traitement en cours...</h1>',
		fadeIn:  200,
        fadeOut:  200,
        timeout: 8000 });
		//$('.blockOverlay').attr('title','Click to unblock').click($.unblockUI);
		}
	}
}";
            self::$showButtons = $display;
        }
    }

    /**
     * Création du tag Pelican_Html d'affichage d'un bouton.
     *
     * @param string $id Identifiant du bouton (button_XX)
     * @param string $label Libellé du bouton
     * @param string $icon Chemin d'accès à l'icone du bouton
     * @param string $action Commande JS associée au click
     */
    public static function display($id, $label, $icon = "", $action = "", $sDisplay = "none")
    {
        if ($action) {
            if ($icon) {
                $img = Pelican_Html::img(
                    array(
                        src => $icon,
                        alt => Pelican_Text::htmlentities($label),
                        height => 12,
                        border => 0,
                        align => "top",
                    )
                );
            }
            $return = Pelican_Html::button(
                array(
                    id => $id,
                    style => "display:".$sDisplay.";padding: 3px 3px 3px 3px;",
                    onclick => $action,
                ),
                $img."&nbsp;".$label
            );
        } else {
            $return = Pelican_Html::span(
                array(
                    id => $id,
                ),
                ""
            );
        }

        return $return;
    }

    public static function updateButtons($bFront = false)
    {
        /* blockui BEGIN */
        $return = self::$showButtons;
        if (!$bFront) {
            $return .= Pelican_Html::script(
                "
            if (typeof(CheckForm) == 'function') {
				CheckFormO = CheckForm;
				CheckForm = function(obj) {
					if (CheckFormO(obj)) {
					if (top.showLoading) {
					top.showLoading('#frame_right_middle',true);
						}
						return true;
					} else {
						return false;
					}
				}
			}
			if (top.showLoading) {
				top.showLoading('#frame_right_middle',false);
			}
			"
            );
        } else {
            $return .= Pelican_Html::script(
                "if (typeof(CheckForm) == 'function') {
				CheckFormO = CheckForm;
				CheckForm = function(obj) {
					if (CheckFormO(obj)) {
						return true;
					} else {
						return false;
					}
				}
			}
			"
            );
        }

        /* blockui END */

        return $return;
    }

    /**
     * Définition des commandes javascript de gestion des boutons de workflow.
     *
     * @param string $type type d'action
     *
     * @return code javascript de gestion des boutons de workflow
     */
    public static function addJavascriptAction($type, $sScript = "")
    {
        $return = "";
        if ($type == "state") {
            $aStates = Pelican_Cache::fetch("Backend/State");
            if ($aStates) {
                foreach ($aStates as $state) {
                    $return .= "case \"state_".$state["id"]."\" :\n";
                }
                $return .= "state=command.replace(\"state_\",\"\");\n";
            }
        } else {
            $return .= "case '".$type."' : { ".$sScript." }\n";
        }

        return $return;
    }

    public static function clickActionJavascript()
    {
        if ($_SERVER['SCRIPT_FILENAME'] == '/projects/pelican/public/backend/index_front.php' || !empty($_GET["is_edit_front"])) {
            self::init("", true);
        } else {
            self::init();
        }
        $return = self::$showButtons;
        $return .= "\n";
        $return .= "var blnPreview = false;
var blocSubmit = false;

function clickButton(command) {
	var vSubmit=true;
	var page=getIFrameDocument('iframeRight');

	var state=null;
	/*	if (!blocSubmit) { */
	if (buttonDef[command]) {

		switch (command) {
			case 'mutualisation' : {
				wManageRef = popupSimpleNoScroll(libDir + '".Pelican::$config['LIB_FORM']."/popup_content.php?zone=single&contenttype=' + unescape(buttonDef[command]) + '&mutualisation=true', '', 914, 470);
				break;
			}
			case 'add' :
			case 'back' : {
				page.location.href = unescape(buttonDef[command]);
				break;
			}
";
        $return .= self::addJavascriptAction('state')."\n ";
        $return .= "case 'delete' :
			case 'save' : {
				var oForm=page.forms[unescape(buttonDef[command])];
				oForm.form_button.value = command;

				if (state) {

					oForm.STATE_ID.value=state;
					/* envois d'alerte de maj */

					if(state == 4 && oForm.form_alerte) {

						var agree = confirm('".t('An e-mail request for publication will be sent.')."');
						if(agree) {
							oForm.form_alerte.value = '1';

						} else {
							oForm.form_alerte.value = '0';
						}
					}

				}
				if (blnPreview) {

					oForm.form_preview.value = 1;
					oForm.form_retour.value = page.location.href;
					blnPreview = false;
				}
				if (oForm.onsubmit && !(command == 'state_5' && (oForm.form_name.value == 'page' || oForm.form_name.value == 'content'))) {
                                        oForm.form_retour.value = oForm.form_retour.value+'&toprefresh=1';

					vSubmit=oForm.onsubmit();

				} else {
				   if (typeof oForm.onClickDelete == 'function')
                    {
                     oForm.onClickDelete();
                    }
				}
				if (vSubmit) {

					oForm.submit();
				}
				break;
			}
			case 'preview' : {
				if (confirm('".t("NEED_RECORD", 'js')."')) {
					blnPreview = true;
					clickButton('save');
				}
				break;
			}
		}
	}
	blocSubmit = true;
}";

        return $return;
    }
}
