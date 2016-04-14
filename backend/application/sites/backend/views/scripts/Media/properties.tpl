<html>
	{$header}
   <body id="body_child" leftmargin="3" topmargin="3">
<script>
{literal}
var fileAttribut=parent.current.fileAttribut;
function dialogColor() {
	var arr = showModalDialog( libDir + Pelican::$config['LIB_FORM']."/editor/popup_colorpicker.htm",null, "dialogWidth:430px; dialogHeight:270px; scroll:no; status:no; center:yes; help:no" );

	if (arr != null) {
		formFile.bgcolor.value = arr.toUpperCase();
		formFile.bgcolor.focus();
		previewFile();
	}
}

function previewFile() {
	getAttributes();
	parent.showMedia();
}

function getAttributes() {
	mediaType = top.dialogArguments["mediaType"];
	if (mediaType == 'image') {
		if (formFile.align.selectedIndex != -1) {
			if (formFile.align.options[formFile.align.selectedIndex].value != null && formFile.align.options[formFile.align.selectedIndex].value != "") {
				fileAttribut["align"] = formFile.align.options[formFile.align.selectedIndex].value;
			}
		}
		fileAttribut["alt"]=formFile.alt.value;
		fileAttribut["border"]=formFile.border.value;
		fileAttribut["hspace"]=formFile.hspace.value;
		fileAttribut["vspace"]=formFile.vspace.value;
		if (formFile.height) fileAttribut["height"]=formFile.height.value;
		if (formFile.width) fileAttribut["width"]=formFile.width.value;
	} else if (mediaType == 'flash') {
		if (formFile.align.selectedIndex != -1) {
			if (formFile.align.options[formFile.align.selectedIndex].value != null && formFile.align.options[formFile.align.selectedIndex].value != "") {
				fileAttribut["align"] = formFile.align.options[formFile.align.selectedIndex].value;
			}
		}
		if (formFile.quality.selectedIndex != -1) {
			if (formFile.quality.options[formFile.quality.selectedIndex].value != null && formFile.quality.options[formFile.quality.selectedIndex].value != "") {
				fileAttribut["quality"] = formFile.quality.options[formFile.quality.selectedIndex].value;
			}
		}
		fileAttribut["bgcolor"]=formFile.bgcolor.value;
		fileAttribut["id"]=formFile.id.value;
		if (formFile.width) fileAttribut["width"]=formFile.width.value;
		if (formFile.height) fileAttribut["height"]=formFile.height.value;
	} else {
		fileAttribut["caption"]=formFile.caption.value;
		if (formFile.sizeOK.checked) {
			fileAttribut["sizeOK"]=true;
		} else {
			fileAttribut["sizeOK"]=false;
		}
		fileAttribut["size"]=formFile.size.value;
	}
	parent.buildTag();
}

function restoreImageSize(x, y) {
	if (formFile.width) formFile.oldwidth.value = formFile.width.value = x;
	if (formFile.height) formFile.oldheight.value = formFile.height.value = y;
	previewFile();
}

function resizeImage(where) {
	if (formFile.width && formFile.height) {
		if (formFile.ratio.value == "1") {
			var ratio;
			if (where == 0) {
				ratio = formFile.width.value / formFile.oldwidth.value;
				formFile.height.value = parseInt(formFile.height.value * ratio);
			} else {
				ratio = formFile.height.value / formFile.oldheight.value;
				formFile.width.value = parseInt(formFile.width.value * ratio);
			}
		}
		formFile.oldwidth.value = formFile.width.value;
		formFile.oldheight.value = formFile.height.value;
	}
	previewFile();
}

function resizeRatio() {
	if (formFile.ratio.value == "0") {
		formFile.link.src = "images/preserve_size.gif";
		formFile.ratio.value = "1";
	} else {
		formFile.link.src = "images/unpreserve_size.gif";
		formFile.ratio.value = "0";
	}
}

function init() {
	if (parent.current.mediaPath) {
		if (fileAttribut["width"] && formFile.width) formFile.width.value = fileAttribut["width"];
		if (fileAttribut["height"] && formFile.height) formFile.height.value = fileAttribut["height"];
		if (fileAttribut["align"]) formFile.align.value = fileAttribut["align"];
		if (fileAttribut["vspace"]) formFile.vspace.value = fileAttribut["vspace"];
		if (fileAttribut["hspace"]) formFile.hspace.value = fileAttribut["hspace"];
		if (fileAttribut["border"]) formFile.border.value = fileAttribut["border"];
		if (fileAttribut["alt"] && formFile.alt) formFile.alt.value = unescape(fileAttribut["alt"]);
		previewFile();
	}
}

function reload() {
	document.location.href = 'tree.php?mediaType='+parent.dialogArguments["mediaType"];
}
</script>
   <style type="text/css">
			td {vertical-align:middle; text-align:left;}
		</style>
	{/literal}
   <div class="center">
      <fieldset class="center">
         <legend><b>{'POPUP_BUTTON_PROPERTIES'|t}</b></legend>
            <br />
            <form action="" method="post" name="formFile" id="formFile">
               <input type="hidden" name="ratio" id="ratio" value="1" />
			   <input type="hidden" name="oldwidth" id="oldwidth" value="0" />
			   <input type="hidden" name="oldheight" id="oldheight" value="0" />
               <table width="100%" height="100%" border="0" cellspacing="2" cellpadding="2">
{if $bSpecifique}
                  {if $bIsFlash}
                  <tr>
                     <td>
                        {'POPUP_LABEL_NAME'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="id" id="id" value="{$file|basename}" size="10" class="txt" onchange="previewFile()" />
                     </td>
                  </tr>
				  {/if}
                                    <tr>
                     <td>
                        {'POPUP_LABEL_WIDTH'|t}&nbsp;:
                     </td>
                     <td width="25%">
                        <input type="text" name="width" id="width" value="{$size.0}" size="5" class="txt" onchange="resizeImage(0)" />
                     </td>
                     <td colspan="2" rowspan="2" align="left" valign="bottom" nowrap="nowrap">
                        <a class="pointer" onclick="resizeRatio();"><img src="images/preserve_size.gif" alt="{'POPUP_LABEL_PRESERVE'|t}" name="link" id="link" width="22" height="48" border="0"></a>
                  		{if $size}<a class="pointer" onclick="restoreImageSize({$size.0}, {$size.1});"><img src="images/original_size.gif" alt="{'POPUP_LABEL_ORIGINAL_SIZE'|t}" name="original" id="original" width="30" height="48" border="0"></a>{/if}
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_HEIGHT'|t}&nbsp;:
                     </td>
                     <td width="0">
                        <input type="text" name="height" id="height" value="<?=$size[1]?>" size="5" class="txt" onchange="resizeImage(1)" />
                     </td>
                  </tr>
				  {if $bIsImage}
                  <tr>
                     <td>
                        {'POPUP_LABEL_BORDER'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="border" id="border" value="0" size="5" class="txt" onchange="previewFile()" />
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_VERT_SPACE'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="vspace" id="vspace" size="5" class="txt" onchange="previewFile()" />
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_HOR_SPACE'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="hspace" size="5" class="txt" onchange="previewFile()" />
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_ALIGNMENT'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <select name="align" id="align" onchange="previewFile()" class="txt">
                           <option>
                           </option>
                           <option value="left">
                              {'POPUP_LABEL_LEFT'|t}
                           </option>
                           <option value="center">
                              {'POPUP_LABEL_MIDDLE'|t}
                           </option>
                           <option value="right">
                              {'POPUP_LABEL_RIGHT'|t}
                           </option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_ALT'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="alt" id="alt" size="10" class="txt" onChange="previewFile()" maxlength="60" />
                     </td>
                  </tr>
				  {/if}
				  {if $bIsFlash}
                  <tr>
                     <td>
                        {'POPUP_LABEL_BACKGROUND_COLOR'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <input type="text" name="bgcolor" id="bgcolor" value="#FFFFFF" size="10" class="txt" onChange="previewFile()" />&nbsp;&nbsp;<img src="<?=Pelican::$config["LIB_PATH"]?>/Pelican/Form/public/_work/editor/images/backcolor_form.gif" alt="{'POPUP_LABEL_BACKGROUND_COLOR'|t}" width="20" border="0" align="top" class="pointer" onClick="return dialogColor()">
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_ALIGNMENT'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <select name="align" onchange="previewFile()">
                           <option value="left">
                              {'POPUP_LABEL_LEFT'|t}
                           </option>
                           <option value="middle" selected="selected">
                              {'POPUP_LABEL_MIDDLE'|t}
                           </option>
                           <option value="right">
                              {'POPUP_LABEL_RIGHT'|t}
                           </option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_LABEL_QUALITY'|t}&nbsp;:
                     </td>
                     <td colspan="3">
                        <select name="quality" onchange="previewFile()">
                           <option value="Low">
                              {'POPUP_LABEL_QUALITY_LOW'|t}
                           </option>
                           <option value="Medium">
                              {'POPUP_LABEL_QUALITY_MEDIUM'|t}
                           </option>
                           <option value="High" selected="selected">
                              {'POPUP_LABEL_QUALITY_HIGH'|t}
                           </option>
                           <option value="Best">
                              {'POPUP_LABEL_QUALITY_BEST'|t}
                           </option>
                        </select>
                     </td>
                  </tr>
				  {/if}
{else}
                  <tr>
                     <td style="vertical-align:top;">
                        {'POPUP_LABEL_CAPTION'|t}&nbsp;:
                     </td>
                     <td>
                        <textarea rows="5" name="caption" class="text" style="width:100%" onChange="previewFile()">{$file|basename}</textarea>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        {'POPUP_MEDIA_LABEL_SIZE'|t}&nbsp;:
                     </td>
                     <td>
                        <input class="txt" type="TEXT" name="size" size="5" value="{$filesize}" onchange="previewFile()" /> Ko&nbsp; <input type="CHECKBOX" name="sizeOK" checked="checked" onclick="previewFile()" /> Afficher
                     </td>
                  </tr>
{/if}
               </table>
            </form>
      </fieldset>
{if $bSpecifique}
       <br />
{/if}
      </div>
<script type="text/javascript">
init()
{if $size}
formFile.oldwidth.value = formFile.width.value;
formFile.oldheight.value = formFile.height.value;
{/if}
</script>
      <!--
         <tr>
                    <td align="right" colspan="3">
                <hr />
                    </td>
                  </tr>
         -->
   </body>
</html>