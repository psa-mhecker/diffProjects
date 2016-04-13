{if $aContenu.CONTENT_DISPLAY_COMMENT}
<script type="text/javascript">
var objectId = '{$objectId}';
var objectTypeId = '{$objectTypeId}';
var skins = '{$skin}';
{literal}
      $(document).ready(function() {
       $("#form-comment").validationEngine({
        success :  false,
	    failure : function() {}
       })
      })
      $(document).ready(function(){
// simple jRating call
    	  $(".rating").jRating({
    		  bigStarsPath:'/library/External/jquery/jrating/icons/stars.png',
    		  smallStarsPath:'/library/External/jquery/jrating/icons/small.png',
    		  rateMax : 5,
    		  phpPath : '/_/Layout_Comment/rating',
    		  step:true,
    		  length : 5, // nb of stars
    		  });
// more complex jRating call
});
</script>
<script type="text/javascript">
function loadComments() {
	if ($("#div-comment").display == 'none') {
		$("#div-comment").display = 'block';
	}
	callAjax('Layout_Comment/get', objectId, objectTypeId, 'ajax_comment', skins);
}
function updateRatings() {
	$(".rating2").jRating({
		  bigStarsPath:'/library/External/jquery/jrating/icons/stars.png',
		  smallStarsPath:'/library/External/jquery/jrating/icons/small.png',
		  rateMax : 5,
		  phpPath : '/_/Layout_Comment/rating',
		  step:true,
		  isDisabled:true,
		  length : 5, // nb of stars
	});	
}
function submitComment(object) {
	callAjax('Layout_Comment/add', formGetData(object.id));
	/*$('#'+object.id).each(function(){
        this.reset();
});*/
}
</script>
<style>
.art-postcomment {border: 1px dashed #999; border-width: 0px 0px 1px 0px;padding: 5px 5px 1px;}
#form-comment label {
	display: block;
	float: left;
	margin: 5px 10px 0 0;
	width: 105px;
	text-align: right;
}
#div-comment {
	xxdisplay: none;
}
{/literal}
</style>

<a name="comment"></a>
<div class="art-block" id="div-comment">
	<div class="art-block-body">
		<h2 class="art-postheader">{'COMMENTAIRES'|t}</h2>
		<div class="art-postmetadataheader">
			<div id="ajax_comment">
			{if $countComment}
			<a href="#comment" onclick="loadComments()" title="Comments">Afficher le{if $countComment > 1}s {$countComment} commentaires{else} commentaire{/if} &#187;</a>
			{else}
			Pas de commentaires
			{/if}
			</div>
		</div>
			<form action="#" onsubmit="submitComment(this);return false;" method="post" id="form-comment">
				<fieldset class="input">
					<h4>Ajouter un commentaire</h4>
					<input type="hidden" name="type" value="comment">
					<input type="hidden" name="dir" value="/Comment">
					<input type="hidden" name="form_retour" value="{$retour}">
					<input type="hidden" name="COMMENT_ID" value="-2">
					<input type="hidden" name="form_action" value="INS">
					<input type="hidden" name="OBJECT_ID" value="{$objectId}">
					<input type="hidden" name="OBJECT_TYPE_ID" value="1">
					<input type="hidden" name="COMMENT_STATUS" value="1">
					<input type="hidden" name="COMMENT_CREATION_DATE" value=":DATE_COURANTE">
					<input type="hidden" name="SITE_ID" value="{$siteId}">
					<p><label>Titre&nbsp;*&nbsp;:</label> <input type="text" name="COMMENT_TITLE" class="validate[required,length[0,100]] inputbox" id="COMMENT_TITLE"/></p>
					<p><label>Pseudo&nbsp;*&nbsp;:</label> <input type="text" name="COMMENT_PSEUDO" class="validate[required,length[0,100]] inputbox" id="COMMENT_PSEUDO"/></p>
					<p><label>Evaluation&nbsp;&nbsp;:</label> <div class="rating" id="5_1"></div><input type="hidden" name="COMMENT_RATING" id="COMMENT_RATING" class="inputbox"/></p>
					<p><label>Email&nbsp;*&nbsp;:</label> <input type="text" name="COMMENT_EMAIL" class="validate[required,custom[email]] inputbox" id="COMMENT_EMAIL" /></p>
					<p><label>Commentaire&nbsp;*&nbsp;:</label> <textarea cols="70" rows="10" name="COMMENT_TEXT" class="validate[required,length[0,400]] inputbox" id="COMMENT_TEXT"></textarea></p>
					<!-- <p><label>Image de contrôle&nbsp;*&nbsp;:</label>{$recaptcha}</p>-->
					<p><span class="art-button-wrapper">
						<span class="l"></span>
						<span class="r"></span>
						<input type="submit" name="Submit" class="art-button" value="{'POPUP_BUTTON_SAVE'|t}" />
					</span>
					&nbsp;
					<span class="art-button-wrapper">
						<span class="l"></span>
						<span class="r"></span>
						<input type="reset" name="Reset" class="art-button" value="{'POPUP_BUTTON_CANCEL'|t}" />
					</span></p>
				</fieldset>
			</form>
	</div>
</div>
{/if}
