{literal}
    <style>
        div.sliceDetailPromotionDesk .clslistreturn a{
        {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important; {literal} 
        {/literal}{/if}{literal}
        }
        div.sliceDetailPromotionDesk .clslistreturn a:active, .sliceDetailPromotionDesk .clslistreturn a:hover{
        {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important; {literal}
        {/literal}{/if}{literal}
        }
        div.sliceDetailPromotionDesk .clslistreturn a:after{
        {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important; {literal}
        {/literal}{/if}{literal}
        }
    </style>
{/literal}

<div class="sliceNew sliceDetailPromotionDesk">
        <ul id="{$aData.ID_HTML}" class="actions clslistreturn row">
                <li class="back">
                        <a href="{urlParser url=$url}" class="activeRoll">{"BACK_PROMO"|t}</a>
                </li>
        </ul>
</div>