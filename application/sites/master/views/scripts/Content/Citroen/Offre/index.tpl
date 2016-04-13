
{if $aContenu.MEDIA_PATH}
<img style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 100%; " src="{$pelican_config.MEDIA_HTTP}{$aContenu.MEDIA_PATH}" />
{/if}                           
                        <br />
                        <br />
                        
{if $aContenu.CONTENT_TEXT!=$pelican_config.CNT_EMPTY && $aContenu.CONTENT_TEXT}
<span>{$aContenu.CONTENT_TEXT|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'}</span>
{/if}