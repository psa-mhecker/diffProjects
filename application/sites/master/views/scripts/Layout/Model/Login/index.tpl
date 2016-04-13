{if $LOGIN_USER_NAME}
Bonjour {$LOGIN_USER_NAME}
<br />
<br />
<form action="#" method="post" name="form-login" id="form-login" >
<span class="art-button-wrapper">
<span class="l"> </span>
<span class="r"> </span>
<input type="submit" name="logout" class="art-button" value="Se déconnecter" />
</span>
</form>
{else}
{literal}
<script type="text/javascript">
      $(document).ready(function() {
       $("#form-login").validationEngine({
        success :  false,
	    failure : function() {}
       })
      })
</script>
{/literal}

<form action="#" method="post" name="form-login" id="form-login" >
<fieldset class="input">
<p id="form-login-username">
<label for="LOGIN_USER">Utilisateur :</label><br />
<input id="LOGIN_USER" type="text" name="LOGIN_USER" class="validate[required,length[0,100]]] inputbox" alt="{'User name'|t}" size="18" />
</p>
<p id="form-login-password">
<label for="LOGIN_PASSWORD">Mot de passe :</label><br />
<input id="LOGIN_PASSWORD" type="password" name="LOGIN_PASSWORD" class="validate[required,length[0,100]] inputbox" size="18" alt="{'PASSWORD'|t}" />
</p>
<!--<p id="form-login-remember">
<label for="modlgn_remember">Remember Me</label>
<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
</p>-->
<span class="art-button-wrapper">
<span class="l"> </span>
<span class="r"> </span>
<input type="submit" name="Submit" class="art-button" value="{'Authenticate'|t}" />
</span>
</fieldset>
<ul>
<li><a href="/password">{'Lost password'|t}</a></li>
<li><a href="/inscription">{'Subscribe'|t}</a></li>
</ul>
</form>
{/if}