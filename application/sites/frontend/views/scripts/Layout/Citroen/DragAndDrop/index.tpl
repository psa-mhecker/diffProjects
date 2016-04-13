<div id="{$aParams.ID_HTML}" class="sliceNew sliceDragAndDropDesk">
	{literal}
	<style>
		{/literal}
		{if ($aData.SECOND_COLOR|count_characters)==7}
		{literal}

		.sliceDragAndDropDesk .clsdraganddrop .dragnchange .drag:before{
		{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
			background-color:{/literal}{$aData.SECOND_COLOR};{literal}
		{/literal}{/if}{literal}
		}
		{/literal}
		{/if}
		{literal}
	</style>
	{/literal}
	<section id="_150_1" class=" row of3 clsdraganddrop">
		<div class="sep "></div>
		<div class="caption dragnchange" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-handler="background-color:{$aData.SECOND_COLOR}!important;" {/if}{gtmjs type='dragnchange'  action='HorizontalScroll'  data=$aParams  datasup=['eventLabel'=>$aParams.ZONE_TITRE|cat:':'|cat:$aParams.ZONE_TITRE2|cat:'|'|cat:$aParams.ZONE_TITRE3|cat:':'|cat:$aParams.ZONE_TITRE4]}>
			<figure>
				<div class="zone">
					<img class="lazy" src="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" width="1280" height="545" alt="{$aParams.MEDIA_ALT|escape}" />
					<noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" width="1280" height="545" alt="{$aParams.MEDIA_ALT|escape}" /></noscript>
					<figcaption>
						{if $aParams.ZONE_TITRE3}<h5 class="parttitle" style="color:{$aData.SECOND_COLOR};">{$aParams.ZONE_TITRE|escape}</h5>{/if}
						{if $aParams.ZONE_TITRE4}<p style="color:{$aData.SECOND_COLOR};">{$aParams.ZONE_TITRE2|escape}</p>{/if}
					</figcaption>
				</div>
			</figure>
			<figure>
				<div class="zone">
					<img class="lazy" src="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" width="1280" height="545" alt="{$aParams.MEDIA_ALT|escape}" />
					<noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_DRAG_DROP}" width="1280" height="545" alt="{$aParams.MEDIA_ALT|escape}" /></noscript>
					{if $aParams.ZONE_TITRE3 || $aParams.ZONE_TITRE4}
						<figcaption>
							{if $aParams.ZONE_TITRE3}<h5 class="parttitle" style="color:{$aData.SECOND_COLOR};">{$aParams.ZONE_TITRE3|escape}</h5>{/if}
							{if $aParams.ZONE_TITRE4}<p style="color:{$aData.SECOND_COLOR};">{$aParams.ZONE_TITRE4|escape}</p>{/if}
						</figcaption>
					{/if}

				</div>
			</figure>
		</div>
	</section>
</div>