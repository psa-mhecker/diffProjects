<script>
{literal}
$(function() {
	MakeItDraggable();
	$('#EditZone').tabs();
	//init screen tools
	SetScreenAction ();

	var CountTabs = 0;
	$('#EditZone .inner').each(function(){		
		var idTabInner = $(this).attr('id');
		
			MakeItDroppable ( CountTabs );

			if (CountTabs>0) {

				//$('#EditZone ul li:eq('+CountTabs+') a').css('color','');
				$('#EditZone').tabs( "add", "#Inner" + CountTabs, "<img src='/images/mobapp/bullet.png' alt='Ecran " + CountTabs +"' />");
				//$('#EditZone #Inner' + CountTabs + ' ul').droppable( "option", "disabled", false );
				//$('#EditZone #Inner'+(CountTabs-1)+' .message').show();
				//MakeItDroppable(CountTabs);
			}
		CountTabs++;
	});
	MakeItDraggable();	
	//inti bullet tabs position
	moveTabs ();
	$('#EditZone .inner .delete') . hide ();
	//cosmetic actions
	$('#EditZone ul') . removeClass ( 'ui-widget-header' );
	$('#EditZone ul') . removeClass ( 'ui-corner-all' );
	$('#EditZone ul li') . removeClass ( 'ui-state-default' );
	$('#EditZone ul li') . removeClass ( 'ui-corner-top' );

	$('#EditFormButton').bind(
			'click', function() {
				setButtonValues();
			});
});
{/literal}
</script>
<div id="TypeContenus">
<div id="ListApps">
<ul>
   {section name=index loop=$App}
	   {$App[index].html}
   {/section}
	</ul>
</div>
</div>

<form method="post">
<div id="EditZone">
   {section name=index loop=$aPage}
	    {$aPage[index].html}
   {/section}
</div></form>

<div id="EditForm">
<h2>Edition</h2>
<div id="AppForm">
{literal}<form name="fForm" id="fForm" action="/_/Mobapp_HomeConfig" method="post" onsubmit="return setButtonValues();" style="margin:0 0 0 0;" class="fwForm"><table border="0" cellspacing="0" cellpadding="0" class="form" id="tableClassForm" summary="Formulaire"><tr><td class="formlib">Titre *</td><td class="formval"><input type="text" class="text" name="MOBAPP_SITE_HOME_LABEL" id="MOBAPP_SITE_HOME_LABEL" size="50" maxlength="50" value="" /></td></tr><tr><td class="formlib">Image originale</td><td class="formval"><img src="file" height="60" width="60" id="ICON" /></td></tr><tr><td class="formlib">Icone</td><td class="formval"><table cellpadding="0" cellspacing="0" border="0"><tr><td width="2" id="divMEDIA_ID2" nowrap="nowrap"></td><td style="vertical-align:top;"><input type="button" class="button" value="Ajouter" onclick="popupMedia('image', '/library/Pelican/Media/public/', this.form.elements['MEDIA_ID2'], 'divMEDIA_ID2', '','http:\/\/phpfactory.dev.media\/','',true);" />&nbsp;<input type="button" class="button" value="Supprimer" onclick="if(confirm('Confirmez-vous cette suppression ?')) {this.form.elements['MEDIA_ID2'].value=''; document.getElementById('divMEDIA_ID2').innerHTML = '';}" /></td></tr>{/literal}
</table>
</td></tr><input class="button" type="button" name="validate" id="validate" value="Valider" onclick=""  /></table>
<input type="hidden" id="MOBAPP_SITE_HOME_ID" name="MOBAPP_SITE_HOME_ID" />
<input type="hidden" id="MOBAPP_CONTENT_TYPE_CODE" name="MOBAPP_CONTENT_TYPE_CODE" />
<input type="hidden" id="MEDIA_ID2" name="MEDIA_ID2" />
<input type="submit" />
</form>
</div>
</div>

  


