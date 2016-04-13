{if $aData.ZONE_WEB == 1}
{literal}
<style>
    .sliceContratsServicesDesk .slider .bx-prev  , .sliceContratsServicesDesk .slider .bx-next  {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
        border: 2px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .sliceContratsServicesDesk .slider .bx-prev:hover, .sliceContratsServicesDesk .slider .bx-next:hover{
        color: #fff!important;
        background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .sliceContratsServicesDesk .slider .bx-wrapper .bx-controls .bx-pager-item .bx-pager-link.active {
        background: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
	 .sliceContratsServicesDesk .slider .bx-wrapper .col span:hover {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
	
	.sliceContratsServicesDesk .slider.offers a:hover span, .sliceContratsServicesDesk .slider.offers a:active span {
	color: {/literal}{$aData.SECOND_COLOR}{literal}!important; }
	
	.sliceContratsServicesDesk .col .cdsequal.details::after, .sliceContratsServicesDesk .col .cdsplus.details::after{
	background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important; }
	</style>
{/literal}
<div class="sliceNew sliceContratsServicesDesk">
<section id="{$aData.ID_HTML}"  class="contract clscontractsservices">
    <div class="sep {$aData.ZONE_SKIN}"></div>

    {if $aData.ZONE_TITRE}<h2 class="span4 subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"  {/if}>{$aData.ZONE_TITRE}</h2>{/if}
    {if $aData.ZONE_TITRE2}<h3 class="span4 parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$aData.ZONE_TITRE2}</h3>{/if}
	
	{if $aData.ZONE_TITRE ||  $aData.ZONE_TITRE2}
	<div class="no-mgchapo"></div>
	{/if}
	
    {assign var='i' value=1}
	
	{if $aContract|@sizeof > 0}
    <div class="slider"  data-objs="obj:slider,arrospos:center" {gtmjs type='slider' data=$aData  action = 'Click'}>
		<div class="row of3">
			{foreach from=$aContract item=lib}
			{if $lib.PAGE_ZONE_MULTI_VALUE3 == "nopicto"}
				{$lib.PAGE_ZONE_MULTI_VALUE3 = ""}
				{elseif $lib.PAGE_ZONE_MULTI_VALUE3 == "pictoplus"}
				{$lib.PAGE_ZONE_MULTI_VALUE3 = "cdsplus"}
				{elseif $lib.PAGE_ZONE_MULTI_VALUE3 == "pictoegale"}
				{$lib.PAGE_ZONE_MULTI_VALUE3 = "cdsequal"}
			{/IF}
				<div class="col {$lib.PAGE_ZONE_MULTI_TITRE4|lower}"><div class="cont">
						<h4 data-sync="ctrTitle{$aData.ZONE_SKIN}{$aData.ORDER}" class="parttitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"  {/if}>{$lib.PAGE_ZONE_MULTI_TITRE}</h4>
						<div data-sync="ctrPrice{$aData.ZONE_SKIN}{$aData.ORDER}" class="prices" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{'CS_A_PARTIR_DE'|t} <strong>{$lib.PAGE_ZONE_MULTI_TITRE2}</strong></div>
						<figure>
								<img style="max-height:161px;" class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$lib.MEDIA_ID}" width="235" height="100" alt="" />
								<noscript><img src="{$lib.MEDIA_ID}" width="235" height="100" alt="" /></noscript>
						</figure>
						<p data-sync="ctrHead{$aData.ZONE_SKIN}{$aData.ORDER}">{$lib.PAGE_ZONE_MULTI_TITRE3}</p>
						<div class="zonetexte" data-sync="ctrText{$aData.ZONE_SKIN}{$aData.ORDER}">{$lib.PAGE_ZONE_MULTI_TEXT}</div>

						<a href="#services{$i++}" class="folder" data-group="contract">{t('DETAIL_OFFRE')}</a>

						<div class="details {$lib.PAGE_ZONE_MULTI_VALUE3}" data-folder="{$lib.SERVICE}" data-sync="details{$aData.ORDER}">
								<div class="zonetexte">
									{$lib.PAGE_ZONE_MULTI_TEXT2}
								</div>
								{if $lib.PAGE_ZONE_MULTI_URL && $lib.PAGE_ZONE_MULTI_LABEL}
									<ul class="links">
										<li><a class="buttonLink" href="{urlParser url=$lib.PAGE_ZONE_MULTI_URL}" {if $lib.PAGE_ZONE_MULTI_VALUE == 'BLANK'}target="_blank"{/if}>{$lib.PAGE_ZONE_MULTI_LABEL}</a></li>
									</ul>
								{/if}
								{if $lib.PAGE_ZONE_MULTI_URL3 && $lib.PAGE_ZONE_MULTI_LABEL2}
									<ul class="actions">
										<li><a class="buttonLead" href="{urlParser url=$lib.PAGE_ZONE_MULTI_URL3}" {if $lib.PAGE_ZONE_MULTI_VALUE2 == 'BLANK'}target="_blank"{/if}>{$lib.PAGE_ZONE_MULTI_LABEL2}</a></li>
									</ul>
								{/if}
						</div>
						<!-- /.details -->
				</div></div>
			{/foreach}
            <!-- /.col -->
		</div>
	</div>
	{/if}
    <!-- /.row -->
</section>
</div>
{/if}