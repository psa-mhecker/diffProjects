{$doctype}
<html>
<head>
{$header}
</head>
<body id="body_popup" leftmargin="3" topmargin="3">
<script type="text/javascript">
{literal}
function submitMe() {
	callAjax({
        url: "Ndp/updateUrl",
        async: false,
        data:	{
           'pid' : document.fForm.PAGE_NAVIGATION_ID.value,
           'lang' : '{/literal}{$langueId}{literal}'
        },
        success: function(data) {
            {/literal}
                {if $tiny}
            {literal}
                betd_InternalLinkDialog.insert(document.fForm.NAVIGATION_URL.value);
            {/literal}
                {else}
            {literal}
                if (data[0].value != "document.fForm.NAVIGATION_URL.value=''") {
                    updateParent();
                } else {
                    alert('{/literal}{'PAGE_URL_EMPTY'|t:js}{literal}');
                }
            {/literal}
                {/if}
            {literal}
        }
    });
}

function updateParent(){
	if (document.fForm.NAVIGATION_URL.value) {
		if (opener) {
			if (opener.DialogArguments) {
				opener.returnPopupInternalLink(opener.DialogArguments, document.fForm.NAVIGATION_URL.value);
			} else {
				window.returnValue = document.fForm.NAVIGATION_URL.value;
			}
		} else {
			window.returnValue = document.fForm.NAVIGATION_URL.value;
		}
	}
	closePopup();
}
function update() {
    updateURL({/literal}{$url}{literal});
}
function closePopup() {
	window.close();
}
</script>
<style type="text/css">
input.c1 {
	width: 300px
}
</style>
{/literal} {$form}
<p class="bottom">
<button onclick="submitMe()">{'POPUP_BUTTON_OK'|t}</button>
<button onclick="closePopup();">{'POPUP_BUTTON_CANCEL'|t}</button>
</p>
{$footer}
</body>
</html>