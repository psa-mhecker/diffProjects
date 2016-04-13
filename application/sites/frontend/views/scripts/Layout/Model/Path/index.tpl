<div class="breadcrumbs pathway"><!-- jqm-navbar -->
<ul>
	{section name=parent loop=$pathIndex step=-1} 
			{if $pathParentUrl[parent]}
				<li style="float: left;" class="pathway"><a href="{urlParser url=$pathParentUrl[parent]}">{$pathParentName[parent]}</a></li>
			{/if} 
	{/section} 
	{if $cid}
		<li style="float: left;" class="pathway"><a href="#">{$data.PAGE_TITLE}</a></li>
	{/if}
</ul>
<!-- /jqm-navbar --></div>

