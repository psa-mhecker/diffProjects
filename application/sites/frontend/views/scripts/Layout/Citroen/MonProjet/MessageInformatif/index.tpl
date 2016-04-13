{if $bMessageVisible}
<section id="{$aParams.ID_HTML}" class="row alert {$smarty.session.APP.PRJ_MESSAGE_CLOSE2} clsmessageinformatif">
	<div class="col span12 cont">
		<div class="inner row of6">
			<h2 class="title col span4">{$aParams.ZONE_TITRE}</h2>
			<div class="col span5 zonetexte">{$aParams.ZONE_TEXTE}</div>
		</div>
		<div class="closer"></div>
	</div>
</section>
{/if}