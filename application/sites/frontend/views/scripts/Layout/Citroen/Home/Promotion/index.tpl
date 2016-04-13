<div class="sliceNew sliceDetailPromotionDesk">
    {if $aData.ZONE_WEB == 1}
        {foreach from=$ListePromotions item=promotion}
            {if $promotion.PAGE_ZONE_MULTI_LABEL3 eq 1}
                {include file="$pathVisual"}
            {else}
                {include file="$pathMixte"}
            {/if}
        {/foreach}
    {/if}
</div>