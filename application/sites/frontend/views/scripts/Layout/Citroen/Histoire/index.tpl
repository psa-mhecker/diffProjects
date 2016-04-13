<div id="{$aParams.ID_HTML}" class="sliceNew sliceHistoireDesk" data-ws="/_/Layout_Citroen_Histoire/ajaxGetArticlesx">
    <div class="stickyDateNav">
        <div class="stickyNavWrapper">
            <div class="stickyNavWrapperIn row">
                <div class="label columns column_10">
                    {"LES_ANNEES"|t}
                </div>
                <div class="dateColumn columns column_90">
                    <ul class="row">
                        {foreach from=$aFrise key=num item=annee}
                            <li  class="columns" style="width:{floor((100/count($aFrise)))}%">
                                <a href="#{$annee}" class="{if $num == 0}active{/if}">{$annee}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /.sticker -->


    <div class="contentDate">
        {foreach from=$aHistoires  key=annee item=aHistoire}
            <div data-date="{$annee}" id="{$annee}" class="wrapperDate">
                <h2 class="subtitleDate"><span class="line"><span {if $aParams['PRIMARY_COLOR']} style='color:{$aParams['PRIMARY_COLOR']}}'{/if}>{"LES_ANNEES"|t} {$annee}</span></span></h2>

                {foreach from=$aHistoire.COLONNE_GAUCHE  item=articleGauche name=foo }
                    {include file={$smarty.current_dir|cat:"/includes/desktop/textLeft.tpl"} column=55 negativeTop=false article=$articleGauche}

                    {if ($smarty.foreach.foo.index<$aHistoire.COLONNE_DROITE|@count)}
                        {assign var=articleDroite  value=$aHistoire.COLONNE_DROITE[$smarty.foreach.foo.index]}
                        {include file={$smarty.current_dir|cat:"/includes/desktop/textRight.tpl"} column=55 negativeTop=true article=$articleDroite}

                    {/if}

                {/foreach}

            </div>
        {/foreach}

    </div>
</div>

<!-- /.row -->

</div>
{literal}
    <style type="text/css">
        div.sliceHistoireDesk div.contentDate>div.wrapperDate>div.textLeft{
            min-height: 500px;
        }
        
        div.sliceHistoireDesk div.contentDate .textRight .textContent{
            position: relative;
            margin-top: 60px;
        }
    </style>
{/literal}
