<div class="textLeft {if $negativeTop}negativeTop{/if}">
    <div class="row">
        <div class="columns column_{100-$column} titleBlock">
            <div class="dateTop" {if $aParams['PRIMARY_COLOR']} style='color:{$aParams['PRIMARY_COLOR']}}'{/if}>
                {$article.DATE}
            </div>
            <h3 class="titleIn">{$article.CONTENT_TITLE}</h3>
        </div>
    </div>
    <div class="row">
        {if $article.MEDIA || $article.MEDIA_VIDEO_PLAYER}
            <div class="columns column_{100-$column} picContent">
                {if $article.MEDIA_VIDEO_PLAYER && ($article.MEDIA_TYPE_ID !='youtube')}
                    <figure class="shadow" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>{$article.CONTENT_TITLE}]}>
                        {$article.MEDIA_VIDEO_PLAYER}
                    </figure>
                {elseif $article.MEDIA_TYPE_ID =='youtube'}
                    <figure class="video nomgfigure">
                        <a class="activeRoll" data-video="{$article.MEDIA}" href="{urlParser url=$article.MEDIA}" data-sneezy="" target="_blank" >
                            <img  class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$article.MEDIA_PATH}" alt="{$article.CONTENT_TITLE}">
                        </a>
                        <i class="icon-play"></i>
                    </figure>
                {else}
                    <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$article.MEDIA}" alt="{$article.CONTENT_TITLE}">
                        <noscript><img src="{$article.MEDIA}" width="580" height="247" alt="{$article.CONTENT_TITLE}" /></noscript>
                    </figure>
                {/if}
            </div>
        {/if}
        <div class="columns column_{$column} textContent">
            <p>
                {$article.CONTENT_TEXT}
            </p>
        </div>

    </div>
</div>
