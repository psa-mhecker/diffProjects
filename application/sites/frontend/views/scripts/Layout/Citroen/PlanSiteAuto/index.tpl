<div id="{$aData.ID_HTML}" class="sliceNew slicePlanDuSiteDesk">
    {foreach from=$aPlanSite item=aSiteTree}
        {if isset($aSiteTree.n1)}

            <section class="row of3 sitemap">
                <h2 class="parttitle">
                    <a href="{urlParser url=$aSiteTree.n1.url}" {if $aSiteTree.n1.urlExterneTarget == 2}target="_blank"{/if} >
                        {$aSiteTree.n1.lib|upper}
                    </a>
                </h2>

                {if isset($aSiteTree.n2)}

                    {foreach from=$aSiteTree.n2 item=aPageN2 name=siteMapN2}
                        {assign var="index" value=$smarty.foreach.siteMapN2.iteration - 1}

                        <div class="{if $index % 3 == 0}new {/if}col">
                            <h3>
                                <a href="{urlParser url=$aPageN2.url}" {if $aPageN2.urlExterneTarget == 2}target="_blank"{/if} class="activeRoll">
                                    {$aPageN2.lib}
                                </a>
                            </h3>

                            {if isset($aPageN2.n3)}
                                <ul>
                                    {foreach from=$aPageN2.n3 item=aPageN3 name=siteMapN3}
                                        <li>
                                            <a href="{urlParser url=$aPageN3.url}" {if $aPageN3.urlExterneTarget == 2}target="_blank"{/if} class="activeRoll">
                                                {$aPageN3.lib}
                                            </a>
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}

                        </div>
                    {/foreach}

                {/if}
            </section>

        {/if}
    {/foreach}
</div>