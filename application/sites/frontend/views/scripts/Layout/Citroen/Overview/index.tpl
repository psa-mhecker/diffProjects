{if $aData.ZONE_WEB == 1}
{$sSharer}
<div class="sliceNew sliceOverviewDesk">
     <h1 id="overview-n{$aData.ID_HTML}" class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>
        {$aData.ZONE_TITRE|escape}
    </h1>
    <section class="{$aData.ZONE_SKIN} overview clsoverview">
    {if $aData.ZONE_TEXTE}<div class="zonetexte">{$aData.ZONE_TEXTE}</div>{/if}
    {if $aOverview|@sizeof > 0}
        <ul class="row gutter actions">
            {foreach from=$aOverview item=Page name=aOverview}
                {assign var='afficheElement' value='false'}

                {if $Page.PAGE_START_DATE == '' && $Page.PAGE_END_DATE == ''}
                    {assign var='afficheElement' value='true'}
                {else if $Page.PAGE_START_DATE|date_format:"%Y%m%d%H%M" <= $smarty.now|date_format:"%Y%m%d%H%M" 
                    && $smarty.now|date_format:"%Y%m%d%H%M" <= $Page.PAGE_END_DATE|date_format:"%Y%m%d%H%M"}
                    {assign var='afficheElement' value='true'}
                {else if $Page.PAGE_START_DATE|date_format:"%Y%m%d%H%M" <= $smarty.now|date_format:"%Y%m%d%H%M" 
                    && $Page.PAGE_END_DATE == ''}
                    {assign var='afficheElement' value='true'}
                {else if $Page.PAGE_START_DATE == '' 
                    && $smarty.now|date_format:"%Y%m%d%H%M" <= $Page.PAGE_END_DATE|date_format:"%Y%m%d%H%M"}
                    {assign var='afficheElement' value='true'}
                {/if}

                {if $afficheElement == 'true'}
                    <li class="columns column_25 {if $smarty.foreach.aOverview.iteration % 4 == 1}row{/if}">
                        <a class="buttonTransversalInvert" href="{urlParser url=$Page.PAGE_CLEAR_URL}" {gtm data=$aData action='Push' datasup=['eventLabel'=> {$Page.PAGE_TITLE}]}>
                        <span>{$Page.PAGE_TITLE}</span></a>
                    </li>
                {/if}
            {/foreach}
        </ul>
    {/if}
    </section>
</div>
{/if}
