<div class="portal-auth">
<div class="portal-non-moveable">
<div class="portal_header">
{if $isLogged}
<div class="portal-form">
<form action="{$base_url}/" method="post">
	<input type="hidden" name="logout" value="1" />
	<label class="label">Vous êtes : {$userid} </label>
	<input class="submit" type="submit" value="Déconnexion"/>
</form>
</div>
{else}
<div class="portal-form">
<form action="#" method="post">
 <label class="label">{foreach from=$aMessage item=mess}{$mess}{/foreach} </label>
 <label for="userid" class="label">Login : </label><input class="input" id="userid" name="userid" type="text" value="Login" onfocus="if(this.value=='Login') this.value=''" />
 <label for="userpassword" class="label">Password : </label><input class="input" name="userpassword" id="userpassword" type="password" value="Password" onfocus="if(this.value=='Password') this.value='';" />
 <input class="submit" type="submit" value="Se connecter"/>
</form>
</div>
{/if}

<div class="portal-form">
{if $isLogged}
{** utilisateur loggué : lien pour ouvrir le layer de gestion des blocs **}
{** <div class="manage">&nbsp;|&nbsp;<a href="#" onclick="openPortalTopViewLayer('/layout/portal/common/addZone.php','TEMPLATE_PAGE_ID={$tpl}&PAGE_ID={$_GET.pid}');">Gérer les blocs</a></div> **}
{** <div onclick="modifyPortalCss('{$pelican_config.DESIGN_HTTP}/css/portal/portal2.css');">Test changement de css</div> **}
{else}
{** utilisateur non loggué : lien pour ouvrir le layer d'inscription **}
<div class="manage">&nbsp;|&nbsp;<a href="#" onclick="openPortalTopViewLayer('/layout/portal/common/inscription.php','TEMPLATE_PAGE_ID={$tpl}&PAGE_ID={$_GET.pid}');">Inscription</a></div>
{/if}
{if $isLogged}
<label class="label">&nbsp;|&nbsp;Mode d'affichage :</label>
{html_radios options=$aMode name=change_mode onclick="modifyPortalMode(this.value);" class="radio" checked=1}
{/if}
</div>

</div>

</div>
</div>
