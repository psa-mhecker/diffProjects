{literal}
    <style>
    {/literal}
    {if ($aData.PRIMARY_COLOR|count_characters)==7}
        {literal}
            div.sliceDisponibleSurDesk .clsdisponiblesur .cars .zoner:hover{
            {/literal}{if ($aData.PRIMARY_COLOR|count_characters)==7 }{literal}
                    border-color:{/literal}{$aData.PRIMARY_COLOR};{literal}
            {/literal}{/if}{literal}
            }
            div.sliceDisponibleSurDesk .clsdisponiblesur .cars .zoner:hover:before{
                background-color:transparent;
            }
            div.sliceDisponibleSurDesk .mastercars .zoner.bg:before{
                background-color:transparent;
            }
        {/literal}
    {/if}
    {literal}
    </style>
{/literal}
{if $ZONE_WEB == 1}
    {if $aVehicule|@sizeof > 0}
        <div class="sliceNew sliceDisponibleSurDesk">
            <section id="{$aData.ID_HTML}" class="row of3 clsdisponiblesur mastercars">
                {if $ZONE_TITRE}
                    <h2 class="caption parttitle">
                        {$ZONE_TITRE|escape}
                    </h2>
                {/if}
                <div class="caption slider cars" {gtmjs type='slider'  data=$aData  action='Click' }>
                    <div class="row of6 collapse">
                        {foreach from=$aVehicule item=Vehicule}
                            {foreach from=$Vehicule key=key item=item name=oneVehicule}
                                {if $key == 'VEHICULE' && $Vehicule.VEHICULE.PAGE_CLEAR_URL != ''}
                                    <div class="col zoner bg" {gtm name='clic_sur_un_vehicule'  data=$aData labelvars=['%nom du vehicule%'=>$Vehicule.VEHICULE.VEHICULE_LABEL, '%intitule du lien%'=>$Vehicule.VEHICULE.VEHICULE_LABEL]}>
                                        <a {gtm name="disponible_sur_clic_sur_un_vehicule" data=$aData datasup=['value' => $Vehicule.VEHICULE.VEHICULE_LCDV6_CONFIG] labelvars=['%position%' => $smarty.foreach.oneVehicule.iteration, '%nom du button%' => $Vehicule.VEHICULE.VEHICULE_LABEL, '%nom du vehicuke%' => $Vehicule.VEHICULE.VEHICULE_LABEL, '%code lcdv%' => $Vehicule.VEHICULE.VEHICULE_LCDV6_CONFIG]} href="{urlParser url=$Vehicule.VEHICULE.PAGE_CLEAR_URL}">
                                            <figure>
                                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$Vehicule.VEHICULE.THUMBNAIL_PATH_FORMATE}" alt="{$Vehicule.VEHICULE.THUMBNAIL_ALT}" />
                                                <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$Vehicule.VEHICULE.THUMBNAIL_PATH_FORMATE}" alt="{$Vehicule.VEHICULE.THUMBNAIL_ALT}" /></noscript>
                                                <figcaption>{$Vehicule.VEHICULE.VEHICULE_LABEL}</figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                {/if}
                            {/foreach}
                        {/foreach}
                    </div>
                </div>
            </section>
        </div>
    {/if}
{/if}
