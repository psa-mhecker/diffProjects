{if $aData.ZONE_WEB == 1}
{if $OtherPromotions}
<section id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} row of5 clsotherpromo">
	<div class="col span2">
		<div class="elastic">
			<p>{$titre|replace:'#VEHICULE_NAME#':$sVehicleName}</p>
			{foreach from=$OtherPromotions item=promo}
			<p><a href="{urlParser url={$promo.PAGE_CLEAR_URL|cat:"?vid="|cat:$smarty.get.vid}}#{$promo.PAGE_ID}_{$promo.PAGE_ZONE_MULTI_ID}"><em>{$promo.PAGE_ZONE_MULTI_LABEL}</em></a></p>
			{/foreach}
		</div>
	</div>
</section>
{/if}
{/if}