{literal}
<style>
.sneezies .vjs-default-skin .vjs-big-play-button{
	 color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
}

.sliceMurMediaDesk .popit.photo.activeRoll:hover:after{
background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
}

.sliceMurMediaDesk .popThis.video .pictoVideoPlay, .sliceMurMediaDesk .popit.video .pictoVideoPlay{
color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
}
</style>
{/literal}
{if $aData.ZONE_WEB == 1}
<div class="sliceNew sliceMurMediaDesk">
    <section id="{$aData.ID_HTML}" class="showroom of3 gallery clsmurmedia">
        <div class="sep {$aData.ZONE_SKIN}"></div>
        
        
 {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
 {if $aData.ZONE_TITRE2}<h3 class="parttitle"  {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}


        {if $iNbMulti > 10}
            {assign var='iMaxBoucleMore' value=$iNbMulti - 10}
            {assign var='iMaxBoucleGene' value=$iNbMulti - $iMaxBoucleMore}
        {else}
            {assign var='iMaxBoucleGene' value=$iNbMulti}
        {/if}

        
        {if $aData.ZONE_TITRE3}
            {assign var='noMediaSharer' value=''}
        {else}
            {assign var='noMediaSharer' value='noMediaSharer'}
        {/if}

        <div class="row of6">
            
            {section name=i loop=$aMulti max=$iMaxBoucleGene}

                {if $aMulti[i].PAGE_ZONE_MULTI_VALUE == 1}<!-- Visuel CinemaScope -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="caption">
                        <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                            <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                            {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                            {else}
                                {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                            {/if}
                            >
                               <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode|urlencode}" data-original="{$aMulti[i].MEDIA_ID}" width="1200" height="514" alt="{$aMulti[i].MEDIA_ALT}" />
                                <noscript><img src="{$aMulti[i].MEDIA_ID}" width="1200" height="514" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
								{if $aMulti[i].TYPE_MEDIA_ID ==2}<div class="pictoVideoPlay"></div>{/if}
                            </a>
							 
                        </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 2}<!-- 2 Visuel 16/9 -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="new col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank" {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {/if}>
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID}" width="589" height="329" alt="{$aMulti[i].MEDIA_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID}" width="589" height="329" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
										 {if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
									
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}
                                    >
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
										{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 3}<!-- 1 visuel portrait + 2 visuels 16/9 empilése -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="row of2">
                        <div class="new col span3">
                                <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID}" width="589" height="681" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="589" height="681" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
                                        </a>
										{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                </figure>
                        </div>
                        <!-- /.col -->

                        <div class="col span3">
                                {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                         {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
                                        </a>
										{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                </figure>

                                {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                         {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}>
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID5}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
                                        </a>
										{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                </figure>
                        </div>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 4}<!-- 2 visuels 16/9 empilése + 1 visuel portrait -->
                    <div class="row of2">
                        <div class="new col span3">

                                {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                         {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="328" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="328" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
										
                                </figure>

                                {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                         {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                        >
                                                <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="328" alt="{$aMulti[i].MEDIA3_ALT}" />
                                                <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="328" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
												{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>

                        </div>

                        <div class="col span3">
                            {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                            {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                                {assign var='ClassMedia' value='video'}
                            {/if}
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                     {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}
                                    >
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID5}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
										{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                        </div>
                    </div>
                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 5}<!-- 2 visuels formats carrés -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="590" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="590" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                     {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}>
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="590" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="590" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>
                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 6}<!-- 2 Visuel Portrait -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"  {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="676" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="676" alt="{$aMulti[i].MEDIA_ALT}" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aData.PAGE_CLEAR_URL}?mur_media_photo_title={$aMulti[i].MEDIA_TITLE}&mur_media_photo={$aMulti[i].MEDIA_ID|urlencode}/></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span3">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aData.PAGE_CLEAR_URL}?mur_media_photo_title={$aMulti[i].MEDIA3_TITLE}&mur_media_photo={$aMulti[i].MEDIA_ID3|urlencode}/></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 7}<!-- 3 Visuels Carrés -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span2">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID}" width="387" height="387" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="387" height="387" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span2">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}

                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID3}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span2">
                            <figure class="shareable" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-dgloupe="background-color:{$aData.SECOND_COLOR};" data-hover="border-width:15px; border-color:{$aData.SECOND_COLOR};" {/if}>
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID5}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>
                {/if}
            {/section}

        </div>

        {if $iNbMulti > 10}
            <div id="moreMedias" class="caption row of6">

            {section name=i loop=$aMulti start=$iMaxBoucleGene max=$iMaxBoucleMore}
                {if $aMulti[i].PAGE_ZONE_MULTI_VALUE == 1}<!-- Visuel CinemaScope -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="caption">
                        <figure class="shareable">
                            <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                            {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                            {else}
                                {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                            {/if}
                            >
                                <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$aMulti[i].MEDIA_ID}" width="1200" height="514" alt="{$aMulti[i].MEDIA_ALT}" />
                                <noscript><img src="{$aMulti[i].MEDIA_ID}" width="1200" height="514" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
								{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                            </a>
                        </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 2}<!-- 2 Visuel 16/9 -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="new col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank" {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                    {/if}
                                    >
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID}" width="589" height="329" alt="{$aMulti[i].MEDIA_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID}" width="589" height="329" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
										{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                        {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {else}
                                        {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                    {/if}
                                    >
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
										{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 3}<!-- 1 visuel portrait + 2 visuels 16/9 empilése -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="row of2">
                        <div class="new col span3">
                                <figure class="shareable">
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID}" width="589" height="681" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="589" height="681" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>
                        </div>
                        <!-- /.col -->

                        <div class="col span3">
                                {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable">
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>

                                {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable">
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}>
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID5}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="589" height="329" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>
                        </div>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 4}<!-- 2 visuels 16/9 empilése + 1 visuel portrait -->
                    <div class="row of2">
                        <div class="new col span3">

                                {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable">
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                        >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="328" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="328" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>

                                {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                    {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                                {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                                    {assign var='ClassMedia' value='video'}
                                {/if}
                                <figure class="shareable">
                                        <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                        >
                                                <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="328" alt="{$aMulti[i].MEDIA3_ALT}" />
                                                <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="328" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
												{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                        </a>
                                </figure>

                        </div>

                        <div class="col span3">
                            {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                            {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                                {assign var='ClassMedia' value='video'}
                            {/if}
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                    >
                                        <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID5}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" />
                                        <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
										{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                        </div>
                    </div>
                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 5}<!-- 2 visuels formats carrés -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                        {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="590" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="590" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="590" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="590" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>
                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 6}<!-- 2 Visuel Portrait -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID}" width="590" height="676" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="590" height="676" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span3">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{$aMulti[i].MEDIA_ID3}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="590" height="676" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                {elseif $aMulti[i].PAGE_ZONE_MULTI_VALUE == 7}<!-- 3 Visuels Carrés -->

                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}

                    <div class="new col span2">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID == 2}data-video="{$aMulti[i].MEDIA_ID_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID}" width="387" height="387" alt="{$aMulti[i].MEDIA_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID}" width="387" height="387" alt="{$aMulti[i].MEDIA_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span2">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID2_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID2_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID2_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID3 == 2}data-video="{$aMulti[i].MEDIA_ID2_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID3 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID3|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID3}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID3}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID3 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>

                    {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                        {assign var='ClassMedia' value='photo '|cat:$noMediaSharer}
                    {elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}
                        {assign var='ClassMedia' value='video'}
                    {/if}
                    <div class="col span2">
                            <figure class="shareable">
                                    <a class="popit {$ClassMedia}" data-sneezy="gallery{$aData.ORDER}" href="{urlParser url=$aMulti[i].MEDIA_ID3_ZOOM}" {if $aMulti[i].OTHER_MEDIA_ID3_ZOOM}data-video="{$aMulti[i].OTHER_MEDIA_ID3_ZOOM}"{elseif $aMulti[i].TYPE_MEDIA_ID5 == 2}data-video="{$aMulti[i].MEDIA_ID3_ZOOM_FOR_DATA_VIDEO}"{/if} target="_blank"
                                    {if $aMulti[i].TYPE_MEDIA_ID5 == 1}
                                            {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {else}
                                            {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMulti[i].MEDIA3_TITLE}]}
                                        {/if}
                                    >
                                            <img class="lazy" data-mediashare ="{Pelican::$config.DOCUMENT_HTTP|urlencode}{$aData.PAGE_CLEAR_URL|urlencode}%3Fcontent_title%3D{$aMulti[i].MEDIA_TITLE|urlencode}%26content_media%3D{$aMulti[i].MEDIA_ID5|urlencode}|urlencode}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$aMulti[i].MEDIA_ID5}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" />
                                            <noscript><img src="{$aMulti[i].MEDIA_ID5}" width="387" height="387" alt="{$aMulti[i].MEDIA3_ALT}" /></noscript>
											{if $aMulti[i].TYPE_MEDIA_ID5 == 2}<div class="pictoVideoPlay"></div>{/if}
                                    </a>
                            </figure>
                    </div>
                {/if}
            {/section}

            </div>

            <div class="caption addmore folder" data-toggle-open="{t("Voir plus")}" data-toggle-close="{'VOIR_MOINS'|t}"><a href="#moreMedias">{'Voir plus'|t}</a></div>
        {/if}
        
    </section>
	</div>
    <div class="parent" id="trancheParent" style="display: none;"></div>

    {if $aData.ZONE_LANGUETTE == 1}
        <section class="{$aData.ZONE_SKIN} {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
            <div class="caption addmore folder" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.SECOND_COLOR}; color:{$aData.SECOND_COLOR};" data-hover="border:4px solid {$aData.SECOND_COLOR}; color:{$aData.SECOND_COLOR};"{/if} data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_MUR_MEDIA']}><span>{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>   
        </section>
    {/if}   
    

{/if}   