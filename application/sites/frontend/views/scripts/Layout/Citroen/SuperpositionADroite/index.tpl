{if $aData.ZONE_WEB == 1}
	<div id="{$aData.ID_HTML}" class="sliceNew sliceSuperpositionRightDesk">
		{if $aData.ZONE_TITRE4 == "1"}
			{assign var="classSuperposition" value="superposition-left"}
		{else}
			{assign var="classSuperposition" value="superposition-right"}
		{/if}
		<section id="_150_1" class="clearfix of6 cls2colonnemixte {$classSuperposition}" style="margin-bottom: 0px;">
			<div class="col span3 superposition-box" style="margin-bottom: 0px;">
				<h2 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE3}</h2>
				<div class="no-mgchapo"></div>
				<div class="zonetexte">
					{$aData.ZONE_TEXTE|nl2br}
				</div>
				<div class="col span" style="margin-bottom: 0px;">
					{if $aCta|@sizeof > 0}
						<ul class="actions" style="margin-bottom: 0px;" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
							<!--cta toolbar -->
							{section name=cta loop=$aCta}
								{if $aCta[cta].OUTIL}
									{$aCta[cta].OUTIL}
								{else}
									<li data-services="">
										<a href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}" {gtm action='Redirection' data=$aData datasup=['eventCategory'=>'Showroom:OverlayRight' , 'eventLabel' => $aCta[cta].PAGE_ZONE_MULTI_LABEL]} class="buttonTransversalInvert">
											<span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span>
										</a>
									</li>
								{/if}
							{/section}
						</ul>
					{/if}
				</div>
			</div>
			<figure class="col span3 shadow nomgfigure" style="margin-bottom: 0px;">
				<img style="display: inline-block; margin-bottom: 0px;" alt="{$MEDIA_ALT}" class="" src="{$MEDIA_PATH}" data-original="{$MEDIA_PATH}">
			</figure>
		</section>
	</div>
{/if}