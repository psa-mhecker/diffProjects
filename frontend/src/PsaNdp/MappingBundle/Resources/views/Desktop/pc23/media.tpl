{if $media.type == 'image'}
    <a href="{$media.size.original}"
       data-gtm='{ldelim}"eventType":"click","dataList":{ldelim}"event":"uaevent","eventCategory":"{$slicePC23.blockName}::position-{$slicePC23.blockOrder}","eventAction":"Select::Zoom","eventLabel":"Icon::Zoom::{$media.title|escape:javascript}"{rdelim}{rdelim}'
    >
        {include file='../../Components/images.html.smarty' image=$media currentIndex=$popinIndex}
    </a>
    {assign var=$popinIndex value=$popinIndex++}
{else}
    <div class="video-container" data-gtm='{ldelim}"eventType":"click","dataList":{ldelim}"event":"uaevent","eventCategory":"{$slicePC23.blockName}::position-{$slicePC23.blockOrder}","eventAction":"Play::Video","eventLabel":"{$media.title|escape:javascript}"{rdelim}{rdelim}'>
        <button class="play-video-from-wall" data-index="{$popinIndex}" ></button>
        <a
            type="video/webm"
            data-index="{$popinIndex}"
            data-poster="{$media.poster}"
            data-sources='[
            {ldelim}"href": "http://cdn.streamlike.com/html5/idevicev2/media_id/{$media.media_id}/width/{$media.width}/height/{$media.height}", "type": "application/x-mpegURL"{rdelim},
            {ldelim}"href": "http://cdn.streamlike.com/html5/idevicev1/media_id/{$media.media_id}/width/{$media.width}/height/{$media.height}", "type": "application/x-mpegURL"{rdelim},
            {ldelim}"href": "http://cdn.streamlike.com/html5/webm/media_id/{$media.media_id}/width/{$media.width}/height/{$media.height}", "type": "video/webm"{rdelim},
            {ldelim}"href": "http://cdn.streamlike.com/html5/mp4low/media_id/{$media.media_id}/width/{$media.width}/height/{$media.height}", "type": "video/mp4"{rdelim}
            ]'
        >
            <picture class="lazy-load" data-index="{$popinIndex}">
                <source
                        media="(min-width: 40.001em)"
                        data-srcset="{$media.poster} 1x">
                <source
                        media="(max-width: 40em)"
                        data-srcset="{$media.poster} 1x">
                <img
                        src="{$media.blank}"
                        data-src="{$media.poster}"
                        alt="{$media.title}">
            </picture>
        </a>
    </div>
    {assign var=$popinIndex value=$popinIndex++}
{/if}
