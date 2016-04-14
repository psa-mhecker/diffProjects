{$doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
<body id="body_login" {$load}>
<script type="text/javascript">getResolution();</script>
<form action="" method="post" name="fLogin" id="fLogin" autocomplete="off">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="bottom" width="10%" height="98">&nbsp;</td>
		<td valign="middle" width="90%" height="98">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top" width="100%" align="center" colspan="2">
		<table border="0" cellspacing="0" cellpadding="0" align="center" width="500" class="login">
			<tr>
				<td colspan="3" align="center">
				<br />
				{$logo}<br />
				<br />
				{$msg}</td>
			</tr>
			{if $combo}
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right"><b><label for="SITE_ID">{'CHOOSE_SITE'|t}</label> :</b>&nbsp;&nbsp;&nbsp;</td>
				<td align="left">
				{$combo}</td>
			</tr>
			{else}
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="left">&nbsp;</td>
			</tr>
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right"><b><label for="login">{'LOGIN'|t}</label> :</b>&nbsp;&nbsp;&nbsp;</td>
				<td align="left"><input type="text" name="login" id="login" size="30" maxlength="255" class="text" value="{$login}" />&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td align="left">&nbsp;</td>
				<td align="right"><b><label for="password">{'PASSWORD'|t}</label> :</b>&nbsp;&nbsp;&nbsp;</td>
				<td align="left"><input type="password" name="password" id="password" size="30" maxlength="255" class="text" />&nbsp;&nbsp;</td>
			</tr>
            {if $langCombo}
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="right"><b><label for="lang">{'LANGUAGE'|t}</label> :</b>&nbsp;&nbsp;&nbsp;</td>
                    <td align="left">{$langCombo}&nbsp;&nbsp;</td>
                </tr>
            {/if}
            <tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="center"><input type="submit" name="val" value="&nbsp;&nbsp;&nbsp;{'Valider'|t}&nbsp;&nbsp;&nbsp;" class="button" />
				</td>
			</tr>
			{/if}
			<tr>
				<td colspan="3" align="center">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
{$footer}
</body>
</html>