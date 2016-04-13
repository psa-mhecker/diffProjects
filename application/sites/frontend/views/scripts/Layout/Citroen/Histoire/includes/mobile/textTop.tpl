<div class="textTop">
    <div class="row">
        <div class="columns column_100 titleBlock">
            <div class="dateTop">
                {$article.DATE}
            </div>
            <h3 class="titleIn">{$article.CONTENT_TITLE}</h3>
        </div>
    </div>
    <div class="row">
        {if $article.MEDIA || $article.MEDIA_VIDEO_PLAYER}
            <div class="columns column_100 picContent">
                {if $article.MEDIA_VIDEO_PLAYER && ($article.MEDIA_TYPE_ID !='youtube')}
                    <figure class="shadow" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>{$article.CONTENT_TITLE}]}>
                        {$article.MEDIA_VIDEO_PLAYER}
                    </figure>
                {elseif $article.MEDIA_TYPE_ID =='youtube'}
                    <a class="galleryPop popThis" data-video="{$article.MEDIA}" href="{urlParser url=$article.MEDIA}" data-bundle="" data-gtm="" data-gtm-init="">
                        <figure class="video">
                            <i class="icon-play"></i>
                            <img class="lazy"  src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$article.MEDIA_PATH}" alt="{$article.CONTENT_TITLE}" style="display: inline-block;">
                        </figure>
                    </a>
                {else}
                    <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$article.MEDIA}" alt="{$article.CONTENT_TITLE}">
                        <noscript><img src="{$article.MEDIA}" width="580" height="247" alt="{$article.CONTENT_TITLE}" /></noscript>
                    </figure>
                {/if}
            </div>
        {/if}
        <div class="columns column_100 textContent">
            <p>
                {$article.CONTENT_TEXT}
            </p>
        </div>
    </div>
</div>
