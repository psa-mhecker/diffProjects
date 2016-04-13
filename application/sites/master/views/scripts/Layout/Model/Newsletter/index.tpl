{literal}
<script type="text/javascript">
	$(document).ready(function() {
		$("#form-newsletter").validationEngine({
			success :  false,
			failure : function() {}
		})
	})
</script>
{/literal}
<div>
<form method="post" id="form-newsletter" action="#" onsubmit="callAjax('newsletter', $('NEWSLETTER_EMAIL').value);return false;">
	<input type="text" value="" name="NEWSLETTER_EMAIL" id="NEWSLETTER_EMAIL" class="validate[required,custom[email]] inputbox" />
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input type="submit" name="Subscribe" class="art-button" value="{'Subscribe'|t}" />
	</span>
</form>
</div>