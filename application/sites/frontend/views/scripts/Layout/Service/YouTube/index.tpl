<link rel="stylesheet" href="/css/youtube.css">
<script>
{literal}
function showVideo(id) {
{/literal}
		jQuery('#video{$id}').html(
				'<center><iframe id="iframeVideo{$id}" width="100%" height="400" src="http://www.youtube.com/embed/'+id+'" frameborder="0" allowfullscreen></center>');
		jQuery('#link{$id}').focus();
		{literal}
}
{/literal}
</script>
<input id="link{$id}" style="display: none;" />
<div id="video{$id}">
{if $data.ZONE_TITRE}
<center><iframe id="iframeVideo{$id}" width="100%" height="400" src="http://www.youtube.com/embed/{$data.ZONE_TITRE}" frameborder="0" allowfullscreen></iframe></center>
{/if}
</div>
{if $video}
<ul class="v_list ">
	{section name=index loop=$video}
	<li class="blogger-video">
	<div class="video yt-tile-visible"><span class="video-details"> <span
		class="video-time">{$video[index].time}</span> <span
		class="title video-title" title="{$video[index].title}">{$video[index].title}</span>
<span class="video-thumb"><a
		href="javascript:showVideo('{$video[index].id}')" class=""
		data-sessionlink="ei=COLtlLnom7ICFR-ZIQod1iMCpw%3D%3D&amp;feature=plcp">
	<img src="{$video[index].thumbnail}" alt="Miniature" width="288"></a></span>
		<span class="yt-user-name video-owner" dir="ltr">CitroenFrance</span> <span
		class="video-view-count">{$video[index].viewCount}&nbsp;vues </span> <span
		class="video-item-description">{$video[index].description}</span> </span>
	</div>
	</li>
	{/section}
</ul>
{/if}