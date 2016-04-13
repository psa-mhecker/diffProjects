{section name=index loop=$list}
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
												{if $list[index].CONTENT_TITLE}<h2 class="art-postheader">
													<a href="{urlParser url=$list[index].CONTENT_CLEAR_URL}">{$list[index].CONTENT_TITLE}</a>
												</h2>{/if}
                                            </div>
                                               <div class="art-postheadericons art-metadata-icons">
			{if $list[index].CONTENT_DISPLAY_DATE}<img class="art-metadata-icon" src="{$skin}/images/postdateicon.png" width="17" height="18" alt="Date de publication" />
			{if $list[index].CONTENT_PUBLICATION_DATE}{$list[index].CONTENT_PUBLICATION_DATE}{else}{$list[index].CONTENT_PUBLICATION_DATE}{/if}{/if}
			{if $list[index].CONTENT_DISPLAY_AUTHOR}| <img class="art-metadata-icon" src="{$skin}/images/postauthoricon.png" width="14" height="14" alt="Auteur(s)" />
			{$CONTENT_DISPLAY_AUTHOR}{/if}
			{if $list[index].CONTENT_DISPLAY_PDF}| <img class="art-metadata-icon" src="{$skin}/images/postpdficon.png" width="16" height="16" alt="Imprimer en PDF" onclick="window.open('http://pdfmyurl.com/?url={$list[index].CONTENT_CLEAR_URL}&--orientation=Portrait&--zoom=0.7')" />
			{/if}
			{if $list[index].CONTENT_DISPLAY_PRINT}| <img class="art-metadata-icon" src="{$skin}/images/postprinticon.png" width="15" height="13" alt="Imprimer" onclick="document.location.href='{urlParser url=$list[index].CONTENT_CLEAR_URL}#print'" />
			{/if}
			{if $list[index].CONTENT_DISPLAY_SEND}| <img class="art-metadata-icon" src="{$skin}/images/postemailicon.png" width="16" height="16" alt="Envoyer a un ami" />
			{/if}
			{if $list[index].CONTENT_DISPLAY_COMMENT}| <img class="art-metadata-icon" src="{$skin}/images/postediticon.png" width="14" height="14" alt="Poster un commentaire" />
			<a href="{urlParser url=$list[index].CONTENT_CLEAR_URL}#comment" title="Comments">Commentaires &#187;</a>
			{/if}
			</div>
                                            <div class="art-postcontent">
                                                <!-- article-content -->
{if $list[index].CONTENT_SHORTTEXT}
{$list[index].CONTENT_SHORTTEXT}
<div class="cleared"></div>
<span class="art-button-wrapper">
	<span class="l"> </span>
	<span class="r"> </span>
	<input type="button" name="readmore" class="art-button" value="En savoir plus..." onclick="document.location.href='{urlParser url=$list[index].CONTENT_CLEAR_URL}'"/>
</span>
{else}
{$list[index].CONTENT_TEXT|replace:"#MEDIA_HTTP#":$pelican_config.MEDIA_HTTP}
{/if}
                                                <!-- /Article-content -->
                                            </div>
                                            <div class="cleared"></div>
                            </div>
                            		<div class="cleared"></div>
                                </div>
                            </div>
{/section}