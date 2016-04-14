{literal}
<script>
function check_url(url_label,url_div, id, width){
	var url = document.getElementById(url_label).value;
	callAjax("/Administration_Site/checkUrl", url_label, url, url_div, id, width);
}

function check_url_textarea(url_label,url_div,id){
	var urls = document.getElementById(url_label).value;
	callAjax("/Administration_Site/checkUrlTextarea", url_label, urls, url_div, id);
}
</script>
{/literal}
{$content}