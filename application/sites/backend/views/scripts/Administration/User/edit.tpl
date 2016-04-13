<script>
{literal}
function checkLogin() {
{/literal}
	{if $id == -2}
	var exists =  callAjax("Administration_User/checkLogin", document.getElementById("USER_LOGIN").value);
	{else}
	return true;
	{/if}
{literal}
}
{/literal}
</script>
{$content}