{if $aRsTechno|@sizeof > 0}

{literal}
<style>
.sliceTechnologieGallerieDesk .stickyplaceholder .sticky{
background:{/literal}{$aData.PRIMARY_COLOR}!important;{literal}
}
.sliceTechnologieGallerieDesk .stickyplaceholder .sticky li a:hover, .sliceTechnologieGallerieDesk .stickyplaceholder .sticky li.on a, .sliceTechnologieGallerieDesk .stickyplaceholder .sticky.fixed li a:hover, .sliceTechnologieGallerieDesk .stickyplaceholder .sticky.fixed li.on a{
border: 4px solid {/literal}{$aData.PRIMARY_COLOR}!important;{literal}
}
div.sliceTechnologieGallerieDesk .stickyplaceholder .sticky li.on a span{
    color:{/literal}{$aData.PRIMARY_COLOR};{literal}
}
div.sliceTechnologieGallerieDesk .stickyplaceholder .sticky li a span{
    color:#ffffff;
}
div.sliceTechnologieGallerieDesk .stickyplaceholder .sticky li a:hover span{
    color:{/literal}{$aData.PRIMARY_COLOR}!important;{literal}
}
</style>

{/literal}
	<div class="sliceNew sliceTechnologieGallerieDesk">

		<div id="{$aData.ID_HTML}" class="stickyplaceholder lite clstechnologiegalleriesticky">
			<div class="sticky">
				<div class="inner">
					<div class="logo">
						<a href="{urlParser url=$url_home}">Citroën</a>
					</div>
					<ul>
						{assign var='i' value=1}
						{foreach from=$aRsTechno key=key item=Techno}
							<li class="on">
								<a href="#part{$i++}" {gtm name='clic_sur_un_theme'  data=$aParams datasup=['value'=>$i] labelvars=['%nom du modele%'=>'', '%intitule de la rubrique%'=>$key]} >
									<span>{$key}</span>
								</a>
							</li>
						{/foreach}
						{assign var='i' value=1}
					</ul>
				</div>
			</div>
		</div>

		<section class="collection separated clsTechnologieGallerie">
			{foreach from=$aRsTechno key=key item=Techno}
				{if $key}
					<h2 class="subtitle" id="part{$i++}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>
						{$key}
					</h2>
				{/if}

				<div class="row of2">
					{assign var='itemIndex' value=0}
					{foreach from=$Techno item=Detail name="technoItem"}

						{$itemIndex =  $smarty.foreach.technoItem.iteration }
						{if $smarty.foreach.technoItem.iteration is div by 2}
							{$itemIndex = $itemIndex-1}
						{/if}

						<div class="col zoner" {if $smarty.foreach.technoItem.iteration is not div by 2}style="clear:left;"{/if}>
							<figure>
								<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/banner-tall.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Detail.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_TECHNOLOGIE}" width="580" height="247" alt="{$Detail.MEDIA_ALT}">
								<noscript<img src="{Pelican::$config.MEDIA_HTTP}{$Detail.MEDIA_PATH}" width="580" height="247" alt="{$Detail.MEDIA_ALT}" /></noscript>
							</figure>

							<h2 class="parttitle" {if ($aData.SECOND_COLOR|count_characters) == 7 }style="color:{$aData.SECOND_COLOR};"{/if}>{$Detail.PAGE_TITLE_BO}</h2>
							<p>{$Detail.ZONE_TEXTE|escape}</p>

							<ul class="actions">
								<li>
									<a class="buttonTransversalInvert" href="{urlParser url=$Detail.PAGE_CLEAR_URL}">{'EN_SAVOIR_PLUS'|t}</a>
								</li>
							</ul>
						</div>

					{/foreach}
				</div>
			{/foreach}
		</section>

	</div>
{/if}