{if $aParams.ZONE_WEB && $actualites}
<section id="{$aParams.ID_HTML}" class="actualityReviewDesktop sliceNew">
  <div class="headActualityReviewDesktop">
    <h2>
       {$aParams.ZONE_TITRE|escape}
    </h2>
    <div class="panelActuHead">
       <a href="{urlParser url=$aParams.ZONE_URL}"{if $aParams.ZONE_TITRE3=='2'}target="blank"{/if} class="button compLink" {gtm action="Push::Click" data=$aParams datasup=['eventCategory' => 'News', 'eventLabel' => $aParams.ZONE_TITRE2] idMulti=$aParams.PAGE_ZONE_MULTI_ID}>{$aParams.ZONE_TITRE2|escape}</a>
    </div>
  </div>
  {foreach from=$actualites item=actualite key=k name=actualites}
  
  {if $k%2 == 0}
  <div class="row">
  {/if}
    <div class="columns column_50 actuWrapper">
      <div class="row">
        <div class="columns column_50{if $k >1 } right{/if} pictureContent">
          <a href="{$actualite.PAGE_CLEAR_URL}" >
		     <img class="lazy responsive-images" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$actualite.MEDIA_PATH}" width="300" height="388" alt="{$actualite.MEDIA_ALT|escape}"  />
             <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$actualite.MEDIA_PATH}" width="270" height="152" alt="{$actualite.MEDIA_ALT|escape}" /></noscript>
          </a>
        </div>
        <div class="columns column_50 textContent">
          <a href="{$actualite.PAGE_CLEAR_URL}" class="activeRoll">
            <p class="tagActu">
             <time>{if $actualite.CONTENT_CODE2=='2'}{$actualite.DATE_FR}{else}{$actualite.DATE_UK}{/if}</time>
            </p>
            <h3>
              {$actualite.CONTENT_TITLE|upper}
            </h3>
            <p>
               <p>{$actualite.CONTENT_TEXT2|strip_tags|truncate:150}</p>
            </p>
          </a>
        </div>
      </div>
    </div>
{if $k%2 == 1}
  </div>
 {/if}
   {/foreach}
</section>
 {/if}