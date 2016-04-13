{section name=index loop=$comment}
<div id="c{$comment[index].COMMENT_ID}" class="art-postcomment">
<h4><a name="c{$comment[index].COMMENT_ID}"></a>{$comment[index].COMMENT_TITLE}</h4>
<ul>
	<li>
	<div class="rating2" style="float:right;" id="{$comment[index].COMMENT_RATING}_1{$index}"></div>
	<div class="art-postheadericons art-metadata-icons"><img
		class="art-metadata-icon"
		src="{$skin}/images/postauthoricon.png" width="14"
		height="14" alt="" />posté le {$comment[index].DATEJ} par <strong>{$comment[index].COMMENT_PSEUDO}</strong></div>
	</li>
</ul>
<blockquote>
<p>{$comment[index].COMMENT_TEXT|nl2br}</p>
</blockquote>
</div>
{/section}
{$pagination}
