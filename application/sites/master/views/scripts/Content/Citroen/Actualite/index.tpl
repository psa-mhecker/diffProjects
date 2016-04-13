
{if $aContenu.MEDIA_PATH}
<img style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 100%; " src="{$pelican_config.MEDIA_HTTP}{$aContenu.MEDIA_PATH}" />
{/if}
                           
<br />                    
<br />                    
                        
{if $aContenu.CONTENT_TEXT!=$pelican_config.CNT_EMPTY && $aContenu.CONTENT_TEXT}
<span>{$aContenu.CONTENT_TEXT|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'}</span>
{/if}

<br />                    
<br />                    

{if $aContenu.CONTENT_LONGITUDE && $aContenu.CONTENT_LATITUDE}
{if $map == 'image'}
		<img src="http://maps.google.com/maps/api/staticmap?center={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&markers={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&zoom=15&size=600x400&sensor=false" alt="Map Statique" />
{else}
		<center><div id="map_{$id}" style="position:relative; width: 500px; height: 300px"></div></center>
		<script>
		MyMapInitialize("map_{$id}", {$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE}, 6, MYMODE_MAP);
		MyMapAddMarker({$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE}, MYMARKER_TYPE1, '<p>{$aContenu.CONTENT_TEXT2|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'}</p>');
		MyMapSetZoom(15);
   		MyMapGoto({$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE});
		</script>
{/if}
{/if}