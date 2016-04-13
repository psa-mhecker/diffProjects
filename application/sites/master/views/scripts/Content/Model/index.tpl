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
			{if $title}    
				<h1>
					{$title}
				</h1>
			{/if}
			{if $subtitle}    
				<h2>
					{$subtitle}
				</h2>
			{/if}
		</div>
            <div class="art-postmetadataheader00">
			<div class="art-postheadericons art-metadata-icons">
			{if $aContenu.CONTENT_DISPLAY_DATE}<img class="art-metadata-icon" src="{$skin}/images/postdateicon.png" width="17" height="18" alt="" />
			{if $aContenu.CONTENT_PUBLICATION_DATE}{$aContenu.CONTENT_PUBLICATION_DATE}{else}{$aContenu.CONTENT_PUBLICATION_DATE}{/if}{/if}
<!--			{if $aContenu.CONTENT_DISPLAY_AUTHOR}| <img class="art-metadata-icon" src="{$skin}/images/postauthoricon.png" width="14" height="14" alt="Auteur(s)" />
			{$CONTENT_DISPLAY_AUTHOR}{/if}
			{if $aContenu.CONTENT_DISPLAY_PDF}| <img class="art-metadata-icon" src="{$skin}/images/postpdficon.png" width="16" height="16" alt="Imprimer en PDF" onclick="window.open(document.location.href.replace('.html','.pdf'));" />
			{/if}
			{if $aContenu.CONTENT_DISPLAY_PRINT}| <img class="art-metadata-icon" src="{$skin}/images/postprinticon.png" width="15" height="13" alt="Imprimer" onclick="window.print();"/>
			{/if}
			{if $aContenu.CONTENT_DISPLAY_SEND}| <img class="art-metadata-icon" src="{$skin}/images/postemailicon.png" width="16" height="16" alt="Envoyer a un ami" />
			{/if}
			{if $aContenu.CONTENT_DISPLAY_COMMENT}| <img class="art-metadata-icon" src="{$skin}/images/postediticon.png" width="14" height="14" alt="Poster un commentaire" />
			<a href="#comment" onclick="loadComments()" title="Comments">Commentaires &#187;</a>
			{/if}-->
			</div>
		</div>
		<div class="art-postcontent">
		<br />
			{if $shorttext}    
				<div class="cleared"></div>
				{$shorttext}
				<div class="cleared"></div>
			{/if}
			<!-- article-content -->
				{include file="$template"}
			<!-- /Article-content -->
		</div>
		<div class="cleared"></div>
		<br />
		<!--<div class="art-postmetadatafooter">
			<div data-role="controlgroup" data-type="horizontal" class="art-postfootericons art-metadata-icons">
				{if $aContenu.CONTENT_DISPLAY_TAGS}Balises : 
				{section name=index loop=$tags}{if  $view.section.index.index != 0}, {/if}<a href="#{$tags[index].TERMS_NAME}"data-role="button" title="{$tags[index].TERMS_NAME}">{$tags[index].TERMS_NAME}</a>{/section}
				{/if}
			</div>
		</div>-->
	</div>
</div>
<br />