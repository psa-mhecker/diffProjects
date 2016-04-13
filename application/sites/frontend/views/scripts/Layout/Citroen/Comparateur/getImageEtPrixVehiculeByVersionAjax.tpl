<figure>
	<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$urlImage}" width="239" height="134" alt=""  data-backup="{$imageCarDefaut}"/>
	<noscript><img src="{$urlImage}" width="239" height="134" alt="" /></noscript>
</figure>
<div class="prices">
	{'A_PARTIR_DE'|t} <em><strong>{$prixComptant}</strong> {$priceType|t}</em>{if $mLComptant neq ''}<a class="tooltip pop" href="#mLComptant{$idBloc}" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['eventLabel'=>{'A_PARTIR_DE'|t}]}>?</a>{/if}<br>
	{if $hasCreditPrice == true && $prixCredit neq ''}{'OU_A_PARTIR_DE'|t} <em><strong>{$prixCredit}</strong> {$priceType|t}</em>{if $mLCredit neq ''}<a class="tooltip pop" href="#mLCredit{$idBloc}" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='click'   data=$aParams  datasup=['eventLabel'=>{'A_PARTIR_DE'|t}]}>?</a>{/if}<br>{/if}
</div>
<script type="text/template" id="mLComptant{$idBloc}">
	<div class="legal layerpop">
		{$mLComptant}
	</div>
</script>
<script type="text/template" id="mLCredit{$idBloc}">
	<div class="legal layerpop">
		{$mLCredit}
	</div>
</script>