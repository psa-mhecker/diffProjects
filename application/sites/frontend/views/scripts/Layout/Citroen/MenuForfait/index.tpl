{if $aMenuForfait}
    <div class="sliceNew sliceMenuForfaitDesk">
        <section id="contenu-texte-2-3-cta-1-3_2394" class=" row of3 clsMenuForfait" style="padding-top: 0px;">
            <div class="row of3 piclinks" style="margin-bottom: 0px;">
                {foreach $aMenuForfait as $MenuOption name=forfait}
                    {if ($smarty.foreach.forfait.first && !$forfaitSelected) || $idForfaitSelected == $MenuOption.CONTENT_ID}
                        {assign var="activeClass" value="active"}
                        {assign var="secondColor" value="color:{$aData.SECOND_COLOR};"}
                    {/if}

                    <div class="col {$activeClass}">
                        <a href="{urlParser url=$MenuOption.CONTENT_CLEAR_URL}#content" style="{$secondColor}">
                            <img src="{$MenuOption.MEDIA_ID}" width="63" height="63" alt="{$MenuOption.TITRE}">
                            <span>{$MenuOption.TITRE}</span>
                        </a>
                    </div>

                    {assign var="activeClass" value=""}
                    {assign var="secondColor" value=""}
                {/foreach}
            </div>
            <!-- /.piclinks -->
        </section>
    </div>
{/if}