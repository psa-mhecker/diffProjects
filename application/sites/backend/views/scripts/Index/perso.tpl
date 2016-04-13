{$doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	{$header}
	</head>
	<body>
		<div id="addPerso" style="float:right;padding:5px 0;clear:both;">
			<button class="addPerso">{'ADD_PERSO'|t}</button>
			<button class="savePerso">{'ENREGISTRER_FERMER'|t}</button>
			<button class="cancelPerso">{'ANNULER'|t}</button>
			<input type="hidden" name="zoneId" id="zoneId" value="{$iZoneId}" />
			<input type="hidden" name="multiId" id="multiId" value="{$sMulti}" />
		</div>
		<div id="tabs" style="float:left;clear:both;width:100%;">
		</div>
	   {$footer}
   </body>
</html>


