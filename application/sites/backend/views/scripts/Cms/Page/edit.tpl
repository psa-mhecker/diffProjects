{$languageTab} 
<br />
{if $isSchedule}
    <div style="color:white;background-color:red;text-align: center;padding-bottom: 5px;padding-top: 5px;" > 
        {'ATTENTION_LA_PAGE_EST_EN_COURS_DE_PLANIFICATION'|t} 
    </div>
{/if}

{$form}
{$scheduleForm}
{$versionForm}
{if $tableZone}
    {$tableZone}
{/if}
<script type="text/javascript">
fwFocus = pageFocus;
var lblShow = '{'AFFICHER'|t}';
var lblHide = '{'MASQUER'|t}';
var lbldelete = '{'CONFIRM_DELETE_ZONE'|t}';
{literal}
function pageFocus(obj) {
	var ori = obj;
	while (obj != null && obj.tagName != "DIV" && obj.id.indexOf("Divtogglezone") == -1) {
		obj = obj.parentElement;
	}
	if (obj.id.indexOf("Divtogglezone") != -1) {
		id = obj.id.replace("Divtogglezone","");
		if (iZone != id) {
			showHideZone(id);
		}
	}
	ori.focus();
}

var iZone;
function deleteZone(obj) {
    if (confirm(lbldelete)) {
        $('#'+obj.id)
            .closest('div')
            .slideUp("slow", function(){
                $(this).remove();
            });
        $('#' + obj.id.replace('deleteZone', '') + 'multi_display').val('');
    }
}
function showHideZone(divID, doSetCookie, nbrzone) {

	var ici = window.location;
	urlici = String(ici);

	var searchresult = urlici.search(/blc/);

	if(searchresult != -1){
		urlici = urlici.replace(/&blc=./,"");
		document.location.href=urlici+"&openZone="+divID;
	}

	var divIDobj = $("#Divtogglezone" + divID);
	var toggleobj = $("#togglezone" + divID);
	var callbackInfo = {};

	if (iZone && iZone != divID) {
		showHideZone(iZone, doSetCookie, nbrzone);
	}

	for(i=1;i<=nbrzone;i++) {
		if(i != divID) {
			var divIDobj2 = $("#Divtogglezone" +i);
			var toggleobj2 = $("#togglezone" +i);

			if (divIDobj2 != null && toggleobj2 != null) {
				toggleobj2.attr('src' , libDir+"/public/images/toggle_zone_close.gif");
				toggleobj2.attr('alt' , lblShow);
				divIDobj2.fadeIn();
			}
		}
	}
	if (divIDobj != null && toggleobj != null) {
		if (divIDobj.is(':hidden')) {
			callbackInfo.action = 'open';
			toggleobj.attr('src' , libDir+"/public/images/toggle_zone_open.gif");
			toggleobj.attr('alt' , lblHide);
			divIDobj.fadeIn("fast", function(){
			    $('body').animate({scrollTop: $("#togglezone"+divID).offset().top}, 'slow');
			});
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, false, 30);
			}
			iZone = divID;
		} else {
			callbackInfo.action = 'close';
			toggleobj.attr('src' , libDir+"/public/images/toggle_zone_close.gif");
			toggleobj.attr('alt' , lblShow);
			divIDobj.hide();
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, true, 30);
			}
			iZone = "";
		}
	}
	
	var zoneHeight = $('#visualZoneView').height() + $('#visualZoneDisplay').height() - $('#visualZoneDisplayMin').height();
	$('#visualZoneTop').stop().animate({
	        top:'-'+zoneHeight+'px'
	    },500
	);
    
	// Appel fonction JS callback lors de l'ouverture/fermeture du bloc
	try {
		window['showHideZone_callback_'+divID](callbackInfo);
	} catch(ex){}
}

function showHideZone2(divID, doSetCookie, nbrzone) {

	var ici = window.location;
	urlici = String(ici);

	var searchresult =urlici.search(/blc/);

	if(searchresult != -1){
		urlici = urlici.replace(/&blc=./,"");
		document.location.href=urlici+"&openZone="+divID;
	}

	var divIDobj = document.getElementById("Divtogglezone" + divID);
	var toggleobj = document.getElementById("togglezone" + divID);
	var divObj = document.getElementById("divZone" + divID);

	if (iZone && iZone != divID) {
		showHideZone(iZone, doSetCookie, nbrzone);
	}

	for(i=1;i<=nbrzone;i++) {
		if(i != divID) {
			var divIDobj2 = document.getElementById("Divtogglezone" +i);
			var toggleobj2 = document.getElementById("togglezone" +i);
			var divObj2 = document.getElementById("divZone" +i);

			if (divIDobj2 != null && toggleobj2 != null) {
				toggleobj2.src = libDir+"/public/images/toggle_zone_close.gif";
				toggleobj2.alt = lblShow;
				divObj2.style.display = "none";
			}
		}
	}
	if (divObj != null && toggleobj != null) {
		if (divObj.style.display == "none") {

			divObj.style.top="100";
			divObj.style.left="50";
			divObj.style.width="85%";
			{/literal}
			divObj.style.height="{$height}";
			{literal}

			toggleobj.src = libDir+"/public/images/toggle_zone_open.gif";
			toggleobj.alt = lblHide;
			divObj.style.display = "";
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, false, 30);
			}
			iZone = divID;
		} else {
			toggleobj.src = libDir+"/public/images/toggle_zone_close.gif";
			toggleobj.alt = lblShow;
			divObj.style.display = "none";
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, true, 30);
			}
			iZone = "";
		}
	}
}

function changeGabarit(obj) {
	{/literal}document.location.href = '{$clean_url}' + '&gid=' + obj.value;{literal}
}

function controlDesc() {
	var chapo = document.getElementById('PAGE_TEXT').value;
	var desc = document.getElementById('PAGE_META_DESC').value;
	if (chapo && desc) {
		if (chapo != desc) {
			if (confirm('Vous venez de modifier le chapô, souhaitez-vous reporter cette modification dans le champ "description" ?')) {
				document.getElementById('PAGE_META_DESC').value = document.getElementById('PAGE_TEXT').value;
			}
		}
	}
}
{/literal}
{$zoneDynamiqueJs}
</script>
<script for="PAGE_TEXT" event="onchange" type="text/javascript">
controlDesc();
</script>
{if $openZone}
<script type="text/javascript">
showHideZone('{$openZone}',false,'{$nbrbloc}')
				</script>
{/if}