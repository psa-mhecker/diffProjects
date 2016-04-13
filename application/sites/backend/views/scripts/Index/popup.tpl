{$doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
   <body id="body_popup" leftmargin="3" topmargin="3">
    <fieldset id="div_fieldset">
        <legend><b>{'CONTENUS'|t}</b></legend>
	</fieldset>

   	   <div id="div_content">
			<div id="frame_left_top">{$title}</div>
			<div id="frame_left_middle">{$left_middle}</div>
			<div id="frame_left_bottom">{$left_bottom}</div>

			<div id="frame_right_top">&nbsp;</div>
			<div id="frame_right_middle">{$right_middle}</div>
			<div id="frame_right_bottom">{$right_bottom}</div>
		</div>


 	<div id="div_popup_footer">
       	<button id="buttonOk" style="display:none;" onClick="select();">{'CHOISIR'|t}</button>
        &nbsp;
       	<button onclick="window.close();">{'POPUP_BUTTON_CLOSE'|t}</button>
	</div>
{$default}
{$footer}
   </body>
</html>


