<div class="sliceNew sliceResultsDesk">
    <section id="{$aData.ID_HTML}" class="row of4 results clsresults">
        <div class="col span3 row of2" style="margin-bottom: 0px;">
            <form class="col" novalidate id="searchPage">
                <fieldset>
                    <div class="field include">
                        <input type="text" name="search" id="searchField" value="{$search|escape}" placeholder="{'RECHERCHE_SUR_SITE'|t}"  class="tt-query placeholder" autocomplete="off" spellcheck="false" dir="auto"/>
                    </div>
                    <button type="submit" name="submit-page" id="submit-seachpage" class="submit">{'OK'|t}</button>
                </fieldset>
            </form>
            {if $search neq ''}
                <div class="caption subtitle result-title">{$sResults}</div>
                {if $aSearch|@sizeof > 0}
                    <div id="allResults">
                        <input type="hidden" name="iCount" id="iCount" value="{$iCount}"/>
                        {foreach from=$aSearch item=result name=listResults}
                            <div class="caption item zoner">
                                {if $result.title}<h2 class="parttitle"><a href="{urlParser url=$result.url}" {if $result.mode_ouverture==2}target='_blank'{/if}>{$result.title}</a></h2>{/if}
                                <p>{$result.desc}</p>
                            </div>
                        {/foreach}
                    </div>
                    {if $nbResults > 15}
                        <div id="seeMoreResults" class="caption addmore"><a href="javascript://">{'VOIR_PLUS_RESULTATS'|t}</a></div>
                        {/if}

                {/if}
            {else} {* CPW-3911 class subtitle n�cessaire dans la page sinon padding-top->0 cf:main.js --> $('section').each(function()   *}
                <div class="caption subtitle"></div>
            {/if}
        </div>
        <aside class="col" style="margin-bottom: 0px;">
            {if $aTerme|@sizeof > 0}
                <div class="parttitle">{'TOP_RECHERCHES'|t}</div>
                <ul class="list">
                    {foreach from=$aTerme item=terme name=listTerme}
                        <li><a href="{urlParser url={$aData.PAGE_CLEAR_URL|cat:"?search="|cat:$terme.search}}">{$terme.label}</a></li>
                        {/foreach}
                </ul>
            {/if}
            {if $aOutils|@sizeof > 2}
                <ul class="actions">
                    {foreach from=$aOutils item=outil name=listOutils}
                        {$outils}

                    {/foreach}
                </ul>
            {/if}
            <ul class="actions">
                <li><a href="{urlParser url=$sURLPagePlanDuSite}" class="buttonTransversalInvert"><span>{'PLAN_DU_SITE'|t}</span></a></li>
            </ul>
        </aside>
    </section>
</div>