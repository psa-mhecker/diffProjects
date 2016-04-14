{$doctype}
<html>
<head>
{$header}
</head>
<body id="body_popup" leftmargin="3" topmargin="3">
<script type="text/javascript">
{literal}
function submitMe() {
	document.fForm.submit();
}
function closeMe() {
	window.close();
}
{/literal}
{$body}
</script>
<fieldset><legend><b>{"Ordre d'affichage"|t}</b></legend>
<div id="scroll-container" class="area">
<ul id="thelist2" style="padding: 2px;">
	{$li}
</ul>
</div>
<form name="fForm" style="margin: 0px;" method="post"><input
	type="hidden" name="PAGE_ID" value="{$pid}" /> <input type="hidden"
	name="uid" value="{$uid}" /> <input type="hidden" id="PAGE_ORDER"
	name="PAGE_ORDER" /></form>
<br />
</fieldset>
<script type="text/javascript" language="javascript">
// <![CDATA[
{literal}
Position.includeScrollOffsets = true;
Sortable.create('thelist2',{scroll:'scroll-container',
onChange:function(element){$('PAGE_ORDER').value = Sortable.join(element.parentNode, ',')}
});
{/literal}
// ]]>
</script>
<p class="bottom">
<button onclick="submitMe();">{'OK'|t}</button>
<button onclick="closeMe();">{'POPUP_BUTTON_CANCEL'|t}</button>
</p>
{$footer}
</body>
</html>