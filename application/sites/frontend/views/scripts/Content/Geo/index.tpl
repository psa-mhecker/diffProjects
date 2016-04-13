{if $aContenu.CONTENT_TEXT!=$pelican_config.CNT_EMPTY && $aContenu.CONTENT_TEXT}
<span>{$aContenu.CONTENT_TEXT|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'}</span>
{/if}
{if $map == 'image'}
		<img src="http://maps.google.com/maps/api/staticmap?center={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&markers={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&zoom=15&size=600x400&sensor=false" alt="Map Statique" />
{else}
<!--  <img src="http://maps.google.com/maps/api/staticmap?center={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&markers={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&zoom=15&size=600x400&sensor=false" alt="Map Statique" />
<img src='http://m.ovi.me/?poi={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE}&
                 h=300&w=600&z=15&nord' />
<img src='http://dev.virtualearth.net/REST/v1/Imagery/Map/Road/{$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_longitudeE}/15?pp={$aContenu.CONTENT_LATITUDE},{$aContenu.CONTENT_LONGITUDE};;1&dcl=1&key={$map_key}' />-->
		<div id="map_{$id}" style="position:relative; width: 600px; height: 400px"></div>
		<script>
		var global_appid_code = '{$map_appid}';
		var global_key = '{$map_key}';
		MyMapInitialize("map_{$id}", {$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE}, 6, MYMODE_MAP);
		MyMapAddMarker({$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE}, MYMARKER_TYPE1, '<p>{$aContenu.CONTENT_TEXT2|replace:'#MEDIA_HTTP#':$pelican_config.MEDIA_HTTP|replace:'<!-- pagebreak -->':'</span><span>'|replace:"'":"\'"|replace:"\r\n":""}</p>');
		MyMapSetZoom(15);
   		MyMapGoto({$aContenu.CONTENT_LATITUDE}, {$aContenu.CONTENT_LONGITUDE});
		</script>
{/if}

