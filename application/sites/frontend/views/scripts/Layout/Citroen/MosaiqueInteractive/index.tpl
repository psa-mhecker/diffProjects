{if $aData.ZONE_WEB == 1}
{literal}
    <style>
{/literal}
{if ($aData.SECOND_COLOR|count_characters)==7}
    {literal}
    .sliceInteractiveMosaicDesk .actions .buttonTransversalInvert{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
            background-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
            border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
            color:#ffffff!important;
    {/literal}{/if}{literal}
    }
    .sliceInteractiveMosaicDesk .actions .buttonTransversalInvert:hover{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
            background-color:#ffffff!important;
            border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
            color:{/literal}{$aData.SECOND_COLOR} {literal}!important;
    {/literal}{/if}

{/if}
{literal}
    .showroom.clslanguetteshowroom .addmore.folder a {
        filter: none;
        -webkit-filter: none;
        -moz-filter: none;
        -o-filter: none;
        -ms-filter: none;
    }
    </style>
{/literal}
    <div class="sliceNew sliceInteractiveMosaicDesk">
<section id="{$aData.ID_HTML}" class="row of6 clsmosaiqueinteractive">
    <div class="sep {$aData.ZONE_SKIN}"></div>

    {if $aData.ZONE_TITRE}<h2 class="subtitle"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE}</h2>{/if}
    {if $aData.ZONE_TITRE2}<h3 class="parttitle"{if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2}</h3>{/if}
    {if $aData.ZONE_TEXTE}
        <div class="mgchapo">{$aData.ZONE_TEXTE}</div>
    {else}
        <div class="no-mgchapo"></div>
    {/if}


    {if $aVisuelMosaique|@sizeof > 0}
        <div class="caption row of4 collapse tiles">

            {foreach from=$aVisuelMosaique item=Visuel name=mosaiqueLoop}
                {if $Visuel.PAGE_ZONE_MULTI_ATTRIBUT == 1}
                    <figure class="col zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT}"/>
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL && $Visuel.PAGE_ZONE_MULTI_URL}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL}" {if $Visuel.PAGE_ZONE_MULTI_VALUE == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL2 && $Visuel.PAGE_ZONE_MULTI_URL3}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.__________________, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL3}" {if $Visuel.PAGE_ZONE_MULTI_MODE == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>

                    <figure class="col span2 zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE2}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE2}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT2}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL3 && $Visuel.PAGE_ZONE_MULTI_URL5}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL5}" {if $Visuel.PAGE_ZONE_MULTI_VALUE2 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL3}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL4 && $Visuel.PAGE_ZONE_MULTI_URL7}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.__________________, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL7}" {if $Visuel.PAGE_ZONE_MULTI_MODE2 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL4}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>

                    <figure class="col zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE3}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT3}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT3}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE3}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT3}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL5 && $Visuel.PAGE_ZONE_MULTI_URL9}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL9}" {if $Visuel.PAGE_ZONE_MULTI_VALUE3 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL5}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL6 && $Visuel.PAGE_ZONE_MULTI_URL11}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.__________________, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL11}" {if $Visuel.PAGE_ZONE_MULTI_MODE3 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL6}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                {elseif $Visuel.PAGE_ZONE_MULTI_ATTRIBUT == 2}
                    <figure class="col span2 zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL && $Visuel.PAGE_ZONE_MULTI_URL}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL}" {if $Visuel.PAGE_ZONE_MULTI_VALUE == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL2 && $Visuel.PAGE_ZONE_MULTI_URL3}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL2, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL3}" {if $Visuel.PAGE_ZONE_MULTI_MODE == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                    <figure class="col span2 zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE2}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE2}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT2}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL3 && $Visuel.PAGE_ZONE_MULTI_URL5}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL5}" {if $Visuel.PAGE_ZONE_MULTI_VALUE2 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL3}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL4 && $Visuel.PAGE_ZONE_MULTI_URL7}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL4, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL7}" {if $Visuel.PAGE_ZONE_MULTI_MODE2 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL4}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                {elseif $Visuel.PAGE_ZONE_MULTI_ATTRIBUT == 3}
                    <figure class="col span2 zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL && $Visuel.PAGE_ZONE_MULTI_URL}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL}" {if $Visuel.PAGE_ZONE_MULTI_VALUE == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL2 && $Visuel.PAGE_ZONE_MULTI_URL3}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL2, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL3}" {if $Visuel.PAGE_ZONE_MULTI_MODE == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                    <figure class="col zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE2}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT2}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT2}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE2}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT2}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL3 && $Visuel.PAGE_ZONE_MULTI_URL5}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL5}" {if $Visuel.PAGE_ZONE_MULTI_VALUE2 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL3}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL4 && $Visuel.PAGE_ZONE_MULTI_URL7}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL4, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL7}" {if $Visuel.PAGE_ZONE_MULTI_MODE2 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL4}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>

                    <figure class="col zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE3}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT3}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT3}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE3}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT3}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL5 && $Visuel.PAGE_ZONE_MULTI_URL9}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL9}" {if $Visuel.PAGE_ZONE_MULTI_VALUE3 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL5}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL6 && $Visuel.PAGE_ZONE_MULTI_URL11}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL6, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL11}" {if $Visuel.PAGE_ZONE_MULTI_MODE3 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL6}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                {elseif $Visuel.PAGE_ZONE_MULTI_ATTRIBUT == 4}
                    <figure class="col span2 zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x790}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_600x790}" width="590" height="388" alt="{$Visuel.MEDIA_ALT}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL && $Visuel.PAGE_ZONE_MULTI_URL}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL}" {if $Visuel.PAGE_ZONE_MULTI_VALUE == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL2 && $Visuel.PAGE_ZONE_MULTI_URL3}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL2, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL3}" {if $Visuel.PAGE_ZONE_MULTI_MODE == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                    <div class="col">

                        <figure class="zoner">
                            <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE2}{/if}</span>
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" />
                            <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="590" height="388" alt="{$Visuel.MEDIA_ALT2}" /></noscript>
                            <figcaption>
                                <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE2}</h3>
                                <p>{$Visuel.PAGE_ZONE_MULTI_TEXT2}</p>
                                {if $Visuel.PAGE_ZONE_MULTI_LABEL3 && $Visuel.PAGE_ZONE_MULTI_URL5}
                                    <ul class="links-new">
                                        <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL5}" {if $Visuel.PAGE_ZONE_MULTI_VALUE2 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL3}</a></li>
                                    </ul>
                                {/if}
                                {if $Visuel.PAGE_ZONE_MULTI_LABEL4 && $Visuel.PAGE_ZONE_MULTI_URL7}
                                    <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                        <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL4, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL7}" {if $Visuel.PAGE_ZONE_MULTI_MODE2 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL4}</span></a></li>
                                    </ul>
                                {/if}
                            </figcaption>
                        </figure>

                        <figure class="zoner">
                            <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE3}{/if}</span>
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT}{$Visuel.MEDIA_ALT3}" />
                            <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID3}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x395}" width="295" height="388" alt="{$Visuel.MEDIA_ALT3}" /></noscript>
                            <figcaption>
                                <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE3}</h3>
                                <p>{$Visuel.PAGE_ZONE_MULTI_TEXT3}</p>
                                {if $Visuel.PAGE_ZONE_MULTI_LABEL5 && $Visuel.PAGE_ZONE_MULTI_URL9}
                                    <ul class="links-new">
                                        <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL9}" {if $Visuel.PAGE_ZONE_MULTI_VALUE3 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL5}</a></li>
                                    </ul>
                                {/if}
                                {if $Visuel.PAGE_ZONE_MULTI_LABEL6 && $Visuel.PAGE_ZONE_MULTI_URL11}
                                    <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                        <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL6, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL11}" {if $Visuel.PAGE_ZONE_MULTI_MODE3 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL6}</span></a></li>
                                    </ul>
                                {/if}
                            </figcaption>
                        </figure>

                    </div>
                    <figure class="col zoner">
                        <span class="optiontitle">{if $aData.ZONE_TITRE3}{$Visuel.PAGE_ZONE_MULTI_TITRE4}{/if}</span>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/tile-2col.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x790}" width="295" height="388" alt="{$Visuel.MEDIA_ALT4}" />
                        <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$Visuel.MEDIA_ID4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MOSAIQUE_300x790}" width="295" height="388" alt="{$Visuel.MEDIA_ALT4}" /></noscript>
                        <figcaption>
                            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$Visuel.PAGE_ZONE_MULTI_TITRE4}</h3>
                            <p>{$Visuel.PAGE_ZONE_MULTI_TEXT4}</p>
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL7 && $Visuel.PAGE_ZONE_MULTI_URL13}
                                <ul class="links-new">
                                    <li><a class="buttonLink" href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL13}" {if $Visuel.PAGE_ZONE_MULTI_VALUE4 == 2}target="_blank"{/if}>{$Visuel.PAGE_ZONE_MULTI_LABEL7}</a></li>
                                </ul>
                            {/if}
                            {if $Visuel.PAGE_ZONE_MULTI_LABEL8 && $Visuel.PAGE_ZONE_MULTI_URL15}
                                <ul class="actions clean" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                    <li class="blue cta"><a class="buttonTransversalInvert" {gtm data=$aData name="mobile_mosaique_interactive_clic_sur_en_savoir_en_plus" datasup=['value' => $smarty.foreach.mosaiqueLoop.iteration] labelvars=['%position%' => $smarty.foreach.mosaiqueLoop.iteration, '%nom du button%' => $Visuel.PAGE_ZONE_MULTI_LABEL8, '%id du lien%' => $smarty.foreach.mosaiqueLoop.iteration]} href="{urlParser url=$Visuel.PAGE_ZONE_MULTI_URL15}" {if $Visuel.PAGE_ZONE_MULTI_MODE4 == 2}target="_blank"{/if}><span style="background-color:inherit;color:inherit;">{$Visuel.PAGE_ZONE_MULTI_LABEL8}</span></a></li>
                                </ul>
                            {/if}
                        </figcaption>
                    </figure>
                {/if}
            {/foreach}

        </div>
        <!-- /.tiles -->
    {/if}
    <div class="caption">
    {if $aData.ZONE_TITRE6}
        {if $aData.ZONE_TITRE5 == "ROLL"}
            <small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6}</a></small>
            <div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
                {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                {if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
            </div>

        {elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
            <small class="legal">
                <a href="{urlParser url={$urlPopInMention|cat:"?popin=1"}}" class="popinfos fancybox.ajax">
                    {$aData.ZONE_TITRE6}
                </a>
            </small>
        {elseif $aData.ZONE_TITRE5 == "TEXT"}
            <figure>
            {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
        </figure>
        <small class="legal">{$aData.ZONE_TITRE6}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
        {/if}
    {/if}
</div>
</section>
<div class="parent" id="trancheParent" style="display: none;"></div>

    {if $aData.ZONE_LANGUETTE == 1}
    <section class="showroom row of3 clslanguetteshowroom">
    <div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_MOSAIQUE_INTERACTIVE']}><span style="color: inherit;">{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
    </section>
    {/if}
</div>
{/if}