<div class="sliceNew slicePromotionListDesk">
    {if $aData.ZONE_WEB == 1 && $ListePromotions|@sizeof > 0 && $affichTranch==1}    
        <!--section id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN}"-->
            {if $ListePromotions|@sizeof > 0}
                {foreach from=$ListePromotions item=promotion}
                    {if $promotion.PAGE_ZONE_MULTI_LABEL3 eq 1}
                        {include file="$pathVisual"}
                    {else}
                        {include file="$pathMixte"}
                    {/if}
                {/foreach}
            {/if}
            {if $response|@sizeof > 0}
                {foreach from=$response item=zone}{$zone}{/foreach}
            {/if}
        <!--/section-->
    {/if}
</div>