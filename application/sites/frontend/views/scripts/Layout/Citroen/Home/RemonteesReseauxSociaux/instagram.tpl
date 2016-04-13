<ul>
	{foreach from=$reseauxSociaux[$aParams.instaFeedId].FEED item=feed name=listFeed}
		<li>
			<img src="{$feed.image}" width="45"/>
			{$feed.title}
		</li>
	{/foreach}
</ul>