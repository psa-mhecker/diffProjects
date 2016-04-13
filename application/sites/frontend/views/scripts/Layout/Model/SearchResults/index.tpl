<style>
{literal}
.art-content-layout .art-content
{
  width: 75%;
}
.art-content-layout .art-sidebar2
{
  width: 0%;
}
{/literal}
</style>
<div class="art-post">
	<div class="art-post-tl"></div>
	<div class="art-post-tr"></div>
	<div class="art-post-bl"></div>
	<div class="art-post-br"></div>
	<div class="art-post-tc"></div>
	<div class="art-post-bc"></div>
	<div class="art-post-cl"></div>
	<div class="art-post-cr"></div>
	<div class="art-post-cc"></div>
	<div class="art-post-body">
	<div class="art-post-inner art-article">
		<div class="art-postmetadataheader">
            {if $title}<h2 class="art-postheader">{$title}</h2>{/if}
		</div>


<fieldset>
  <form method="get" id="form-search" action="/recherche">
                                        <input type="text" value="{$recMot}" name="recMot" id="recMot" style="width: 75%;" />
                                                            	<span class="art-button-wrapper">
                                                            		<span class="l"> </span>
                                                            		<span class="r"> </span>
                                                            		<input type="submit" name="research" class="art-button" value="{'FORM_BUTTON_SEARCH'|t}" />
                                                            	</span>
                                        </form>
	{if $aReport.total}
<div style="clear:both;"><br /></div>
					<div style="float:left;"><strong>{$aReport.total}</strong> {'RESULTS_FOUND'|t}</div>
					<div style="float:right"><strong>{'RESULTS_ORDERED_BY'|t} : </strong> {if $aReport.typeper == "date"}<a href="{urlParser url={$aReport.per|cat:'&recPer=per'}}">Pertinence</a>{else}<span>Pertinence</span>{/if} {if $aReport.typeper == "per"}<a href="{urlParser url={$aReport.per|cat:'&recPer=date'}}">{'DATE'|t}</a>{else}<span>{'DATE'|t}</span>{/if}</div>
<div style="clear:both;"><br /></div>
					<div style="float:left;">{'RESULTS_OF'|t} {$aReport.min} {'TO'|t} {$aReport.max}</div>
					<div style="float:right;">{'RESULTS_PER_PAGE'|t} : <select onchange="document.location.href = '{urlParser url=$aReport.urlStep}' + '&step=' + this.value;">
						<option value="5" {if $aReport.step == 5}selected="selected"{/if}>5</option>
						<option value="10" {if $aReport.step == 10}selected="selected"{/if}>10</option>
						<option value="15" {if $aReport.step == 15}selected="selected"{/if}>15</option>
						<option value="20" {if $aReport.step == 20}selected="selected"{/if}>20</option>
						<option value="25" {if $aReport.step == 25}selected="selected"{/if}>25</option>
						</select>
					</div>
</fieldset>
			{section name=index loop=$list}
				<!--  <p>{$list[index].PERTINENCE} %</p>-->
				<fieldset>
						<div class="art-post" style="overflow:hidden;">
                                <div class="art-post-tl"></div>
                                <div class="art-post-tr"></div>
                                <div class="art-post-bl"></div>
                                <div class="art-post-br"></div>
                                <div class="art-post-tc"></div>
                                <div class="art-post-bc"></div>
                                <div class="art-post-cl"></div>
                                <div class="art-post-cr"></div>
                                <div class="art-post-cc"></div>
                                <div class="art-post-body">
                            <div class="art-post-inner art-article">
                                            <div class="art-postmetadataheader">
                                            {if $list[index].RESEARCH_TITLE}<h3 class="art-postheader">
                                                <a href="{urlParser url={$list[index].RESEARCH_URL|replace:"#MEDIA_PATH#":$pelican_config.MEDIA_HTTP}}">{$list[index].RESEARCH_TITLE|truncate}</a>
                                            </h3>{/if}

                                            </div>
                                            <div class="art-postheadericons art-metadata-icons">
									{if $list[index].RESEARCH_DATE_FR}<img height="18" width="17" alt="Date de publication" src="{$pelican_config.SKIN_HTTP}/images/postdateicon.png" class="art-metadata-icon"> {$list[index].RESEARCH_DATE_FR}{/if}
								{if $list[index].MORE_PATH}
										| <a href="/">Accueil</a>{section name=item loop=$list[index].MORE_PATH}
										 &gt; <a href="{urlParser url={'/pid'|cat:$list[index].MORE_PATH[item][0]|cat:$list[index].MORE_PATH[item][1]|escape|cat:'.html'}}" title="{$list[index].MORE_PATH[item][1]|truncate}">{$list[index].MORE_PATH[item][1]|truncate}</a>
										{/section} &gt; {$list[index].RESEARCH_TITLE|truncate}
								{/if}
								</div>
								<div class="art-postcontent">
                                                <!-- article-content -->
								{if $list[index].RESEARCH_DESCRIPTION}<p>{$list[index].RESEARCH_DESCRIPTION|truncate:600}</p>{/if}
                                                <!-- /article-content -->
                                            </div>
                                            <div class="cleared"></div>
                            </div>
                            		<div class="cleared"></div>
                                </div>
                            </div>
                            </fieldset>
			{/section}
<div class="cleared"></div>
<center><div><a href="{urlParser url=$aReport.url}">&lt;&lt;</a>
							{if $aReport.page > 1}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page-1}}">&lt;</a>{/if}
							{if $aReport.page > 2}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page-2}}">{$aReport.page-2}</a>{/if}
							{if $aReport.page > 1}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page-1}}">{$aReport.page-1}</a>{/if}
							<span class="current">{$aReport.page}</span>
							{if $aReport.page < $aReport.totalpage}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page+1}}">{$aReport.page+1}</a>{/if}
							{if $aReport.page + 1 < $aReport.totalpage}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page+2}}">{$aReport.page+2}</a>{/if} / {$aReport.totalpage}
							{if $aReport.page < $aReport.totalpage}<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.page+1}}">&gt;</a>{/if}
							<a href="{urlParser url={$aReport.url|cat:'&recPage='|cat:$aReport.totalpage}}">&gt;&gt;</a>
						</div></center>
	{else}
		<p>Nous n'avons pas trouvé de résultat.</p>
	</fieldset>
	{/if}


	</div>
</div>