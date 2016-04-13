{if $aData.ZONE_WEB == 1}
	<div class="sliceNew sliceVerbatimDesk">
		<section class=" showroom clsverbatim row" id="{$aData.ID_HTML}" style="padding-top: 0px;">
			<div class="row of6 verbatim" style="margin-bottom: 0px;">
				{if $aData.ZONE_TITRE}<h3 class="col span6 parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE}</h3>{/if}
				{if $aData.ZONE_TEXTE || $aData.ZONE_TEXTE2}
					<blockquote class="col span3">
						<p>"{$aData.ZONE_TEXTE}"</p>
						<footer>{$aData.ZONE_TEXTE2}</footer>
					</blockquote>
				{/if}
				<figure class="col span3">
					<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{Pelican::$config.MEDIA_HTTP}{$aData.MEDIA_PATH}" width="195" height="195" alt="{$MEDIA_ALT}" style="display: inline-block;" />
					<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$aData.MEDIA_PATH}" width="195" height="195" alt="{$MEDIA_ALT}" /></noscript>
				</figure>
			</div>
		</section>
	</div>
{/if}