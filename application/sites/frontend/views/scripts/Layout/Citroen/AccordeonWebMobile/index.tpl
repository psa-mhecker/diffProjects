{literal}
    <style>
    {/literal}
    {if ($aData.SECOND_COLOR|count_characters)==7}
        {literal}
            div.sliceAccordeonWebDesk .toggle .toghead{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                    background-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                    border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }
            div.sliceAccordeonWebDesk .toggle .toghead:hover{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                    background-color:#ffffff!important;
                    border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                    color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }
            div.sliceAccordeonWebDesk .toggle .toghead:hover .parttitle{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                    color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }
            div.sliceAccordeonWebDesk .toggle .toghead:hover:after{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                    color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }
            div.sliceAccordeonWebDesk .toggle .toghead.open{
                background-color:#ffffff!important;
            }
            div.sliceAccordeonWebDesk .toggle .toghead.open .parttitle, div.sliceAccordeonWebDesk .toggle .toghead.open:hover .parttitle{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }
            div.sliceAccordeonWebDesk .toggle .toghead.open:after, div.sliceAccordeonWebDesk .toggle .toghead.open:hover:after{
            {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
            {/literal}{/if}{literal}
            }

        {/literal}
    {/if}
    {literal}
    </style>
{/literal}
    
    {if $aData.ZONE_WEB && $NbToggle >= 2}
    {assign var=i value=1}
    {if $aData.ZONE_ATTRIBUT == 2}
        {assign var=flag value=open}
    {/if}
<div class="sliceNew sliceAccordeonWebDesk">
    <section id="{$aData.ID_HTML}" class=" row togglebloc clsAccordeonwebMobile" style="padding-top: 0px;">
        <div class="toggle" style="margin-bottom: 0px;">
            {foreach from=$aToggle item=toggle}
                <div class="toghead folder {$flag}" data-group="toggle">
                    <a href="#toggle{$aData.ZONE_ORDER}_{$i}">
                        {if $toggle.MEDIA_ID}
                            <figure>
                                <img src="{$toggle.MEDIA_ID}" width="141" height="78" alt="{$toggle.MEDIA_ALT}" />
                            </figure>
                        {/if}
                        {if $toggle.PAGE_ZONE_MULTI_TITRE}<h2 class="parttitle">{$toggle.PAGE_ZONE_MULTI_TITRE}</h2>{/if}
                        {if $toggle.PAGE_ZONE_MULTI_TITRE2}<p>{$toggle.PAGE_ZONE_MULTI_TITRE2}</p>{/if}
                    </a>
                </div>

                <div class="togbody" id="toggle{$aData.ZONE_ORDER}_{$i++}"></div>
            {/foreach}
        </div>
    </section>
</div>
{/if}