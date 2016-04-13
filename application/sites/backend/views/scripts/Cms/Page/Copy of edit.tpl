{$languageTab}
<br />
{$form}
{$versionForm}
{if $tableZone}
<script type="text/javascript">
		startMenu(" ZONES");
		addItem(unescape('{$tableZone}'));
		endMenu();
		</script>
{/if}
<script type="text/javascript">
fwFocus = pageFocus;
var lblShow = '{'AFFICHER'|t}';
var lblHide = '{'MASQUER'|t}';
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
function showHideZone(divID, doSetCookie, nbrzone) {

	var ici = window.location;
	urlici = String(ici);

	var searchresult =urlici.search(/blc/);

	if(searchresult != -1){
		urlici = urlici.replace(/&blc=./,"");
		document.location.href=urlici+"&openZone="+divID;
	}

	var divIDobj = document.getElementById("Divtogglezone" + divID);
	var toggleobj = document.getElementById("togglezone" + divID);

	if (iZone && iZone != divID) {
		showHideZone(iZone, doSetCookie, nbrzone);
	}

	for(i=1;i<=nbrzone;i++) {
		if(i != divID) {
			var divIDobj2 = document.getElementById("Divtogglezone" +i);
			var toggleobj2 = document.getElementById("togglezone" +i);

			if (divIDobj2 != null && toggleobj2 != null) {
				toggleobj2.src = libDir+"/public/images/toggle_zone_close.gif";
				toggleobj2.alt = lblShow;
				divIDobj2.style.display = "none";
			}
		}
	}
	if (divIDobj != null && toggleobj != null) {
		if (divIDobj.style.display == "none") {
			toggleobj.src = libDir+"/public/images/toggle_zone_open.gif";
			toggleobj.alt = lblHide;
			divIDobj.style.display = "";
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, false, 30);
			}
			iZone = divID;
		} else {
			toggleobj.src = libDir+"/public/images/toggle_zone_close.gif";
			toggleobj.alt = lblShow;
			divIDobj.style.display = "none";
			if (doSetCookie == true) {
				setCookie('togglezone'+divID, true, 30);
			}
			iZone = "";
		}
	}
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
</script>
<script for="PAGE_TEXT" event="onchange" type="text/javascript">
controlDesc();
</script>
{if $openZone}
<script type="text/javascript">
showHideZone('{$openZone}',false,'{$nbrbloc}')
				</script>
{/if}