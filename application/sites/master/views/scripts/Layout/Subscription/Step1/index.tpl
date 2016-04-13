{if $messageConfirmation}
<div class="inscription" style="margin: 10px; padding: 10px;">
<div class="bloc">{$messageConfirmation}</div>
</div>
{else}
{literal}
<script type="text/javascript">
	$(document).ready(function() {
	       $("#form-subscription").validationEngine({
	        success :  false,
		    failure : function() {}
	       })
	      })
	</script>
{/literal}

<div class="art-inscription" style="margin: 10px; padding: 10px;">

<form name="form-subscription" id="form-subscription" method="post" action="">
{if $msgSendInscriptionConfirmation==1}
<div class="bloc">{'PEL.INSCRIPTION.MAIL_CONFIRMATION_ENVOI'|t}</div>
{elseif $msgSendInscriptionConfirmation==2}
<div class="bloc">{'PEL.INSCRIPTION.MAIL_CONFIRMATION_ENVOI.PAS_MAIL_ASSOCIE'|t}</div>
{elseif $msgSendInscriptionConfirmation==3}
<div class="bloc">{'PEL.INSCRIPTION.MAIL_CONFIRMATION_ENVOI.PROBLEME_MAIL'|t}</div>
{/if}

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.NOM'|t}* :<br /></label>
	<input id="lastname" type="text" name="lastname" class="validate[required,length[2,50]] inputbox" maxlength="50" size="50" value="{$post_Lastname}" />
</p>

<p id="form-subscription-firstname">
	<label for="lastname">{'PEL.INSCRIPTION.PRENOM'|t}* :<br /></label>
	<input id="firstname" type="text" name="firstname" class="validate[required,length[2,50]] inputbox" maxlength="50" size="50" value="{$post_Firstname}" />
</p>

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.NICKNAME'|t}* :<br /></label>
	<input id="nickname" type="text" name="nickname" class="validate[required,length[2,50]] inputbox" maxlength="50" size="50" value="{$post_Nickname}" />
</p>

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.EMAIL'|t}* :<br /></label>
	<input id="email" type="text" name="email" class="validate[required,custom[email]] inputbox" maxlength="50" size="50" value="{$post_Email}" />
</p>

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.EMAIL_VERIFICATION'|t}* :<br /></label>
	<input id="emailConfirm" type="text" name="emailConfirm" class="validate[required,custom[email],confirm['email']] inputbox" maxlength="50" size="50" value="{$post_EmailConfirm}" />
</p>

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.MDP'|t}* :<br /></label>
	<input id="password" type="password" name="password" class="validate[required,length[6,12]] inputbox" maxlength="50" size="50" value="" />
</p>

<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.MDP_VERIFICATION'|t}* :<br /></label>
	<input id="mdpConfirm" type="password" name="mdpConfirm" class="validate[required,length[6,12],confirm['password']] inputbox" maxlength="50" size="50" value="" />
</p>


   



 
 
			<p data-role="fieldcontain"> 
	         <label for="name">Texte :<br /></label> 
	         <input type="text" name="name" id="name" value=""  /> 
			</p> 
 
			<p data-role="fieldcontain"> 
			<label for="textarea">Textarea :<br /></label> 
			<textarea cols="40" rows="8" name="textarea" id="textarea"></textarea> 
			</p> 
 
			<p data-role="fieldcontain"> 
	         <label for="search">Recherche :<br /></label> 
	         <input type="search" name="password" id="search" value=""  /> 
			</p> 
 
			<p data-role="fieldcontain"> 
				<label for="slider2">Commutateur :<br /></label> 
				<select name="slider2" id="slider2" data-role="slider"> 
					<option value="off">Off</option> 
					<option value="on">On</option> 
				</select> 
			</p> 
 
			<p data-role="fieldcontain"> 
				<label for="slider">Curseur :<br /></label> 
			 	<input type="range" name="slider" id="slider" value="0" min="0" max="100"  /> 
			</p> 
 
			<p data-role="fieldcontain"> 
			<fieldset data-role="controlgroup"> 
				<legend>Case a cocher :</legend> 
				<input type="checkbox" name="checkbox-1a" id="checkbox-1a" class="custom" /> 
				<label for="checkbox-1a">Cheetos</label> 
 
				<input type="checkbox" name="checkbox-2a" id="checkbox-2a" class="custom" /> 
				<label for="checkbox-2a">Doritos</label> 
 
				<input type="checkbox" name="checkbox-3a" id="checkbox-3a" class="custom" /> 
				<label for="checkbox-3a">Fritos</label> 
 
				<input type="checkbox" name="checkbox-4a" id="checkbox-4a" class="custom" /> 
				<label for="checkbox-4a">Sun Chips</label> 
		    </fieldset> 
			</p> 
 
			<p data-role="fieldcontain"> 
			<fieldset data-role="controlgroup" data-type="horizontal"> 
		    	<legend>Style :</legend> 
		    	<input type="checkbox" name="checkbox-6" id="checkbox-6" class="custom" /> 
				<label for="checkbox-6">g</label> 
 
				<input type="checkbox" name="checkbox-7" id="checkbox-7" class="custom" /> 
				<label for="checkbox-7"><em>i</em></label> 
 
				<input type="checkbox" name="checkbox-8" id="checkbox-8" class="custom" /> 
				<label for="checkbox-8">s</label> 
		    </fieldset> 
			</p> 
 
			<p data-role="fieldcontain"> 
			    <fieldset data-role="controlgroup"> 
			    	<legend>Bouton radio :<br /></legend> 
			         	<input type="radio" name="radio-choice-1" id="radio-choice-1" value="choice-1" checked="checked" /> 
			         	<label for="radio-choice-1">Cat</label> 
 
			         	<input type="radio" name="radio-choice-1" id="radio-choice-2" value="choice-2"  /> 
			         	<label for="radio-choice-2">Dog</label> 
 
			         	<input type="radio" name="radio-choice-1" id="radio-choice-3" value="choice-3"  /> 
			         	<label for="radio-choice-3">Hampster</label> 
 
			         	<input type="radio" name="radio-choice-1" id="radio-choice-4" value="choice-4"  /> 
			         	<label for="radio-choice-4">Lizard</label> 
			    </fieldset> 
			</p> 
 
			<p data-role="fieldcontain"> 
			    <fieldset data-role="controlgroup" data-type="horizontal"> 
			     	<legend>Tags :<br /></legend> 
			         	<input type="radio" name="radio-choice-b" id="radio-choice-c" value="on" checked="checked" /> 
			         	<label for="radio-choice-c">List</label> 
			         	<input type="radio" name="radio-choice-b" id="radio-choice-d" value="off" /> 
			         	<label for="radio-choice-d">Grid</label> 
			         	<input type="radio" name="radio-choice-b" id="radio-choice-e" value="other" /> 
			         	<label for="radio-choice-e">Gallery</label> 
			    </fieldset> 
			</p> 
 
			<p data-role="fieldcontain"> 
				<label for="select-choice-1" class="select">Mode de livraison :<br /></label> 
				<select name="select-choice-1" id="select-choice-1"> 
					<option value="standard">Standard : 7 jours</option> 
					<option value="rush">Rapide : 3 jours</option> 
					<option value="express">Express : lendemain</option> 
					<option value="overnight">Nocturne</option> 
				</select> 
			</p> 
 
			<p data-role="fieldcontain"> 
				<label for="select-choice-3" class="select">Votre r&eacute;gion :<br /></label>
				<select name="select-choice-3" id="select-choice-3"> 
					<option>Custom menu...</option> 
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA">California</option> 
					<option value="CO">Colorado</option> 
					<option value="CT">Connecticut</option> 
					<option value="DE">Delaware</option> 
					<option value="FL">Florida</option> 
					<option value="GA">Georgia</option> 
					<option value="HI">Hawaii</option> 
					<option value="ID">Idaho</option> 
					<option value="IL">Illinois</option> 
					<option value="IN">Indiana</option> 
					<option value="IA">Iowa</option> 
					<option value="KS">Kansas</option> 
					<option value="KY">Kentucky</option> 
					<option value="LA">Louisiana</option> 
					<option value="ME">Maine</option> 
					<option value="MD">Maryland</option> 
					<option value="MA">Massachusetts</option> 
					<option value="MI">Michigan</option> 
					<option value="MN">Minnesota</option> 
					<option value="MS">Mississippi</option> 
					<option value="MO">Missouri</option> 
					<option value="MT">Montana</option> 
					<option value="NE">Nebraska</option> 
					<option value="NV">Nevada</option> 
					<option value="NH">New Hampshire</option> 
					<option value="NJ">New Jersey</option> 
					<option value="NM">New Mexico</option> 
					<option value="NY">New York</option> 
					<option value="NC">North Carolina</option> 
					<option value="ND">North Dakota</option> 
					<option value="OH">Ohio</option> 
					<option value="OK">Oklahoma</option> 
					<option value="OR">Oregon</option> 
					<option value="PA">Pennsylvania</option> 
					<option value="RI">Rhode Island</option> 
					<option value="SC">South Carolina</option> 
					<option value="SD">South Dakota</option> 
					<option value="TN">Tennessee</option> 
					<option value="TX">Texas</option> 
					<option value="UT">Utah</option> 
					<option value="VT">Vermont</option> 
					<option value="VA">Virginia</option> 
					<option value="WA">Washington</option> 
					<option value="WV">West Virginia</option> 
					<option value="WI">Wisconsin</option> 
					<option value="WY">Wyoming</option> 
				</select> 
			</p> 
 
				<p data-role="fieldcontain"> 
					<label for="select-choice-native" class="select">Votre &eacute;tat :</label> <br />
					<select name="select-choice-native" id="select-choice-native" data-native-menu="true"> 
						<option>Native menu...</option> 
						<option value="AL">Alabama</option> 
						<option value="AK">Alaska</option> 
						<option value="AZ">Arizona</option> 
						<option value="AR">Arkansas</option> 
						<option value="CA">California</option> 
						<option value="CO">Colorado</option> 
						<option value="CT">Connecticut</option> 
						<option value="DE">Delaware</option> 
						<option value="FL">Florida</option> 
						<option value="GA">Georgia</option> 
						<option value="HI">Hawaii</option> 
						<option value="ID">Idaho</option> 
						<option value="IL">Illinois</option> 
						<option value="IN">Indiana</option> 
						<option value="IA">Iowa</option> 
						<option value="KS">Kansas</option> 
						<option value="KY">Kentucky</option> 
						<option value="LA">Louisiana</option> 
						<option value="ME">Maine</option> 
						<option value="MD">Maryland</option> 
						<option value="MA">Massachusetts</option> 
						<option value="MI">Michigan</option> 
						<option value="MN">Minnesota</option> 
						<option value="MS">Mississippi</option> 
						<option value="MO">Missouri</option> 
						<option value="MT">Montana</option> 
						<option value="NE">Nebraska</option> 
						<option value="NV">Nevada</option> 
						<option value="NH">New Hampshire</option> 
						<option value="NJ">New Jersey</option> 
						<option value="NM">New Mexico</option> 
						<option value="NY">New York</option> 
						<option value="NC">North Carolina</option> 
						<option value="ND">North Dakota</option> 
						<option value="OH">Ohio</option> 
						<option value="OK">Oklahoma</option> 
						<option value="OR">Oregon</option> 
						<option value="PA">Pennsylvania</option> 
						<option value="RI">Rhode Island</option> 
						<option value="SC">South Carolina</option> 
						<option value="SD">South Dakota</option> 
						<option value="TN">Tennessee</option> 
						<option value="TX">Texas</option> 
						<option value="UT">Utah</option> 
						<option value="VT">Vermont</option> 
						<option value="VA">Virginia</option> 
						<option value="WA">Washington</option> 
						<option value="WV">West Virginia</option> 
						<option value="WI">Wisconsin</option> 
						<option value="WY">Wyoming</option> 
					</select> 
				</p> 
 <p>
  <label for="date">Date :<br /></label>
<input type="date" name="date" id="date" value=""  />	
</p>








<p id="form-subscription-lastname">
	<label for="lastname">{'PEL.INSCRIPTION.CATPCHA_SECURITE'|t}* :<br /></label>
	{$captcha}
</p>


<input type="hidden" id="save_etp1" name="save_etp1" value="1">

<span class="art-button-wrapper">
	<span class="l"> </span>
	<span class="r"> </span>
	<input type="submit" id="submit" name="submit" class="art-button" value="{'POPUP_BUTTON_SAVE'|t}" />
</span>

</form>

				</div> 
 



 
	
	{/if}