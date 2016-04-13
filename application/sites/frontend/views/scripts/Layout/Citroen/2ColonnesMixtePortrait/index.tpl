{if $aData.ZONE_WEB == 1}
	{literal}
		<style>
	{/literal}
	{if ($aData.SECOND_COLOR|count_characters)==7}
	{literal}
	.slice2columnsMixedPortraitDesk .actions .buttonTransversalInvert, .slice2columnsMixedPortraitDesk .buttonTransversalInvert{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aData.SECOND_COLOR};{literal}
		border-color:{/literal}{$aData.SECOND_COLOR};{literal}
		color:#ffffff;
	{/literal}{/if}{literal}
	}
	.slice2columnsMixedPortraitDesk .actions .buttonTransversalInvert:hover, .slice2columnsMixedPortraitDesk .actions .buttonTransversalInvert:active, .slice2columnsMixedPortraitDesk .buttonTransversalInvert:hover, .slice2columnsMixedPortraitDesk .buttonTransversalInvert:active:hover{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:#ffffff;
		border-color:{/literal}{$aData.SECOND_COLOR};{literal}
		color:{/literal}{$aData.SECOND_COLOR}; {literal}
	{/literal}{/if}{literal}
	}
	{/literal}
	{/if}
	{literal}
		</style>
	{/literal}
	<div class="sliceNew slice2columnsMixedPortraitDesk">
		<section id="{$aData.ID_HTML}" class="row section">
			<div class="portrait-bloc gutter row">
				{if $aData.ZONE_TITRE4==1}
					<div class="columns column_50">
						<figure class="visual">
							<img alt="{$MEDIA_ALT}" class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$MEDIA_PATH}">
						</figure>
					</div>
				{/if}
				<div class="columns column_50">
					<h2 class="subtitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$aData.ZONE_TITRE3}</h2>
					<div class="no-mgchapo"></div>
					<div class="zonetexte">
						{$aData.ZONE_TEXTE}
					</div>
					{if $aCta|@sizeof > 0}
						<ul class="actions">
							{section name=cta loop=$aCta}
								{if $aCta[cta].OUTIL}
									{$aCta[cta].OUTIL}
								{/if}
							{/section}
						</ul>
						<ul class="actions">
							{section name=cta loop=$aCta}
								{if !$aCta[cta].OUTIL}
									<li class="cta">
										<a {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aCta[cta].PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}" {*if ($aData.SECOND_COLOR|count_characters)==7 } style="background-color:{$aData.SECOND_COLOR};border-color:{$aData.SECOND_COLOR};color:{$aData.PRIMARY_COLOR};"  {/if*} class="buttonTransversalInvert ">
											{$aCta[cta].PAGE_ZONE_MULTI_LABEL}
										</a>
									</li>
								{/if}
							{/section}
						</ul>
					{/if}
				</div>
				{if $aData.ZONE_TITRE4!=1}
					<div class="columns column_50">
						<figure class="visual">
							<img alt="{$MEDIA_ALT}" class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$MEDIA_PATH}">
						</figure>
					</div>
				{/if}
			</div>
		</section>
	</div>
{/if}
