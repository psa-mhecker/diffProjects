{if $aAccessoires|@sizeof > 0}
	<div class="cumulative row of4">
		{foreach from=$aAccessoires.CONTENTS item=accessoire name=listAccessoires}
			{if $accessoire.IMAGE}
				<div class="col">
					<figure>
						<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/accessory.png" data-original="{Pelican::$config.MEDIA_HTTP}{$accessoire.IMAGE}" width="250" height="166" alt="{$accessoire.LABEL}">
						<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$accessoire.IMAGE}" width="250" height="166" alt="{$accessoire.LABEL}"></noscript>
					</figure>
					<div class="cart">
						<strong>
							{if $aConfiguration.ZONE_TITRE15 == 1}
								{$aConfiguration.ZONE_TITRE2}
							{/if}
							{$accessoire.PRIX}
							{if $aConfiguration.ZONE_TITRE15 == 2}
								{$aConfiguration.ZONE_TITRE2}
							{/if}
							*
						</strong><br>
						{$accessoire.LABEL}<br>
						<small class="legal">Ref. {$accessoire.REF}</small><br>
						{if $accessoire.URL_BUY}
							<a class="button" target="_blank" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="border:3px solid {$aData.SECOND_COLOR}"{/if}
							   {gtm action='Accessories' data=$aData datasup=['eventLabel'=>$accessoire.LABEL]} href="{urlParser url=$accessoire.URL_BUY}"
							>
								{'BUY'|t}
							</a>
						{/if}
					</div>
				</div>

				{if $smarty.foreach.listAccessoires.iteration % 4 == 0}
					</div><div class="cumulative row of4">
				{/if}
			{/if}
		{/foreach}
	</div>
{/if}
