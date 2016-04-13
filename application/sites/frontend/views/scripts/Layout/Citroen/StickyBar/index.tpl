{if $stickyBar|sizeof >= 2}
{literal}
<style>
.stickyBarShowroomReviewDesktop .stickyplaceholder.showroom .sticky li a:hover span, .stickyBarShowroomReviewDesktop .stickyplaceholder.showroom .sticky li.on a span, .stickyBarShowroomReviewDesktop .stickyplaceholder.showroom .sticky.fixed li a:hover span, .stickyBarShowroomReviewDesktop .stickyplaceholder.showroom .sticky.fixed li.on a span{
color:{/literal}{$aData.PRIMARY_COLOR}{literal}
}
div.stickyplaceholder.showroom .sticky.fixed li a:hover, div.stickyplaceholder.showroom .sticky.fixed li.on a{
    margin: 0;
    color:{/literal}{$aData.PRIMARY_COLOR};{literal}
}
div.stickyplaceholder.showroom .sticky li.on a span{
    font-family: citroen, Arial, Helvetica, sans-serif!important;
    font-weight: bold;
    color:{/literal}{$aData.PRIMARY_COLOR};{literal}
}
div.stickyplaceholder.showroom .sticky li a span{
    font-family: citroen, Arial, Helvetica, sans-serif!important;
    font-weight: bold;
    color:#ffffff;
}
div.stickyplaceholder.showroom .sticky li a:hover span{
    color:{/literal}{$aData.PRIMARY_COLOR};{literal}
}
</style>
{/literal}
<div class="stickyBarShowroomReviewDesktop  sliceNew">
{foreach from=$aZonesHerites item=zone}{$zone}{/foreach}
<a name="sticky" id="sticky"></a>
	<div id="{$aData.ID_HTML}" style='margin:0;padding-top:8px;padding-bottom:8px;' class="showroom stickyplaceholder keep" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-bg="background:{$aData.PRIMARY_COLOR};" data-on="border:4px solid {$aData.PRIMARY_COLOR}; background:#ffffff;" data-hover="border:4px solid {$aData.PRIMARY_COLOR}; background:#ffffff;" {/if}>
		<div class="sticky" {if $aData.PRIMARY_COLOR}style="background:{$aData.PRIMARY_COLOR};"{/if}>
			<div class="inner" style="right: 17px;">
				<div class="logo"><a href="/">Citroën</a></div>
                {strip}
				<ul>
					{foreach from=$stickyBar item=nav key=id}
                                            {assign var='afficheElement' value='false'}
                                            {if $nav.PAGE_CLEAR_URL}

                                                {if $nav.PAGE_START_DATE == '' && $nav.PAGE_END_DATE == ''}
                                                    {assign var='afficheElement' value='true'}
                                                {else if $nav.PAGE_START_DATE|date_format:"%Y%m%d%H%M" <= $smarty.now|date_format:"%Y%m%d%H%M" 
                                                        && $smarty.now|date_format:"%Y%m%d%H%M" <= $nav.PAGE_END_DATE|date_format:"%Y%m%d%H%M"}
                                                        {assign var='afficheElement' value='true'}
                                                {else if $nav.PAGE_START_DATE|date_format:"%Y%m%d%H%M" <= $smarty.now|date_format:"%Y%m%d%H%M" 
                                                        && $nav.PAGE_END_DATE == ''}
                                                        {assign var='afficheElement' value='true'}
                                                {else if $nav.PAGE_START_DATE == '' 
                                                        && $smarty.now|date_format:"%Y%m%d%H%M" <= $nav.PAGE_END_DATE|date_format:"%Y%m%d%H%M"}
                                                        {assign var='afficheElement' value='true'}
                                                {/if}

                                                {if $afficheElement == 'true'}
                                                    <li{if $nav.PAGE_ID==$pidCourant} class="on"{/if}><a class="activeRoll" href="{urlParser url=$nav.PAGE_CLEAR_URL}#sticky" 
                                                        {gtm action='StickyBar' data=$aData datasup=['value'=>{$id+1}, 'eventLabel'=>{$nav.PAGE_TITLE|trim}] }>
                                                        <span>{if $shortTitle}{$nav.PAGE_TITLE_BO}{else}{$nav.PAGE_TITLE}{/if}</span></a>
                                                    </li>
                                                {/if}

                                            {/if}
					{/foreach}
				</ul>
                {/strip}
			</div>
		</div>
	</div>
	<br/>
	{$sSharer}
	{if $aZonesHerites}
		{*<div class="finitionsReviewDesktop  sliceNew"> <h2 class="title showroom"><span class="line"><span {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$pageTitle}</span></span></h2></div>*}
	{/if}
	{if $aZonesHeritesCurrent|sizeof > 0}
	{foreach from=$aZonesHeritesCurrent item=zoneCurrent}{$zoneCurrent}{/foreach}
	{/if}
	</div>
{/if}	
