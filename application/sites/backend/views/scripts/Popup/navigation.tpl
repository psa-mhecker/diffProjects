{$doctype}
<html>
<head>
{$header}
</head>
<body id="body_popup" leftmargin="3" topmargin="3">
<script type="text/javascript">
var windowArguments = new Object;
/** firefox 2- */
{literal}
if (opener && !window.dialogArguments) {
	if (opener.DialogArguments) {
		windowArguments = opener.DialogArguments;
	}
}
/** ie en modal et firefox 3 */
if (window.dialogArguments) {
	windowArguments = window.dialogArguments;
}

function updateURLAjax(clearUrl) {
    callAjax({
        url: "Citroen/updateUrl",
        async: false,
        data:	{
           'pid' : document.fForm.PAGE_NAVIGATION_ID.value,
           'lang' : {/literal}{$langue_id}{literal}
        }
    });
    return false;
}

{/literal}{if $multiple}{literal}
	/** ajout multiple */
	function submitMe() {
		if (document.fForm.CONTENT_NAVIGATION_ID.options.length) {
			var aQuery = new Array;
			var pid;
			var url = "/index.php?cid=";
			if (document.fForm.PAGE_NAVIGATION_ID.value) {
				url = "/index.php?pid=" + document.fForm.PAGE_NAVIGATION_ID.value + "&cid=";
			}
			for (var i=0; i < document.fForm.CONTENT_NAVIGATION_ID.options.length; i++) {
				aQuery[i] = new Object;
				aQuery[i].NAVIGATION_TITLE = document.fForm.CONTENT_NAVIGATION_ID.options[i].innerText;
				aQuery[i].NAVIGATION_URL = url + document.fForm.CONTENT_NAVIGATION_ID.options[i].value;
				aQuery[i].NAVIGATION_TITLE2 = "<a href='http://" + windowArguments["host"] + "/" + aQuery[i].NAVIGATION_URL + "' target='_blank'>" + aQuery[i].NAVIGATION_TITLE + "</a>";
			}
			if (opener) {
				opener.returnAddMenu2(windowArguments['obj'], aQuery);
			} else {
				window.returnValue = aQuery;
			}
		}
		window.close();
	}
	{/literal}{else}{literal}
	/** propriété d'un menu */
	var obj;
	var initObj = new Object;
	var child = new Object;
	var childObj = new Object;
	var childTab = new Array;
	var j = 0;
	if (windowArguments["obj"]) {
	initObj = windowArguments["obj"];
	if (initObj.hasChildNodes()) {
		for(var i=0; i<initObj.childNodes.length; i++) {
			child = initObj.childNodes[i];
			/** patch Firefox et autres */
			if (child.tagName == "SPAN") {
				child.name = "NAVIGATION_TITLE2[]";
			}
			if (child.name) {
				if (child.name.indexOf("[]") != -1) {
					str = child.name.replace("[]","");
					childObj[str] = child;
					childTab[j]=str;
					j++;
				}
			}
		}
	}
	}

	function submitMe() {
		{/literal}{$img}{literal}
		document.fForm.NAVIGATION_TITLE2.value = document.fForm.NAVIGATION_TITLE.value;
		if (document.fForm.NAVIGATION_BOLD.checked) {
{/literal}
		{if $plan_site}
				document.fForm.NAVIGATION_TITLE2.value = "&nbsp;&nbsp;&nbsp;&nbsp;" + document.fForm.NAVIGATION_TITLE2.value;
				{else}
				document.fForm.NAVIGATION_TITLE2.value = "<b>" + document.fForm.NAVIGATION_TITLE2.value + "</b>";
				{/if}
{literal}
				document.fForm.NAVIGATION_BOLD.value = 1;
		} else {
			document.fForm.NAVIGATION_BOLD.value = 0;
		}
		if (document.fForm.NAVIGATION_URL.value) {
			urltemp = document.fForm.NAVIGATION_URL.value;
			if (document.fForm.NAVIGATION_URL.value.indexOf("http") == -1) {
				urltemp = "http://" + windowArguments["host"] + "/" + urltemp;
			}
			document.fForm.NAVIGATION_TITLE2.value = "<a href='" + urltemp + "' target='_blank'>" + document.fForm.NAVIGATION_TITLE2.value + "</a>";
		}
		if (childTab) {
			for (i=0; i<childTab.length; i++) {
				if (document.fForm.NAVIGATION_TITLE.value) {
					obj = document.getElementById(childTab[i]);
					if (obj) {
						value = obj.value;
					}
				} else {
					value = "" ;
				}
				if (childTab[i] == "NAVIGATION_TITLE2") {
					childObj[childTab[i]].innerHTML = value;
				} else {
					childObj[childTab[i]].value = value;
				}
                if (childTab[i] == "NAVIGATION_PARAMETERS" && !document.getElementById(childTab[i])) {
                    tmp = document.getElementsByName(childTab[i]);
                    for(j=0;j<tmp.length;j++) {
                        if(tmp[j].checked) {
                            childObj[childTab[i]].value = tmp[j].value;
                        }
                    }
                }
			}
		}
		window.close();
	}
{/literal}{/if}{literal}

</script>
<style type="text/css">
input.c1 {
	width: 300px
}
</style>
{/literal}{$form}
<p class="bottom">
<button onclick="if (CheckForm(document.fForm)) submitMe()">{'OK'|t}</button>
<button onclick="window.close();">{'POPUP_BUTTON_CANCEL'|t}</button>
</p>
{if !$multiple}{literal}
<script type="text/javascript">
      if (childTab) {
      	for (i=0; i<childTab.length; i++) {
      		obj = document.getElementById(childTab[i]);
      		if (obj) {
      			if (obj.name != "PAGE_NAVIGATION_ID" && obj.name != "CONTENT_NAVIGATION_ID" {/literal}{if $media} && obj.name != "NAVIGATION_IMG" {/if}{literal}) {
      				//obj = eval("document.fForm." + childTab[i]);
      				obj.value = childObj[childTab[i]].value ;
      				if (childTab[i] == "NAVIGATION_BOLD" && childObj[childTab[i]].value>0) {
      					obj.checked=true;
      				}
      			}
      		} else {
                obj = document.getElementsByName(childTab[i]);
                if (obj.length) {
                    if (childObj[childTab[i]].value) {
                        obj[childObj[childTab[i]].value-1].checked = true;
                    }
                }
            }
      	}
      	{/literal}{$img}{literal}
      }
      </script>
{/literal}{/if}
{$footer}
</body>
</html>