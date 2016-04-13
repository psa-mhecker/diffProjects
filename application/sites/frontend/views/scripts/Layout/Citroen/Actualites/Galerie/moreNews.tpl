{foreach from=$aActualites item=actualite name=listeActu}
	<div class="row item zoner gutter">
		{if $actualite.MEDIA_ID}
			<figure class="columns column_55 {if $actualite@iteration is even}right{/if}">
				{if $actualite.MEDIA_PATH}
					<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$actualite.MEDIA_PATH}" width="373" height="210" alt="{$actualite.CONTENT_TITLE}" />
					<noscript><img src="{$actualite.MEDIA_PATH}" width="373" height="210" alt="{$actualite.CONTENT_TITLE}" /></noscript>
					<div class="sharebar">
						{$sSharerBar}
					</div>
				{/if}
			</figure>
		{/if}
		<div class="columns {if $actualite.MEDIA_ID}column_45{/if}">
			{if $actualite.DATE_FORMATEE}
				<time class="item-date" datetime="{$actualite.DATE_TIME_HTML}">
					{$actualite.DATE_FORMATEE}
				</time>
			{/if}
			{if $actualite.CONTENT_TITLE}
				<h2 class="item-title">
					{$actualite.CONTENT_TITLE}
				</h2>
			{/if}
			{if $actualite.CONTENT_TEXT2}
				<p>{$actualite.CONTENT_TEXT2|strip_tags|truncate:240}</p>
			{/if}
			<ul class="actions">
				<li>
					<a class="buttonTransversalInvert" href="{urlParser url=$aClearUrls[$actualite.CONTENT_ID]}">
						{'EN_SAVOIR_PLUS'|t}
					</a>
				</li>
			</ul>
		</div>
	</div>
{/foreach}