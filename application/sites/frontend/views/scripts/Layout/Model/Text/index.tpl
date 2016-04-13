{if $data.ZONE_TITRE}
	<div>{$data.ZONE_TITRE}</div>
{/if}
{if $data.ZONE_TEXTE != $pelican_config.CNT_EMPTY && $data.ZONE_TEXTE}
	{$data.ZONE_TEXTE|replace:"#MEDIA_HTTP#":$pelican_config.MEDIA_HTTP}
{/if}