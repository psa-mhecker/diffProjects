{if $aContenu.CONTENT_TEXT!=$pelican_config.CNT_EMPTY && $aContenu.CONTENT_TEXT}
<span>{$aContenu.CONTENT_TEXT|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'}</span>
{/if}