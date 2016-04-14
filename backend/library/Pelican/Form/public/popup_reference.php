<?php
    /** Popup de recherche à distance d'entrées en base de données pour mettre à jour les listes ou combo
     * @version 3.0
     *
     * @author Jean-Baptiste Ruscassie <jbruscassie@businessdecision.com>
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 15/01/2002
     */
    /** Fichier de configuration */
    include_once 'config.php';
    include_once pelican_path('Form');

    $strLib = $_REQUEST[$_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"]];
    $strIdDel = $_REQUEST["CONTENT_CATEGORY_ID"];

    if (!empty($strLib) || !empty($strIdDel)) {
        if ($_REQUEST["Act"] == 'add') {
            $oConnection = Pelican_Db::getInstance();
            $oConnection->Query("select 1 from ".Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]." where lower(".$_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"].") = '".str_replace("'", "''", $strLib)."'");

            if ($oConnection->rows == 0) {
                $iID = $oConnection->getNextId(strtolower(Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]));
                if (!$iID) {
                    $iID = $oConnection->QueryItem("select max(".$_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_ID"].")+1 from ".Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]);
                }
                if (!$iID) {
                    $iID = 1;
                }
                Pelican_Db::$values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                Pelican_Db::$values[strtoupper($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_ID"])] = $iID;
                Pelican_Db::$values[strtoupper($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"])] = $strLib;
                $oConnection->insertQuery(Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]);
                ?>
<html>
<head>
<script type="text/javascript">
<!--
	window.opener.addValue("<?= $_REQUEST["Form"] ?>.<?= (($_REQUEST["Mul"] != 1) ? "elements[\\"."\"".$_REQUEST["Field"]."[]\\"."\"]" : $_REQUEST["Field"]) ?>", "<?= str_replace("\"", "\\"."\"", $strLib) ?>", <?= $iID ?>);

		<?php
            if ($_REQUEST["Ref"] != 1) {
                ?>
	window.opener.addValue("<?= $_REQUEST["Form"] ?>.src<?= $_REQUEST["Field"] ?>", "<?= str_replace("\"", "\\"."\"", $strLib) ?>", <?= $iID ?>);
			<?php

            }
                ?>
	self.close();
//-->
</script>
</head>
</html>

		<?php

            } else {
                $strError = t('POPUP_REF_USED');
            }
        }

        if ($_REQUEST["Act"] == 'del' && $_REQUEST["Confirm"] == 1) {
            //debug($strIdDel,'$strIdDel');
            //exit;

            $oConnection = Pelican_Db::getInstance();
            $strDelCat = "DELETE FROM ".Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]."
											  WHERE CONTENT_CATEGORY_ID =".$strIdDel;
            $oConnection->Query($strDelCat);

            $strUpdContent = "Update #pref#_content_version
												  SET CONTENT_CATEGORY_ID = NULL
												  WHERE CONTENT_CATEGORY_ID =".$strIdDel;
            $oConnection->Query($strUpdContent);

            ?>
			<html>
				<head>
					<script type="text/javascript">
					<!--

						window.opener.delValue("<?= $_REQUEST["Form"] ?>.<?= (($_REQUEST["Mul"] != 1) ? "elements[\\"."\"".$_REQUEST["Field"]."[]\\"."\"]" : $_REQUEST["Field"]) ?>", <?= $strIdDel ?>);

						<?php
                            if ($_REQUEST["Ref"] != 1) {
                                ?>
								window.opener.delValue("<?= $_REQUEST["Form"] ?>.src<?= $_REQUEST["Field"] ?>",  <?= $strIdDel ?>);
						<?php

                            }
            ?>

						self.close();
					//-->
					</script>
				</head>
			</html>

<?php

        }

        if ($_REQUEST["Act"] == 'upd') {
            debug($strIdDel, '$strIdDel');
            debug($strLib, 'lib');
            //exit;

            $oConnection = Pelican_Db::getInstance();
            $strDelCat = "DELETE FROM ".Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]."
											  WHERE CONTENT_CATEGORY_ID =".$strIdDel;
            $oConnection->Query($strDelCat);

            Pelican_Db::$values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            Pelican_Db::$values[strtoupper($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_ID"])] = $strIdDel;
            Pelican_Db::$values[strtoupper($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"])] = $strLib;
            $oConnection->insertQuery(Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]);
            /*$strUpdContent = "INSERT INTO ".Pelican::$config['FW_PREFIXE_TABLE'].$_REQUEST["Table"]."
                              VALUES (".$strIdDel.",'".$strLib."')";
            $oConnection->Query($strUpdContent);*/

?>
			<html>
				<head>
					<script type="text/javascript">
					<!--

						window.opener.updValue("<?= $_REQUEST["Form"] ?>.<?= (($_REQUEST["Mul"] != 1) ? "elements[\\"."\"".$_REQUEST["Field"]."[]\\"."\"]" : $_REQUEST["Field"]) ?>", "<?= str_replace("\"", "\\"."\"", $strLib) ?>", <?= $strIdDel ?>);

						<?php
                            if ($_REQUEST["Ref"] != 1) {
                                ?>
								window.opener.updValue("<?= $_REQUEST["Form"] ?>.src<?= $_REQUEST["Field"] ?>", "<?= str_replace("\"", "\\"."\"", $strLib) ?>",  <?= $strIdDel ?>);
						<?php

                            }
            ?>

						self.close();
					//-->
					</script>
				</head>
			</html>

<?php

        }
    }

    if (empty($strLib) || !empty($strError)) {
        $oForm = Pelican_Factory::getInstance('Form', true);
        if ($_REQUEST["Act"] == 'add') {
            ?>
<html>
<?php
pelican_import('Index');
            Pelican::$frontController = new Pelican_Index(false);
            Pelican::$frontController->setTitle(t('FORM_BUTTON_ADD'));
            pelican_import('Controller.Back');
            include_once Pelican::$config ['APPLICATION_VIEW_HELPERS'].'/Div.php';
            Pelican_Controller_Back::_setSkin(Pelican::$frontController);
            Pelican::$frontController->setCss(Pelican::$frontController->skinPath."/css/popup.css.php");
            echo Pelican::$frontController->getHeader();
            ?>
<body id="body_popup">
<div align="center">
<br />
	<?php
        if (!empty($strError)) {
            ?>
<font color="#ff0000"><?php echo $strError ?></font><br /><br />
		<?php

        }
            ?>
<table width="300" cellpadding="0" cellspacing="0" border="0">
	<?php
        $oForm->open("popup_reference.php");
            $oForm->createHidden("Form", $_REQUEST["Form"]);
            $oForm->createHidden("Field", $_REQUEST["Field"]);
            $oForm->createHidden("Table", $_REQUEST["Table"]);
            $oForm->createHidden("Ref", $_REQUEST["Ref"]);
            $oForm->createHidden("Mul", $_REQUEST["Mul"]);
            $oForm->createHidden("Act", $_REQUEST["Act"]);

            $oForm->createInput($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"], t('FORM_LABEL'), 100, "", true, $strLib, false, 30);
            ?>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><?php $oForm->createSubmit("Save", t('POPUP_BUTTON_SAVE'));
            ?>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php $oForm->createButton("Close", t('POPUP_BUTTON_CLOSE'), "close");
            ?></td>
</tr>
	<?php
        $oForm->close();

            if ($oForm instanceof Zend_Form) {
                $oForm->hideFormTabTag(array("hideTab" => true));
                echo $oForm;
            }
            ?>
</table>
					</div>
				</body>
			</html>
	<?php

        } elseif ($_REQUEST["Act"] == 'del') {
            ?>
			<html>
				<head>
					<link href="css/popup.css.php" rel="stylesheet" type="text/css" />
					<title><?=t('POPUP_LABEL_DEL')?></title>
				</head>

				<body id="body_popup">
					<div align="center">
						<br />
						<?php
                            if (!empty($strError)) {
                                ?>
								<font color="#ff0000"><?php echo $strError ?></font><br /><br />
						<?php

                            }
            ?>
						<table width="300" cellpadding="0" cellspacing="0" border="0">
							<?php
                                $oForm->open("popup_reference.php");
            $oForm->createHidden("Form", $_REQUEST["Form"]);
            $oForm->createHidden("Field", $_REQUEST["Field"]);
            $oForm->createHidden("Table", $_REQUEST["Table"]);
            $oForm->createHidden("Ref", $_REQUEST["Ref"]);
            $oForm->createHidden("Mul", $_REQUEST["Mul"]);
            $oForm->createHidden("Act", $_REQUEST["Act"]);
            $oForm->createHidden("Confirm", "");

            $oConnection = Pelican_Db::getInstance();

            $strCategorie = "Select CONTENT_CATEGORY_ID,
														CONTENT_CATEGORY_LABEL
												 from #pref#_content_category
												 order by CONTENT_CATEGORY_LABEL";

            $oForm->createComboFromSql($oConnection, "CONTENT_CATEGORY_ID", t('FORM_LABEL'), $strCategorie, $strLib, true);
                                //$oForm->createInput($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"], t('FORM_LABEL'), 100, "", true, $strLib, false, 30);
                            ?>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
									<td colspan="2" align="center"><?php $oForm->createSubmit("Delete", t('POPUP_LABEL_DEL'));
            ?>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php $oForm->createButton("Close", t('POPUP_BUTTON_CLOSE'), "close");
            ?></td>
							</tr>
							<?php
                                $oForm->createJS("if (confirm('".t('POPUP_CONFIRM_COMBO')."')){
													obj.Confirm.value = 1;
												} else {
													obj.Confirm.value = 0;
												}");
            $oForm->close();

            if ($oForm instanceof Zend_Form) {
                $oForm->hideFormTabTag(array("hideTab" => true));
                echo $oForm;
            }

            ?>
						</table>
					</div>
				</body>
			</html>
<?php

        } elseif ($_REQUEST["Act"] == 'upd') {
            ?>
			<html>
				<head>
					<link href="css/popup.css.php" rel="stylesheet" type="text/css" />
					<title><?=t('POPUP_LABEL_DEL')?></title>
				</head>

				<body id="body_popup">
					<div align="center">
						<br />
						<?php
                            if (!empty($strError)) {
                                ?>
								<font color="#ff0000"><?php echo $strError ?></font><br /><br />
	<?php

                            }
            ?>
						<table width="300" cellpadding="0" cellspacing="0" border="0">
							<?php
                                $oForm->open("popup_reference.php");
            $oForm->createHidden("Form", $_REQUEST["Form"]);
            $oForm->createHidden("Field", $_REQUEST["Field"]);
            $oForm->createHidden("Table", $_REQUEST["Table"]);
            $oForm->createHidden("Ref", $_REQUEST["Ref"]);
            $oForm->createHidden("Mul", $_REQUEST["Mul"]);
            $oForm->createHidden("Act", $_REQUEST["Act"]);
            $oForm->createHidden("Confirm", "");

            $oConnection = Pelican_Db::getInstance();

            $strCategorie = "Select CONTENT_CATEGORY_ID,
														CONTENT_CATEGORY_LABEL
												 from #pref#_content_category
												 order by CONTENT_CATEGORY_LABEL";

            $oForm->createLabel('Info :', t('POPUP_INFO_UPDATE_COMBO'));
            $oForm->createComboFromSql($oConnection, "CONTENT_CATEGORY_ID", t('POPUP_OLD_LABEL_COMBO'), $strCategorie, $strLib, true);
            $oForm->createInput($_REQUEST["Table"].Pelican::$config["FW_SUFFIXE_LIBELLE"], t('POPUP_NEW_LABEL_COMBO'), 100, "", true, $strLib, false, 30);
            ?>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
									<td colspan="2" align="center"><?php $oForm->createSubmit("Update", t('POPUP_BUTTON_OK'));
            ?>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php $oForm->createButton("Close", t('POPUP_BUTTON_CLOSE'), "close");
            ?></td>
							</tr>
							<?php
                                /*$oForm->createJS("if (confirm('".t('POPUP_CONFIRM')."')){
                                                    obj.Confirm.value = 1;
                                                } else {
                                                    obj.Confirm.value = 0;
                                                }");*/
                                $oForm->close();

            if ($oForm instanceof Zend_Form) {
                $oForm->hideFormTabTag(array("hideTab" => true));
                echo $oForm;
            }
            ?>
						</table>
</div>
</body>
</html>
<?php

        }
    }
?>
