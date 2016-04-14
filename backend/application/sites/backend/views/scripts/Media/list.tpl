<script>
{literal}
		if (top.cSelected) {
			top.cSelected = '';
		}
		</script>
<style type="text/css">
table.thumbnail {
	border: 0px solid #CCCCCC;
}

td.file_td {
	cursor: pointer;
	padding: 2px 2px 2px 2px;
	text-align: left;
	vertical-align: middle;
}

td.thumbnail_td {
	border: 1px solid #CCCCCC;
	color: #000000;
	cursor: pointer;
	font-size: 9px;
	height: 90px;
	padding: 2px 2px 2px 2px;
	vertical-align: top;
	width: 120px;
	overflow: hidden;
}

div.thumbnail_div {
	background-color: #FFFFFF;
	width: 80px;
	height: 60px;
	overflow: hidden;
	border: 1px solid #000000;
	padding: 0px 0px 0px 0px;
	vertical-align: top;
}

th.tblmediath,th.tblmediathon {
	visibility: hidden;
	line-height: 1px;
}

.tblmediatd {
	padding: 1px 1px 1px 1px;
	cursor: pointer;
}

.tblmediafooter td {
	border-left: 0px solid;
	border-right: 0px solid;
	line-height: 24px;
}
</style>
{/literal}


{if $showMireConnectionOauth eq true}
{literal}
    <script>       
        function getOAuth2(){
            window.open('/_/Media/getOAuth2', '','width=500,height=500');
        }
      getOAuth2();
    </script>
{/literal}
{/if}

<div id="media_left" style="width: 50%; float: left;">
	{$tabs} 
	{if $isShowList eq false}</br>{'PAS_DE_COMPTE_ASSOCIE'|t}</br>{'MERCI'|t}{/if}
	{$list}
</div>
<div id="media_right"
	style="width: 50%; height: 500px; float: left; display: none;"><iframe
	id="properties" name="properties" frameborder="no" src="about:blank"
	style="width: 100%; height: 95%; overflow: -moz-scrollbars-vertical; overflow-x: hidden; overflow-y: auto;"></iframe>
</div>
{literal}
<script>
if (parent.location.href.indexOf('Media/popup') != -1 || parent.location.href.indexOf('tiny_mce') != -1) {
	document.getElementById('media_left').style.width = '100%';
} else {
	document.getElementById('media_right').style.display = '';
	top.setAction('add','file');	
}
</script>
{/literal}
