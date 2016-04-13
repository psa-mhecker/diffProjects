<section id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} row of2 others clsgalerieniveau2">

    {if Pelican::$config.ZONE_TEMPLATE_ID.HOME_BUSINESS == $aData.ZONE_TEMPLATE_ID}
        <div class= "subtitle">{"BUSINESS_GALERIE_NIV_2_TITLE"|t}</div>
    {/if}
    {foreach from=$aRsGalerie item=Galerie key=key}
        <div class="col row of7 zoner bg" data-sync="forcesync_{$aData.ORDER}line{if $key+1 is odd}{assign var=previousKey value=$key+1}{$key+1}{else}{$previousKey}{/if}">

            <figure class="col span3">
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$Galerie.MEDIA_ID2}" width="182" height="103" alt="" />
                    <noscript><img src="{$Galerie.MEDIA_ID2}" width="182" height="103" alt="" /></noscript>
            </figure>
            <!-- /.col -->

			{ if $Galerie.PAGE_TITLE_BO || $Galerie.PAGE_TEXT}
            <div class="col span4">
                    {if $Galerie.PAGE_TITLE_BO}<h2 class="parttitle"><a {if $Galerie.PAGE_URL_EXTERNE_MODE_OUVERTURE == 2}target="_blank"{/if} href="{urlParser url=$Galerie.PAGE_CLEAR_URL}" {gtm action='Push' data=$aData datasup=['eventLabel'=>{$Galerie.PAGE_TITLE_BO}]}>{$Galerie.PAGE_TITLE_BO}</a></h2>{/if}
                    {if $Galerie.PAGE_TEXT}<p>{$Galerie.PAGE_TEXT}</p>{/if}
            </div>
			{/if}

        </div>
    {/foreach}
</section> 