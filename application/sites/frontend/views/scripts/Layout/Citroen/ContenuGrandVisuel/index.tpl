<br/>
<div class="sliceNew sliceBigVisuDesktop">
{if $aParams.ZONE_WEB && $aParams.MEDIA_PATH neq ''}


	{if $show_both_title_visuel}
		<div id="{$aParams.ID_HTML}" class="{$aParams.ZONE_SKIN} banner full invert clsgrandvisuel">
		{if !$bSticky}
			{$sSharer}  
			<div class="texts">			
			<h1 class="title strike"><span class="lin"><span {if ($aParams.PRIMARY_COLOR|count_characters)==7 } style="color:{$aParams.PRIMARY_COLOR};" {/if}>{$aParams.PAGE_TITLE|upper}</span></span></h1>
			</div>
		{/if}
		{if $aParams.MEDIA_PATH}

				<figure>
					<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/banner.png" data-original="{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}" width="1440" height="300" alt="{$aParams.PAGE_TITLE}" />
					<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}" width="1440" height="300" alt="{$aParams.PAGE_TITLE}" /></noscript>
				</figure>

		{/if}
</div>		
	{else}
	<div id="{$aParams.ID_HTML}" class="{$aParams.ZONE_SKIN} banner full invert clsgrandvisuel">
		{if !$bSticky}
			{$sSharer}
			{if !$aParams.MEDIA_PATH}<div class="texts"><h1 class="title strike"><span class="line"><span>{$aParams.PAGE_TITLE|upper}</span></span></h1></div>{/if}
		{/if}
		{if $aParams.MEDIA_PATH}


				{if $aParams.PAGE_TITLE}
				<div class="texts">
					<h1 class="title">{$aParams.PAGE_TITLE}</h1>
				</div>
				{/if}

				<figure>
					<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/banner.png" data-original="{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}" width="1440" height="300" alt="{$aParams.PAGE_TITLE}" />
					<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}" width="1440" height="300" alt="{$aParams.PAGE_TITLE}" /></noscript>
				</figure>

		{/if}
	</div>
	{/if}
{else}
	<h1 class="subtitle" {if ($aParams.PRIMARY_COLOR|count_characters)==7 } style="color:{$aParams.PRIMARY_COLOR};" {/if}>{$aParams.PAGE_TITLE}</h1>
{/if}
</div>