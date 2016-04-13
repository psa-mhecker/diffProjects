{if $aData.ZONE_WEB == 1 && $aManager|@sizeof > 0}

    <div class="sliceNew sliceAccountManagersDesk">
        <section id="{$aData.ID_HTML}" class="clsaccountmanagers">
            {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE}</h2>{/if}

            {if $aData.ZONE_TITRE2}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2}</h3>{/if}

            {if $aData.ZONE_TEXTE}
                <div class="mgchapo">{$aData.ZONE_TEXTE}</div>
            {/if}

            <div class="row of6">
            {assign var='new' value='new '}
            {assign var='i' value=1}
            {foreach from=$aManager item=lib}
                    <div class="col span2 row of2 contact" style="margin-bottom: 0px;">
                        <figure class="col">
                            <img class="" src="{$lib.MEDIA_ID}" data-original="{$lib.MEDIA_ID}" width="138" height="138" alt="" />
                            <noscript><img src="{$lib.MEDIA_ID}" width="138" height="138" alt="" /></noscript>
                        </figure>
                        <div class="caption text">
                            <strong>{$lib.PAGE_ZONE_MULTI_LABEL7} {$lib.PAGE_ZONE_MULTI_LABEL} {$lib.PAGE_ZONE_MULTI_LABEL2}</strong><br />
                            {$lib.PAGE_ZONE_MULTI_LABEL3}<br />
                            {if $lib.PAGE_ZONE_MULTI_TEXT}<div class="state">{$lib.PAGE_ZONE_MULTI_TEXT}</div>{/if}
                            {if $lib.PAGE_ZONE_MULTI_LABEL5}<div class="email" style="overflow:hidden;"><a href="mailto:{$lib.PAGE_ZONE_MULTI_LABEL5}">{if $lib.MAIL}{$lib.MAIL}{else}{$lib.PAGE_ZONE_MULTI_LABEL5}{/if}</a></div>{/if}
                            {if $lib.PAGE_ZONE_MULTI_LABEL6}<div class="phone" style="word-wrap:break-word;">{$lib.PAGE_ZONE_MULTI_LABEL6}</div>{/if}
                        </div>
                    </div>
                {assign var='i' value=$i + 1}
                {if $i%3 == 1}
                    {assign var='new' value='new '}
                {else}
                    {assign var='new' value=''}
                {/if}
            {/foreach}
            </div>
        </section>
    </div>


{/if}