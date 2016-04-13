<section id="{$aData.ID_HTML}"  class="row of{if bTplPreHome && $aPlanSite|@count == 2}2{else}3{/if} withsep prehome">
    {foreach from=$aPlanSite key=i item=SiteLangue}
        <div class="col" data-sync="langline{$aData.ORDER}">
            <div class="parttitle">{$aTrad[$i]}</div>
            <a href="{urlParser url=$home[$i]}" class="button">{$aLangue[$i].LANGUE_TRANSLATE}</a>
            {assign var='new' value='new'}
            <div class="row of2 listlinks">
                {foreach from=$SiteLangue item=SiteNiveau}
                    <div class="{$new} col">
                        {if $new == 'new'}
                            {assign var='new' value=''}
                        {else}
                            {assign var='new' value='new'}
                        {/if}
                        {foreach from=$SiteNiveau key=Niveau item=InfoPageSite}
                                {if $Niveau=='n1'}
                                    <div class="listtitle"><a href="{urlParser url=$InfoPageSite.url}">{$InfoPageSite.lib}</a></div>
                                {elseif $Niveau=='n2'}
                                    <ul>
                                        {foreach from=$InfoPageSite item=InfoPage}
                                            <li><a href="{urlParser url=$InfoPage.url}" {if $InfoPage.urlExterneTarget == 2}target="_blank"{/if}>{$InfoPage.lib}</a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                        {/foreach}
                    </div>
                {/foreach}
            </div>
        </div>
    {/foreach}
    
</section>
{if $bTplPreHome}</div>{/if}
