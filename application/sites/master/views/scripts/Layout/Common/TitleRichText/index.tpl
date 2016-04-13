{if $aZone.ZONE_TITRE}
	<div>{$aZone.ZONE_TITRE}</div>
{/if}
{if $aZone.ZONE_TEXTE != $pelican_config.CNT_EMPTY && $aZone.ZONE_TEXTE}
	{$aZone.ZONE_TEXTE|replace:"#MEDIA_HTTP#":$pelican_config.MEDIA_HTTP}
{/if}