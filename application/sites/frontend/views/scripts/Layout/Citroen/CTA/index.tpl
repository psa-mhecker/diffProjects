{if $cta->mode_ouverture == 1}
	{assign var='target' value='_self'}
{elseif  $cta->mode_ouverture == 2}
	{assign var='target' value='_blank'}
{elseif  $cta->mode_ouverture == 3}
	{assign var='target' value=''}
	{assign var='classCss' value='folder'}
{/if}
{assign var='url_cta' value =$cta->getValidUrl()}
{assign var='class_cta_new_showroom' value =$cta->addCss}
{assign var='class_cta_ctt' value =$cta->addCtt}
{if $cta->type == 'toolbar' }
	<!--cta toolbar -->

	{if $url_cta}
		{if $aData.CONTENUS eq 'RECOMMANDES'}
			{assign var='aDataGtm' value =''}
		{else}
			{assign var='aDataGtm' value =$aData}
		{/if}


		<li  class="{$cta->defaultColor}{if $classCss && Pelican::$config.TEMPLATE_PAGE.POINT_DE_VENTE_IFRAME neq $aData.TEMPLATE_PAGE_ID} {$classCss}{/if}{if $cta->addCss && $cta->addCss neq 'buttonLink'&& $cta->addCss neq 'buttonLead' && $cta->addCss neq 'buttonTransversalInvert'  } {$cta->addCss}{/if}" data-services="{$cta->serviceAllowed}">
			<a  {if $cta->form_data} gtm-form-data="{$cta->form_data}"{/if} 	href="{urlParser url=$url_cta}" {if $target} target="{$target}" {/if}
					{if $cta->context.ZONE == 'POINT_DE_VENTE'} onClick='chargeIframeDeploy("{$cta->url_web_deploy}");'{/if}
					{gtm action={$cta->eventAction} data=$aDataGtm datasup=['value'=>{$cta->index},'eventLabel'=>{$cta->title}]}
																			   class="{if $cta->addCtt neq '' && $cta->media_generique_on neq ''}{$cta->addCtt}{elseif $class_cta_new_showroom != ""}{$class_cta_new_showroom}{else}buttonLead{/if}"
			>



				{if $cta->context.ZONE eq 'OUTILS' || (isset($cta->cta_general) && $cta->cta_general eq '1') || $class_cta_new_showroom eq 'general'}
					{if $cta->context.ZONE neq 'POINT_DE_VENTE'}
						<div class="rollOutState">
							<div class="wrapperButton">
								{if $cta->media_generique_on neq ''} <img  src="{$cta->media_generique_on}"  /> {/if}
								{$cta->title_court}
							</div>
						</div>
						<div class="rollOverState">
							<div class="wrapperButton">
								{if $cta->media_generique_on neq ''}<img src="{$cta->media_generique_on}"  />{/if}
								<div class="valign">
									<div>
										{$cta->title}
									</div>
								</div>
							</div>
						</div>
					{else}
						{if ($aData.PRIMARY_COLOR|count_characters)==7 && $cta->picto_new_shoroom neq '' }
							<img class="picto" src="{$cta->picto_new_shoroom}" alt="" style="background:{$aData.PRIMARY_COLOR};" />
						{else}
							{if $cta->picto_off}<img class="picto" src="{$cta->picto_off}" data-hover="{$cta->picto_on}" alt="" />{/if}
						{/if}
						{if $cta->noSpan ==false }<span>{/if}{$cta->title}{if $cta->noSpan ==false }</span>{/if}
						{*{if $cta->addCtt neq '' && $cta->media_generique_on neq ''} <img  src="{$cta->media_generique_on}" class="out" /> {/if}*}

					{/if}
				{else}
					{if $cta->context.ZONE neq 'POINT_DE_VENTE'}
						{if ($aData.PRIMARY_COLOR|count_characters)==7 && $cta->picto_new_shoroom neq '' }
							<img class="picto" src="{$cta->picto_new_shoroom}" alt="" style="background:{$aData.PRIMARY_COLOR};" />
						{else}
							{if $cta->picto_off}<img class="picto" src="{$cta->picto_off}" data-hover="{$cta->picto_on}" alt="" />{/if}
						{/if}
					{/if}
					{if $cta->noSpan ==false }<span>{/if}{$cta->title}{if $cta->noSpan ==false }</span>{/if}
					{*{if $cta->addCtt neq '' && $cta->media_generique_on neq ''} <img  src="{$cta->media_generique_on}" class="out" /> {/if}*}
				{/if}
			</a>

		</li>



	{/if}
{/if}
{if $cta->type == 'expand'}
	<li>
		<a class="{$class_cta_new_showroom}" href="{urlParser url=$url_cta}" {gtm action={$cta->eventAction} data=$aData datasup=['eventCategory' => {$cta->eventCategory}, 'eventLabel' => {$cta->title}]} {if $target} target="{$target}"{/if}>
			<span>{$cta->title}</span>
		</a>
	</li>

{/if}